<?php
if (!defined('ABSPATH')) exit;

// Endpoint AJAX: adicionar ao carrinho
add_action('wp_ajax_kce_ajax_add_to_cart', 'kce_ajax_add_to_cart_callback');
add_action('wp_ajax_nopriv_kce_ajax_add_to_cart', 'kce_ajax_add_to_cart_callback');
function kce_ajax_add_to_cart_callback() {
    $product_id = absint($_POST['product_id']);
    $variation_id = absint($_POST['variation_id']); 
    $kickflip_props = isset($_POST['kickflip_props']) ? json_decode(stripslashes($_POST['kickflip_props']), true) : [];

    if (empty($product_id) || empty($variation_id)) { 
        wp_send_json_error(['message' => 'ID do produto ou da variação está ausente.']);
        wp_die();
    }
    
    $variation_attributes = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'attribute_') === 0) {
            $variation_attributes[$key] = sanitize_text_field($value);
        }
    }

    $cart_item_data = ['mczrMetas' => $kickflip_props];
    $cart_item_key = WC()->cart->add_to_cart($product_id, 1, $variation_id, $variation_attributes, $cart_item_data);

    // Sempre retorna o nome do produto principal (não da variação)
    $product_obj = wc_get_product($product_id);
    $product_name = $product_obj ? $product_obj->get_name() : '';

    if ($cart_item_key) {
        wp_send_json_success([
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', []),
            'cart_hash' => WC()->cart->get_cart_hash(),
            'product_name' => $product_name,
        ]);
    } else {
        wp_send_json_error(['message' => 'Não foi possível adicionar este item ao carrinho.']);
    }
    wp_die();
}