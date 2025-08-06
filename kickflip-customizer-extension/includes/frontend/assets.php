<?php
if (!defined('ABSPATH')) exit;

add_action('wp_footer', function() {
    if (!is_product()) return;
    $product = wc_get_product(get_the_ID());
    if (!$product || !$product->is_type('variable') || empty($product->get_meta('mczrStartingPoint'))) return;

    $starting_point = esc_js($product->get_meta('mczrStartingPoint'));
    $admin_ajax_url = esc_url(admin_url('admin-ajax.php'));

    wp_enqueue_style('kce-frontend', plugin_dir_url(__DIR__) . '../assets/css/frontend.css', [], '1.3.6');
    wp_enqueue_script('kce-frontend', plugin_dir_url(__DIR__) . '../assets/js/frontend.js', ['jquery'], '1.3.6', true);
    ?>
    <script>
    window.kceStartingPointUrl = "https://quadriframe.gokickflip.com/customize/startingpoint/<?php echo $starting_point; ?>";
    window.kceAdminAjaxUrl = "<?php echo $admin_ajax_url; ?>";
    </script>
    <div id="kce-popup-wrapper">
        <div id="kce-popup-content">
            <div id="kce-popup-close">×</div>
            <div id="kce-popup-iframe-container"><iframe id="kce-iframe" src="about:blank"></iframe></div>
        </div>
    </div>
    <?php
});