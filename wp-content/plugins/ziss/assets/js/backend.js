jQuery(document).ready(function ($) {
    "use strict";
    
    $(document).on('click', '.ziss-tabs .ziss-nav', function (e) {
        var $this = $(this);
        var thisTab = $this.closest('.ziss-tabs');
        var tab_name = $this.attr('data-tab');
        
        if ($this.is('.active')) {
            return false;
        }
        
        thisTab.find('.ziss-nav').removeClass('active');
        thisTab.find('.tab-content').removeClass('ziss-show');
        
        $this.addClass('active');
        thisTab.find('#tab-' + tab_name).addClass('ziss-show');
        
        e.preventDefault();
    });
    
    // Click on image item to add
    $(document).on('click', '.img-item .ziss-add-image', function (e) {
        var $this = $(this);
        var img_src = $this.attr('data-src');
        var img_w = $this.find('> img').attr('width');
        var img_h = $this.find('> img').attr('height');
        var social_source = $this.attr('data-social_source');
        var remove_img_html = '<a class="remove-img-item" href="#" data-src="' + img_src + '" title="Remove"><i class="fa fa-minus"></i></a>';
        var added_item_html = '<div class="used-img-item-wrap col-md-3"><div class="used-img-item hover-zoom-img"><figure data-pin_data="" data-src="' + img_src + '" data-width="' + img_w + '" data-height="' + img_h + '" data-social_source="' + social_source + '" style="background-image: url(' + img_src + ');"></figure>' + remove_img_html + '</div></div>';
        
        if (!$('.ziss-used-imgs .used-img-item figure[data-src="' + img_src + '"]').length) {
            $('.ziss-used-imgs .ziss-sortable').sortable('destroy');
            $('.ziss-used-imgs .used-img-items').append(added_item_html).addClass('row ziss-sortable');
            $('.ziss-used-imgs').addClass('has-img');
            
            $this.addClass('added-to-list').closest('.img-item').addClass('added-img-item');
        }
        if ($('.ziss-sortable .used-img-item-wrap').length) {
            ziss_init_sortable();
        }
        
        ziss_update_social_shop_pin_data();
        
        e.preventDefault();
    });
    
    // Remove used image
    $(document).on('click', '.used-img-item .remove-img-item', function (e) {
        var $this = $(this);
        var thisItemWrap = $this.closest('.used-img-item-wrap');
        var allImgsWrap = $this.closest('.used-img-items');
        var img_src = $this.attr('data-src');
        
        thisItemWrap.remove();
        allImgsWrap.sortable('destroy');
        if (!allImgsWrap.find('.used-img-item-wrap').length) {
            allImgsWrap.removeClass('ziss-sortable');
            allImgsWrap.closest('.ziss-used-imgs').removeClass('has-img');
        }
        else {
            ziss_init_sortable();
        }
        $('.img-items .ziss-add-image[data-src="' + img_src + '"]').removeClass('added-to-list').closest('.img-item').removeClass('added-img-item');
        
        ziss_update_social_shop_pin_data();
        
        e.preventDefault();
    });
    
    // Show pin form popup
    $(document).on('click', '.ziss-used-imgs .used-img-item > figure', function (e) {
        var $this = $(this);
        var img_src = $this.attr('data-src');
        var pin_data = $this.attr('data-pin_data');
        
        if (!$('.ziss-popup-wrap').length) {
            $('body').append(ziss['html']['popup']);
        }
        
        var popupContentLeft = $('.ziss-popup-content .ziss-popup-body-left');
        var popupContentRight = $('.ziss-popup-content .ziss-popup-body-right');
        var img_html = '<div class="img-holder" data-src="' + img_src + '"><img src="' + img_src + '" alt="" title="" /></div>';
        var add_hotpost_btn_html = '<a href="#" class="add-hotspot-btn ziss-hover-scan"><i class="fa fa-plus"></i></a>';
        
        popupContentLeft.html(img_html);
        popupContentRight.html(ziss['html']['right_popup_title'] + '<div class="ziss-hotspots-list"></div>' + add_hotpost_btn_html);
        
        // Get pin data
        if ($.trim(pin_data) != '') {
            pin_data = JSON.parse(pin_data);
            $.each(pin_data, function (key, p_data) {
                var p_num = !isNaN(key) ? key + 1 : key;
                var hotspot_num_img_html = '<div data-hotspot_num="' + p_num + '" class="hotspot-num ziss-draggable hotspot-num-on-img hotspot-num-on-img-' + p_num + '" data-product_id="' + p_data['product_id'] + '" data-top_percent="' + p_data['top_percent'] + '" data-left_percent="' + p_data['left_percent'] + '" style="top: ' + p_data['top_percent'] + '%; left: ' + p_data['left_percent'] + '%;">' + p_num + '</div>';
                popupContentLeft.find('.img-holder').append(hotspot_num_img_html);
                var add_hotspot_html = '<div data-hotspot_num="' + p_num + '" class="add-hotspot-wrap add-hotspot-wrap-num-' + p_num + '">' +
                    '<div class="add-hotspot-left-wrap">' +
                    '<div class="hotspot-num hotspot-num-on-list hotspot-num-on-list-' + p_num + '">' + p_num + '</div>' +
                    '<a href="#" class="remove-hotspot ziss-hover-scan-15"><i class="fa fa-trash"></i></a>' +
                    '</div>' +
                    ziss['html']['select_product'] +
                    '</div><!-- .add-hotspot-wrap -->';
                var post_id = $('#post_ID').val();
                var single_img_shortcode = '[ziss id="' + post_id + '" img_src="' + img_src + '"]';
                popupContentRight.find('.ziss-hotspots-list').append(add_hotspot_html);
                popupContentRight.find('.add-hotspot-wrap-num-' + p_num + ' .ziss-product-select').val(p_data['product_id']);
                $('.ziss-popup-content .ziss-single-shortcode').html(single_img_shortcode);
            });
            
            if ($('.ziss-draggable.ui-draggable').length) {
                $('.ziss-draggable.ui-draggable').draggable('destroy');
            }
            ziss_init_draggable();
        }
        
        ziss_update_product_thumb_preview();
        
        $('body .ziss-popup-wrap').show(300, function () {
            ziss_left_right_popup_equal_height();
        });
        $('body').addClass('ziss-show-popup');
        
        e.preventDefault();
    });
    
    // Add hotspot
    $(document).on('click', '.ziss-popup-body .add-hotspot-btn', function (e) {
        var $this = $(this);
        var select_product_html = ziss['html']['select_product'];
        var add_hotspot_num = 1;
        var number_and_action_html = '';
        
        while ($('.ziss-hotspots-list .add-hotspot-wrap-num-' + add_hotspot_num).length) {
            add_hotspot_num++;
        }
        number_and_action_html = '<div class="add-hotspot-left-wrap"><div class="hotspot-num hotspot-num-on-list hotspot-num-on-list-' + add_hotspot_num + '">' + add_hotspot_num + '</div><a href="#" class="remove-hotspot ziss-hover-scan-15"><i class="fa fa-trash"></i></a></div>';
        $('.ziss-popup-body .ziss-hotspots-list').append('<div data-hotspot_num="' + add_hotspot_num + '" class="add-hotspot-wrap add-hotspot-wrap-num-' + add_hotspot_num + '">' + number_and_action_html + select_product_html + '</div>');
        $('.ziss-popup-body-left .img-holder').append('<div class="hotspot-num ziss-draggable hotspot-num-on-img hotspot-num-on-img-' + add_hotspot_num + '" data-product_id="0" data-top_percent="50" data-left_percent="50" style="top: 50%; left: 50%;">' + add_hotspot_num + '</div>');
        
        if ($('.ziss-draggable.ui-draggable').length) {
            $('.ziss-draggable.ui-draggable').draggable('destroy');
        }
        ziss_update_social_shop_pin_data();
        ziss_init_draggable();
        
        e.preventDefault();
    });
    
    // Add hotspot when click on the holder image
    $(document).on('click', '.ziss-popup-body-left .img-holder > img', function (e) {
        var $this = $(this);
        var thisImgHolder = $this.closest('.img-holder');
        var holder_w = thisImgHolder.innerWidth();
        var holder_h = thisImgHolder.innerHeight();
        var cursor_pos = {}; // Percent
        var img_holder_offset = thisImgHolder.offset();
        
        cursor_pos = {
            left: ((e.pageX - img_holder_offset.left - 16) / holder_w) * 100,
            top: ((e.pageY - img_holder_offset.top - 16) / holder_h) * 100
        };
        
        var select_product_html = ziss['html']['select_product'];
        var add_hotspot_num = 1;
        var number_and_action_html = '';
        
        while ($('.ziss-hotspots-list .add-hotspot-wrap-num-' + add_hotspot_num).length) {
            add_hotspot_num++;
        }
        number_and_action_html = '<div class="add-hotspot-left-wrap"><div class="hotspot-num hotspot-num-on-list hotspot-num-on-list-' + add_hotspot_num + '">' + add_hotspot_num + '</div><a href="#" class="remove-hotspot ziss-hover-scan-15"><i class="fa fa-trash"></i></a></div>';
        $('.ziss-popup-body .ziss-hotspots-list').append('<div data-hotspot_num="' + add_hotspot_num + '" class="add-hotspot-wrap add-hotspot-wrap-num-' + add_hotspot_num + '">' + number_and_action_html + select_product_html + '</div>');
        $('.ziss-popup-body-left .img-holder').append('<div class="hotspot-num ziss-draggable hotspot-num-on-img hotspot-num-on-img-' + add_hotspot_num + '" data-product_id="0" data-top_percent="' + cursor_pos['top'] + '" data-left_percent="' + cursor_pos['left'] + '" style="top: ' + cursor_pos['top'] + '%; left: ' + cursor_pos['left'] + '%;">' + add_hotspot_num + '</div>');
        
        if ($('.ziss-draggable.ui-draggable').length) {
            $('.ziss-draggable.ui-draggable').draggable('destroy');
        }
        ziss_update_social_shop_pin_data();
        ziss_init_draggable();
        
    });
    
    // Remove hotspot
    $(document).on('click', '.remove-hotspot', function (e) {
        var $this = $(this);
        var thisHotspotWrap = $this.closest('.add-hotspot-wrap');
        var this_hotspot_num = thisHotspotWrap.attr('data-hotspot_num');
        if ($('.ziss-draggable.ui-draggable').length) {
            $('.ziss-draggable.ui-draggable').draggable('destroy');
        }
        if ($('.hotspot-num-on-img-' + this_hotspot_num).length) {
            $('.hotspot-num-on-img-' + this_hotspot_num).remove();
        }
        thisHotspotWrap.remove();
        ziss_update_current_hotspot_num();
        ziss_init_draggable();
        ziss_update_social_shop_pin_data();
        
        e.preventDefault();
    });
    
    // Load more Media images
    $(document).on('click', '.ziss-load-more-media-btn', function (e) {
        var $this = $(this);
        var thisTabContent = $this.closest('.tab-content');
        var thisImgItems = thisTabContent.find('.img-items');
        var paged = $this.attr('data-page');
        var nonce = $('.ziss-load-more-nonce').val();
        
        if ($this.is('.processing')) {
            return false;
        }
        
        $this.addClass('processing');
        
        var data = {
            action: 'ziss_load_more_media_images_via_ajax',
            paged: paged,
            nonce: nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            console.log(response['html']);
            if (response['err'] === 'yes') {
                $this.removeClass('processing');
            }
            else {
                $this.removeClass('processing');
                thisImgItems.append(response['html']);
                if (response['has_more'] === 'yes') {
                    $this.attr('data-page', response['next_page']);
                }
                else {
                    $this.remove();
                }
                ziss_update_social_tab_content();
            }
            
        });
        
        e.preventDefault();
    });
    
    // Load more Instagram images
    $(document).on('click', '.ziss-load-more-instagram-btn', function (e) {
        var $this = $(this);
        var next_page_url = $this.attr('href');
        var nonce = $('.ziss-load-more-nonce').val();
        
        if ($this.is('.processing')) {
            return false;
        }
        
        $this.addClass('processing');
        
        var data = {
            action: 'ziss_load_more_instagram_images_via_ajax',
            next_page_url: next_page_url,
            nonce: nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] === 'yes') {
                $this.removeClass('processing');
            }
            else {
                $this.removeClass('processing');
                $('.instagram-items').append(response['html']);
                if (response['has_more'] === 'yes') {
                    $this.attr('href', response['next_url']);
                }
                else {
                    $this.remove();
                }
                ziss_update_social_tab_content();
            }
            
        });
        
        e.preventDefault();
    });
    
    // Load more Instagram images
    $(document).on('click', '.ziss-load-more-fb-btn', function (e) {
        var $this = $(this);
        var thisTabContent = $this.closest('.tab-content');
        var thisImgItems = thisTabContent.find('.img-items');
        var next_page_url = $this.attr('href');
        var nonce = $('.ziss-load-more-nonce').val();
        
        if ($this.is('.processing')) {
            return false;
        }
        
        $this.addClass('processing');
        
        var data = {
            action: 'ziss_load_more_fb_images_via_ajax',
            next_page_url: next_page_url,
            nonce: nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] === 'yes') {
                $this.removeClass('processing');
            }
            else {
                $this.removeClass('processing');
                thisImgItems.append(response['html']);
                if (response['has_more'] === 'yes') {
                    $this.attr('href', response['next_url']);
                }
                else {
                    $this.remove();
                }
                ziss_update_social_tab_content();
            }
            
        });
        
        e.preventDefault();
    });
    
    function ziss_update_current_hotspot_num() {
        if ($('body').is('.ziss-show-popup')) {
            var hp_num = 0;
            $('.ziss-hotspots-list').html('');
            $('.ziss-popup-body-left .img-holder .hotspot-num-on-img').each(function () {
                hp_num++;
                var product_id = $(this).attr('data-product_id');
                var this_cur_hp_num = $(this).attr('data-hotspot_num');
                $(this).removeClass('hotspot-num-on-img-' + this_cur_hp_num).addClass('hotspot-num-on-img-' + hp_num).text(hp_num).attr('data-hotspot_num', hp_num);
                
                var number_and_action_html = '<div class="add-hotspot-left-wrap"><div class="hotspot-num hotspot-num-on-list hotspot-num-on-list-' + hp_num + '">' + hp_num + '</div><a href="#" class="remove-hotspot ziss-hover-scan-15"><i class="fa fa-trash"></i></a></div>';
                $('.ziss-popup-body .ziss-hotspots-list').append('<div data-hotspot_num="' + hp_num + '" class="add-hotspot-wrap add-hotspot-wrap-num-' + hp_num + '">' + number_and_action_html + ziss['html']['select_product'] + '</div>');
                
                $('.ziss-hotspots-list .add-hotspot-wrap-num-' + hp_num + ' .ziss-product-select').val(product_id);
                
            });
            
            ziss_update_social_shop_pin_data();
        }
    }
    
    // Close popup
    $(document).on('click', '.ziss-close-popup, .ziss-popup-backdrop', function (e) {
        $('.ziss-popup-wrap').hide();
        $('body').removeClass('ziss-show-popup');
        
        e.preventDefault();
    });
    
    console.log('thumb 2');
    
    // Choose product
    $(document).on('change', '.add-hotspot-wrap .ziss-product-select', function () {
        var $this = $(this);
        var thisHotspotWrap = $this.closest('.add-hotspot-wrap');
        var product_id = $this.val();
        var hotspot_num = thisHotspotWrap.attr('data-hotspot_num');
        $('.img-holder .hotspot-num-on-img-' + hotspot_num).attr('data-product_id', product_id);
        
        ziss_update_product_thumb_preview();
        ziss_update_social_shop_pin_data();
    });
    
    function ziss_update_product_thumb_preview() {
        $('.ziss-hotspots-list .add-select-product-wrap .ziss-product-select').each(function () {
            var $this = $(this);
            var thisSelectWrap = $this.closest('.add-select-product-wrap');
            var thumb_preview_url = $(this).find('option:selected').attr('data-thumb_src');
            if (!thisSelectWrap.find('.ziss-thumb-preview-wrap').length) {
                thisSelectWrap.append('<div class="ziss-thumb-preview-wrap"></div>');
            }
            if ($.trim(thumb_preview_url) != '') {
                thisSelectWrap.find('.ziss-thumb-preview-wrap').html('<img class="ziss-product-thumb-preview ziss-thumb-preview" src="' + thumb_preview_url + '" width="150" height="150" alt="" />');
            }
            else {
                thisSelectWrap.find('.ziss-thumb-preview-wrap').html('');
            }
        });
    }
    
    // Make sure left and right of popup equal height
    function ziss_left_right_popup_equal_height() {
        if ($('body').is('.ziss-show-popup')) {
            var left_img_h = $('.ziss-popup-body .ziss-popup-body-left .img-holder > img').innerHeight();
            $('.ziss-popup-body .ziss-popup-body-left').css({
                'height': left_img_h + 'px'
            });
            $('.ziss-popup-body .ziss-popup-body-right').css({
                'height': left_img_h + 'px'
            });
        }
    }
    
    // Update social_shop_pin data
    function ziss_update_social_shop_pin_data() {
        
        if ($('body').is('.ziss-show-popup')) {
            var img_src = $('.img-holder').attr('data-src');
            if ($('.ziss-used-imgs .used-img-item figure[data-src="' + img_src + '"]').length) {
                var this_pin_data = Array();
                $('.img-holder .hotspot-num-on-img').each(function () {
                    this_pin_data.push(
                        {
                            'product_id': $(this).attr('data-product_id'),
                            'top_percent': $(this).attr('data-top_percent'),
                            'left_percent': $(this).attr('data-left_percent')
                        }
                    );
                });
                
                $('.ziss-used-imgs .used-img-item figure[data-src="' + img_src + '"]').attr('data-pin_data', JSON.stringify(this_pin_data));
            }
        }
        
        var hotspot_data = Array();
        $('.ziss-used-imgs .used-img-item figure').each(function () {
            var $this = $(this);
            var thisImgItemWrap = $this.closest('.used-img-item-wrap');
            var img_src = $this.attr('data-src');
            var social_source = $this.attr('data-social_source');
            var img_width = $this.attr('data-width');
            var img_height = $this.attr('data-height');
            var pin_data = $this.attr('data-pin_data');
            if ($.trim(pin_data) != '') {
                pin_data = JSON.parse(pin_data);
            }
            else {
                pin_data = {};
            }
            
            var img_item = {
                'social_source': social_source,
                'img_src': img_src,
                'img_width': img_width,
                'img_height': img_height,
                'pin_data': pin_data
            };
            hotspot_data.push(img_item);
            
            thisImgItemWrap.find('.ziss-hotspot-wrap').remove();
            $(pin_data).each(function (key, data) {
                if (!isNaN(data['product_id'])) {
                    var hotspot_num = key + 1;
                    var hotspot_top_percent = data['top_percent'];
                    var hotspot_left_percent = data['left_percent'];
                    hotspot_top_percent = (data['top_percent'] * img_height + 50 * img_width - 50 * img_height) / img_width;
                    var hotspot_html = '<div class="ziss-hotspot-wrap" ' +
                        'data-product_id="' + data['product_id'] + '" data-top_percent="' + data['top_percent'] + '" data-left_percent="' + data['left_percent'] + '" ' +
                        'style="top: ' + hotspot_top_percent + '%; left: ' + hotspot_left_percent + '%;"> ' +
                        '<div data-hotspot_num="' + hotspot_num + '" class="hotspot-num ziss-cursor-default"> ' +
                        '<div class="ziss-hotspot-text">' + hotspot_num + '</div> ' +
                        '</div> ' +
                        '</div>';
                    thisImgItemWrap.find('.used-img-item').append(hotspot_html);
                }
            });
            
        });
        
        var hotspot_data_str = JSON.stringify(hotspot_data);
        $('input[name="social_shop_pin"]').val(hotspot_data_str);
    }
    
    function ziss_update_social_tab_content() {
        $('.used-img-items .used-img-item > figure').each(function () {
            var img_src = $(this).attr('data-src');
            if ($('.tab-content .img-item .ziss-add-image[data-src="' + img_src + '"]').length) {
                $('.tab-content .img-item .ziss-add-image[data-src="' + img_src + '"]').addClass('added-to-list').closest('.img-item').addClass('added-img-item');
            }
        });
    }
    
    function ziss_init_sortable() {
        $('.ziss-sortable').each(function () {
            if (!$(this).is('.ui-sortable')) {
                $(this).sortable({
                    'update': function (event, ui) {
                        ziss_update_social_shop_pin_data();
                    }
                });
            }
        });
    }
    
    ziss_init_sortable();
    
    function ziss_init_draggable() {
        $('.ziss-draggable').each(function () {
            if (!$(this).is('.ui-draggable')) {
                $(this).draggable({
                    'containment': 'parent', // .img-holder
                    'scroll': false,
                    'stop': function (event, ui) {
                        var thisParent = ui.helper.closest('.img-holder');
                        var parent_w = thisParent.innerWidth();
                        var parent_h = thisParent.innerHeight();
                        var ui_top_percent = 0;
                        var ui_left_percent = 0;
                        if (parent_h > 0) {
                            ui_top_percent = (ui.position.top * 100) / parent_h;
                        }
                        if (parent_w > 0) {
                            ui_left_percent = (ui.position.left * 100) / parent_w;
                        }
                        
                        ui.helper.attr('data-top_percent', ui_top_percent).attr('data-left_percent', ui_left_percent).css({
                            'top': ui_top_percent + '%',
                            'left': ui_left_percent + '%'
                        });
                        
                        ziss_update_social_shop_pin_data();
                    }
                });
            }
        });
    }
    
    // Don't show Media used images
    $(document).on('change', 'input[name="ziss_dont_show_media_used_imgs"]', function (e) {
        var $this = $(this);
        var thisTabContent = $this.closest('.tab-content');
        var dont_show_media_used_imgs = 'no';
        var ziss_edit_nonce = $('input[name="ziss_edit_nonce"]').val();
        if ($(this).is(':checked')) {
            dont_show_media_used_imgs = 'yes';
            thisTabContent.find('.img-items').addClass('dont-show-used-imgs');
        }
        else {
            thisTabContent.find('.img-items').removeClass('dont-show-used-imgs');
        }
        
        var data = {
            action: 'ziss_update_media_used_img_display_admin',
            dont_show_media_used_imgs: dont_show_media_used_imgs,
            nonce: ziss_edit_nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] == 'yes') {
                console.log(response['message']);
            }
            
        });
    });
    
    // Don't show Instagram used images
    $(document).on('change', 'input[name="ziss_dont_show_insta_used_imgs"]', function (e) {
        var dont_show_insta_used_imgs = 'no';
        var ziss_edit_nonce = $('input[name="ziss_edit_nonce"]').val();
        if ($(this).is(':checked')) {
            dont_show_insta_used_imgs = 'yes';
            $('.instagram-items').addClass('dont-show-used-imgs');
        }
        else {
            $('.instagram-items').removeClass('dont-show-used-imgs');
        }
        
        var data = {
            action: 'ziss_update_insta_used_img_display_admin',
            dont_show_insta_used_imgs: dont_show_insta_used_imgs,
            nonce: ziss_edit_nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] == 'yes') {
                console.log(response['message']);
            }
            
        });
    });
    
    // Don't show Facebook used images
    $(document).on('change', 'input[name="ziss_dont_show_fb_used_imgs"]', function (e) {
        var dont_show_fb_used_imgs = 'no';
        var ziss_edit_nonce = $('input[name="ziss_edit_nonce"]').val();
        if ($(this).is(':checked')) {
            dont_show_fb_used_imgs = 'yes';
            $('.fb-items').addClass('dont-show-used-imgs');
        }
        else {
            $('.fb-items').removeClass('dont-show-used-imgs');
        }
        
        var data = {
            action: 'ziss_update_fb_used_img_display_admin',
            dont_show_fb_used_imgs: dont_show_fb_used_imgs,
            nonce: ziss_edit_nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] == 'yes') {
                console.log(response['message']);
            }
            
        });
    });
    
    // Update Facebook access token via ajax
    $(document).on('change', '.ziss-get-fb-token-wrap input', function (e) {
        var $this = $(this);
        var getFbTokenWrap = $this.closest('.ziss-get-fb-token-wrap');
        var thisTabContent = $this.closest('.tab-content');
        var thisImgItems = thisTabContent.find('.img-items');
        var fb_id = getFbTokenWrap.find('input[name=ziss_db_id]').val();
        var access_token = getFbTokenWrap.find('input[name=ziss_fb_token]').val();
        var nonce = $('input[name=ziss_edit_nonce]').val();
        
        if (getFbTokenWrap.is('.processing')) {
            return false;
        }
        
        getFbTokenWrap.find('.notice').remove();
        getFbTokenWrap.addClass('processing');
        
        var data = {
            action: 'ziss_update_fb_access_token_via_ajax',
            fb_id: fb_id,
            access_token: access_token,
            nonce: nonce
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] === 'yes') {
                getFbTokenWrap.find('.notice').remove();
                getFbTokenWrap.append(response['html']);
            }
            else {
                thisTabContent.html(response['html']);
                if (response['has_more'] === 'yes') {
                    getFbTokenWrap.attr('href', response['next_url']);
                }
                else {
                    getFbTokenWrap.remove();
                }
                ziss_update_social_tab_content();
            }
            getFbTokenWrap.removeClass('processing');
            
        });
        
    });
    
    // Update shortcode when change shortcode options
    function ziss_update_shortcode() {
        var post_id = $('#post_ID').val();
        var is_use_custom_responsive = $('#ziss_use_custom_responsive').is(':checked') ? 'yes' : 'no';
        var lg_cols = $('#ziss_items_on_lg').val();
        var md_cols = $('#ziss_items_on_md').val();
        var sm_cols = $('#ziss_items_on_sm').val();
        var xs_cols = $('#ziss_items_on_xs').val();
        var xxs_cols = $('#ziss_items_on_xxs').val();
        
        var shortcode = '[ziss id=' + post_id + ']';
        
        if (is_use_custom_responsive == 'yes') {
            shortcode = '[ziss id=' + post_id + ' lg_cols=' + lg_cols + ' md_cols=' + md_cols + ' md_cols=' + md_cols + ' sm_cols=' + sm_cols + ' xs_cols=' + xs_cols + ' xxs_cols=' + xxs_cols + ']';
            $('#ziss_use_custom_responsive').val('yes');
        }
        else {
            shortcode = '[ziss id=' + post_id + ']';
            $('#ziss_use_custom_responsive').val('no');
        }
        $('.ziss-shortcode').val(shortcode);
    }
    
    ziss_update_shortcode();
    $(document).on('change', '.shortcode-options-wrap .form-control, .shortcode-options-wrap input[type="checkbox"]', function () {
        ziss_update_shortcode();
    });
    
    $(document).on('focus', '.ziss-shortcode', function () {
        $(this).select();
    });
    
    $(window).resize(function () {
        ziss_left_right_popup_equal_height();
    });
    
    $(window).load(function () {
        ziss_init_sortable();
        ziss_update_social_tab_content();
    });
    
});