var $ = jQuery;
var _ajaxBlocked = false;

function FilterProducts( $order, $category, $search_term, $page_type ) {

    if ( ! _ajaxBlocked ) {

        $.ajax({
            dataType: 'html',
            method: 'POST',
            url: TesseractAjax.ajaxurl,
            data: {
                action: 'products_filter',
                order: $order,
                category: $category,
                search_term: $search_term,
                page_type: $page_type
            },
            beforeSend: function() {
                $('#products').empty().append('<div class="loading-gif"><svg width="150"  height="150"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-double-ring" style="background: none;"><circle cx="50" cy="50" ng-attr-r="{{config.radius}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.c1}}" ng-attr-stroke-dasharray="{{config.dasharray}}" fill="none" stroke-linecap="round" r="40" stroke-width="4" stroke="#D54E66" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(47.8541 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle><circle cx="50" cy="50" ng-attr-r="{{config.radius2}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.c2}}" ng-attr-stroke-dasharray="{{config.dasharray2}}" ng-attr-stroke-dashoffset="{{config.dashoffset2}}" fill="none" stroke-linecap="round" r="35" stroke-width="4" stroke="#CBE0F5" stroke-dasharray="54.97787143782138 54.97787143782138" stroke-dashoffset="54.97787143782138" transform="rotate(-47.8541 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;-360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg></div>');

                _ajaxBlocked = true;
            },
            success: function( data ) {
                _ajaxBlocked = false;
                
                $('#products').empty().append( data );

                $('.produtos-wrapper').isotope({
                    itemSelector: '.item',
                    percentPosition: true,
                    masonry: {
                        // use outer width of grid-sizer for columnWidth
                        columnWidth: '.col-md-3'
                    }
                });
            }
        });

    }

}
