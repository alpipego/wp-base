<?php

include __DIR__ . '/vendor/autoload.php';

use Noodlehaus\Config;

$env = new Config( __DIR__ . '/config/env.json' );

$conf = new Config( [
	__DIR__ . '/config/env/default.json',
	__DIR__ . '/config/env/' . $env->get( 'env' ) . '.json',
] );
/**
 * DB Settings
 */
define( 'DB_NAME', $conf->get( 'connection.wp.db' ) );
define( 'DB_USER', $conf->get( 'connection.wp.user' ) );
define( 'DB_PASSWORD', $conf->get( 'connection.wp.password' ) );
define( 'DB_HOST', $conf->get( 'connection.wp.host' ) );
define( 'DB_CHARSET', $conf->get( 'connection.wp.charset' ) );
define( 'DB_COLLATE', $conf->get( 'connection.wp.collate' ) );
$table_prefix = $conf->get( 'connection.wp.tablePrefix' );

/**
 * Authentication Unique Keys and Salts.
 */
define( 'AUTH_KEY', '|Z/o<o/pDq~}x$,7l)}>dOyWsjr CkdUbQAV<}q;C@P:UclIhfk{3vXZkx)diiji' );
define( 'SECURE_AUTH_KEY', 'W4OR&dXJ0M9BliS;BA{w_Oz>v-HpSMO],%=@{(*/k%%xbTk}myg-y}:9bmd G>{=' );
define( 'LOGGED_IN_KEY', '|X!+kzbcy[95I5KHmBXc_GPs?A1l0YpUylHb{T+RH=-r~SOpS1E#T_Yz5J;Z*25]' );
define( 'NONCE_KEY', 'UV}1UFk9Zx&Ny1W[6zpW8hLoO?YJ*ZcjnZk@[!f`%C*EJhD06nc>lyv-Rm[Bp  ;' );
define( 'AUTH_SALT', '`*M!wKy-sfi:*8TZcZ8=z|#UDBQL}NY4gW>RyH^U]^8E?@1vkY7R4dL|q,w-lOdb' );
define( 'SECURE_AUTH_SALT', 'z-cO,gxRyQh,,m?fK}l-{/R]RKU]H3)|5iD3rv`P4 i.rBg|MTiC)- ^/:iSL|p=' );
define( 'LOGGED_IN_SALT', '@|HA1YB@J1f>CximM{]ZkIz%z~ akYl|:1l1#1gDC?-,l/o&iB7Onr#U+v-{q+#}' );
define( 'NONCE_SALT', '*X=ewfmEzTR/Ah~YS]>9%)t-5f-g-I^&r*L#w<LM@=zHb[&v@RC/Vj3q+b{|61+-' );

/**
 * WordPress debugging mode.
 */
switch ( $env->get( 'env' ) ) {
	case 'local':
		define( 'WP_DEBUG', true );
		define( 'SAVEQUERIES', true );
		define( 'WP_DEBUG_DISPLAY', true );
		define( 'WP_DEBUG_LOG', true );
		define( 'DISABLE_CACHE', true );
		break;
	case 'staging':
		define( 'WP_DEBUG', true );
		define( 'SAVEQUERIES', false );
		define( 'WP_DEBUG_DISPLAY', false );
		define( 'WP_DEBUG_LOG', true );
		define( 'DISABLE_CACHE', false );
		break;
	case 'production':
		define( 'WP_DEBUG', false );
		define( 'SAVEQUERIES', false );
		define( 'DISABLE_CACHE', false );
		break;
}

if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
	ini_set( 'error_log', __DIR__ . '/log/wp.log' );
}

/**
 * Folder Structure
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

define( 'WP_SITEURL', $conf->get( 'url' ) . 'wp/' );
define( 'WP_HOME', $conf->get( 'url' ) );

define( 'WP_CONTENT_FOLDERNAME', 'assets' );
define( 'WP_CONTENT_DIR', ABSPATH . '../' . WP_CONTENT_FOLDERNAME );
define( 'WP_CONTENT_URL', WP_HOME . WP_CONTENT_FOLDERNAME );
define( 'WP_PLUGIN_DIR', ABSPATH . '../plugins' );
define( 'WP_PLUGIN_URL', WP_HOME . 'plugins' );
define( 'WPMU_PLUGIN_DIR', ABSPATH . '../must-use' );
define( 'WPMU_PLUGIN_URL', WP_HOME . 'must-use' );
define( 'UPLOADS', '../uploads' );
define( 'WP_TEMP_DIR', ABSPATH . '../temp' );
define( 'WP_LANG_DIR', ABSPATH . '../languages' );

/**
 * MISC
 */
define( 'DISALLOW_FILE_MODS', false );
define( 'DISALLOW_FILE_EDIT', true );
define( 'WP_MAX_MEMORY_LIMIT', $conf->get( 'memory' ) );
define( 'WP_MEMORY_LIMIT', $conf->get( 'memory' ) );
define( 'DISABLE_WP_CRON', false );

/**
 * Environment Constants
 */
define( 'WP_STAGE', $env->get( 'env' ) );
define( 'WP_ENV', $env->get( 'env' ) );

/**
 * Sets up WordPress vars and included files.
 */
require_once( ABSPATH . 'wp-settings.php' );
