<?php

include __DIR__ . '/vendor/autoload.php';

try {
    $conf = new WPBase_TomlConfig(__DIR__ . '/config/env.' . (new WPBase_TomlEnv(__DIR__ . '/config/env.toml'))->get('env') . '.toml');
} catch (\Exception $e) {
    echo 'We can\'t find a valid configuration. Please run `/usr/bin/env php ./vendor/bin/dep --file=setup.php setup` first.';
}

if ($conf->get('connection.type') === 'sqlite') {
    define('USE_MYSQL', false);
    define('DB_FILE', $conf->get('connection.wp.file') . '.sqlite');
    define('DB_DIR', __DIR__ . '/' . $conf->get('connection.wp.dir'));
}

define('DB_NAME', $conf->get('connection.wp.db'));
define('DB_USER', $conf->get('connection.wp.user'));
define('DB_PASSWORD', $conf->get('connection.wp.password'));
define('DB_HOST', $conf->get('connection.wp.host'));
define('DB_CHARSET', $conf->get('connection.wp.charset'));
define('DB_COLLATE', $conf->get('connection.wp.collate'));

$table_prefix = $conf->get('connection.wp.table_prefix');

define('AUTH_KEY', $conf->get('keys.auth'));
define('SECURE_AUTH_KEY', $conf->get('keys.secure_auth'));
define('LOGGED_IN_KEY', $conf->get('keys.logged_in'));
define('NONCE_KEY', $conf->get('keys.nonce'));
define('AUTH_SALT', $conf->get('salt.auth'));
define('SECURE_AUTH_SALT', $conf->get('salt.secure_auth'));
define('LOGGED_IN_SALT', $conf->get('salt.logged_in'));
define('NONCE_SALT', $conf->get('salt.nonce'));

define('BASE_PATH', __DIR__ . '');
define('LOG_DIR', __DIR__ . '/log');
define('CONTENT_PATH', realpath(ABSPATH . '../'));

ini_set('error_log', LOG_DIR . '/wp.log');
ini_set('log_errors_max_len', '0');
define('WP_DEBUG', true);
switch ($conf->get('env')) {
    case 'local':
        define('SAVEQUERIES', true);
        define('WP_DEBUG_DISPLAY', true);
        define('DISABLE_CACHE', true);
        break;
    case 'staging':
        define('SAVEQUERIES', true);
        define('WP_DEBUG_DISPLAY', false);
        define('DISABLE_CACHE', false);
        break;
    case 'production':
        define('SAVEQUERIES', false);
        define('WP_DEBUG_DISPLAY', false);
        define('DISABLE_CACHE', false);
        break;
    default:
        break;
}

define('WP_SITEURL', $conf->get('domain.url') . 'wp/');
define('WP_HOME', $conf->get('domain.url'));

define('WP_CONTENT_FOLDERNAME', 'assets');
define('WP_CONTENT_DIR', CONTENT_PATH . '/' . WP_CONTENT_FOLDERNAME);
define('WP_CONTENT_URL', WP_HOME . WP_CONTENT_FOLDERNAME);
define('WP_PLUGIN_DIR', CONTENT_PATH . '/plugins');
define('WP_PLUGIN_URL', WP_HOME . 'plugins');
define('WPMU_PLUGIN_DIR', CONTENT_PATH . '/mu-plugins');
define('WPMU_PLUGIN_URL', WP_HOME . 'mu-plugins');
define('UPLOADS', '../uploads');
define('WP_TEMP_DIR', CONTENT_PATH . '/temp');
define('WP_LANG_DIR', CONTENT_PATH . '/languages');

define('DISALLOW_FILE_MODS', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_MAX_MEMORY_LIMIT', $conf->get('memory'));
define('WP_MEMORY_LIMIT', $conf->get('memory'));
define('DISABLE_WP_CRON', $conf->get('disable_cron'));

define('WP_STAGE', $conf->get('env'));
define('WP_ENV', $conf->get('env'));

define('SCRIPT_DEBUG', $conf->get('env') === 'local');
