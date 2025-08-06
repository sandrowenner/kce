<?php
if (!defined('ABSPATH')) exit;

add_action('wp_footer', function() {
    $get_cart_url = esc_url(admin_url('admin-ajax.php?action=kce_get_cart'));
    wp_enqueue_script('kce-mini-cart', plugin_dir_url(__DIR__) . '../assets/js/mini-cart.js', [], '1.3.6', true);
    ?>
    <script>
    window.kceGetCartUrl = "<?php echo $get_cart_url; ?>";
    </script>
    <?php
});