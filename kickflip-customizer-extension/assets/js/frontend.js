jQuery(function($) {
    var customButton = $('#kce-customize-button');
    var variationsForm = $('form.variations_form');
    var selects = variationsForm.find('select');
    var variationWrap = $('.single_variation_wrap');

    function areAllVariationsSelected() {
        var allSelected = true;
        selects.each(function() {
            if (!$(this).val() || $(this).val() === '' || $(this).val() === '0') {
                allSelected = false;
            }
        });
        return allSelected;
    }

    function checkAndToggleButton() {
        if (areAllVariationsSelected() && variationWrap.is(':visible') && $.trim(variationWrap.text()).length > 0) {
            customButton.prop('disabled', false).removeClass('disabled');
        } else {
            customButton.prop('disabled', true).addClass('disabled');
        }
    }

    setInterval(checkAndToggleButton, 200);
    selects.on('change', checkAndToggleButton);
    variationsForm.on('found_variation reset_data hide_variation', checkAndToggleButton);

    customButton.on('click', function(e) {
        e.preventDefault();
        if ($(this).is(':disabled')) {
            alert('Por favor, selecione todas as opções antes de customizar.');
            return;
        }
        var iframeUrl = window.kceStartingPointUrl;
        var params = new URLSearchParams();
        variationsForm.serializeArray().forEach(function(item) {
            if (item.name.startsWith('attribute_')) { params.append(item.name, item.value); }
        });
        iframeUrl += '?' + params.toString();
        $('#kce-iframe').attr('src', iframeUrl);
        $('#kce-popup-wrapper').css('display', 'flex');
        $('body').addClass('kce-modal-open');
    });

    window.addEventListener('message', function(event) {
        if (event.origin !== 'https://quadriframe.gokickflip.com' || !event.data || event.data.eventName !== 'mczrAddToCart') return;
        $('#kce-popup-wrapper').hide().find('iframe').attr('src', 'about:blank');
        $('body').removeClass('kce-modal-open');
        var details = event.data.detail;
        var props = {
            image: details.designImage ? details.designImage.url : '',
            designId: details._id || details.id,
            summary_v2: details.summary || []
        };

        var formData = new FormData();
        formData.append('action', 'kce_ajax_add_to_cart');
        formData.append('product_id', $('input[name="product_id"]').val());

        let attrs = {};
        variationsForm.serializeArray().forEach(function(item) {
            if (item.name.startsWith('attribute_')) { 
                attrs[item.name] = item.value;
                formData.append(item.name, item.value); 
            }
        });

        let variationsData = jQuery('form.variations_form').data("product_variations");
        let findVariationId = variationsData.find(e => {
            if (e.attributes) {
                let match = true;
                for (const attr in attrs) {
                    if (e.attributes[attr] !== attrs[attr]) {
                        match = false;
                        break;
                    }
                }
                return match;
            }
            return false;
        });

        if (findVariationId) {
            formData.append('variation_id', findVariationId.variation_id);
        } else {
            formData.append('variation_id', '');
        }

        formData.append('kickflip_props', JSON.stringify(props));

        $.ajax({
            type: 'POST',
            url: window.kceAdminAjaxUrl,
            data: formData,
            processData: false, contentType: false,
            success: function(response) {
                if (response.success) {
                    $(document.body).trigger('wc_fragment_refresh');
                    if (response.data && response.data.product_name) {
                        $('.woo-alert-group').remove();
                        var msg = $('<div class="woo-alert-group"><div class="ajax-cart-response alert -fixed -success"><p>' 
                            + response.data.product_name + ' has been added to the cart&nbsp;<a class="view_cart_button" href="/carrinho/">Ver carrinho de compras</a></p>' +
                            '<button class="icon-button -extra-small" aria-label="close"> <i class="icon">' +
                            '<svg class="default" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z"></path></svg></i></button></div></div>');
                        $('main, #main, body').first().prepend(msg);
                        msg.find('button[aria-label="close"]').on('click', function(){ msg.remove(); });
                        setTimeout(function(){ msg.fadeOut(400, function(){ $(this).remove(); }); }, 4000);
                    }
                } else {
                    alert('Erro: ' + (response.data.message || 'Erro desconhecido.'));
                }
            }
        });
    });

    $('body').on('click', '#kce-popup-close', function() {
        $('#kce-popup-wrapper').hide().find('iframe').attr('src', 'about:blank');
        $('body').removeClass('kce-modal-open');
    });

    function initWishlistJS() {
        if (typeof yith_wcwl_init != "undefined") {
            yith_wcwl_init();
        }
    }
    initWishlistJS();
    $(document.body).on('found_variation reset_data hide_variation', function() {
        setTimeout(initWishlistJS, 200);
    });
});