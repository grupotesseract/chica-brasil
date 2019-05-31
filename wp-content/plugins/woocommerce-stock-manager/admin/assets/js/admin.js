(function ( $ ) {
	"use strict";

	$(function () {

		/**
         * Fix for stupids
         *
         */
        jQuery( '.manage_stock_select' ).on( 'change' , function(){
            
            var id = jQuery( this ).data( 'item' );
            var manage = jQuery( this ).val();
            
            if( manage == 'no' ){
                
                //jQuery( '.stock_status_' + id ).val( 'outofstock' );
                jQuery( '.stock_status_' + id ).prop( 'disabled', false );
                jQuery( '.backorders_' + id ).val( 'no' );
                jQuery( '.backorders_' + id ).prop( 'disabled', 'disabled' );
                jQuery( '.stock_' + id ).val( '0' );
                jQuery( '.stock_' + id ).prop( 'disabled', 'disabled' );                

            }else{

                jQuery( '.stock_status_' + id ).prop( 'disabled', 'disabled' );
                jQuery( '.backorders_' + id ).prop( 'disabled', false );
                jQuery( '.stock_' + id ).prop( 'disabled', false );            

                var data = {
                    'action'  : 'wsm_get_product_data',
                    'productid' : id
                };

                jQuery.post( ajaxurl, data, function( response ) {

                    var result = jQuery.parseJSON( response );

                    //console.log( result );

                    if( result.stock_status ){
                        jQuery( '.stock_status_' + result.productid ).val( result.stock_status );
                    }
                    if( result.backorders ){
                        jQuery( '.backorders_' + result.productid ).val( result.backorders );
                    }
                    if( result.stock ){
                        jQuery( '.stock_' + result.productid ).val( result.stock );
                    }

                });
            
            }

        });

        /**
         * Fix for stupids 2
         *
         */
        jQuery( '.backorders_select' ).on( 'change' , function(){
            
            var id = jQuery( this ).data( 'item' );
            var backorders = jQuery( this ).val();
            
            if( backorders == 'no' ){
                
                var number = jQuery( '.stock_' + id ).val();

                if( number ){
                    if( number > 0 ){
                        jQuery( '.stock_status_' + id ).val( 'instock' );
                    }else{
                        jQuery( '.stock_status_' + id ).val( 'outofstock' );
                    }
                }else{
                    jQuery( '.stock_status_' + id ).val( 'outofstock' );
                }
                
            }else{

                jQuery( '.stock_status_' + id ).val( 'onbackorder' );                
            
            }

        });

        /**
         * Fix for stupids 3
         *
         */
        jQuery( '.stock_number' ).on( 'change' , function(){
            
            var id = jQuery( this ).data( 'item' );
            var stock = jQuery( this ).val();
            //console.log( stock );

                if( stock ){
                    if( stock > 0 ){
                        jQuery( '.stock_status_' + id ).val( 'instock' );
                    }else{
                        var backorder = jQuery( '.backorders_' + id ).val();
                        if( backorder == 'no' ){
                            jQuery( '.stock_status_' + id ).val( 'outofstock' );
                        }else{
                            jQuery( '.stock_status_' + id ).val( 'onbackorder' );
                        }
                    }
                }else{
                    jQuery( '.stock_status_' + id ).val( 'outofstock' );
                }            

        });

        /**
		 * Save single product line in stock table
		 *
		 */              
        jQuery('.save-product').on('click', function(){
        jQuery('.lineloader').css('display','block');
        var product = jQuery(this).data('product');
       
       
       var sku            = jQuery('.sku_' + product).val();
       var manage_stock   = jQuery('.manage_stock_' + product).val();
       var stock_status   = jQuery('.stock_status_' + product).val();
       var backorders     = jQuery('.backorders_' + product).val();
       var stock          = jQuery('.stock_' + product).val();
       var regular_price  = jQuery('.regular_price_' + product).val();
       var sales_price    = jQuery('.sales_price_' + product).val();
       var weight         = jQuery('.weight_' + product).val();
       var tax_status     = jQuery('.tax_status' + product).val();
       var tax_class      = jQuery('.tax_class' + product).val();
       var shipping_class = jQuery('.shipping_class' + product).val();
       var secure         = jQuery('.wsm-ajax-nonce_' + product).val();
   
       var data = {
            action         : 'save_one_product',
            product        : product,
            sku            : sku,
            manage_stock   : manage_stock,
            stock_status   : stock_status,
            backorders     : backorders,
            stock          : stock,
            regular_price  : regular_price,
            sales_price    : sales_price,
            weight         : weight,
            tax_status     : tax_status,
            tax_class      : tax_class,
            shipping_class : shipping_class,
            secure         : secure
       };


        jQuery.post(ajaxurl, data, function(response){
           
          jQuery('.lineloader').css('display','none'); 
        
        });
       
    });
    
    
    /**
     * Show variations of selected product
     *
     */ 
    jQuery('.show-variable').on('click', function(){
       var variable = jQuery(this).data('variable');
       jQuery('.variation-item-' + variable).toggleClass('show-variations');
              
    });                 
    
    
    /**
     * Navigation
     *
     */          
    jQuery('.stock-manager-navigation li span').on('click', function(){
        jQuery('.stock-manager-navigation li span').removeClass('activ');
        jQuery(this).addClass('activ');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-default').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.stock-filter').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-by-sku').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-by-sku').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-by-title').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-by-title').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-display').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-display').addClass('active-filter');
    });


        //Open box for product title saving
        jQuery('.table_name_box .dashicons').on('click', function(){      
            var item = jQuery(this).data('item');
            jQuery('.item-post-title-wrap-'+item).css('display','block');
        });
        //Close box for product title saving
        jQuery('.item-post-title-button-close').on('click', function(){
            jQuery(this).parent().css('display', 'none');
        });
        //Save product title
        jQuery('.item-post-title-button').on('click', function(ajax_object){
           
            var item = jQuery(this).data('item');
            var title = jQuery('.item-post-title-'+item).val();
            var data = {
                action  : 'wsm_save_title_product',
                item    : item,
                title   : title,
                secure  : ajax_object.ajax_nonce
            };
            jQuery('.item-post-title-'+item).val(title); 
            jQuery('.item-post-link-'+item).text(title);  
            
            jQuery.post(ajaxurl, data, function(response, item, title){   
                jQuery('.item-post-title-wrap-'+response).css('display', 'none');      
            });
            
        });



        //Open box for sku saving
        jQuery('.item_sku_box .dashicons').on('click', function(){      
            var item = jQuery(this).data('item');
            jQuery('.item-sku-wrap-'+item).css('display','block');
        });
        //Close box for sku saving
        jQuery('.item-sku-button-close').on('click', function(){
            jQuery(this).parent().css('display', 'none');
        });
        //Save sku
        jQuery('.item-sku-button').on('click', function(ajax_object){
           
            var item = jQuery(this).data('item');
            var sku = jQuery('.sku_'+item).val();
            var data = {
                action  : 'wsm_save_sku',
                item    : item,
                sku     : sku,
                secure  : ajax_object.ajax_nonce
            };
            jQuery('.sku_-'+item).val(sku); 
            jQuery('.item-sku-text-'+item).text(sku);  
            
            jQuery.post(ajaxurl, data, function(response, item, sku){   
                jQuery('.item-sku-wrap-'+response).css('display', 'none');      
            });
            
        });


	});

}(jQuery));