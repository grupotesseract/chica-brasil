
(function ($) {
    "use strict"; // Start of use strict
    /* ---------------------------------------------
     FULL PAGE
     --------------------------------------------- */
    $('#fullpage').fullpage({
        css3: true,
        navigation: true,
        verticalCentered: true,
        scrollOverflow:false,
        sectionSelector: '.ciloe-full-page-elem', // '.section-slide',
        autoScrolling:true,
        scrollHorizontally: true,
        // responsiveWidth: 991
    });
    
    function full_page() {
        var menuType = 'desktop';

        $(window).on('load resize', function() {
            var currMenuType = 'desktop';

            if ( matchMedia( 'only screen and (max-width: 1024px)' ).matches ) {
                currMenuType = 'mobile';
            }

            if ( currMenuType !== menuType ) {
                menuType = currMenuType;

                if ( currMenuType === 'mobile' ) {
                    
                } else {
                    
                }
            }else{
                var _hw       = $(window).innerHeight();
                var _win      = $(window).innerWidth();
                var _height_h = $('#header').outerHeight(true);
                var _h_admin  = 32;

                if (_win <= 782) {
                    _h_admin = 46;
                }

                $('#fullpage').each(function () {
                    if ($('body').hasClass('admin-bar')) {
                        var total = _hw - ( _height_h + _h_admin );

                        $(this).css('margin-top', '-' + _h_admin + 'px');

                        if (_win <= 991) {
                            $(this).find('.type-banner, .single-image, .vc_single_image-img').css('height', total / 2);
                        } else {
                            $(this).find('.fp-tableCell,.type-banner, .single-image, .vc_single_image-img').css('height', total);
                        }
                    } else {
                        var total = _hw - _height_h;
                        //$(this).find('.section-slide').css('padding-top', _height_h + 'px');

                        if (_win <= 991) {
                            $(this).find('.type-banner, .single-image, .vc_single_image-img').css('height', total / 2);
                        } else {
                            $(this).find('.fp-tableCell, .single-image, .vc_single_image-img, .type-banner').css('height', total);
                        }
                    }
                });
                
            }
        });

    };

})(jQuery); // End of use strict