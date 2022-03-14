<?php
namespace SWCMP;
class Actions
{
    function __construct()
    {
        add_action( 'add_attachment', array($this,'soalwp_create_product_automatically'), 9999 );
    }

    function soalwp_create_product_automatically( $image_id ) {
        $options = get_option( 'woo_multi' );
        if($options['product_create']) {
            $product = new \WC_Product_Simple();
            $product->save();
            $product->set_name($options['product_title'] .__('کد:','solowoo'). ' '. $product->get_id());
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_price(0);
            $product->set_regular_price(0);
            $product->set_sold_individually(true);
            $product->set_image_id($image_id);
            $product->set_downloadable(false);
            $product->set_virtual(false);
            $product->set_category_ids(explode(',', $options['product_categories']));
            $product->set_sku($product->get_id());
            $product->set_slug($options['product_title'].'-'.$product->get_id());
            $product->save();
            wp_set_object_terms($product->get_id(), explode(',', $options['product_tags']), 'product_tag');
        }
    }
}