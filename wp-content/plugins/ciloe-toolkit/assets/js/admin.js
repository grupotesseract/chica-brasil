/**
 * Admin
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Navigation
 * @version 1.3.2
 */
jQuery(function ($) {
    $.add_new_range = function () {
        var range_filter = $('#widgets-right').find('.range-filter'),
            input_field = range_filter.find('input:last-child'),
            field_name = range_filter.data('field_name'),
            position = parseInt(input_field.data('position')) + 1,
            html = '<input type="text" placeholder="min" name="' + field_name + '[' + position + '][min]" value="" class="yith-wcan-price-filter-input widefat" data-position="' + position + '"/>' +
                '<input type="text" placeholder="max" name="' + field_name + '[' + position + '][max]" value="" class="yith-wcan-price-filter-input widefat" data-position="' + position + '"/>';

        range_filter.append(html);
    };
});