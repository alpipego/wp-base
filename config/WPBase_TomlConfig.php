<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 03.07.18
 * Time: 11:00
 */
declare(strict_types = 1);

use Noodlehaus\AbstractConfig;
use Yosymfony\Toml\Toml;

class WPBase_TomlConfig extends AbstractConfig
{
    public function __construct(string $path)
    {
        try {
            $conf = Toml::parseFile($path);
        } catch(Exception $e) {
            throw new $e;
        }

        parent::__construct($conf);
    }
    protected function getDefaults()
    {
        return Toml::parseFile(__DIR__ . '/env.default.toml');
    }
}
