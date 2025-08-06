<?php
if (!defined('ABSPATH')) exit;

// Produto personalizado vendido individualmente
add_filter('woocommerce_is_sold_individually', function($is_sold_individually, $product) {
    if (!empty($product->get_meta('mczrStartingPoint'))) {
        return true;
    }
    return $is_sold_individually;
}, 10, 2);

// Adiciona unique_key ao item do carrinho
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
    if (isset($cart_item_data['mczrMetas'])) {
        $cart_item_data['unique_key'] = md5(json_encode($cart_item_data['mczrMetas']) . $variation_id);
    }
    return $cart_item_data;
}, 10, 3);

// Thumbnail do produto = imagem customizada
add_filter('woocommerce_cart_item_thumbnail', function ($product_image, $cart_item) {
    if (!empty($cart_item['mczrMetas']['image'])) {
        return '<img src="' . esc_url($cart_item['mczrMetas']['image']) . '" />';
    }
    return $product_image;
}, 10, 2);

// Salva metas customizadas no pedido
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (isset($values['mczrMetas'])) {
        $item->add_meta_data('_mczr_metas', $values['mczrMetas']);
        if (isset($values['mczrMetas']['summary_v2'])) {
            foreach ($values['mczrMetas']['summary_v2'] as $summary) {
                if (!empty($summary['key']) && isset($summary['value'])) {
                    $value = is_array($summary['value']) ? implode(', ', array_map('trim', $summary['value'])) : $summary['value'];
                    $item->add_meta_data(sanitize_text_field($summary['key']), sanitize_text_field($value));
                }
            }
        }
    }
}, 10, 4);

// Remove JS original se necessário
add_action('wp_enqueue_scripts', function() {
    if (is_product()) {
        $product = wc_get_product(get_the_ID());
        if ($product && $product->is_type('variable') && !empty($product->get_meta('mczrStartingPoint'))) {
            wp_dequeue_script('mczr');
        }
    }
}, 999);