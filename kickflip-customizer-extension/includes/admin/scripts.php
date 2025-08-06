<?php
if (!defined('ABSPATH')) exit;

add_action('admin_footer', function() {
    if ('product' !== get_post_type()) { return; }
    $panel_id = 'mczr_data';
    wp_enqueue_style('kce-admin', plugin_dir_url(__DIR__) . '../assets/css/admin.css', [], '1.3.6');
    wp_enqueue_script('kce-admin', plugin_dir_url(__DIR__) . '../assets/js/admin.js', ['jquery'], '1.3.6', true);
    ?>
    <script>
    window.kcePanelId = "<?php echo esc_js($panel_id); ?>";
    </script>
    <?php
});