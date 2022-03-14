<?php
/*
 *
 * Plugin Name:       woo product upload image
 * Plugin URI:        n/a
 * Description:       Create a product woocommerce by uploading a photo
 * Version:           1.0.0
 * Author:            soalwp
 * Author URI:        https://soalwp.com
 * License:           GPL-2.0+
 * License URI:       n/a
 * Text Domain:       solowoo
 * Domain Path:       /languages
 *
 */
if( ! defined('ABSPATH') ) {
    return;
}
if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
    require_once dirname(__FILE__).'/vendor/autoload.php';
}
/*
 * textdomain
 *
 */
if( ! function_exists('solowoo_load_textdomain')) {
    function solowoo_load_textdomain() {
        load_plugin_textdomain('solowoo', false, basename( dirname( __FILE__ ) ) . '/languages');
    }
    add_action('init', 'solowoo_load_textdomain');
}
/*
 * const
 *
 */
define('WOOMULTI_PLUGIN', __FILE__ );
define('WOOMULTI_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR ));
define('WOOMULTI_URI', plugin_dir_url( __FILE__ ));
define('WOOMULTI_VERSION', '1.0.0');
$active=new SWCMP\Activate();
register_activation_hook(__FILE__, array($active, "active"));
if(is_admin()) {
    new SWCMP\Menu();
    new SWCMP\Actions();
}