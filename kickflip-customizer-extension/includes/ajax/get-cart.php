<?php
if (!defined('ABSPATH')) exit;

// Endpoint AJAX: busca itens do carrinho
add_action('wp_ajax_kce_get_cart', 'kce_get_cart_callback');
add_action('wp_ajax_nopriv_kce_get_cart', 'kce_get_cart_callback');
function kce_get_cart_callback() {
    $cart = WC()->cart->get_cart();
    $items = [];
    foreach ($cart as $cart_item_key => $cart_item) {
        $item = [
            'cart_item_key' => $cart_item_key,
            'product_id'    => $cart_item['product_id'],
            'variation_id'  => $cart_item['variation_id'] ?? null,
            'name'          => $cart_item['data']->get_name(),
            'mczrMetas'     => $cart_item['mczrMetas'] ?? [],
        ];
        $items[] = $item;
    }
    wp_send_json_success(['items' => $items]);
}