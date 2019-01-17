(function ($) {
    "use strict"; // Start of use strict

    $(document).ready(function () {

        $(document).on('submit', 'form.newsletter-form-wrap', function (e) {
            var thisWrap = $(this);
    
            if ( thisWrap.hasClass('processing') ) {
                return false;
            }
            var email    = thisWrap.find('input[name="email"]').val(),
                fname    = thisWrap.find('input[name="fname"]').val(),
                lname    = thisWrap.find('input[name="lname"]').val(),
                list_id  = thisWrap.find('input[name="list_id"]:checked').val(),
                // list_id  = "",
                selected = thisWrap.find('input[name="list_id"]:checked');
    
            if ( selected.length > 0 ) {
                list_id = selected.val();
            }
            var data = {
                action: 'submit_mailchimp_via_ajax',
                email: email,
                list_id: list_id,
                fname: fname,
                lname: lname
            }
    
            thisWrap.addClass('processing');
            thisWrap.parent().find('.return-message').remove();
    
            $.post(fami_mailchimp.ajaxurl, data, function (response) {
        
                if ( $.trim(response[ 'success' ]) == 'yes' ) {
            
                    thisWrap.parent().append('<div class="return-message bg-success">' + response[ 'message' ] + '</div>');
                    thisWrap.find('input[name="email"]').val('');
                    thisWrap.find('input[name="fname"]').val('');
                    thisWrap.find('input[name="lname"]').val('');
                    $( document.body ).trigger( 'fami_newsletter_success', response[ 'message' ] );
                }
                else {
                    thisWrap.parent().append('<div class="return-message bg-danger">' + response[ 'message' ] + '</div>');
                    $( document.body ).trigger( 'fami_newsletter_error', response[ 'message' ] );
                }
        
                thisWrap.removeClass('processing');
        
            });
            e.preventDefault();
            return false;
        });
    });

})(jQuery); // End of use strict