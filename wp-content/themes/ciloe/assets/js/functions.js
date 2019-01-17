(function ($) {
    "use strict"; // Start of use strict
    
    function fami_init_lazy_load() {
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
    
    function ciloe_init_popup() {
        var popup_news = $('#popup-newsletter');
        if (popup_news.length > 0) {
            if ($('body').hasClass('home')) {
                setTimeout(function () {
                    $('#popup-newsletter').modal({
                        keyboard: false
                    })
                }, 80000);
            }
        }
    }
    
    function sticky_detail_content() {
        $('.detail-content')
            .theiaStickySidebar({
                additionalMarginTop: 100
            });
    }
    
    function sticky_scrollup() {
        if ($('.sticky_info_single_product').length) {
            
            var previousScroll = 0,
                headerOrgOffset = $('.site-header').outerHeight() + $('.site-header').outerHeight() + $('.product-top-inner').offset().top;
            
            $(window).scroll(function () {
                var currentScroll = $(this).scrollTop();
                if (currentScroll > headerOrgOffset) {
                    $('body').addClass('show-sticky_info_single');
                } else {
                    $('body').removeClass('show-sticky_info_single');
                }
                previousScroll = currentScroll;
            });
        }
        if ($('.menu-sticky-smart .header-wrap-stick').length) {
            if ($('.header-position').length) {
                
                var previousScroll = 0,
                    headerOrgOffset = $('.header-position').offset().top ,
                    header_height = $('.header-position').outerHeight();
                
                $('.menu-sticky-smart .header-wrap-stick').outerHeight($('.header-position').outerHeight());
                
                $(window).scroll(function () {
                    var currentScroll = $(this).scrollTop();
                    if (currentScroll < headerOrgOffset) {
                        $('.sticky-wrapper').css({'height': header_height + 'px'});
                    }
                    previousScroll = currentScroll;
                });
            }
        }
    }
    
    sticky_scrollup();
    
    //My Account
    function toggle_form() {
        $('.toggle-form').on('click', function (e) {
            $(this).toggleClass('active');
            $('.block-form').toggleClass('nav-show form-show');
            e.preventDefault();
            e.stopPropagation();
        });
    }
    
    // sidebar canvas
    function ciloe_sidebar_offcanvas() {
        $(document).on('click', '.btn-canvas, .sidebar-canvas-overlay', function (e) {
            $('body,html').toggleClass('sidebar-canvas-open');
            e.preventDefault();
        });
    }
    
    // minicart canvas
    function ciloe_minicart_offcanvas() {
        $(document).on('click', '.mini-cart-icon,.minicart-canvas-overlay,.close-minicart', function (e) {
            $('body,html').toggleClass('minicart-canvas-open');
            e.preventDefault();
        })
    }
    
    // instant search
    function ciloe_instant_search() {
        $(document).on('click', '.header-search-box .icons,.instant-search-close', function (e) {
            $('body').toggleClass('instant-search-open');
            e.preventDefault();
        });
    }
    
    // instant search
    function ciloe_vertical_menu() {
        $(document).on('click', '.vertical-menu-btn,.close-vertical-menu,.vertical-menu-overlay', function (e) {
            $('body,html').toggleClass('vertical-menu-open');
            e.preventDefault();
        })
    }
    
    function getCookie(c_name) {
        var c_value = document.cookie;
        var c_start = c_value.indexOf(" " + c_name + "=");
        if (c_start == -1) {
            c_start = c_value.indexOf(c_name + "=");
        }
        if (c_start == -1) {
            c_value = null;
        } else {
            c_start = c_value.indexOf("=", c_start) + 1;
            var c_end = c_value.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = c_value.length;
            }
            c_value = decodeURI(c_value.substring(c_start, c_end));
        }
        return c_value;
    }
    
    function setCookie(c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = encodeURI(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value;
    }
    
    function ciloe_singleProduct_popup() {
        $('.ciloe-bt-video a').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            disableOn: false,
            fixedContentPos: false
        });
        $('.product-360-button a').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $(window).resize()
                },
            },
        });
        $('.open-popup-link').magnificPopup({
            mainClass: 'mfp-fade',
            removalDelay: 100,
            type: 'inline',
            callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = this.st.el.attr('data-effect');
                }
            },
            midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
        $('.block-account .acc-popup').magnificPopup({
            mainClass: 'mfp-fade',
            removalDelay: 100,
            type: 'inline',
            callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = this.st.el.attr('data-effect');
                }
            },
            midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
        
        var expireDate = getCookie("showpopup");
        var today = new Date().toUTCString();
        if (expireDate != null && expireDate > today) {
            //Do nothing!
        }
        else {
            setTimeout(function () {
                if ($('#popup-discount').length) {
                    $.magnificPopup.open({
                        items: {
                            src: '#popup-discount'
                        },
                        type: 'inline',
                        mainClass: 'mfp-zoom-in'
                    });
                }
            }, 3000);
            //Create cookie
            setCookie("showpopup", "anything", 1);
        }
        
    }
    
    /* Main Menu */
    
    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */
    function ciloe_resizeMegamenu() {
        var window_size = jQuery('body').innerWidth();
        window_size += ciloe_get_scrollbar_width();
        if (window_size > 767) {
            if ($('#header .main-menu-wrapper').length > 0) {
                var container = $('#header .main-menu-wrapper');
                if (container != 'undefined') {
                    var container_width = 0;
                    container_width = container.innerWidth();
                    var container_offset = container.offset();
                    setTimeout(function () {
                        $('.main-menu .item-megamenu').each(function (index, element) {
                            $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                            var sub_menu_width = $(element).children('.megamenu').outerWidth();
                            var item_width = $(element).outerWidth();
                            $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                            var container_left = container_offset.left;
                            var container_right = (container_left + container_width);
                            var item_left = $(element).offset().left;
                            var overflow_left = (sub_menu_width / 2 > (item_left - container_left));
                            var overflow_right = ((sub_menu_width / 2 + item_left) > container_right);
                            if (overflow_left) {
                                var left = (item_left - container_left);
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                            if (overflow_right && !overflow_left) {
                                var left = (item_left - container_left);
                                left = left - ( container_width - sub_menu_width );
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                        })
                    }, 100);
                }
            }
        }else{
            $('.owl-carousel').each(function (index, el) {
                var config = $(this).data();
                if ($(this).is('.instagram,.product-list-owl')) {
                    config.responsive = {
                        0: {items: 2, margin: 15},
                        481: {items: 2, margin: 15}
                    }
                }
            });
        }
    }
    
    function ciloe_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px; height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner = $inner[0],
            outer = $outer[0];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }
    
    function dropdown_menu(contain) {
        $(contain).each(function () {
            var _main = $(this);
            _main.children('.menu-item.parent').each(function () {
                
                var curent = $(this).find('.submenu');
                
                $(this).children('.toggle-submenu').on('click', function () {
                    $(this).parent().children('.submenu').slideToggle(400);
                    _main.find('.submenu').not(curent).slideUp(400);
                    
                    $(this).parent().toggleClass('show-submenu');
                    _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                });
                
                var next_curent = $(this).find('.submenu');
                
                next_curent.children('.menu-item.parent').each(function () {
                    
                    var child_curent = $(this).find('.submenu');
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().parent().find('.submenu').not(child_curent).slideUp(400);
                        $(this).parent().children('.submenu').slideToggle(400);
                        
                        $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                        $(this).parent().toggleClass('show-submenu');
                    })
                });
            });
        });
    };
    
    
    function ciloe_clone_main_menu() {
        if ($('#header .clone-main-menu').length > 0) {
            var _winw = $(window).innerWidth();
            var _clone_menu = $('#header .clone-main-menu');
            var _target = $('#box-mobile-menu .clone-main-menu');
            var main_menu_break_point = ciloe_theme_frontend['main_menu_break_point'];
            
            if (_winw <= main_menu_break_point) {
                if (_clone_menu.length > 0 && _target.length == 0) {
                    _clone_menu.each(function () {
                        $(this).clone().appendTo("#box-mobile-menu .box-inner");
                    });
                }
            } else {
            
            }
        }
    }
    function ciloe_clone_append_category() {
        if ($('.product-category').length > 0) {
            $('.main-product').prepend('<ul class="list-cate"></ul>')
            $('.product-category').detach().prependTo('.list-cate');
                    
        }
    }
    
    /* Ciloe Carousel  */
    function ciloe_init_carousel() {
        $('.owl-carousel').each(function (index, el) {
            var config = $(this).data();
            if ($(this).is('.category-filter-mobile')) {
                config['autoWidth'] = true;
            }
            config.navText = ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'];
            var animateOut = $(this).data('animateout');
            var animateIn = $(this).data('animatein');
            var slidespeed = $(this).data('slidespeed');
            
            if (typeof animateOut != 'undefined') {
                config.animateOut = animateOut;
            }
            if (typeof animateIn != 'undefined') {
                config.animateIn = animateIn;
            }
            if (typeof (slidespeed) != 'undefined') {
                config.smartSpeed = slidespeed;
            }
            
            var owl = $(this);
            owl.on('initialized.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
            })
            owl.on('refreshed.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
            })
            owl.on('change.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
                
                
            });
            owl.on('translated.owl.carousel', function (event) {
                // Fami lazy load for owl
                if ($('.owl-item .lazy').length) {
                    $('.owl-item .lazy').lazy({
                        bind: "event"
                    });
                }
            });
            owl.owlCarousel(config);
            
        });
    }
    
    function thumbnail_product() {
        $('.default:not(.product-mobile-layout) .flex-control-thumbs').not('.slick-initialized').slick({
            slidesToShow: 4,
            infinite: false,
            slidesToScroll: 1,
            prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
            ]
        });
        $('.vertical_thumnail:not(.product-mobile-layout) .flex-control-thumbs').each(function () {
            if ($(this).not('.slick-initialized').children().length == 0) {
                return;
            }
            $(this).slick({
                vertical: true,
                verticalSwiping: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: false,
                prevArrow: '<i class="fa fa-angle-up" aria-hidden="true"></i>',
                nextArrow: '<i class="fa fa-angle-down" aria-hidden="true"></i>',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            vertical: false,
                            verticalSwiping: false,
                            slidesToShow: 4,
                            prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                            nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            vertical: false,
                            verticalSwiping: false,
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                            nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                        }
                    },
                    {
                        breakpoint: 320,
                        settings: {
                            vertical: false,
                            verticalSwiping: false,
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                            nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                        }
                    }
                ]
            });
        });
        $('.gallery_detail:not(.product-mobile-layout) .single-left .ciloe-center-mode').not('.slick-initialized').slick({
            centerMode: true,
            centerPadding: '27.8947368%',
            slidesToShow: 1,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        centerMode: false,
                    }
                }
            ]
        });
        
    }
    
    function number_dots_rev() {
        $('.dione').each(function () {
            var n = $(this).find('.tp-bullet').length;
            var $dots = $(this).find('.tp-bullet');
            
            for (var i = 0; i <= n; i++) {
                var _number;
                if (i < 9) {
                    _number = '0' + (i + 1);
                } else {
                    _number = i + 1;
                }
                $dots.eq(i).html('<span class="number">' + _number + '</span>');
            }
        });
    }
    
    /* ---------------------------------------------
     COUNTDOWN
     --------------------------------------------- */
    function ciloe_countdown() {
        $('.ciloe-countdown').each(function () {
            var $el = $(this),
                $timers = $el.find('.timers'),
                output = '';
            $timers.countdown($timers.data('date'), function (event) {
                output = '';
                var day = event.strftime('%D');
                for (var i = 0; i < day.length; i++) {
                    output += '<span>' + day[i] + '</span>';
                }
                $timers.find('.day').html(output);
                
                output = '';
                var hour = event.strftime('%H');
                for (i = 0; i < hour.length; i++) {
                    output += '<span>' + hour[i] + '</span>';
                }
                $timers.find('.hour').html(output);
                
                output = '';
                var minu = event.strftime('%M');
                for (i = 0; i < minu.length; i++) {
                    output += '<span>' + minu[i] + '</span>';
                }
                $(this).find('.min').html(output);
                
                output = '';
                var secs = event.strftime('%S');
                for (i = 0; i < secs.length; i++) {
                    output += '<span>' + secs[i] + '</span>';
                }
                $timers.find('.secs').html(output);
            });
        });
    };
    
    /* ---------------------------------------------
     Woocommerce Quantily
     --------------------------------------------- */
    function ciloe_woo_quantily() {
        $('body').on('click', '.quantity .quantity-plus', function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.data('min')),
                max_qty = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty + step_qty;
            if (max_qty && val_qty > max_qty) {
                val_qty = max_qty;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
        
        $('body').on('click', '.quantity .quantity-minus', function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.data('min')),
                max_qty = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty - step_qty;
            if (min_qty && val_qty < min_qty) {
                val_qty = min_qty;
            }
            if (!min_qty && val_qty < 0) {
                val_qty = 0;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
    }
    
    /* ---------------------------------------------
     TAB EFFECT
     --------------------------------------------- */
    function ciloe_tab_fade_effect() {
        // effect click
        $(document).on('click', '.ciloe-tabs .tab-link a', function () {
            var tab_id = $(this).attr('href');
            var tab_animated = $(this).data('animate');
            
            tab_animated = ( tab_animated == undefined || tab_animated == "" ) ? '' : tab_animated;
            if (tab_animated == "") {
                return false;
            }
            
            $(tab_id).find('.product-list-owl .owl-item.active, .product-list-grid .product-item').each(function (i) {
                
                var t = $(this);
                var style = $(this).attr("style");
                style = ( style == undefined ) ? '' : style;
                var delay = i * 400;
                t.attr("style", style +
                    ";-webkit-animation-delay:" + delay + "ms;"
                    + "-moz-animation-delay:" + delay + "ms;"
                    + "-o-animation-delay:" + delay + "ms;"
                    + "animation-delay:" + delay + "ms;"
                ).addClass(tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    t.removeClass(tab_animated + ' animated');
                    t.attr("style", style);
                });
            })
        })
    }
    
    /* ---------------------------------------------
     Ajax Tab
     --------------------------------------------- */
    $(document).on('click', '[data-ajax="1"]', function () {
        better_equal_elems();
        if (!$(this).hasClass('loaded')) {
            var id = $(this).data('id');
            var tab_id = $(this).attr('href');
            var section_id = tab_id.replace('#', '');
            var t = $(this);
            
            $(tab_id).closest('.tab-container').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
            $(tab_id).closest('.panel-collapse').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
            $.ajax({
                type: 'POST',
                data: {
                    action: 'ciloe_ajax_tabs',
                    security: ciloe_theme_frontend.security,
                    id: id,
                    section_id: section_id,
                },
                url: ciloe_theme_frontend.ajaxurl,
                success: function (response) {
                    $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                    $(tab_id).closest('.panel-collapse').find('.cssload-wapper').remove();
                    $(tab_id).html($(response['html']).find('.vc_tta-panel-body').html());
                    t.addClass('loaded');
                },
                complete: function () {
                    ciloe_product_loadmore();
                    better_equal_elems();
                    ciloe_tab_fade_effect();
                }
            });
        }
    });
    
    function ciloe_google_maps() {
        if ($('.ciloe-google-maps').length <= 0) {
            return;
        }
        $('.ciloe-google-maps').each(function () {
            var $this = $(this),
                $id = $this.attr('id'),
                $title_maps = $this.attr('data-title_maps'),
                $phone = $this.attr('data-phone'),
                $email = $this.attr('data-email'),
                $zoom = parseInt($this.attr('data-zoom')),
                $latitude = $this.data('latitude'),
                $longitude = $this.data('longitude'),
                $address = $this.attr('data-address'),
                $map_type = $this.attr('data-map-type'),
                $pin_icon = $this.attr('data-pin-icon'),
                $modify_coloring = true,
                $saturation = $this.data('saturation'),
                $hue = $this.data('hue'),
                $map_style = $this.data('map-style'),
                $styles;
            
            if ($modify_coloring == true) {
                var $styles = [
                    {
                        stylers: [
                            {hue: $hue},
                            {invert_lightness: false},
                            {saturation: $saturation},
                            {lightness: 1},
                            {
                                featureType: "landscape.man_made",
                                stylers: [{
                                    visibility: "on"
                                }]
                            }
                        ]
                    }, {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [
                            {color: '#46bcec'}
                        ]
                    }
                ];
            }
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: $zoom,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId[$map_type],
                styles: $styles
            };
            
            map = new google.maps.Map(document.getElementById($id), mapOptions);
            map.setTilt(45);
            
            // Multiple Markers
            var markers = [];
            var infoWindowContent = [];
            
            if ($latitude != '' && $longitude != '') {
                markers[0] = [$address, $latitude, $longitude];
                infoWindowContent[0] = [$address];
            }
            
            var infoWindow = new google.maps.InfoWindow(), marker, i;
            
            for (i = 0; i < markers.length; i++) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0],
                    icon: $pin_icon
                });
                if ($map_style == '1') {
                    
                    if (infoWindowContent[i][0].length > 1) {
                        infoWindow.setContent(
                            '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="ciloe-map-info">' +
                            '<h4 class="map-title">' + $title_maps + '</h4>' +
                            '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                            '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                            '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                            '</div>'
                        );
                    }
                    
                    infoWindow.open(map, marker);
                    
                }
                if ($map_style == '2') {
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            if (infoWindowContent[i][0].length > 1) {
                                infoWindow.setContent(
                                    '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="ciloe-map-info">' +
                                    '<h4 class="map-title">' + $title_maps + '</h4>' +
                                    '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                                    '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                                    '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                                    '</div>'
                                );
                            }
                            
                            infoWindow.open(map, marker);
                        }
                    })(marker, i));
                }
                
                map.fitBounds(bounds);
            }
            
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                this.setZoom($zoom);
                google.maps.event.removeListener(boundsListener);
            });
        });
    }
    
    //EQUAL ELEM
    function better_equal_elems() {
        setTimeout(function () {
            $('.equal-container.better-height').each(function () {
                var $this = $(this);
                if ($this.find('.equal-elem').length) {
                    $this.find('.equal-elem').css({
                        'height': 'auto'
                    });
                    var elem_height = 0;
                    $this.find('.equal-elem').each(function () {
                        var this_elem_h = $(this).height();
                        if (elem_height < this_elem_h) {
                            elem_height = this_elem_h;
                        }
                    });
                    $this.find('.equal-elem').height(elem_height);
                }
            });
        }, 1000);
    }
    
    /* Mobile menu (Desktop/responsive) */
    $(document).on('click', '.box-mobile-menu .clone-main-menu .toggle-submenu', function (e) {
        var $this = $(this);
        var thisMenu = $this.closest('.clone-main-menu');
        var thisMenuWrap = thisMenu.closest('.box-mobile-menu');
        thisMenu.removeClass('active');
        var text_next = $this.prev().text();
        thisMenuWrap.find('.box-title').html(text_next);
        thisMenu.find('li').removeClass('mobile-active');
        $this.parent().addClass('mobile-active');
        $this.parent().closest('.submenu').css({
            'position': 'static',
            'height': '0'
        });
        thisMenuWrap.find('.back-menu, .box-title').css('display', 'block');
        // Fix lazy for mobile menu
        if ($this.parent().find('.fami-lazy:not(.already-fix-lazy)').length) {
            $this.parent().find('.fami-lazy:not(.already-fix-lazy)').lazy({
                bind: 'event',
                delay: 0
            }).addClass('already-fix-lazy');
        }
        e.preventDefault();
    });
    
    $(document).on('click', '.box-mobile-menu .back-menu', function (e) {
        var $this = $(this);
        var thisMenuWrap = $this.closest('.box-mobile-menu');
        var thisMenu = thisMenuWrap.find('.clone-main-menu');
        thisMenu.find('li.mobile-active').each(function () {
            thisMenu.find('li').removeClass('mobile-active');
            if ($(this).parent().hasClass('main-menu')) {
                thisMenu.addClass('active');
                $('.box-mobile-menu .box-title').html('MAIN MENU');
                $('.box-mobile-menu .back-menu, .box-mobile-menu .box-title').css('display', 'none');
            } else {
                thisMenu.removeClass('active');
                $(this).parent().parent().addClass('mobile-active');
                $(this).parent().css({
                    'position': 'absolute',
                    'height': 'auto'
                });
                var text_prev = $(this).parent().parent().children('a').text();
                $('.box-mobile-menu .box-title').html(text_prev);
            }
            e.preventDefault();
        })
    });
    
    $(document).on('click', '.mobile-navigation', function (e) {
        $('body').addClass('box-mobile-menu-open');
    });
    $(document).on('click', '.box-mobile-menu .close-menu, .body-overlay,.box-mibile-overlay', function (e) {
        $('body').removeClass('box-mobile-menu-open real-mobile-show-menu');
        $('.hamburger').removeClass('is-active');
    });
    // Menu hover Mouse
    $('.ciloe-categorylist').mouseenter(function () {
        $('.ciloe-categorylist').addClass('item-hover');
        $(this).removeClass('item-hover');
        $(this).addClass('item-active');
    })
        .mouseleave(function () {
            $('.ciloe-categorylist').removeClass('item-hover item-active');
        });
    //Count Cart
    $('.ciloe-categorylist').each(function () {
        var count = $('.ciloe-categorylist').length;
        if (count == 6) {
            $(this).addClass('col-lg-2');
        } else if (count == 5) {
            $(this).addClass('col-lg-15');
        } else if (count == 4) {
            $(this).addClass('col-lg-3');
        } else if (count == 3) {
            $(this).addClass('col-lg-4');
        } else if (count == 2) {
            $(this).addClass('col-lg-6');
        }
        $('.header-top').addClass('has-' + count + '-element');
    });
    /*  Mobile Menu on real mobile (if header mobile is enabled) */
    $(document).on('click', '.mobile-hamburger-navigation ', function (e) {
        $(this).find('.hamburger').addClass('is-active');
        if ($(this).find('.hamburger').is('.is-active')) {
            $('body').addClass('real-mobile-show-menu box-mobile-menu-open');
        } else {
            $('body').removeClass('real-mobile-show-menu box-mobile-menu-open');
        }
        e.preventDefault();
    });
    
    /* Mobile Tabs on real mobile */
    $(document).on('click', '.box-tabs .box-tab-nav', function (e) {
        var $this = $(this);
        var thisTab = $this.closest('.box-tabs');
        var tab_id = $this.attr('href');
        
        if ($this.is('.active')) {
            return false;
        }
        
        thisTab.find('.box-tab-nav').removeClass('active');
        $this.addClass('active');
        
        thisTab.find('.box-tab-content').removeClass('active');
        thisTab.find(tab_id).addClass('active');
        
        e.preventDefault();
    });
    
    // Wish list on real menu mobile
    if ($('.box-mobile-menu .wish-list-mobile-menu-link-wrap').length) {
        if (!$('.box-mobile-menu').is('.moved-wish-list')) {
            var wish_list_html = $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').html();
            $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').remove();
            $('.box-mobile-menu .main-menu').append('<li class="menu-item-for-wish-list menu-item menu-item-type-custom menu-item-object-custom">' + wish_list_html + '</li>');
            $('.box-mobile-menu').addClass('moved-wish-list');
        }
    }
    /* Loading wishlist */
    $('.add_to_wishlist').on('click', function () {
        $(this).addClass('loading');
    });
    
    /* update wishlist count */
    function update_wishlist_count() {
        var ciloe_update_wishlist_count = function () {
            $.ajax({
                beforeSend: function () {
                
                },
                complete: function () {
                
                },
                data: {
                    action: 'ciloe_update_wishlist_count'
                },
                success: function (data) {
                    //do something
                    $('.block-wishlist .count').text(data);
                },
                
                url: yith_wcwl_l10n.ajax_url
            });
        };
        
        $('body').on('added_to_wishlist removed_from_wishlist', ciloe_update_wishlist_count);
    }
    
    /* Toggle filter */
    $(document).on('click', '.filter-toggle', function (e) {
        if ($('.prdctfltr_woocommerce_filter').length) {
            $('.prdctfltr_woocommerce_filter').trigger('click');
        }
        $(this).toggleClass('active');
        e.preventDefault();
    });
    
    /* ---------------------------------------------
     AJAX LOADMORE
     -----------------------------------------------*/
    
    var initAjaxLoad = function () {
        var button = $('.ciloe-ajax-load:not(.already-init)');
        
        button.each(function (i, val) {
            $(this).addClass('already-init');
            var _option = $(this).data('load-more');
            var _mode = $(this).data('mode');
            
            if (_option !== undefined) {
                var page = _option.page,
                    container = _option.container,
                    layout = _option.layout,
                    isLoading = false,
                    anchor = $(val).find('a'),
                    next = $(anchor).attr('href'),
                    cur_page = 2;
                
                if (layout == 'loadmore') {
                    $(val).on('click', 'a', function (e) {
                        e.preventDefault();
                        cur_page = parseInt($(val).attr('data-cur_page'));
                        var total_page = parseInt($(val).attr('data-total_page'));
                        anchor = $(val).find('a');
                        next = $(anchor).attr('href');
                        if (total_page <= cur_page) {
                            anchor.text(ciloe_theme_frontend['text']['no_more_product']).addClass('disabled ciloe-loadmore-disabled');
                            return false;
                        }
                        
                        $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                        
                        getData();
                    });
                } else {
                    var animationFrame = function () {
                        cur_page = parseInt($(val).attr('data-cur_page'));
                        var total_page = parseInt($(val).attr('data-total_page'));
                        anchor = $(val).find('a');
                        next = $(anchor).attr('href');
                        if (total_page <= cur_page) {
                            anchor.text(ciloe_theme_frontend['text']['no_more_product']);
                            return false;
                        }
                        
                        var bottomOffset = $('.' + container).offset().top + $('.' + container).height() - $(window).scrollTop();
                        if (bottomOffset < window.innerHeight && bottomOffset > 0 && !isLoading) {
                            if (!next)
                                return;
                            isLoading = true;
                            $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                            
                            getData();
                        }
                    }
                    
                    var scrollHandler = function () {
                        requestAnimationFrame(animationFrame);
                    };
                    
                    $(window).scroll(scrollHandler);
                }
                
                var getData = function () {
                    $.get(next + '', function (data) {
                        var content = $('.' + container, data).wrapInner('').html(),
                            newElement = $('.' + container, data).find('.product-item');
                        
                        $(content).imagesLoaded(function () {
                            next = $(anchor, data).attr('href');
                            $('.' + container).append(newElement);
                            ciloe_init_products_size();
                            fami_init_lazy_load();
                        });
                        ciloe_init_products_size();
                        fami_init_lazy_load();
                        
                        $(anchor).text(ciloe_theme_frontend['text']['load_more']); // ciloe_theme_frontend
                        
                        if (page > cur_page) {
                            cur_page++;
                            if (ZIGG_Data_Js != undefined && ZIGG_Data_Js['permalink'] == 'plain') {
                                var link = next.replace(/paged=+[0-9]+/gi, 'paged=' + cur_page);
                            } else {
                                var link = next.replace(/page\/+[0-9]+\//gi, 'page/' + cur_page + '/');
                            }
                            
                            if (cur_page >= page) {
                                $(anchor).text(ciloe_theme_frontend['text']['no_more_product']);
                                $(anchor).addClass('disabled ciloe-loadmore-disabled');
                            }
                            
                            $(anchor).attr('href', link);
                        } else {
                            $(anchor).text(ciloe_theme_frontend['text']['no_more_product']);
                            $(anchor).addClass('disabled ciloe-loadmore-disabled');
                        }
                        isLoading = false;
                        
                        // cur_page++;
                        $(val).attr('data-cur_page', cur_page);
                    });
                }
            }
        });
    }
    
    function ciloe_get_url_var(key, url) {
        var result = new RegExp(key + "=([^&]*)", "i").exec(url);
        return result && result[1] || "";
    }
    
    function ciloe_remove_product() {
        $(document).on('click', '.minicart-items .product-cart .product-remove a.remove', function (e) {
            var $this = $(this);
            var thisItem = $this.closest('.product-cart');
            var remove_url = $this.attr('href');
            var product_id = $this.attr('data-product_id');
            if (thisItem.is('.loading')) {
                return false;
            }
            
            if ($.trim(remove_url) !== '' && $.trim(remove_url) !== '#') {
                
                thisItem.addClass('loading');
                
                var nonce = ciloe_get_url_var('_wpnonce', remove_url);
                var cart_item_key = ciloe_get_url_var('remove_item', remove_url);
                
                var data = {
                    action: 'ciloe_remove_cart_item_via_ajax',
                    product_id: product_id,
                    cart_item_key: cart_item_key,
                    nonce: nonce
                };
                
                $.post(ciloe_theme_frontend['ajaxurl'], data, function (response) {
                    
                    if (response['err'] != 'yes') {
                        $('.ciloe-minicart').html(response['mini_cart_html']);
                    }
                    thisItem.removeClass('loading');
                    
                });
                
                e.preventDefault();
            }
            
            return false;
            
        });
        
    }
    
    // Single product: Title, Price And Stars Outside Sumary
    function ciloe_update_single_product_title_price_stars_outside_summary() {
        $('.single-product .summary.title-price-stars-outside-summary').each(function () {
            var $this = $(this);
            var thisProduct = $this.closest('.product');
            if (thisProduct.is('.product-mobile-layout')) {
                return;
            }
            var stars_html = $this.find('.woocommerce-product-rating').html();
            var title_html = $this.find('.product_title').html();
            var price_html = $this.find('.price').html();
            var outside_html = '';
            if ($.trim(stars_html) != '') {
                outside_html += '<div class="woocommerce-product-rating woocommerce-product-rating-outside">' + stars_html + '</div>';
            }
            if ($.trim(title_html) != '') {
                outside_html += '<h2 class="product_title entry-title product_title-outside">' + title_html + '</h2>';
            }
            if ($.trim(price_html) != '') {
                outside_html += '<p class="price price-outside">' + price_html + '</p>';
            }
            if ($.trim(outside_html) != '') {
                outside_html = '<div class="outside-title-price-stars-wrap">' + outside_html + '</div>';
            }
            thisProduct.find('.outside-title-price-stars-wrap').remove();
            thisProduct.find('.main-content-product').append(outside_html);
        });
    }
    
    ciloe_update_single_product_title_price_stars_outside_summary();
    
    // Single product mobile more detail
    $(document).on('click', '.product-toggle-more-detail', function (e) {
        var thisSummary = $(this).closest('.summary');
        thisSummary.find('.product-mobile-more-detail-wrap').toggleClass('active').slideToggle();
        if (thisSummary.find('.product-mobile-more-detail-wrap').is('.active')) {
            $(this).addClass('active').text(ciloe_theme_frontend['text']['less_detail']);
        }
        else {
            $(this).removeClass('active').text(ciloe_theme_frontend['text']['more_detail']);
        }
        e.preventDefault();
    });
    
    // Single product mobile structure
    function ciloe_single_product_mobile_structure() {
        $('.product-mobile-layout').each(function (e) {
            var $this = $(this);
            var thisSummary = $this.find('.summary');
            if (thisSummary.is('.moved-some-elems')) {
                return;
            }
            // Quantity
            if (thisSummary.find('.quantity').length) {
                var quantity_html = thisSummary.find('.quantity').html();
                thisSummary.find('.product_title').after('<div class="quantity quantity-clone">' + quantity_html + '</div>');
            }
            
            // Star rating
            if (!thisSummary.is('.moved-star-rating')) {
                if (thisSummary.find('.woocommerce-product-rating').length) {
                    var star_rating_html = thisSummary.find('.woocommerce-product-rating').html();
                    thisSummary.find('.woocommerce-product-rating').remove();
                    thisSummary.find('.product_title').after('<div class="woocommerce-product-rating woocommerce-product-rating-clone">' + star_rating_html + '</div>');
                }
                thisSummary.addClass('moved-star-rating');
            }
            thisSummary.addClass('moved-some-elems');
        });
    }
    
    ciloe_single_product_mobile_structure();
    
    $(document).on('change', '.quantity-clone .input-qty', function (e) {
        var $this = $(this);
        var thisSummary = $this.closest('.summary');
        var this_val = $this.val();
        thisSummary.find('.cart .quantity .input-qty').val(this_val).trigger('change');
    });
    
    // Single product mobile add to cart fixed button
    $(document).on('click', '.add-to-cart-fixed-btn', function (e) {
        var $this = $(this);
        if ($('.product .summary button.single_add_to_cart_button').length) {
            $('.product .summary button.single_add_to_cart_button').trigger('click');
        }
        e.preventDefault();
    });
    // Single product mobile add to cart fixed button
    $(document).on('click', '.ciloe-single-add-to-cart-fixed-top', function (e) {
        var $this = $(this);
        if ($('.product .summary button.single_add_to_cart_button').length) {
            $('.product .summary button.single_add_to_cart_button').trigger('click');
        }
        e.preventDefault();
    });
    $(document).on('wc_variation_form', '.variations_form', function (e) {
        $(this).addClass('fami-active-wc_variation_form');
    });
    
    // Single product attributes
    function ciloe_variations_custom() {
        $('.product-item .variations_form:not(.moved-reset_variations)').each(function () {
            $(this).find('.reset_variations').appendTo($(this));
            $(this).addClass('moved-reset_variations');
        });
        
        $('.variations_form').find('.data-val').html('');
        $('.variations_form select, .fami_variations_form select').each(function () {
            var _this = $(this);
            _this.find('option').each(function () {
                var _ID = $(this).parent().data('id'),
                    _data = $(this).data(_ID),
                    _value = $(this).attr('value'),
                    _name = $(this).data('name'),
                    _data_type = $(this).data('type'),
                    _itemclass = _data_type;
                
                if ($(this).is(':selected')) {
                    _itemclass += ' active';
                }
                if (_value !== '') {
                    if (_data_type == 'color' || _data_type == 'photo') {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" style="background: ' + _data + ';background-size: cover; background-repeat: no-repeat " data-value="' + _value + '"></a>');
                    } else {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '">' + _name + '</a>');
                    }
                }
            });
        });
    }
    
    function ciloe_variations_custom_ajax() {
        if ($('.products .variations_form:not(.fami-active-wc_variation_form)').length) {
            $('.products .variations_form:not(.fami-active-wc_variation_form)').each(function () {
                $(this).wc_variation_form();
            });
        }
        ciloe_variations_custom();
    }
    
    $(document).on('click', '.reset_variations', function () {
        $('.variations_form').find('.change-value').removeClass('active');
        ciloe_single_product_mobile_structure();
        ciloe_update_single_product_title_price_stars_outside_summary();
    });
    $(document).on('click', '.variations_form .change-value', function (e) {
        var _this = $(this),
            _change = _this.data('value');
        
        _this.parent().parent().children('select').val(_change).trigger('change');
        _this.addClass('active').siblings().removeClass('active');
        ciloe_single_product_mobile_structure();
        ciloe_update_single_product_title_price_stars_outside_summary();
        if (_this.closest('.product-item').length) {
            var $thisProduct = _this.closest('.product-item');
            $thisProduct.removeAttr('srcset').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes');
        }
        e.preventDefault();
    });
    $(document).on('woocommerce_variation_has_changed wc_variation_form', function () {
        ciloe_variations_custom();
        $('.product-item').find('.images .fami-img').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes');
    });
    
    function ciloe_fix_responsive_img_issue_product_var() {
        $('.product-item .variations_form:not(.fami-fixed-res-img)').each(function () {
            var $this = $(this);
            var product_variations = $this.attr('data-product_variations');
            
            product_variations = JSON.parse(product_variations);
            for (var i = 0; i < product_variations.length; i++) {
                if (product_variations[i].image.hasOwnProperty('srcset')) {
                    product_variations[i].image.srcset = '';
                }
            }
            product_variations = JSON.stringify(product_variations);
            $this.attr('data-product_variations', product_variations);
            $this.addClass('fami-fixed-res-img');
        });
    }
    
    ciloe_fix_responsive_img_issue_product_var();
    
    function ciloe_add_to_cart_single() {
        /* SINGLE ADD TO CART */
        $(document).on('click', '.product:not(.product-type-external) .single_add_to_cart_button', function (e) {
            
            e.preventDefault();
            var _this = $(this);
            var _product_id = _this.val();
            var _form = _this.closest('form');
            var _form_data = _form.serialize();
            
            if (_product_id != '') {
                var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
            } else {
                var _data = _form_data;
            }
            if (_this.is('.disabled') || _this.is('.wc-variation-selection-needed')) {
                return false;
            }
            $('body,html').toggleClass('minicart-canvas-open');
            $('.ciloe-minicart').addClass('is-adding-to-cart');
            var atcUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', 'add-to-cart=' + _product_id + '&ciloe-ajax-add-to-cart=1');
            $.ajax({
                type: 'POST',
                url: atcUrl,
                data: _data,
                dataType: 'html',
                cache: false,
                headers: {'cache-control': 'no-cache'},
                success: function () {
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document).on('added_to_cart', function () {
                        $('.ciloe-minicart').removeClass('is-adding-to-cart');
                    });
                }
            });
            
            
        });
        $(document).on('click', '.famibt-add-all-to-cart', function (e) {
            $('body,html').toggleClass('minicart-canvas-open');
            if ($('.ciloe-minicart').length) {
                $('.ciloe-minicart').addClass('is-adding-to-cart');
            }
        });
        
    }
    
    /* Categories filter */
    $(document).on('click', '.category-filter a', function (e) {
        var $this = $(this);
        var toolBarProducts = $this.closest('.toolbar-products');
        var parentWrap = toolBarProducts.parent();
        var cat_slug = $this.closest('*[data-cat_slug]').data('cat_slug');
        
        if ($.trim(cat_slug) != '') {
            if ($('.shop-prdctfltr-filter-wrap .prdctfltr_ft_' + cat_slug).length) {
                parentWrap.find('.shop-prdctfltr-filter-wrap .prdctfltr_ft_' + cat_slug).trigger('click');
                e.preventDefault();
            }
            
        }
        else {
            if ($('.shop-prdctfltr-filter-wrap .prdctfltr_ft_none').length) {
                parentWrap.find('.shop-prdctfltr-filter-wrap .prdctfltr_ft_none').trigger('click');
                e.preventDefault();
            }
        }
        
    });
    if ($('.products-grid').hasClass('active')) {
        $('.main-content .products').addClass('grid-size');
    }
    
    /* Products size */
    $(document).on('click', '.products-sizes .products-size', function (e) {
        var $this = $(this);
        var product_size = parseInt($this.attr('data-products_num'));
        var thisParent = $this.closest('.products-sizes');
        var thisContainer = $this.closest('.main-container');
        var is_shortcode = thisParent.is('.products-sizes-shortcode');
        if (is_shortcode) {
            thisContainer = $this.closest('.prdctfltr_sc_products');
        }
        var productsList = thisContainer.find('.products');
        var product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6'; // 5 items
        
        // Remove all classes with prefix "products_list-size-"
        productsList.removeClass(function (index, class_name) {
            return (class_name.match(/\bproducts_list-size-\S+/g) || []).join(' '); // removes anything that starts with "products_list-size-"
        }).addClass('products_list-size-' + product_size);
        // Remove all classes with prefix "col-"
        productsList.find('.product-item').removeClass(function (index, class_name) {
            return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
        });
        
        switch (product_size) {
            case 6:
                product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                break;
            case 5:
                product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6';
                break;
            case 4:
                product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-12';
                break;
            case 3:
                product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-12';
                break;
        }
        
        productsList.find('.product-item').addClass(product_item_classes);
        thisParent.find('.products-size').removeClass('active');
        $this.addClass('active');
        
        
        if ($this.hasClass('products-grid')) {
            productsList.addClass('grid-size');
        } else {
            productsList.removeClass('grid-size');
        }
        
        e.preventDefault();
    });
    
    function ciloe_init_products_size() {
        $('.products-sizes .products-size.active').each(function () {
            var $this = $(this);
            var product_size = parseInt($this.attr('data-products_num'));
            var thisParent = $this.closest('.products-sizes');
            var thisContainer = $this.closest('.main-container');
            var is_shortcode = thisParent.is('.products-sizes-shortcode');
            if (is_shortcode) {
                thisContainer = $this.closest('.prdctfltr_sc_products');
            }
            var productsList = thisContainer.find('.products');
            var product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6'; // 5 items
            
            // Remove all classes with prefix "col-"
            productsList.find('.product-item').removeClass(function (index, class_name) {
                return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
            });
            
            switch (product_size) {
                case 6:
                    product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                    break;
                case 5:
                    product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6';
                    break;
                case 4:
                    product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-12';
                    break;
                case 3:
                    product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-12';
                    break;
            }
            productsList.find('.product-item').addClass(product_item_classes);
        });
    }
    
    function ciloe_add_class_active_to_list_cats_on_top_bar_filter() {
        if ($('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').length) {
            $('.part-cats-list-wrap .category-filter *[data-cat_slug]').removeClass('active');
            $('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').each(function () {
                var cat_slug = $(this).find('input').val();
                if ($.trim(cat_slug) != '') {
                    $('.part-cats-list-wrap .category-filter *[data-cat_slug="' + cat_slug + '"]').addClass('active');
                }
            });
        }
        else {
            if ($('.prdctfltr_filter[data-filter="product_cat"]').length) {
                $('.part-cats-list-wrap .category-filter *[data-cat_slug]').removeClass('active');
                $('.part-cats-list-wrap .category-filter *[data-cat_slug=""]').addClass('active');
            }
        }
    }
    
    /* ---------------------------------------------
     Load More Product (will remove)
     -----------------------------------------------*/
    function ciloe_product_loadmore() {
        $('.woo-product-loadmore').on('click', function (e) {
            $('.woo-product-loadmore').addClass('loading');
            // get post ID in array
            var except_post_ids = Array();
            var _product_wrap = $(this).closest('.ciloe-products');
            
            _product_wrap.find('.product-item').each(function () {
                var post_id = $(this).attr('id').replace('post-', '');
                except_post_ids.push(post_id);
            });
            // get post ID in array
            var attr = $(this).attr('data-attribute');
            var cats = $(this).attr('data-cats');
            var id = $(this).data('id');
            var page = parseInt($(this).attr('page'));
            var data = {
                action: 'ciloe_loadmore_product',
                except_post_ids: except_post_ids,
                security: ciloe_theme_frontend.security,
                attr: attr,
                cats: cats,
                page: page,
            };
            $.post(ciloe_theme_frontend.ajaxurl, data, function (response) {
                var items = $('' + response['html'] + '');
                if ($.trim(response['success']) == 'ok') {
                    var tab_id = '.' + id;
                    $('#' + id).append(items);
                    if ($.trim(response['show_bt']) == '0') {
                        $(tab_id + ' .woo-product-loadmore').html('No More Product');
                    } else {
                        $(tab_id + ' .woo-product-loadmore').attr('page', page + 1);
                    }
                } else {
                    $('#' + id).append('<p class="return-message bg-success">Not ok</p>');
                }
                $('.woo-product-loadmore').removeClass('loading');
            });
            return false;
        });
    }
    
    /*----------------------------------------------
     Slick Slider
     ------------------------------------------------*/
    
    //Slick Slider
    function ciloe_init_slick() {
        $('.slick-slide-wrap').slick({
            centerMode: true,
            centerPadding: "26.2105263%",
            slidesToShow: 1,
            dots: false,
            prevArrow: "<span class='arrow-prev'><span class='arrow_sep'><span class='arrow_begin'></span><span class='arrow_end'></span><span class='arrow_top'></span><span class='arrow_bottom'></span></span></span>",
            nextArrow: "<span class='arrow-next'><span class='arrow_sep'><span class='arrow_begin'></span><span class='arrow_end'></span><span class='arrow_top'></span><span class='arrow_bottom'></span></span></span>",
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        centerMode: false,
                    }
                }
            ]
        });
    }
    
    
    /* ---------------------------------------------
     Scripts scroll
     --------------------------------------------- */
    
    // Back to top button
    function backToTop() {
        var el = $('.totop-holder');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 180) {
                el.addClass('b_show');
            } else {
                el.removeClass('b_show');
            }
        });
        el.on('click', function (e) {
            $('body,html').animate({
                scrollTop: 0
            }, 500);
            return false;
        });
    }
    
    /* ---------------------------------------------
     // Woof Ajax done
     --------------------------------------------- */
    function woof_ajax_done_handler(e) {
        if ($('img.lazy').length > 0) {
            $('img.lazy').each(function () {
                if ($(this).data('original')) {
                    $(this).attr('src', $(this).data('original'));
                }
            });
        }
    }
    
    function ciloe_scrolldown() {
        $('.rev_slider_wrapper').on('mousewheel', function (event) {
            var $this = $(this);
            if ($this.find('.arrow-down').length) {
                if (!$this.find('.arrow-down').is('.has-scrolled')) {
                    $this.find('.arrow-down').trigger('click').addClass('has-scrolled');
                }
            }
        });
    }
    
    
    /* Search Instant */
    function ciloe_search_json(search_key, cat_slug, json_args) {
        var all_results = Array();
        $.each(json_args, function (i, v) {
            var regex = new RegExp(search_key, "i");
            if (v.post_title.search(new RegExp(regex)) != -1) {
                if (cat_slug != '') {
                    var regex_cat_slug = new RegExp(cat_slug, "i");
                    if (v.cat_slugs.search(new RegExp(regex_cat_slug)) != -1) {
                        all_results.push(v);
                    }
                }
                else {
                    all_results.push(v);
                }
            }
        });
        
        return all_results;
    }
    
    var products_data_array = {};
    
    // Instant Search
    function ciloe_search_instant() {
        $('.header-search-box span.icons').on('click', function (e) {
            $('.header-search-content').addClass('show-content-search');
            $('.site-header').addClass('show-search');
            $('.site-header .search-field').focus();
            
            if ($.isEmptyObject(products_data_array) && !$('.instant-search-disabled').length) {
                var data = {
                    action: 'ciloe_instant_search_data',
                    security: ciloe_theme_frontend.security,
                };
                $.post(ciloe_theme_frontend.ajaxurl, data, function (response) {
                    $('.site-header .search-field').focus();
                    products_data_array = response['array'];
                    var $modal = $('.instant-search-modal'),
                        $form = $modal.find('form');
                    var url = $form.attr('action') + '?' + $form.serialize();
                    if ($.trim(response['success']) == 'yes') {
                        $(document).on('keyup', '.instant-search .search-fields input[name="s"]', function (e) {
                            var $this = $(this);
                            var thisSeachForm = $this.closest('.instant-search');
                            if (thisSeachForm.is('.instant-search-disabled')) {
                                return false;
                            }
                            var searchWrap = $this.closest('.search-fields').find('.search-results-container .search-results-container-inner');
                            var search_key = $.trim($this.val());
                            var cat_slug = ''; // All cats
                            if ($('.header-search-box .instant-search input[name="product_cat"]:checked').length) {
                                cat_slug = $('.header-search-box .instant-search input[name="product_cat"]:checked').val();
                            }
                            searchWrap.find('.container-search').remove();
                            $(this).removeClass('search-has-results');
                            if (products_data_array && search_key != '') {
                                var search_results = ciloe_search_json(search_key, cat_slug, products_data_array);
                                if (search_results) {
                                    $(this).addClass('search-has-results');
                                    searchWrap.html('<div class="container-search"><div class="search-results-wrap row auto-clear"></div></div>');
                                    var max_instant_search_results = parseInt(ciloe_theme_frontend['max_instant_search_results']);
                                    if (isNaN(max_instant_search_results) || max_instant_search_results <= 0) {
                                        max_instant_search_results = 100;
                                    }
                                    for (var i = 0; i < search_results.length && i < max_instant_search_results; i++) {
                                        searchWrap.find('.container-search .search-results-wrap').append(search_results[i]['post_html']);
                                    }
                                    if ($('.product-item-search').length > 0) {
                                        searchWrap.find('.container-search').append('<a class="search-view" href="' + url + '">View All</a>');
                                    }
                                }
                                
                            }
                        });
                        $('.search-fields input[name="s"]').trigger('keyup');
                        $('.product-cats input[name="product_cat"]').on('click', function (e) {
                            $('.search-fields input[name="s"]').trigger('keyup');
                            $(this).parent().addClass('selected').siblings().removeClass('selected');
                            e.preventDefault();
                        });
                        $('.search-reset').on('click', function (e) {
                            var searchWrap = $(this).closest('.search-fields');
                            searchWrap.find('.container-search').remove();
                        });
                    }
                });
            }
            e.preventDefault();
        });
    }
    
    function fami_main_header_sticky() {
        if ($('.menu-sticky-smart').length) {
            var mainHeader = $('.header-position');
            var top_spacing = 0;
            var admin_bar_h = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
            top_spacing += admin_bar_h;
            mainHeader.sticky({topSpacing: top_spacing});
        }
    }
    
    /* ---------------------------------------------
     Scripts bind
     --------------------------------------------- */
    
    $(window).on('load', function () {
        better_equal_elems();
        number_dots_rev();
        $('.scrollbar-macosx').scrollbar();
        
    });
    /* ---------------------------------------------
     Scripts load
     --------------------------------------------- */
    
    window.addEventListener('load',
        function (ev) {
            thumbnail_product();
            fami_init_lazy_load();
            ciloe_init_slick();
            better_equal_elems();
            ciloe_init_carousel();
            ciloe_single_product_mobile_structure();
            ciloe_update_single_product_title_price_stars_outside_summary();
        }
    );
    
    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */
    
    $(window).on("resize", function () {
        $('.scrollbar-macosx').scrollbar();
        thumbnail_product();
        better_equal_elems();
        ciloe_clone_main_menu();
        ciloe_resizeMegamenu();
    });
    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    // Reinit some important things after ajax
    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.hasOwnProperty('data')) {
            ciloe_variations_custom_ajax();
            fami_init_lazy_load();
            ciloe_init_products_size();
            ciloe_fix_responsive_img_issue_product_var();
        }
        initAjaxLoad();
        ciloe_single_product_mobile_structure();
        ciloe_update_single_product_title_price_stars_outside_summary();
        ciloe_add_class_active_to_list_cats_on_top_bar_filter();
        ciloe_init_carousel();
        $('.scrollbar-macosx').scrollbar();
        ciloe_remove_product();
        number_dots_rev();
    });
    
    $(document).on("woof_ajax_done", woof_ajax_done_handler);
    $(document).on('adding_to_cart', function () {
        $('body,html').toggleClass('minicart-canvas-open');
        if ($('.ciloe-minicart').length) {
            $('.ciloe-minicart').addClass('is-adding-to-cart');
        }
    });
    $(document).on('added_to_cart', function () {
        if ($('.ciloe-minicart').length) {
            $('.ciloe-minicart').removeClass('is-adding-to-cart');
        }
    });
    $(document).ready(function () {
        ciloe_clone_append_category();
        ciloe_clone_main_menu();
        fami_main_header_sticky();
        ciloe_search_instant();
        ciloe_init_products_size();
        $('.scrollbar-macosx').scrollbar();
        ciloe_add_class_active_to_list_cats_on_top_bar_filter();
        ciloe_add_to_cart_single();
        dropdown_menu('#menu-vertical-menu');
        ciloe_scrolldown();
        ciloe_remove_product();
        toggle_form();
        ciloe_sidebar_offcanvas();
        ciloe_minicart_offcanvas();
        ciloe_instant_search();
        ciloe_vertical_menu();
        initAjaxLoad();
        thumbnail_product();
        ciloe_countdown();
        ciloe_woo_quantily();
        ciloe_tab_fade_effect();
        ciloe_google_maps();
        ciloe_resizeMegamenu();
        update_wishlist_count();
        ciloe_singleProduct_popup();
        ciloe_product_loadmore();
        backToTop();
        sticky_detail_content();
        ciloe_init_popup();
        number_dots_rev();
        //Scroll Down
        $('.scroll-down a').on('click', function (e) {
            var url_wl = $(this).attr("href");
            var taget_wl = $(url_wl).offset().top;
            $('html, body').animate({scrollTop: taget_wl - 70}, 'slow');
            e.preventDefault();
        });
        if ($('.big_images').length) {
            $('.product-toolbar').addClass('product-big');
        }
        // Submit when choose sort by
        $(document).on('change', 'form.fami-woocommerce-ordering select[name="orderby"]', function () {
            var $this = $(this);
            var thisForm = $this.closest('form');
            var order_val = $this.val();
            var trigger_submit = true;
            $('.prdctfltr_wc .prdctfltr_woocommerce_ordering').each(function () {
                if ($(this).closest('.prdctfltr_sc_products').length == 0) {
                    if ($(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val + ' input[type="checkbox"]').length) {
                        $(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val).click();
                        trigger_submit = false;
                        return false;
                    }
                }
            });
            
            if (trigger_submit) {
                thisForm.submit();
            }
        });
        $(document).on('click', '.prdctfltr_orderby .prdctfltr_checkboxes label', function () {
            var $this = $(this);
            var order_val = $this.find('input[type="checkbox"]').val();
            if ($('form.fami-woocommerce-ordering select[name="orderby"]').length) {
                $('form.fami-woocommerce-ordering select[name="orderby"]').val(order_val);
                var selected_index = $('form.fami-woocommerce-ordering select[name="orderby"]').prop('selectedIndex');
                var order_text = $('form.fami-woocommerce-ordering select[name="orderby"] option:selected').text();
                $('form.fami-woocommerce-ordering .chosen-results .active-result').removeClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-results .active-result[data-option-array-index="' + selected_index + '"]').addClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-single span').text(order_text);
                
            }
        });
        if ($('.toolbar-products select').length > 0) {
            // $('.toolbar-products select').chosen();
        }
        if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
            $('body').addClass('safari');
        }
    });
})(jQuery); // End of use strict