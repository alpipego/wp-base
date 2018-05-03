<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 02.05.18
 * Time: 11:02
 */

require __DIR__ . '/vendor/autoload.php';

use Yosymfony\Toml\Toml;
use Yosymfony\Toml\TomlBuilder;
use function Deployer\{
    ask, askChoice, askConfirmation, askHiddenResponse, localhost, runLocally, task, writeln
};

localhost();

function testLocally($command, $testValue = true)
{
    return runLocally("if $command; then echo ''; fi") === $testValue;
}

function getUrl()
{
    return Toml::parseFile(__DIR__.'/config/env.local.toml')['domain']['url'];
}

task('setup', [
    'setup:config',
    'setup:packages',
    'setup:packages_install',
    'setup:wpcli',
    'setup:finish',
]);

task('setup:packages_install', function () {
    runLocally('npm i');
});

task('setup:packages', function () {
    writeln('');
    if (file_exists(__DIR__ . '/package.json')) {
        if ( ! askConfirmation('<error>package.json already exists, do you want to override it?', false)) {
            die();
        }
    }
    writeln('<comment>Writing package.json</comment>');
    $defaults = Toml::parseFile(__DIR__ . '/config/package.toml');

    $defaults['name'] = ask('Enter your projects name', $defaults['name']);
    while (empty($defaults['name'])) {
        $defaults['name'] = ask('Enter your projects name', '');
    }

    $defaults['version'] = ask('Enter your projects initial version', $defaults['version']);
    while (empty($defaults['version'])) {
        $defaults['version'] = ask('Enter your projects initial version', '');
    }

    $package = (new TomlBuilder())
        ->addValue('name', $defaults['name'])
        ->addValue('version', $defaults['version'])
        ->addValue('private', ask('Keep this package private?', $defaults['private'] ? 'true' : 'false'));

    foreach (['devDependencies', 'dependencies'] as $type) {
        if ( ! array_key_exists($type, $defaults)) {
            continue;
        }
        $package->addTable($type);

        writeln('');
        writeln($type);
        foreach ($defaults[$type] as $dep => $version) {
            writeln(sprintf('<info>Adding "%s": "%s"</info>', $dep, $version));
            $package->addValue($dep, $version);
        }
    }

    file_put_contents(__DIR__ . '/package.json', json_encode(Toml::parse($package->getTomlString()), JSON_UNESCAPED_SLASHES));
});

task('setup:finish', function () {
    if (askConfirmation('Do you want to run the local server now?', true)) {
        writeln(sprintf('<info>Your site is accessible at %s, check the output below for the browserSync url.</info>', getUrl()));
        runLocally('grunt');
    }

    writeln('<comment>If you want to start the server, run `grunt` (or `grunt fast`).</comment>');
});

task('setup:wpcli', function () {
    $url       = getUrl();
    $user = ask('WordPress main user?', '');
    $cliConfig = <<<EOT
path: web/wp
url: $url
user: $user
EOT;

    file_put_contents(__DIR__ . '/wp-cli.local.yml', $cliConfig);
});

task('setup:config', function () {
    if ( ! file_exists(__DIR__ . '/config/.machine/')) {
        mkdir(__DIR__ . '/config/.machine/', 0755);
    }

    if (file_exists(__DIR__ . '/config/.machine/env.json')) {
        if (json_decode(file_get_contents(__DIR__ . '/config/.machine/env.json'))->env !== 'local') {
            writeln('<error>Your env shows you\'re not in local environment.</error>');
        }
        if ( ! askConfirmation('<comment>Do you want to override your existing env.json?</comment>', false)) {
            die();
        }
    }

    $defaults = Toml::parseFile(__DIR__ . '/config/env.default.toml');
    $env      = (new TomlBuilder())
        ->addValue('env', 'local')
        ->addValue('memory', ask('Set PHP memory limit', $defaults['memory']))
        ->addValue('disable_cron', ask('Disbale WordPress\' cron job?', $defaults['disable_cron'] ? 'true' : 'false'));

    writeln('');
    writeln('<comment>Local Domain Settings</comment>');
    $env->addTable('domain')
        ->addValue('scheme', ask('Enter your local scheme', $defaults['domain']['scheme'], ['http', 'https']))
        ->addValue('host', ask('Enter your local domain', $defaults['domain']['host']))
        ->addValue('port', ask('Enter your local port', $defaults['domain']['port']));

    $local = Toml::parse($env->getTomlString());
    file_put_contents(__DIR__ . '/config/.machine/env.json', json_encode($local, JSON_UNESCAPED_SLASHES));

    // set the URL
    $url = sprintf('%s://%s', $local['domain']['scheme'], $local['domain']['host']);
    $url .= empty($local['domain']['port']) ? '/' : ':' . $local['domain']['port'];
    $env->addValue('url', $url);


    // Setup Database
    writeln('');
    writeln('<comment>Database Settings</comment>');
    $possibleTypes = ['mysql' => 'MySQL', 'sqlite' => 'SQLite3'];
    $type          = askChoice('Database type', $possibleTypes, $defaults['connection']['type']);
    $type          = strtolower($type);
    $env->addTable('connection.wp')->addValue('type', $type);

    if ($type === 'mysql') {
        while (empty($db)) {
            $db = ask('Enter database name', $defaults['connection']['wp']['db']);
        }
        while (empty($user)) {
            $user = ask('Enter database user', $defaults['connection']['wp']['user']);
        }

        $env
            ->addValue('db', $db)
            ->addValue('user', $user)
            ->addValue('password', askHiddenResponse('Enter database password') ?? '')
            ->addValue('host', ask('Enter database host', $defaults['connection']['wp']['host']))
            ->addValue('collate', ask('Enter database collation', $defaults['connection']['wp']['collate']))
            ->addValue('charset', ask('Enter database charset', $defaults['connection']['wp']['charset']));
    }
    if ($type === 'sqlite') {
        $env
            ->addValue('file', ask('Enter the database file', $defaults['connection']['wp']['file']))
            ->addValue('dir', ask('Enter the database directory', $defaults['connection']['wp']['dir']));
    }
    $env->addValue('table_prefix', ask('Enter the table prefix', $defaults['connection']['wp']['table_prefix']));

    // browsersync
    writeln('');
    writeln('<comment>browserSync Settings (https://browsersync.io/docs)</comment>');
    $env->addTable('bs');
    $online = askConfirmation('Make the website available in your network?', $defaults['bs']['online']);
    $env->addValue('online', ! ($online === 'false' || (bool)$online === false))
        ->addValue('delay', (int)ask('Enter a reload delay', (int)$defaults['bs']['delay']))
        ->addValue('open', askConfirmation('Open the browser by default?', $defaults['bs']['open']))
        ->addValue('notify', askConfirmation('Send a notification on reload?', $defaults['bs']['notify']));

    if (Toml::parse($env->getTomlString())['bs']['open']) {
        $browser = ask('Enter a browser to open by default', 'default');
        if ($browser !== 'default') {
            $env->addValue('browser', $browser);
        }
    }

    // get keys and salts
    foreach (['salts', 'keys'] as $table) {
        $env->addTable($table);
        foreach ($defaults['keys'] as $key => $value) {
            $env->addValue($key, bin2hex(random_bytes(32)));
        }
    }

    writeln('');
    writeln('<comment>Writing config files</comment>');
    file_put_contents(__DIR__ . '/config/env.local.toml', $env->getTomlString());
    file_put_contents(__DIR__ . '/config/.machine/local.json', json_encode(Toml::parse($env->getTomlString()), JSON_UNESCAPED_SLASHES));
});
