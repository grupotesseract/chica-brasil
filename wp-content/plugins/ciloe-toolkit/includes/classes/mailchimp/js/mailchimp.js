 (function($){
    "use strict"; // Start of use strict
    $(document).ready(function() {

        $(document).on('click','.submit-newsletter',function(e){

            var thisWrap = $(this).closest('.newsletter-form-wrap');

            if (thisWrap.hasClass('processing')) {
                return false;
            }
            var email           = thisWrap.find('input[name="email"]').val();

            var data = {
                action : 'submit_mailchimp_via_ajax',
                email : email
            }

            thisWrap.addClass('processing');
            thisWrap.find('.return-message').remove();

            $.post(ciloe_ajax_mailchiml.ajaxurl, data, function (response) {
                
                if ($.trim(response['success']) == 'yes') {

                    thisWrap.append('<div class="return-message bg-success">' + response['message'] + '</div>');
                    thisWrap.find('input[name="email"]').val('');
                }
                else {
                    thisWrap.append('<div class="return-message bg-danger">' + response['message'] + '</div>');
                }

                thisWrap.removeClass('processing');

            });
            return false;
        })
    });

})(jQuery); // End of use strict