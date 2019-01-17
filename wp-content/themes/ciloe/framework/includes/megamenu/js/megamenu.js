/*
*
*	Admin $ Mega menu
*	------------------------------------------------
*
*/

(function($){
    "use strict";
    $(document).ready(function() {
        $( 'body' ).on( 'click', '.ciloe_image_menu', function ( e ){
            e.preventDefault();
            var item_id = $(this).data('item_id');
            var t = $(this);
            var frame,
                frameOptions = {
    				className: 'media-frame rwmb-file-frame',
    				multiple : true
    			};
                
            frame = wp.media( frameOptions );
            // Open media uploader
            frame.open();
            frame.off( 'select' );
            // When an image is selected in the media frame...
            frame.on( 'select', function() {
              // Get media attachment details from the frame state
              var attachment = frame.state().get('selection').first().toJSON();
              t.closest('.image-field').find('img.preview').attr('src', attachment.url).attr('alt', attachment.alt);
              
              t.closest('.image-field').find('.image_input').val(attachment.id);
              if(t.hasClass('icon_image')){
                  var html ='<img class="image-icon" src="'+attachment.url+'" alt="" />';
                  var item_menu_html = $('#menu-item-'+item_id).find('.menu-item-title').text();
                  $('#menu-item-'+item_id).find('.menu-item-title').html(html + item_menu_html)
              }
            });
        });

        //
        $(document).on('change','.item_icon_type input',function(){
            var type = $(this).val();
            if( type=="fonticon" ){
                $(this).closest('.container-megamenu').find('.field-fonticon').show();
                $(this).closest('.container-megamenu').find('.group-image').hide();
            }
            if( type=="image" ){
                $(this).closest('.container-megamenu').find('.field-fonticon').hide();
                $(this).closest('.container-megamenu').find('.group-image').show();
            }
            if(type=="none"){
                $(this).closest('.container-megamenu').find('.field-fonticon').hide();
                $(this).closest('.container-megamenu').find('.group-image').hide();
            }
        })

        // 
        $(document).on('click','.button-select-icon',function( e ){
            var id = $(this).data('id');
            e.preventDefault();
            var data = {
                action:'megamenu_load_font_icon',
                id:id
            }
            $.post( ajaxurl, data, function( result ) {
                $.magnificPopup.open({
                  items: {
                    src: result, // can be a HTML string, jQuery object, or CSS selector
                    type: 'inline'
                  }
                });
            });
        })

        //
        $(document).on('click','.font-item',function(){
            var icon = $(this).data('icon');
            var id = $(this).data('id');
            // add prevew
            var html='<span class="icon '+icon+'"></span>';
            $('#font-icon-preview-'+id).html(html);
            // add value
            $('#menu-item-font-icon-'+id).val(icon);
            var item_menu_html = $('#menu-item-'+id).find('.menu-item-title').text();
            $('#menu-item-'+id).find('.menu-item-title').html(html + item_menu_html)
            $(this).closest('.icons-popup').find('button.mfp-close').click();
        })
    });
        
})(jQuery);