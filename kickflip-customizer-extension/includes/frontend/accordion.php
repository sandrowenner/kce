<?php
if (!defined('ABSPATH')) exit;

add_action('woocommerce_after_cart_item_name', function($cart_item, $cart_item_key) {
    if (isset($cart_item['mczrMetas']['summary_v2']) && is_array($cart_item['mczrMetas']['summary_v2'])) {
        $texts = [];
        $uploaded_images = [];
        foreach($cart_item['mczrMetas']['summary_v2'] as $summary) {
            $key = !empty($summary['key']) ? $summary['key'] : (!empty($summary['label']) ? $summary['label'] : '');
            $value = isset($summary['value']) ? $summary['value'] : '';
            if (is_array($value)) {
                $value = implode(', ', array_map('trim', $value));
            }
            if (filter_var($value, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $value)) {
                $uploaded_images[] = [ 'title' => $key, 'url' => $value ];
            } else {
                $texts[] = [ 'key' => $key, 'value' => $value ];
            }
        }
        ?>
        <div class="kce-accordion">
            <button class="kce-accordion-toggle" type="button" aria-expanded="false">
                <span class="kce-accordion-label">Ver Personalização</span>
                <span class="kce-accordion-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" style="display:block"><polyline points="6 9 12 15 18 9" fill="none" stroke="#D90A2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </button>
            <div class="kce-accordion-content" style="font-size: 0.9rem;">
                <?php
                foreach ($texts as $txt) {
                    if ($txt['key'] !== '') {
                        echo '<div style="margin-bottom:4px;"><strong style="font-weight:600;">' . esc_html($txt['key']) . ':</strong> ' . esc_html($txt['value']) . '</div>';
                    }
                }
                foreach ($uploaded_images as $img) {
                    echo '<div style="margin-top:8px;">
                            <strong style="font-weight:600;">' . esc_html($img['title']) . ':</strong><br>
                            <img src="' . esc_url($img['url']) . '" style="max-width:110px;width:100%;height:auto;object-fit:contain;border-radius:8px;border:1px solid #eee;vertical-align:middle;display:block;background:#fff;" alt="' . esc_attr($img['title']) . '" />
                          </div>';
                }
                ?>
            </div>
        </div>
        <?php
    }
}, 20, 2);

add_action('wp_footer', function() {
    wp_enqueue_style('kce-accordion', plugin_dir_url(__DIR__) . '../assets/css/accordion.css', [], '1.3.6');
    wp_enqueue_script('kce-accordion', plugin_dir_url(__DIR__) . '../assets/js/accordion.js', ['jquery'], '1.3.6', true);
});