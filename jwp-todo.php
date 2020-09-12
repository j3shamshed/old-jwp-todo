<?php
/*
  Plugin Name: jWpToDO
  Plugin URI: https://github.com/j3shamshed/wp-todo.git
  Description: weDevs job test.
  Version: 1.0
  Author: Jubayer
  Author URI: https://github.com/j3shamshed/
  License: A "Slug" license name e.g. GPL2
 */

defined('ABSPATH') or die('-1');

use Inc\Init;

define('PREFIX_WP_VERSION', "5.0");
define('PREFIX_PHP_VERSION', "7.2");
define('PREFIX_PLUGINURL', plugins_url());
define('PREFIX_PLUGIN_DIR', dirname(__FILE__));
define('PREFIX_PLUGIN_DIR_HTTP', plugin_dir_url(__FILE__));

if (file_exists(PREFIX_PLUGIN_DIR . '/lib/vendor/autoload.php')) {
    require_once PREFIX_PLUGIN_DIR . '/lib/vendor/autoload.php';
}



if (class_exists(Init::class)) {
    
    register_activation_hook(__FILE__, 'Inc\\Init::prefixActivation');
    register_uninstall_hook(__FILE__,'Inc\\Init::prefixUninstall');
    Init::prefixRegisterServices();
}
