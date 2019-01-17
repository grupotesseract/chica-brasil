;(function ($) {
    "use strict";
    
    var fami_import_percent = 0,
        fami_import_percent_increase = 0,
        fami_import_index_request = 0,
        fami_arr_import_request_data = [],
        optionid = '';
    
    $(document).on('click', '.button-primary.open-import', function () {
        var _contentID = $(this).data('id');
        tb_show('Import Option', '#TB_inline?inlineId=content-demo-' + _contentID + '');
    });
    
    function fami_import_ajax_handle() {
        if (fami_import_index_request == fami_arr_import_request_data.length) {
            $('#option-' + optionid).addClass('done-import');
            $('[data-option="' + optionid + '"]').find('.progress').hide();
            $('[data-option="' + optionid + '"]').find('.progress-wapper').addClass('complete');
            return;
        }
        $('[data-option="' + optionid + '"] .progress-item').find('.' + fami_arr_import_request_data[fami_import_index_request]["action"]).show();
        
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: fami_arr_import_request_data[fami_import_index_request],
            complete: function (jqXHR, textStatus) {
                $('[data-option="' + optionid + '"] .progress-item').find('.' + fami_arr_import_request_data[fami_import_index_request]["action"]).addClass('complete');
                fami_import_percent += fami_import_percent_increase;
                kt_progress_bar_handle();
                fami_import_index_request++;
                setTimeout(function () {
                    fami_import_ajax_handle();
                }, 200);
            }
        });
    }
    
    function kt_progress_bar_handle() {
        
        if (fami_import_percent > 100) {
            fami_import_percent = 100;
        }
        
        if (fami_import_percent == 100) {
            $('.fami-button-import.processing').text('Import Completed');
            $('.fami-button-import.processing').removeClass('processing');
        }
        
        var progress_bar = $('[data-option="' + optionid + '"]').find('.progress-circle .c100'),
            class_percent = 'p' + Math.ceil(fami_import_percent);
        progress_bar.addClass(class_percent);
        
        progress_bar.find('.percent').html(Math.ceil(fami_import_percent) + '%');
    }
    
    $(document).on('click', '.fami-button-import', function (e) {
        
        if ($(this).is('processing')) {
            return false;
        }
        
        var c = confirm('Are you sure you want to import this demo?');
        if (!c) {
            return false;
        }
        
        $(this).addClass('processing');
        $('.fami-button-import.processing').text('Importing...');
        
        $(this).closest('#TB_ajaxContent').find('.progress-wapper').show();
        
        var id = $(this).data('id'),
            slug = $(this).data('slug'),
            content_ajax = $(this).closest('#TB_ajaxContent');
        
        content_ajax.find('[data-percent="1"]').attr('class', 'c100 p0 dark green');
        content_ajax.find('.percent').html('0%');
        content_ajax.find('.progress-wapper').show();
        fami_import_percent = 0;
        fami_import_percent_increase = 0;
        fami_import_index_request = 0;
        fami_arr_import_request_data = [];
        optionid = $(this).data('optionid');
        
        var import_full_content = false,
            import_page = false,
            import_post = false,
            import_product = false,
            import_menu = false,
            import_widget = false,
            import_revslider = false,
            import_theme_options = false,
            // import_setting_options = false,
            import_attachments = false;
        
        $('[data-option="' + optionid + '"]').find('.progress-wapper .item').removeClass('complete').css('display', 'none');
        /* IMPORT PAGE */
        if ($('#fami_import_page_content-' + id).is(':checked')) {
            import_page = true;
        } else {
            import_page = false;
        }
        if ($('#fami_import_post_content-' + id).is(':checked')) {
            import_post = true;
        } else {
            import_post = false;
        }
        if ($('#fami_import_product_content-' + id).is(':checked')) {
            import_product = true;
        } else {
            import_product = false;
        }
        if ($('#fami_import_product_content-' + id).is(':checked')) {
            import_product = true;
        } else {
            import_product = false;
        }
        if ($('#fami_import_widget-' + id).is(':checked')) {
            import_widget = true;
        } else {
            import_widget = false;
        }
        if ($('#fami_import_revslider-' + id).is(':checked')) {
            import_revslider = true;
        } else {
            import_revslider = false;
        }
        if ($('#fami_import_attachments-' + id).is(':checked')) {
            import_attachments = true;
        } else {
            import_attachments = false;
        }
        if ($('#fami_import_menu-' + id).is(':checked')) {
            import_menu = true;
        } else {
            import_menu = false;
        }
        if ($('#fami_import_theme_options-' + id).is(':checked')) {
            import_theme_options = true;
        } else {
            import_theme_options = false;
        }
        // if ( $('#fami_import_setting_options-' + id).is(':checked') ) {
        //     import_setting_options = true;
        // } else {
        //     import_setting_options = false;
        // }
        if ($('#fami_import_full_content-' + id).is(':checked')) {
            import_full_content = true;
            import_widget = true;
            import_revslider = true;
            import_menu = true;
            import_page = true;
            import_attachments = true;
            import_theme_options = true;
            // import_setting_options = true;
        }
        
        // Demo content
        fami_arr_import_request_data.push({
            'action': 'fami_import_single_page_content',
            'optionid': optionid,
            'slug_home': [slug, 'blog', 'contact-us', 'about-us'],
        });
        if (import_full_content) {
            var data = {
                'action': 'fami_import_full_content',
                'optionid': optionid,
            };
            fami_arr_import_request_data.push(data);
        }
        
        if (import_page) {
            var data = {
                'action': 'fami_import_page_content',
                'optionid': optionid,
            }
            fami_arr_import_request_data.push(data);
        }
        if (import_post) {
            var data = {
                'action': 'fami_import_post_content',
                'optionid': optionid,
            }
            fami_arr_import_request_data.push(data);
        }
        if (import_product) {
            var data = {
                'action': 'fami_import_product_content',
                'optionid': optionid,
            }
            fami_arr_import_request_data.push(data);
        }
        if (import_attachments) {
            var data = {
                'action': 'fami_import_attachments',
                'optionid': optionid,
            }
            fami_arr_import_request_data.push(data);
        }
        if (import_menu) {
            fami_arr_import_request_data.push({
                'action': 'fami_import_menu',
                'optionid': optionid,
            });
        }
        if (import_theme_options) {
            fami_arr_import_request_data.push({
                'action': 'fami_import_theme_options',
                'optionid': optionid,
            });
        }
        // if ( import_setting_options ) {
        //     fami_arr_import_request_data.push({
        //         'action': 'fami_import_setting_options',
        //         'optionid': optionid,
        //     });
        // }
        if (import_widget) {
            fami_arr_import_request_data.push({'action': 'fami_import_widget', 'optionid': optionid});
        }
        if (import_revslider) {
            fami_arr_import_request_data.push({'action': 'fami_import_revslider', 'optionid': optionid});
        }
        
        fami_arr_import_request_data.push({
            'action': 'fami_import_config',
            'optionid': optionid,
        });
        
        var total_ajaxs = fami_arr_import_request_data.length;
        
        if (total_ajaxs == 0) {
            $(this).removeClass('processing');
            return;
        }
        
        fami_import_percent_increase = (100 / total_ajaxs);
        
        fami_import_ajax_handle();
        
        e.preventDefault();
    });
    
    function full_content_change() {
        $('.fami_import_full_content').each(function () {
            var _this = $(this);
            if (_this.is(':checked')) {
                _this.closest('.group-control').find('input[type="checkbox"]').not(_this).attr('checked', false);
                _this.closest('.group-control').find('label').not(_this.parent()).css({
                    'pointer-events': 'none',
                    'opacity': '0.4'
                });
            } else {
                _this.closest('.group-control').find('label').not(_this.parent()).css({
                    'pointer-events': 'initial',
                    'opacity': '1'
                });
            }
        })
    }
    
    full_content_change();
    
    $(document).on('change', function () {
        full_content_change()
    });
    
})(jQuery, window, document);