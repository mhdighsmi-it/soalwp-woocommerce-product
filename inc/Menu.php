<?php
namespace SWCMP;

if(!class_exists('Menu')){
    class Menu
    {
        public function __construct() {
            add_action( 'admin_menu', array($this,'woo_multi_plugin_menu'),10);
        }
        public function woo_multi_plugin_menu() {
            $hook= add_menu_page(
                __( 'ثبت محصول با آپلود عکس', 'solowoo' ),
                __( 'ثبت محصول با آپلود عکس', 'solowoo' ),
                'manage_options',
                'woo-multi-product',
                array($this,'woo_multi_product_settings'),
                'dashicons-products',
                6
            );
        }
        public function woo_multi_product_settings(){
            ?>
            <div class="woomulti-warp wrap">
                <h2>
                    <?php _e('ایجاد محصولات ووکامرس','solowoo');?>
                </h2>
                <nav class="nav-tab-wrapper">
                    <?php
                    foreach ( $this->woo_multi_allowed_tab() as $tab_key => $tab_label ) {
                        echo '<a href="' . esc_url( add_query_arg( array( 'tab' => $tab_key ) ) ) . '" class="nav-tab ' .$this->woo_multi_get_active_class( $tab_key ) . ' '.$tab_key.'">' . $tab_label . '</a>';
                    }
                    ?>
                </nav>
                <?php $this->woo_multi_get_tab_content(); ?>
            </div>
            <?php
        }
        function woo_multi_allowed_tab() {
            return array(
                'multi' => __('اطلاعات محصول', 'solowoo'),
            );
        }
        function woo_multi_get_active_class( $tab ) {
            return $this->woo_multi_get_active_tab() == $tab ? 'nav-tab-active' : null;
        }
        function woo_multi_get_tab_content() {
            $options = get_option( 'woo_multi' );
            $file = WOOMULTI_PATH . 'templates/tpl-' . $this->woo_multi_get_active_tab() . '.php';
            if ( is_file( $file ) && file_exists( $file ) ) {
                include $file;
                $save_function = 'woo_save_' . str_replace( '-', '_', $this->woo_multi_get_active_tab() ) . '_options';
                call_user_func(array(__NAMESPACE__ .'\Menu', $save_function));
            }
        }
        function woo_multi_get_active_tab() {
            $tab = array_keys( $this->woo_multi_allowed_tab())[0];

            if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], array_keys($this->woo_multi_allowed_tab() ) ) ) {
                $tab = $_GET['tab'];
            }
            return $tab;
        }
        function woo_save_multi_options() {
            if ( isset( $_POST['woo_save_multi'] ) ) {
                if ( ! isset( $_POST['woo_multi_nonce'] ) || ! wp_verify_nonce( $_POST['woo_multi_nonce'], 'woo_save_multi_nonce' ) ) {
                    exit( __('متاسفانه! تایید نشد','solowoo') );
                } else {
                    $this->woo_multi_update_option( 'product_create', false );
                    $this->woo_multi_update_option( 'product_title', false );
                    $this->woo_multi_update_option( 'product_categories', true,false );
                    $this->woo_multi_update_option( 'product_tags', false );
                    _e('با موفقیت ذخیره شد.','solowoo');
                }
            }
        }
        function woo_multi_update_option( $key,$sanitize = true, $html = false ) {
            $options = get_option( 'woo_multi' );
            if ( isset( $_POST[ $key ] )&& $_POST[ $key ]!='' ) {
                if ( $sanitize ) {
                    $options[ $key ] = implode(',',$_POST[ $key ]);
                } elseif ( $html ) {
                    $options[ $key ] = stripslashes( wp_filter_post_kses( addslashes( $_POST[ $key ] ) ) );
                } else {
                    $options[ $key ] = $_POST[ $key ];
                }
            }
            else {
                if ( is_array( $options ) && array_key_exists( $key, $options ) ) {
                    unset( $options[ $key ] );
                }
            }
            update_option( 'woo_multi', $options );}

    }
}