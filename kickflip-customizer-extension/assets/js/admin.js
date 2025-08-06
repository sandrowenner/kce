jQuery(function($) {
    const panel_id = window.kcePanelId || 'mczr_data';
    const targetNode = document.getElementById('woocommerce-product-data');
    if (!targetNode) { return; }
    function setup_kickflip_checkbox() {
        const panel = $('#' + panel_id);
        if (panel.length === 0 || $('#kce_toggle_wrapper').length > 0) { return; }
        const checkbox_html = `<div class="options_group" id="kce_toggle_wrapper" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 15px; margin-bottom: 1em;"><p class="form-field"><label for="kce_is_custom_product">Produto personalizado</label><input type="checkbox" class="checkbox" id="kce_is_custom_product" style="margin-left: 7px; margin-top: 10px;"></p></div>`;
        panel.prepend(checkbox_html);
        const checkbox = $('#kce_is_custom_product'), startingPointInput = $('#mczrStartingPoint'), fieldsContainer = panel.children().not('#kce_toggle_wrapper');
        function toggle_fields() { checkbox.is(':checked') ? fieldsContainer.slideDown() : fieldsContainer.slideUp(); }
        if (startingPointInput.val() && startingPointInput.val().trim() !== '') { checkbox.prop('checked', true); } else { checkbox.prop('checked', false); }
        toggle_fields();
        checkbox.on('change', function() { toggle_fields(); if (!$(this).is(':checked')) { startingPointInput.val(''); } });
    }
    function enforce_mczr_tab_visibility() {
        const mczr_tab = $('.product_data_tabs .mczr_tab');
        if ($('select#product-type').val() === 'variable') { mczr_tab.show(); setup_kickflip_checkbox(); } else { mczr_tab.hide(); }
    }
    const observer = new MutationObserver(enforce_mczr_tab_visibility);
    observer.observe(targetNode, { childList: true, subtree: true });
    enforce_mczr_tab_visibility();
    $('body').on('woocommerce-product-type-change', enforce_mczr_tab_visibility);
});