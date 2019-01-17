jQuery(document).ready(function ($) {
    "use strict";
    
    $(document).on('mouseenter', '.ziss-item .ziss-hotspot-wrap, .ziss-single-item-wrap .ziss-hotspot-wrap', function (e) {
        var $this = $(this);
        var this_offset = $this.offset();
        var this_left = this_offset.left;
        var this_w = $this.innerWidth();
        var thisPopup = $this.find('.ziss-hotspot-popup');
        var popup_offset = thisPopup.offset();
        var popup_left = popup_offset.left;
        var popup_w = thisPopup.innerWidth();
        var ww = $(window).innerWidth();
        var is_popup_center = false;
        
        thisPopup.addClass('ziss-current-popup');
        
        if (thisPopup.is('.popup-right') && this_left < popup_w && ww - this_left - this_w - 10 < popup_w) {
            is_popup_center = true;
        }
        if (thisPopup.is('.popup-left') && this_left < popup_w) {
            is_popup_center = true;
        }
        if (thisPopup.is('.popup-center') && this_left < popup_w && this_left + this_w + popup_w + 10 > ww) {
            is_popup_center = true;
        }
        
        if (!is_popup_center) {
            var is_popup_outside_right = this_left + this_w + popup_w + 10 > ww;
            var is_popup_outside_left = this_left + 10 < popup_w;
            
            // In case of not left and not right
            if (!is_popup_outside_left && !is_popup_outside_right) {
                thisPopup.removeClass('popup-left popup-center').addClass('popup-right');
            }
            else {
                if (is_popup_outside_right) {
                    thisPopup.removeClass('popup-right popup-center').addClass('popup-left');
                }
                if (is_popup_outside_left) {
                    thisPopup.removeClass('popup-left popup-center').addClass('popup-right');
                }
            }
            
        }
        else {
            thisPopup.removeClass('popup-left popup-right').addClass('popup-center');
            var popup_new_left = this_left - ((ww - popup_w) / 2);
            thisPopup.css({
                'left': '-' + popup_new_left + 'px',
                'right': 'auto'
            });
            var arrow_pos_left = popup_new_left + this_w / 2;
            var css_top_arrow = '';
            if (!$('head .ziss-popup-style').length) {
                $('head').append('<style type="text/css" class="ziss-popup-style">.popup-center.ziss-current-popup:before {left: ' + arrow_pos_left + 'px !important;}</style>');
            }
            else {
                $('head .ziss-popup-style').replaceWith('<style type="text/css" class="ziss-popup-style">.popup-center.ziss-current-popup:before {left: ' + arrow_pos_left + 'px !important;}</style>');
            }
        }
    });
    
    // Image popup
    $(document).on('click', '.ziss-figure-wrap .ziss-figure, .ziss-single-item-wrap .ziss-figure', function (e) {
        var $this = $(this);
        var thisItem = $this.closest('.ziss-item');
        var itemsWrap = thisItem.closest('.ziss-shop-wrap');
        var is_ziss_single = $this.closest('.ziss-single-item-wrap').length;
        
        $('.ziss-shop-wrap').removeClass('ziss-current-shop-wrap');
        itemsWrap.addClass('ziss-current-shop-wrap');
        itemsWrap.find('.ziss-item').removeClass('ziss-current-item');
        thisItem.addClass('ziss-current-item');
        
        if (!$('.ziss-popup-wrap').length) {
            $('body').append(ziss['html']['popup']);
        }
        
        var popupContentLeft = $('.ziss-popup-content .ziss-popup-body-left');
        var popupContentRight = $('.ziss-popup-content .ziss-popup-body-right');
        
        var pin_data = $this.attr('data-pin_data');
        var img_src = $this.attr('data-img_src');
        var img_w = $this.attr('data-width');
        var img_h = $this.attr('data-height');
        
        var img_html = '<div class="ziss-img-holder" data-src="' + img_src + '"><img width="' + img_w + '" height="' + img_h + '" src="' + img_src + '" alt="" title="" /></div>';
        var pins_html = '';
        var right_html = '';
        popupContentLeft.html(img_html);
        
        // Get pin data
        if ($.trim(pin_data) != '') {
            pin_data = JSON.parse(pin_data);
            $.each(pin_data, function (key, p_data) {
                var p_num = !isNaN(key) ? key + 1 : key;
                var product_html = ziss['html']['popup_product_tmp'];
                var product_title_link_html = '<a href="' + p_data['product']['permalink'] + '" title="' + p_data['product']['title'] + '">' + p_data['product']['title'] + '</a>';
                product_html = product_html.replace(/\{{product_id}}/g, p_data['product']['id']);
                product_html = product_html.replace('{{thumb_src}}', p_data['product']['thumb']['url']).replace('{{thumb_width}}', p_data['product']['thumb']['width']).replace('{{thumb_height}}', p_data['product']['thumb']['height']);
                product_html = product_html.replace('{{price_html}}', p_data['product']['price_html']).replace('{{add_to_cart_html}}', p_data['product']['add_to_cart_html']).replace('{{title}}', product_title_link_html);
                product_html = product_html.replace('{{rating_html}}', p_data['product']['rating_html']);
                right_html += product_html;
                
                // var hotspot_top_percent = p_data['top_percent'];
                // var hotspot_left_percent = p_data['left_percent'];
                // var figure_h_ratio = p_data['product']['thumb']['height'] / p_data['product']['thumb']['width'];
                // hotspot_top_percent = figure_h_ratio * hotspot_top_percent;
                
                pins_html += '<div class="ziss-hotspot-wrap" data-product_id="' + p_data['product']['id'] + '" data-top_percent="' + p_data['top_percent'] + '" data-left_percent="' + p_data['left_percent'] + '" style="top: ' + p_data['top_percent'] + '%; left: ' + p_data['left_percent'] + '%;">' +
                    '<div data-hotspot_num="' + p_num + '" class="hotspot-num hotspot-num-on-img hotspot-num-on-img-' + p_num + '"> ' +
                    '<div class="ziss-hotspot-text">' + p_num + '</div> ' +
                    '</div>' +
                    '</div>';
            });
            popupContentLeft.append(pins_html);
        }
        
        popupContentRight.html(right_html);
        if (right_html == '') {
            popupContentRight.addClass('ziss-no-content').closest('.ziss-popup-body').addClass('ziss-right-no-content');
        }
        else {
            popupContentRight.removeClass('ziss-no-content').closest('.ziss-popup-body').removeClass('ziss-right-no-content');
        }
        
        if (is_ziss_single) {
            popupContentLeft.closest('.ziss-popup-content').addClass('ziss-single-popup-content');
        }
        else {
            popupContentLeft.closest('.ziss-popup-content').removeClass('ziss-single-popup-content');
        }
        
        $('body .ziss-popup-wrap').show(300, function () {
            if ($('.ziss-custom-scroll').length) {
                $('.ziss-custom-scroll').enscroll('destroy');
            }
            ziss_left_right_popup_equal_height();
            ziss_init_custom_scroll();
            // ziss_left_right_popup_equal_height();
        });
        $('body').addClass('ziss-show-popup');
        
        e.preventDefault();
    });
    
    // Close popup
    $(document).on('click', '.ziss-close-popup, .ziss-popup-backdrop', function (e) {
        $('.ziss-popup-wrap').hide();
        $('body').removeClass('ziss-show-popup');
        
        e.preventDefault();
    });
    
    $(document).on('mouseleave', '.ziss-item .ziss-hotspot-wrap', function (e) {
        $(this).find('.ziss-hotspot-popup').removeClass('ziss-current-popup');
    });
    
    // Back/Next item
    $(document).on('click', '.ziss-popup-nav', function (e) {
        var $this = $(this);
        var currentItem = $('.ziss-current-shop-wrap .ziss-current-item');
        var currentItemsWrap = currentItem.closest('.ziss-shop-wrap');
        var newItem;
        
        // Is previous item
        if ($this.is('.ziss-popup-nav-prev')) {
            if (currentItem.is(':first-child')) {
                newItem = currentItemsWrap.find('.ziss-item:last-child');
            }
            else {
                newItem = currentItem.prev();
            }
        }
        // Is next item
        else {
            if (currentItem.is(':last-child')) {
                newItem = currentItemsWrap.find('.ziss-item:first-child');
            }
            else {
                newItem = currentItem.next();
            }
        }
        
        currentItemsWrap.find('.ziss-item').removeClass('ziss-current-item');
        newItem.addClass('ziss-current-item');
        newItem.find('.ziss-figure').trigger('click');
        
        e.preventDefault();
    });
    
    // Hover on hotspot on popup
    $(document).on('mouseenter', '.ziss-popup-body .ziss-hotspot-wrap', function () {
        var $this = $(this);
        var product_id = $this.attr('data-product_id');
        
        if (!$('.ziss-popup-body-right .ziss-product-wrap-' + product_id).is('.ziss-active-product')) {
            $('.ziss-popup-body-right .ziss-product-wrap').removeClass('ziss-active-product').addClass('ziss-inactive-product');
            $('.ziss-popup-body-right .ziss-product-wrap-' + product_id).addClass('ziss-active-product').removeClass('ziss-inactive-product');
        }
        
    });
    $(document).on('mouseleave', '.ziss-popup-body .ziss-hotspot-wrap', function () {
        $('.ziss-popup-body-right .ziss-product-wrap').removeClass('ziss-active-product ziss-inactive-product');
    });
    
    // Hover on the product on popup
    $(document).on('mouseenter', '.ziss-popup-body .ziss-product-wrap', function () {
        var $this = $(this);
        var product_id = $this.attr('data-product_id');
        
        $('.ziss-popup-body-right .ziss-product-wrap').removeClass('ziss-active-product').addClass('ziss-inactive-product');
        $this.addClass('ziss-active-product').removeClass('ziss-inactive-product');
        
        if (!$('.ziss-popup-body-left .ziss-hotspot-wrap[data-product_id=' + product_id + ']').is('.ziss-active-hotspot')) {
            $('.ziss-popup-body-left .ziss-hotspot-wrap').removeClass('ziss-active-hotspot').addClass('ziss-inactive-hotspot');
            $('.ziss-popup-body-left .ziss-hotspot-wrap[data-product_id=' + product_id + ']').addClass('ziss-active-hotspot').removeClass('ziss-inactive-hotspot');
        }
        
    });
    $(document).on('mouseleave', '.ziss-popup-body .ziss-product-wrap', function () {
        $('.ziss-popup-body-left .ziss-hotspot-wrap').removeClass('ziss-active-hotspot ziss-inactive-hotspot');
        $('.ziss-popup-body-right .ziss-product-wrap').removeClass('ziss-active-product ziss-inactive-product');
    });
    
    // Make sure left and right of popup equal height
    function ziss_left_right_popup_equal_height() {
        if ($('body').is('.ziss-show-popup')) {
            var left_img_h = $('.ziss-popup-body .ziss-popup-body-left .ziss-img-holder > img').innerHeight();
            $('.ziss-popup-body .ziss-popup-body-left').css({
                'height': left_img_h + 'px'
            });
            $('.ziss-popup-body .ziss-popup-body-right').css({
                'height': left_img_h + 'px'
            });
        }
    }
    
    // Custom scroll
    function ziss_init_custom_scroll() {
        $('.ziss-custom-scroll').each(function () {
            //$(this).parent().find('.ziss-track-wrap').remove();
            if ($(this).is('.ziss-custom-scroll-added')) {
                $(this).enscroll('destroy').removeClass('ziss-custom-scroll-added');
            }
            $(this).addClass('ziss-custom-scroll-added').enscroll({
                showOnHover: true,
                verticalTrackClass: 'ziss-ver-track',
                verticalHandleClass: 'ziss-handle',
                scrollIncrement: 60, // Default 40
                easingDuration: 200,
                addPaddingToPane: false
            });
            //$(this).parent().find('.ziss-ver-track').closest('div').addClass('ziss-track-wrap');
        });
    }
    
    $(window).resize(function () {
        ziss_left_right_popup_equal_height();
    });
    
    $(window).load(function () {
        // ziss_update_social_tab_content();
    });
    
});