jQuery(document).ready(function ($) {
    "use strict";
    
    function famibt_init_lazy_load() {
        if ($('.lazy').length > 0) {
            var _config = [];
            
            _config.beforeLoad = function (element) {
                element.parent().addClass('loading-lazy');
            };
            _config.afterLoad = function (element) {
                element.parent().removeClass('loading-lazy');
            };
            _config.effect = 'fadeIn';
            _config.enableThrottle = true;
            _config.throttle = 250;
            _config.effectTime = 1000;
            _config.threshold = 0;
            
            $('.lazy').lazy(_config);
            if ($(this).closest('.megamenu').length > 0) {
                _config.delay = 0;
            }
            
        }
    }
    
    // Select/Un-select product
    $(document).on('change', '.famibt-item input[type="checkbox"]', function (e) {
        var $this = $(this);
        var $thisWrap = $this.closest('.famibt-items-wrap');
        var $thisFamibtWrap = $thisWrap.closest('.famibt-wrap');
        var $thisProductsBtWrap = $thisFamibtWrap.find('.famibt-products-wrap');
        var total_price = 0;
        var total_items = 0;
        
        $thisWrap.find('.famibt-item input[type="checkbox"]').each(function () {
            var this_product_id = $(this).attr('data-product_id');
            if ($(this).is(':checked')) {
                var this_price = parseFloat($(this).attr('data-price'));
                if (!isNaN(this_price)) {
                    total_price += this_price;
                }
                total_items++;
                $thisProductsBtWrap.find('.famibt-product[data-product_id="' + this_product_id + '"]').removeClass('famibt-hidden');
            }
            else {
                $thisProductsBtWrap.find('.famibt-product[data-product_id="' + this_product_id + '"]:not(.famibt-main-product)').addClass('famibt-hidden');
            }
            
        });
        
        var total_price_html = famibt_woo_format_money(total_price);
        $thisFamibtWrap.find('.total-price-html').html(total_price_html);
        $thisFamibtWrap.find('.for-items-text').text(famibt['text']['for_num_of_items'].replace('{{number}}', total_items));
        
    });
    
    // Add all selected products to cart
    $(document).on('click', '.famibt-add-all-to-cart', function (e) {
        var $this = $(this);
        if ($this.is('.adding-to-cart')) {
            return false;
        }
        
        var $thisFamibtWrap = $this.closest('.famibt-wrap');
        var $itemsWrap = $thisFamibtWrap.find('.famibt-items-wrap');
        var i = 0;
        
        // Check selected products
        if (!$itemsWrap.find('.famibt-item input[type="checkbox"]:checked').length) {
            var error_msg_html = '<div class="famibt-error-message woocommerce-error">' + famibt['text']['no_product_selected_text'] + '</div>';
            if (!$thisFamibtWrap.find('.famibt-messages-wrap').length) {
                $thisFamibtWrap.prepend('<div class="famibt-messages-wrap"></div>');
            }
            $thisFamibtWrap.find('.famibt-messages-wrap').html(error_msg_html);
            return false;
        }
        
        $itemsWrap.find('.famibt-item').each(function () {
            $(this).addClass('famibt-item-' + i).attr('data-item_num', i).find('input[type="checkbox"]').prop('disabled', true);
            i++;
        });
        
        $this.addClass('adding-to-cart disabled');
        $this.text(famibt['text']['adding_to_cart_text']);
        $thisFamibtWrap.find('.famibt-messages-wrap').html('');
        famibt_add_to_cart($itemsWrap, 0);
        
    });
    
    function famibt_add_to_cart($itemsWrap, item_num) {
        var next_item_num = item_num + 1;
        var $productItem = $itemsWrap.find('.famibt-item-' + item_num);
        var $nextProductItem = $itemsWrap.find('.famibt-item-' + next_item_num);
        var $famibtWrap = $itemsWrap.closest('.famibt-wrap');
        
        if (!$productItem.length) {
            $itemsWrap.closest('.famibt-wrap').find('.famibt-add-all-to-cart').removeClass('adding-to-cart disabled').text(famibt['text']['add_to_cart_text']);
            $itemsWrap.find('.famibt-item:not(.famibt-main-item) input[type="checkbox"]').prop('disabled', false);
            famibt_display_add_to_cart_messages($itemsWrap);
            famibt_reset_add_to_cart_count_success_fail($itemsWrap);
            $(document.body).trigger('wc_fragment_refresh');
            return;
        }
        
        if (!$productItem.find('input[type="checkbox"]').is(':checked')) {
            if ($nextProductItem.length) {
                famibt_add_to_cart($itemsWrap, next_item_num);
            }
            else {
                $itemsWrap.closest('.famibt-wrap').find('.famibt-add-all-to-cart').removeClass('adding-to-cart disabled').text(famibt['text']['add_to_cart_text']);
                $itemsWrap.find('.famibt-item:not(.famibt-main-item) input[type="checkbox"]').prop('disabled', false);
                famibt_display_add_to_cart_messages($itemsWrap);
                famibt_reset_add_to_cart_count_success_fail($itemsWrap);
                $(document.body).trigger('wc_fragment_refresh');
                return;
            }
        }
        else {
            var product_id = $productItem.attr('data-product_id');
            var data = {
                action: 'woocommerce_add_to_cart',
                product_id: product_id
            };
            
            $.post(famibt['ajaxurl'], data, function (response) {
                if ($nextProductItem.length) {
                    famibt_count_add_to_cart_success_fail($itemsWrap, response);
                    famibt_add_to_cart($itemsWrap, next_item_num);
                }
                else {
                    famibt_count_add_to_cart_success_fail($itemsWrap, response);
                    
                    $famibtWrap.find('.famibt-add-all-to-cart').removeClass('adding-to-cart disabled').text(famibt['text']['add_to_cart_text']);
                    $itemsWrap.find('.famibt-item:not(.famibt-main-item) input[type="checkbox"]').prop('disabled', false);
                    
                    famibt_display_add_to_cart_messages($itemsWrap);
                    famibt_reset_add_to_cart_count_success_fail($famibtWrap);
                    
                    // $itemsWrap.closest('.famibt-wrap').html
                }
            });
        }
    }
    
    function famibt_display_add_to_cart_messages($itemsWrap) {
        var $famibtWrap = $itemsWrap.closest('.famibt-wrap');
        var count_success = parseInt($famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_success'));
        var count_fail = parseInt($famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_fail'));
        var message_success = famibt['text']['add_to_cart_success'].replace('{{number}}', count_success);
        var message_fail = '';
        if (count_fail == 1) {
            message_fail = famibt['text']['add_to_cart_fail_single'].replace('{{number}}', count_fail);
        }
        if (count_fail > 1) {
            message_fail = famibt['text']['add_to_cart_fail_plural'].replace('{{number}}', count_fail);
        }
        var count_success_html = '';
        var count_fail_html = '';
        $(document.body).trigger('wc_fragment_refresh');
        if (!$famibtWrap.find('.famibt-messages-wrap').length) {
            $famibtWrap.prepend('<div class="famibt-messages-wrap"></div>');
        }
        if ($.trim(message_success) != '') {
            var view_cart_html = '<a class="button wc-forward" href="' + famibt['cart_url'] + '">' + famibt['text']['view_cart'] + '</a>';
            count_success_html = '<div class="famibt-success-message woocommerce-message">' + view_cart_html + message_success + '</div>';
        }
        if ($.trim(message_fail) != '') {
            count_fail_html = '<div class="famibt-error-message woocommerce-error">' + message_fail + '</div>';
        }
        $famibtWrap.find('.famibt-messages-wrap').html(count_success_html + count_fail_html);
    }
    
    function famibt_count_add_to_cart_success_fail($itemsWrap, response) {
        var count_fail;
        var count_success;
        var $famibtWrap = $itemsWrap.closest('.famibt-wrap');
        if (response.hasOwnProperty('error')) {
            if (response['error']) {
                count_fail = parseInt($famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_fail'));
                if (isNaN(count_fail)) {
                    count_fail = 0;
                }
                count_fail++;
                $famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_fail', count_fail);
            }
            else {
                count_success = parseInt($famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_success'));
                if (isNaN(count_success)) {
                    count_success = 0;
                }
                count_success++;
                $famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_success', count_success);
            }
        }
        else {
            count_success = parseInt($famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_success'));
            if (isNaN(count_success)) {
                count_success = 0;
            }
            count_success++;
            $famibtWrap.find('.famibt-add-all-to-cart').attr('data-count_success', count_success);
        }
    }
    
    function famibt_reset_add_to_cart_count_success_fail($itemsWrap) {
        $itemsWrap.find('.famibt-add-all-to-cart').attr('data-count_success', 0).attr('data-count_fail', 0);
    }
    
    /**
     *
     * @param $form
     * @param response
     * @param position  top or bottom.
     */
    function famibt_display_multi_messages($form, response, position) {
        $form.find('.famibt-message').remove();
        
        var msg_class = '';
        
        if (response['err'] === 'yes') {
            msg_class += 'alert-danger';
        }
        else {
            msg_class += 'alert-success';
        }
        
        if ($.type(response['message']) === 'string') {
            if (response['message'] !== '') {
                if (position === 'top') {
                    $form.prepend('<div class="famibt-message alert ' + msg_class + '">' + response['message'] + '</div>');
                }
                else {
                    $form.append('<div class="famibt-message alert ' + msg_class + '">' + response['message'] + '</div>');
                }
            }
        }
        else {
            $.each(response['message'], function (index, item) {
                if (position === 'top') {
                    $form.prepend('<div class="famibt-message alert ' + msg_class + '">' + item + '</div>');
                }
                else {
                    $form.append('<div class="famibt-message alert ' + msg_class + '">' + item + '</div>');
                }
            });
        }
    }
    
    // Format WooCommerce money
    function famibt_woo_format_money(number) {
        return famibt_format_money(number, famibt['price_thousand_separator'], famibt['price_decimal_separator'], famibt['price_decimals'], famibt['currency_symbol'], famibt['price_format']);
    }
    
    // Format money
    function famibt_format_money(number, thousand_sep, decimal_sep, tofixed, symbol, woo_price_format) {
        var before_text = '';
        var after_text = '';
        number = number || 0;
        tofixed = !isNaN(tofixed = Math.abs(tofixed)) ? tofixed : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand_sep = thousand_sep || ",";
        decimal_sep = decimal_sep || ".";
        var negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(tofixed), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        
        symbol = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';
        
        switch (woo_price_format) {
            case '%1$s%2$s':
                //left
                before_text += symbol;
                break;
            case '%1$s %2$s':
                //left with space
                before_text += symbol + ' ';
                break;
            case '%2$s%1$s':
                //right
                after_text += symbol;
                break;
            case '%2$s %1$s':
                //right with space
                after_text += ' ' + symbol;
                break;
            default:
                //default
                before_text += symbol;
        }
        
        
        var money_return = before_text +
            negative + (j ? i.substr(0, j) + thousand_sep : "" ) +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand_sep) +
            (tofixed ? decimal_sep + Math.abs(number - i).toFixed(tofixed).slice(2) : "") +
            after_text;
        
        if (famibt['wc_tax_enabled']) {
            money_return += ' <small class="woocommerce-Price-taxLabel tax_label">' + famibt['ex_tax_or_vat'] + '</small>';
        }
        
        money_return = '<span class="woocommerce-Price-amount amount">' + money_return + '</span>';
        
        return money_return;
    }
    
    window.addEventListener('load',
        function (ev) {
            famibt_init_lazy_load();
        }
    );
    
});