jQuery(function ($) {
    "use strict";

    var initTab = function () {
        $(document).on('click', '.ciloe-mapper ul.nav li', function (e) {
            // Switch active tab index.
            $(this).addClass('active').siblings().removeClass('active');

            // Show target tab.
            var tab = $(this).parent().next('.tab-content').children('[data-tab="' + $(this).attr('data-nav') + '"]');

            if (tab.length) {
                tab.removeClass('hidden').siblings().addClass('hidden');
            }
        });

        // Disnable tab Popup Settings if pin-type is link
        $(document).on('click', '.item-styled input', function (e) {
            var _this = $(this);
            var value = _this.val();
            var parent = _this.closest('.pin-setting');

            if (value == 'link') {
                parent.find('.nav li[data-nav="popup-settings"]').hide();
            } else {
                parent.find('.nav li[data-nav="popup-settings"]').show();
            }
        });
    };

    var imageSelector = function () {
        $(document).on('click', '.ciloe-mapper a.btn-add-image, .ciloe-mapper #change-image, .ciloe-mapper a.image-selector', function (event) {
            event.preventDefault();

            if (!window.wr_image_selector) {
                // Create new media manager.
                window.wr_image_selector = wp.media({
                    button: {
                        text: ciloe_mapper.text.img_selector_btn_label,
                    },
                    states: [new wp.media.controller.Library({
                        title: ciloe_mapper.text.img_selector_modal_title,
                        library: wp.media.query({
                            type: 'image'
                        }),
                        multiple: false,
                        date: false,
                    })]
                });

                // When an image is selected, update the edit area.
                window.wr_image_selector.on('select', function () {
                    // Grab the selected attachment.
                    var attachment = window.wr_image_selector.state().get('selection').first();

                    // Update the field value.
                    if (window.wr_image_selector.input_element.attr('id') == 'ciloe_mapper_image') {
                        window.wr_image_selector.input_element.val(attachment.attributes.id);

                        // Update edit area with new image.
                        if ($('.ciloe-mapper-bot > .edit-image').length) {
                            $('.ciloe-mapper-bot .image-wrap > img').attr('src', attachment.attributes.url);
                        } else {
                            var edit_html = $('#ciloe_mapper_image_tmpl').text().replace('%URL%', attachment.attributes.url);

                            $('.ciloe-mapper-bot > .add-image').replaceWith(edit_html);

                            // Trigger event to initialize application.
                            setTimeout(function () {
                                $(document).trigger('init_ciloe_mapper');
                            }, 500);
                        }
                    } else {
                        window.wr_image_selector.input_element.val(attachment.attributes.url).trigger('change');
                    }

                    window.wr_image_selector.close();
                });
            }

            // Store input element for later reference.
            window.wr_image_selector.input_element = $(this).hasClass('image-selector') ? $(this).prev('input') : $('#ciloe_mapper_image');

            // Show media manager modal.
            window.wr_image_selector.open();
        });
    };

    var setupApplication = function () {
        // Define Backbone model for pin.
        var Pin = Backbone.Model.extend({
            // Default attributes for the pin item.
            defaults: function () {
                return {
                    top: 0,
                    left: 0,
                    settings: {},
                };
            },
        });

        // Define Backbone collection for pin list.
        var PinList = Backbone.Collection.extend({
            // Reference to this collection’s model.
            model: Pin,

            // Disable fetching from remote server.
            url: '#',

            // Override default method for fetching data.
            fetch: function () {
                if (window.ciloe_mapper_pins && window.ciloe_mapper_pins.length) {
                    this.add(window.ciloe_mapper_pins);
                }
            },
        });

        // Create the global collection of pins.
        var Pins = new PinList, index = 0;

        // Define Backbone view for pin.
        var PinView = Backbone.View.extend({
            tagName: 'div',
            className: 'csre-pin',

            // Cache the template function for a single item.
            template: _.template($('#ciloe_mapper_pin_tmpl').text()),

            // The DOM events specific to an item.
            events: {
                'click .icon-pin': 'edit',
                'click .icon-pin + img': 'edit',
                'click .text__area': 'edit',
                'click .close-box': 'close',

                'click .pin-action.delete-pin': 'remove',
                'click .pin-action.duplicate-pin': 'clone',
            },

            // The PinView listens for changes to its model, re-rendering.
            // Since there’s a one-to-one correspondence between a Pin and a
            // PinView in this app, we set a direct reference on the model for
            // convenience.
            initialize: function () {
                // Update index.
                this.index = index++;
            },

            // Re-render the titles of the pin item.
            render: function () {
                var self = this, settings = this.model.get('settings');

                this.$el.addClass(this.className).html(this.template(this.model.toJSON()));

                // Position the pin relatively to the image.
                var top = this.model.get('top'), left = this.model.get('left');

                if (typeof top == 'number' || '%' != top.substr(-1)) {
                    top = ( top / $('.image-wrap').height() ) * 100 + '%';

                    this.model.set('top', top);
                }

                if (typeof left == 'number' || '%' != left.substr(-1)) {
                    left = ( left / $('.image-wrap').width() ) * 100 + '%';

                    this.model.set('left', left);
                }

                this.$el.css({
                    top: top,
                    left: left,
                });

                // Make the pin draggable.
                this.$el.draggable({
                    stop: function (event, ui) {
                        // Update model.
                        var top = ( ui.position.top / $('.image-wrap').height() ) * 100 + '%';
                        var left = ( ui.position.left / $('.image-wrap').width() ) * 100 + '%';

                        self.model.set('top', top);
                        self.model.set('left', left);

                        // Update form fields.
                        self.$el.find('[data-option="top"]').val(top);
                        self.$el.find('[data-option="left"]').val(left);

                        // Position the pin relatively to the image.
                        self.$el.css({
                            top: top,
                            left: left,
                        });

                        // Set an attribute to prevent edit form from
                        // displaying.
                        self.just_dragged = true;

                        setTimeout(function () {
                            self.just_dragged = false;
                        }, 200);
                    },
                }).css('position', 'absolute');

                // Init tooltip.
                if (!settings['pin-type'] || settings['pin-type'] == 'woocommerce') {
                    this.$el.find('.tooltip').addClass('hidden');
                }

                if (settings['pin-type'] == 'link') {
                    this.$el.find('.nav li[data-nav="popup-settings"]').hide();
                }

                // Bind pin settings to edit form.
                this.$el.find('[data-option]').each(function () {
                    // Get option name.
                    var option = $(this).attr('data-option').match(/([^\[]+)(\[([^\[]+)\])*/);

                    // Update field name first.
                    if ($(this).attr('name') != option[0]) {
                        if (option[3] !== undefined) {
                            $(this).attr('name', 'ciloe_mapper_pins[' + self.index + '][' + option[1] + '][' + option[3] + ']');
                        } else {
                            $(this).attr('name', 'ciloe_mapper_pins[' + self.index + '][' + option[1] + ']');
                        }
                    }

                    // Then set field value.
                    var value;

                    if (option[3] !== undefined) {
                        if (settings[option[3]]) {
                            value = settings[option[3]];
                        }
                    } else {
                        value = self.model.get(option[1]);
                    }

                    if (value) {
                        if ($(this).prop('nodeName') == 'INPUT') {
                            if ($(this).attr('type') == 'radio') {
                                if ($(this).attr('value') == value) {
                                    $(this).attr('checked', 'checked');
                                } else {
                                    $(this).removeAttr('checked');
                                }
                            } else {
                                $(this).val(value);

                                if ($(this).attr('type') == 'hidden' && $(this).next().attr('type') == 'checkbox') {
                                    if (parseInt(value)) {
                                        $(this).next().attr('checked', 'checked');
                                    } else {
                                        $(this).next().removeAttr('checked');
                                    }
                                }
                            }
                        } else if ($(this).prop('nodeName') == 'TEXTAREA') {
                            $(this).val(value.replace(/<br>/g, "\n"));
                        } else {
                            $(this).val(value);
                        }
                    }

                    // Live preview.
                    switch ($(this).attr('data-option')) {

                        case 'settings[popup-width]':
                        case 'settings[popup-height]':
                            var setting = $(this).attr('data-option').match(/settings\[([^\]]+)\]/);
                            $(this).attr('placeholder', $('#general-settings [name*="' + setting[1] + '"]').val());
                            break;

                        case 'settings[area-text]':
                            $( this ).keyup( function() {
                                if ( $( this ).val().length == 0 ) {
                                    self.$el.find( '.text__area' ).empty();
                                }
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).html( $( this ).val() );
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-text-size]':
                            $( this ).keyup( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'font-size', $( this ).val() + 'px' );
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-text-color]':
                            $( this ).change( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'color', $( this ).val() + 'px' );
                                }
                            } ).trigger( 'change' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-width]':
                            $( this ).keyup( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'width', $( this ).val() + 'px' );
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-height]':
                            $( this ).keyup( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'height', $( this ).val() + 'px' );
                                    self.$el.find( '.text__area' ).css( 'line-height', $( this ).val() + 'px' );
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-border-width]':
                            $( this ).keyup( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css({
                                        'border-width': $( this ).val() + 'px',
                                        'border-style': 'solid'
                                    });
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-border-radius]':
                            $( this ).keyup( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'border-radius', $( this ).val() + 'px' );
                                }
                            } ).trigger( 'keyup' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-bg-color]' :
                            $( this ).change( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'background', $( this ).val() );
                                }
                            } ).trigger( 'change' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        case 'settings[area-border-color]' :
                            $( this ).change( function() {
                                if ( $( this ).val() != '' && 'icon-area' == self.$el.find( '[data-option*="icon-type"]:checked' ).val() ) {
                                    self.$el.find( '.text__area' ).css( 'border-color', $( this ).val() );
                                }
                            } ).trigger( 'change' );
                            self.$el.find('.icon-pin').next().next('div.text__area').removeClass('hidden');
                            break;
                        // End live preview for pin area
                        case 'settings[image-template]':
                            $(this).change(function () {
                                if ($(this).val() != '' && 'icon-image' == self.$el.find('[data-option*="icon-type"]:checked').val()) {
                                    var icon = self.$el.find('.icon-pin').addClass('hidden');

                                    if (icon.next('img').attr('src') != $(this).val()) {
                                        icon.next('img').attr('src', $(this).val());
                                    }

                                    icon.next('img').removeClass('hidden');
                                }
                            }).trigger('change');
                            break;

                        case 'settings[icon-type]':
                            $(this).change(function () {
                                if ($(this).attr('checked')) {
                                    var icon = self.$el.find('.icon-pin');
                                    var text_area = self.$el.find('.text__area');
                                    var icon_img = self.$el.find('img');
                                    var field_wrappers = self.$el.find('[data-icon-type="' + $(this).val() + '"]');

                                    if ('icon-font' == $(this).val()) {
                                        icon.removeClass('hidden').next('img').addClass('hidden');
                                    }else if($( this ).val() == 'icon-area'){
                                        icon.addClass('hidden');
                                        icon_img.addClass('hidden');
                                        text_area.removeClass( 'hidden' );
                                    } else {
                                        if (!icon.next('img').length) {
                                            icon.after('<img class="hidden">');
                                        }
                                        text_area.addClass('hidden');
                                    }

                                    field_wrappers.find('input, select, textarea').trigger('change').trigger('keyup');
                                }
                            }).trigger('change');
                            break;
                    }

                    // Init color picker if needed.
                    if ($(this).hasClass('color-picker')) {
                        $(this).cs_wpColorPicker();

                        setTimeout($.proxy(function () {
                            $(this).parent().parent().click(function () {
                                // Hide all icon selector popup if not being
                                // focused.
                                $('.ciloe-mapper .icon-selector').each(function () {
                                    if ($(this).children('.icon-wrap').css('display') != 'none' && !$.contains(this, event.target)) {
                                        $(this).children('.icon-wrap').hide();
                                    }
                                });
                            });
                        }, this), 500);
                    }

                    // Init icon selector if needed.
                    else if ($(this).hasClass('icon-selector')) {
                        if ($(this).val() == '') {
                            $(this).val('fa-plus');
                        }

                        $(this).addClass('hidden').after($('#ciloe_mapper_icon_selector_tmpl').text().replace('%SELECTED%', $(this).val()));
                    }

                    // Init product selector if needed.
                    else if ($(this).hasClass('product-selector')) {
                        $(this).select2({
                            minimumInputLength: 1,
                            ajax: {
                                url: ciloe_mapper.product_selector.url,
                                dataType: 'json',
                                delay: 250,
                                data: function (terms) {
                                    return {
                                        term: terms,
                                        security: ciloe_mapper.product_selector.security,
                                    };
                                },
                                results: function (data) {
                                    var results = [];

                                    for (var id in data) {
                                        results.push({
                                            id: id,
                                            text: data[id].replace('&ndash;', ' - '),
                                        });
                                    }

                                    return {
                                        results: results,
                                    };
                                },
                            },
                            initSelection: function (element, callback) {
                                var id = $(element).val();

                                if (id !== '') {
                                    $.ajax(ciloe_mapper.product_selector.url + '&term=' + id + '&security=' + ciloe_mapper.product_selector.security, {
                                        dataType: 'json',
                                    }).done(function (data) {
                                        callback(data);
                                    });
                                }
                            },
                        });
                    }
                });

                // Setup icon selector.
                this.$el.on('click', '.icon-selected', function () {
                    $(this).next().toggle();
                }).on('click', '.icon-wrap .close', function () {
                    $(this).parent().parent().hide();
                }).on('click', '.ciloe-icon-list a', function (event) {
                    event.preventDefault();

                    // Update selected icon.
                    var selected_icon = $(this).closest('.icon-wrap').prev().children();

                    selected_icon.attr('class', selected_icon.attr('class').replace(/fa-.+/, $(this).attr('data-value')));

                    // Updated field value.
                    $(this).closest('.icon-selector').prev().val($(this).attr('data-value')).trigger('change');
                });

                return this;
            },

            // Display the settings form.
            edit: function (event) {
                if (!$(event.target).parent().hasClass('csre-pin') || this.just_dragged) {
                    return;
                }

                if (this.$el.hasClass('editing')) {
                    this.close(event);
                } else {
                    if (!this.$el.data('ciloe_mapper_pin_settings_initialized')) {
                        var self = this;

                        this.$el.on('change', 'input[type="radio"], select', function () {
                            if ($(this).attr('data-option')) {
                                var option = $(this).attr('data-option').match(/([^\[]+)(\[([^\[]+)\])*/),
                                    value = $(this).val();

                                if (option[3]) {
                                    self.$el.find('[data-' + option[3] + ']').each(function () {
                                        if ($(this).attr('data-' + option[3]).indexOf(value) > -1) {
                                            $(this).removeClass('hidden');
                                        } else {
                                            $(this).addClass('hidden');
                                        }
                                    });
                                }
                            }
                        }).find('input[type="radio"]:checked, select').trigger('change');

                        this.$el.data('ciloe_mapper_pin_settings_initialized', true);
                    }

                    // Disable draggable on the pin.
                    this.$el.draggable('option', 'disabled', true);

                    // Show edit form.
                    this.$el.addClass('editing');

                    // Make sure the edit form does not go off-screen.
                    var form = this.$el.children('.pin-setting');

                    if ('auto' == form.css('top')) {
                        var offset_top = this.$el.height();

                        if (form.offset().top + form.height() > $(window).height()) {
                            offset_top += $(window).height() - ( form.offset().top + form.height() );
                            offset_top -= ( parseInt(form.css('border-top-width')) + parseInt(form.css('border-bottom-width')) );
                        }

                        form.css('top', offset_top + 'px');
                    }

                    if ('auto' == form.css('left')) {
                        var offset_left = 0;

                        if (form.offset().left + form.width() > $(window).width()) {
                            offset_left += $(window).width() - ( form.offset().left + form.width() );
                            offset_left -= ( parseInt(form.css('border-left-width')) + parseInt(form.css('border-right-width')) );
                        }

                        form.css('left', offset_left + 'px');
                    }

                    // Init draggable on the edit form.
                    form.draggable();
                }
            },

            // Close the settings form, saving changes to the pin.
            close: function (event) {
                // Destroy draggable on the edit form.
                this.$el.children('.pin-setting').draggable('destroy');

                // Hide edit form.
                this.$el.removeClass('editing');

                // Update tooltip.
                var pin_type = this.$el.find('input[data-option*="pin-type"]:checked').val();

                if (pin_type == 'woocommerce') {
                    this.$el.find('.tooltip').addClass('hidden');
                } else {
                    var title = this.$el.find('input[data-option*="popup-title"]').val();

                    this.$el.find('.tooltip').text(title ? title : ciloe_mapper.text.please_input_a_title).removeClass('hidden');
                }

                // Enable draggable on the pin.
                this.$el.draggable('option', 'disabled', false);
            },

            // Remove the item, destroy the model.
            remove: function (event) {
                event.preventDefault();

                if (confirm(ciloe_mapper.text.confirm_removing_pin)) {
                    this.$el.remove();
                    this.model.destroy();

                    // State that data have changed.
                    $('#post #publish').attr('data-changed', 'yes');
                }
            },

            // Clone the item.
            clone: function (event) {
                event.preventDefault();

                // Prevent cloning continously.
                if (!this.just_cloned) {
                    // Prepare settings for new pin.
                    var settings = {};

                    this.$el.children('.pin-setting').find('input, select, textarea').each(function (i, e) {
                        if ($(e).attr('data-option')) {
                            var option = $(e).attr('data-option').match(/([^\[]+)(\[([^\[]+)\])*/);

                            if (option[3] !== undefined) {
                                if (e.nodeName == 'INPUT') {
                                    if (e.type == 'checkbox' || e.type == 'radio') {
                                        if (e.checked) {
                                            settings[option[3]] = $(e).val();
                                        }
                                    } else {
                                        settings[option[3]] = $(e).val();
                                    }
                                } else {
                                    settings[option[3]] = $(e).val();
                                }
                            }
                        }
                    })

                    settings.id = '';

                    // Add new pin.
                    Pins.add([{
                        top: ( parseInt(this.model.get('top')) + 1 ) + '%',
                        left: ( parseInt(this.model.get('left')) + 1 ) + '%',
                        settings: settings,
                    }]);

                    // State that cloning just occurred.
                    var self = this;

                    self.just_cloned = true;

                    setTimeout(function () {
                        self.just_cloned = false;
                    }, 200);
                }
            },
        });

        // Define Backbone view for pin list.
        var PinListView = Backbone.View.extend({
            // Instead of generating a new element, bind to the existing
            // skeleton of the pin list view already present in the HTML.
            el: '.ciloe-mapper-bot > .edit-image > .image-wrap',

            // At initialization we bind to the relevant events on the Pins
            // collection, when items are added or changed. Kick things off by
            // loading any preexisting pins that might be defined before.
            initialize: function () {
                this.listenTo(Pins, 'add', this.addOne);

                // Setup event for creating new pins.
                $(document).on('click', '.ciloe-mapper-bot > .edit-image > .image-wrap > img', this.create);

                // Fetch any preexisting pins that might be defined before.
                Pins.fetch();
            },

            // Add a single pin item to the list by creating a view for it, and
            // appending its element to the wrapper.
            addOne: function (pin) {
                var view = new PinView({
                    model: pin
                }), el = view.render().el;

                this.$el.append(el);
            },

            // Create new pin item.
            create: function (event) {
                if (!$('.csre-pin.editing').length) {
                    Pins.add([{
                        top: ( event.clientY - $(event.target).offset().top ) + $(window).scrollTop() - 12,
                        left: ( event.clientX - $(event.target).offset().left ) + $(window).scrollLeft() - 12,
                    }]);

                    // State that data have changed.
                    $('#post #publish').attr('data-changed', 'yes');
                }
            },
        });

        // Register event to initialize application.
        $(document).on('init_ciloe_mapper', function (event) {
            if (!$(document).data('ciloe_mapper_settings_initialized')) {
                // Init general settings.
                $('#general-settings > a').click(function () {
                    // Show settings form.
                    $(this).parent().toggleClass('editing');

                    // Make sure the edit form does not go off-screen.
                    var form = $(this).next();

                    if ('auto' == form.css('top') || 0 === parseInt(form.css('top'))) {
                        var offset_top = $(this).parent().height();

                        if (form.offset().top + form.height() > $(window).height()) {
                            offset_top += $(window).height() - ( form.offset().top + form.height() );
                            offset_top -= ( parseInt(form.css('border-top-width')) + parseInt(form.css('border-bottom-width')) );
                        }

                        form.css('top', offset_top + 'px');
                    }

                    if ('auto' == form.css('left') || 0 === parseInt(form.css('left'))) {
                        var offset_left = 0;
                        if (form.offset().left + form.width() > $(window).width()) {
                            offset_left += $(window).width() - ( form.offset().left + form.width() );
                            offset_left -= ( parseInt(form.css('border-left-width')) + parseInt(form.css('border-right-width')) );
                        }

                        form.css('left', offset_left + 'px');
                    }
                });

                // Init draggable on the general settings form.
                $('#general-settings > div').draggable().css('position', 'absolute').on('click', '.close-box', function () {
                    $('#general-settings').removeClass('editing');
                });

                // Init all input fields in the general settings form.
                $('#general-settings').find('input, select, textarea').each(function () {
                    if (window.ciloe_mapper_settings[$(this).attr('name')]) {
                        $(this).val(window.ciloe_mapper_settings[$(this).attr('name')]);

                        if ($(this).attr('type') == 'hidden' && $(this).next().attr('type') == 'checkbox') {
                            if (parseInt(window.ciloe_mapper_settings[$(this).attr('name')])) {
                                $(this).next().attr('checked', 'checked');
                            } else {
                                $(this).next().removeAttr('checked');
                            }
                        }
                    }

                    $(this).attr('name', 'ciloe_mapper_settings[' + $(this).attr('name') + ']');

                    // Init color picker if needed.
                    if ($(this).hasClass('color-picker')) {
                        $(this).cs_wpColorPicker();

                        setTimeout($.proxy(function () {
                            $(this).parent().parent().click(function () {
                                // Hide all icon selector popup if not being
                                // focused.
                                $('.ciloe-mapper .icon-selector').each(function () {
                                    if ($(this).children('.icon-wrap').css('display') != 'none' && !$.contains(this, event.target)) {
                                        $(this).children('.icon-wrap').hide();
                                    }
                                });
                            });
                        }, this), 500);
                    }
                });

                // Init all fields that toggle the visibility of other fields.
                $('#general-settings').on('change', 'input[type="radio"], select', function () {
                    var option = $(this).attr('name').match(/ciloe_mapper_settings\[([^\]]+)\]/), value = $(this).val();

                    if (option[1]) {
                        $('#general-settings').find('[data-' + option[1] + ']').each(function () {
                            if ($(this).attr('data-' + option[1]).indexOf(value) > -1) {
                                $(this).removeClass('hidden');
                            } else {
                                $(this).addClass('hidden');
                            }
                        });
                    }
                }).find('input[type="radio"]:checked, select').trigger('change');

                // Track click to hide popup / modal.
                $(document).click(function (event) {
                    // Check if there is any media modal visible.
                    if ($(event.target).closest('.media-modal').length) {
                        return;
                    }

                    // Hide all color picker popup if not being focused.
                    $('.ciloe-mapper .wp-picker-holder .iris-picker').each(function () {
                        if ($(this).css('display') != 'none' && !$.contains(this, event.target)) {
                            $(this).parent().children().hide();
                        }
                    });

                    // Hide all icon selector popup if not being focused.
                    $('.ciloe-mapper .icon-selector').each(function () {
                        if ($(this).children('.icon-wrap').css('display') != 'none' && !$.contains(this, event.target)) {
                            $(this).children('.icon-wrap').hide();
                        }
                    });

                    // Hide all settings popup if not being focused.
                    $('#general-settings.editing, .csre-pin.editing').each(function () {
                        if (!$.contains(this, event.target)) {
                            $(this).find('.close-box').trigger('click');
                        }
                    });
                });

                // Track data changes.
                var data_fields = '.ciloe-mapper input, .ciloe-mapper select, .ciloe-mapper textarea';

                $(document).on('change', data_fields, function () {
                    if ($(this).attr('name') && $(this).attr('name').indexOf('ciloe_mapper_') > -1) {
                        $('#post #publish').attr('data-changed', 'yes');
                    }
                });

                // Clear changed flag after clicking button to save changes.
                $('#post #publish').click(function () {
                    $(this).removeAttr('data-changed');
                    // Remove leaving page action confirmation.
                    $(window).off('beforeunload').unbind('beforeunload');
                });

                // Confirm leaving page action if changes not saved.
                $(window).on('beforeunload', function () {
                    var submit_btn = $('#post #publish');

                    if (submit_btn.length && submit_btn.attr('data-changed') == 'yes') {
                        return ciloe_mapper.text.ask_for_saving_changes;
                    }
                });

                $(document).data('ciloe_mapper_settings_initialized', true);
            }

            if (!window.ciloe_mapper_pins_app && $('.ciloe-mapper-bot > .edit-image > .image-wrap').length) {
                // Init pin list view.
                window.ciloe_mapper_pins_app = new PinListView;
            }
        });
    };
    $(document).ready(function () {
        initTab();
        imageSelector();
        setupApplication();
    });
});
