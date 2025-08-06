<?php
if (!defined('ABSPATH')) exit;

// Remove tipo de produto mczr
add_filter('woocommerce_product_type_selector', function($types) {
    if (isset($types['mczr'])) unset($types['mczr']);
    return $types;
}, 99);

// Processa campo extra ao salvar produto
add_action('woocommerce_process_product_meta_variable', function($post_id) {
    if (class_exists('MyCustomizer\\WooCommerce\\Connector\\Controller\\Admin\\MczrProductTypeController')) {
        $controller = new \MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrProductTypeController();
        $controller->processAndSaveTabMetaField();
    }
});