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

class WPBase_TomlEnv extends AbstractConfig
{
    public function __construct(string $path)
    {
        try {
            $env = Toml::parseFile($path);
        } catch(Exception $e) {
            $env = $this->getDefaults();
        }

        parent::__construct($env);
    }

    protected function getDefaults()
    {
        return ['env' => 'production'];
    }
}
