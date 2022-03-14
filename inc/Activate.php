<?php
namespace SWCMP;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if(!class_exists("Activate")) {
    class Activate
    {
        public function __construct()
        {

        }

        public function active()
        {
            if( !class_exists( 'WooCommerce' ) ) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                wp_die( __( 'Please install and Activate WooCommerce.', 'wooholo' ), 'Plugin dependency check', array( 'back_link' => true ) );
            }

        }
    }
}