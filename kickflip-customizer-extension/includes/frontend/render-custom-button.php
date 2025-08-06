<?php
if (!defined('ABSPATH')) exit;

add_action('woocommerce_before_variations_form', 'kce_prepare_custom_button_area');
function kce_prepare_custom_button_area() {
    if (!is_product()) return;
    $product = wc_get_product(get_the_ID());
    if (!$product || !$product->is_type('variable') || empty($product->get_meta('mczrStartingPoint'))) return;

    remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);
    remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);

    add_action('woocommerce_single_variation', 'kce_render_custom_button_area', 15);
}

function kce_render_custom_button_area() {
    ?>
    <div class="woocommerce-variation-add-to-cart kce-custom-button-container">
        <button type="button" id="kce-customize-button" class="single_add_to_cart_button button alt" disabled>
            <?php echo esc_html__('Customizar', 'kickflip-customizer-extension'); ?>
        </button>
        <span class="kce-wishlist-area">
            <?php 
                if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
                    global $product;
                    echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]');
                }
            ?>
        </span>
    </div>
    <?php
}