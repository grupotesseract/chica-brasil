/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework
 * A Lightweight and easy-to-use WordPress Options Framework
 *
 * Copyright 2015 Codestar <info@codestarlive.com>
 *
 * -----------------------------------------------------------
 *
 */
;(function ($, window, document, undefined) {
    'use strict';

    $(document).ready(function () {
        // ======================================================
        // SELECT PREVIEW
        // ------------------------------------------------------
        $(document).on('change', '.cs-select-images', function () {
            var url = jQuery(this).find(':selected').data('preview');
            jQuery(this).closest('.container-select-preview').find('.preview img').attr('src', url);
        })
    })

})(jQuery, window, document);