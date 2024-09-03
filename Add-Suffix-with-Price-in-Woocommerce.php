<?php
/*
Plugin Name: Add Suffix with Price in Woocommerce
Description: Adds a customizable suffix to WooCommerce product prices
Version: 1.0
Author: Mayank Kumar
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Add settings field to WooCommerce product data tab
function add_price_suffix_field() {
    woocommerce_wp_text_input(
        array(
            'id' => '_price_suffix',
            'label' => __('Price Suffix', 'woocommerce'),
            'description' => __('Enter the suffix to be added after the price (e.g. "/ piece")', 'woocommerce'),
            'desc_tip' => true,
        )
    );
}
add_action('woocommerce_product_options_pricing', 'add_price_suffix_field');

// Save the custom field value
function save_price_suffix_field($post_id) {
    $price_suffix = isset($_POST['_price_suffix']) ? sanitize_text_field($_POST['_price_suffix']) : '';
    update_post_meta($post_id, '_price_suffix', $price_suffix);
}
add_action('woocommerce_process_product_meta', 'save_price_suffix_field');

// Display the price with suffix
function display_price_with_suffix($price, $product) {
    $suffix = get_post_meta($product->get_id(), '_price_suffix', true);
    if (!empty($suffix)) {
        $price .= ' ' . $suffix;
    }
    return $price;
}
add_filter('woocommerce_get_price_html', 'display_price_with_suffix', 10, 2);
add_filter('woocommerce_cart_item_price', 'display_price_with_suffix', 10, 2);
