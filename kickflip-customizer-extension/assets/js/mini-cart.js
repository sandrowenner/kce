document.addEventListener('DOMContentLoaded', function () {
    function renderKickflipAccordion(miniCart) {
        miniCart.querySelectorAll('.kce-accordion').forEach(e => e.remove());
        miniCart.querySelectorAll('.woocommerce-mini-cart-item').forEach(function (item) {
            let removeBtn = item.querySelector('a[data-cart_item_key]');
            if (!removeBtn) return;
            let cartItemKey = removeBtn.getAttribute('data-cart_item_key');
            if (!cartItemKey) return;

            fetch(window.kceGetCartUrl)
                .then(response => response.json())
                .then(obj => {
                    let data = obj.data || obj;
                    if (!data.items) return;
                    let cartItem = data.items.find(i => i.cart_item_key === cartItemKey);
                    if (!cartItem) return;
                    if (cartItem.mczrMetas && cartItem.mczrMetas.summary_v2 && Array.isArray(cartItem.mczrMetas.summary_v2)) {
                        let productName = item.querySelector('.product-name');
                        if (!productName) return;
                        
                        let texts = [], images = [];
                        cartItem.mczrMetas.summary_v2.forEach(summary => {
                            const key = summary.key || summary.label || "";
                            let value = summary.value || "";
                            if (Array.isArray(value)) {
                                value = value.join(', ');
                            }
                            if (/\.(jpg|jpeg|png|gif|webp)$/i.test(value)) {
                                images.push({key, value});
                            } else {
                                texts.push({key, value});
                            }
                        });
                        let accordion = document.createElement('div');
                        accordion.className = 'kce-accordion';
                        accordion.innerHTML = `
                            <button class="kce-accordion-toggle" type="button" aria-expanded="false">
                                <span class="kce-accordion-label">Ver Personalização</span>
                                <span class="kce-accordion-arrow">
                                    <svg width="18" height="18" viewBox="0 0 24 24" style="display:block"><polyline points="6 9 12 15 18 9" fill="none" stroke="#D90A2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                            </button>
                            <div class="kce-accordion-content" style="font-size: 0.9rem;">
                                ${texts.map(t => t.key !== '' ? `<div style="margin-bottom:4px;"><strong>${t.key}:</strong> ${t.value}</div>` : '').join('')}
                                ${images.map(img => `<div style="margin-top:8px;"><strong>${img.key}:</strong><br><img src="${img.value}" style="max-width:110px;width:100%;height:auto;object-fit:contain;border-radius:8px;border:1px solid #eee;vertical-align:middle;display:block;background:#fff;" /></div>`).join('')}
                            </div>
                        `;
                        productName.appendChild(accordion);
                    }
                });
        });
    }

    function customizaMiniCart() {
        var miniCart = document.querySelector('.cart-mini');
        if (!miniCart) return;
        renderKickflipAccordion(miniCart);
    }

    document.body.addEventListener('click', function(event) {
        if(event.target.closest('[data-js="open-mini-cart"]') || event.target.closest('.cart-icon')) {
            setTimeout(customizaMiniCart, 800);
        }
    });
    document.addEventListener('wc_fragments_refreshed', customizaMiniCart);
});