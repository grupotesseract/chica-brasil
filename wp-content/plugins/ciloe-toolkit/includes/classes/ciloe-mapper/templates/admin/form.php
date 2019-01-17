<?php
    global $post;

    // Get current image.
    $attachment_id = get_post_meta($post->ID, 'ciloe_mapper_image', true);

    if ($attachment_id) {
        // Get image source.
        $image_src = wp_get_attachment_url($attachment_id);
    }

    // Get general settings.
    $settings = get_post_meta($post->ID, 'ciloe_mapper_settings', true);

    // Get all pins.
    $pins = get_post_meta($post->ID, 'ciloe_mapper_pins', true);
?>
<script type="text/html" id="ciloe_mapper_tmpl">
    <div class="ciloe-mapper-wrap">
        <div class="ciloe-mapper-top">
            <div class="title">
                <input type="text" name="post_title" class="input-text" placeholder="<?php
                    _e('Enter Title Here', 'ciloe-toolkit');
                ?>" value="<?php
                    esc_attr_e($post->post_title);
                ?>">
            </div>
        </div><!-- .ciloe-mapper-top -->
        <div class="ciloe-mapper bdgr bgw">
            <div class="ciloe-mapper-mid fc aic jcsb pd__20 bggr">
                <div class="global-setting fc aic">
                    <div id="general-settings">
                        <a href="javascript:void(0)" class="btn br__3 dib">
                            <i class="wricon-cog mr__5"></i>
                            <?php _e('General Settings', 'ciloe-toolkit'); ?>
                        </a>

                        <div class="setting-box general-setting br__3 bgw bdgr">
                            <h4 class="mg__0 pr">
                                <?php _e('General Settings', 'ciloe-toolkit'); ?>
                                <i class="close-box pa">x</i>
                            </h4>

                            <ul class="nav mg__0 fc">
                                <li data-nav="style"
                                    class="mg__0 active"><?php _e('Style Settings', 'ciloe-toolkit'); ?></li>
                                <!-- <li data-nav="image-effect" class="mg__0"><?php _e('Image Effect', 'ciloe-toolkit'); ?></li> -->
                            </ul>

                            <div class="tab-content">
                                <div data-tab="style" class="tab-item">
                                    <label class="db mb__10"><?php _e('Popup size', 'ciloe-toolkit'); ?></label>
                                    <div class="row mb__25">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Width', 'ciloe-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-width"
                                                       class="input-text input-large"
                                                       value="305">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Height', 'ciloe-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-height" min="50"
                                                       class="input-text input-large" value="314">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Tooltip style', 'ciloe-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="tooltip-style" class="slt select-large">
                                                    <option value="light"><?php _e('Light', 'ciloe-toolkit'); ?></option>
                                                    <option value="dark"><?php _e('Dark', 'ciloe-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Popup box shadow', 'ciloe-toolkit'); ?></label>
                                            <div class="picker-styled pr">
                                                <input type="text" name="popup-box-shadow" class="color-picker"
                                                       data-default-color="#f0f0f0" value="#f0f0f0">
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Popup show fffect', 'ciloe-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="popup-show-effect" class="slt select-large">
                                                    <option value="fade"><?php _e('Fade In', 'ciloe-toolkit'); ?></option>
                                                    <option value="slide-left"><?php _e('Slide From Left', 'ciloe-toolkit'); ?></option>
                                                    <option value="slide-right"><?php _e('Slide From Right', 'ciloe-toolkit'); ?></option>
                                                    <option value="slide-top"><?php _e('Slide From Top', 'ciloe-toolkit'); ?></option>
                                                    <option value="slide-bottom"><?php _e('Slide From Bottom', 'ciloe-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Popup border radius', 'ciloe-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-border-radius"
                                                       class="input-text input-large" value="3">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Popup border width', 'ciloe-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-border-width"
                                                       class="input-text input-large" value="0">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Popup border color', 'ciloe-toolkit'); ?></label>
                                            <div class="picker-styled pr setting-border-color-picker">
                                                <input type="text" name="popup-border-color" class="color-picker"
                                                       data-default-color="#dfdfdf" value="#dfdfdf">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php _e('Image effect', 'ciloe-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="image-effect" class="slt select-large">
                                                    <option value="none"><?php _e('None', 'ciloe-toolkit'); ?></option>
                                                    <option value="blur"><?php _e('Blur', 'ciloe-toolkit'); ?></option>
                                                    <option value="gray"><?php _e('Gray', 'ciloe-toolkit'); ?></option>
                                                    <option value="mask"><?php _e('Mask Overlay', 'ciloe-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="cm-4" data-image-effect="mask">
                                            <label class="db mb__10"><?php _e('Mask color', 'ciloe-toolkit'); ?></label>
                                            <div class="picker-styled pr">
                                                <input type="text" name="mask-color" class="color-picker"
                                                       data-default-color="rgba(0, 0, 0, 0.5)"
                                                       value="rgba(0, 0, 0, 0.5)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-tab="image-effect" class="tab-item hidden"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ciloe-copy-shortcode">
                        <span class="shortcode-name"><?php echo esc_html__('Copy Shortcode','ciloe')?></span>
                        <input id="shortcode-syntax" class="shortcode-syntax" value='[ciloe_mapper id="<?php echo absint($post->ID); ?>"]'>
                    </div>
                </div>
            </div><!-- .ciloe-mapper-mid -->

            <div class="ciloe-mapper-bot pr aic fc jcc <?php if ($attachment_id) echo 'pr'; ?>">
                <input type="hidden" id="ciloe_mapper_image" name="ciloe_mapper_image"
                       value="<?php echo absint($attachment_id); ?>">

                <?php if ($attachment_id) : ?>
                    <div class="edit-image pr">
                        <a id="change-image" href="#" class="btn-change-image pa db br__3">
                            <i class="wricon-camera mr__5"></i><?php _e('Change Image', 'ciloe-toolkit'); ?>
                        </a>

                        <div class="image-wrap">
                            <img src="<?php echo esc_url($image_src); ?>">
                        </div>
                    </div>
                <?php else : ?>
                    <div class="add-image">
                        <a href="#" class="btn-add-image db tc"><i class="fa fa-upload" aria-hidden="true"></i></a>
                        <span class="empty-mapper"><?php _e('Add your image mapping', 'ciloe-toolkit'); ?></span>
                    </div>
                <?php endif; ?>
            </div><!-- .ciloe-mapper-bot -->
        </div><!-- .ciloe-mapper -->
    </div>
</script>

<script type="text/html" id="ciloe_mapper_image_tmpl">
    <div class="edit-image pr">
        <a id="change-image" href="#" class="btn-change-image pa db br__3">
            <i class="fa fa-camera-retro mr__5" aria-hidden="true"></i><?php _e('Change Image', 'ciloe-toolkit'); ?>
        </a>

        <div class="image-wrap">
            <img src="%URL%">
        </div>
    </div>
</script>

<script type="text/html" id="ciloe_mapper_pin_tmpl">
    <i class="icon-pin fa fa-plus"></i>
    <div class="text__area hidden"></div>
    <a class="pin-action delete-pin" href="#"><i class="fa fa-close"></i></a>
    <a class="pin-action duplicate-pin" href="#"><i class="fa fa-files-o"></i></a>
    <div class="setting-box pin-setting br__3 bgw bdgr">
        <h4 class="mg__0 pr">
            <?php _e('Pin Settings', 'ciloe-toolkit'); ?>
            <i class="close-box pa">x</i>
        </h4>

        <input type="hidden" data-option="top" value="<%= top %>">
        <input type="hidden" data-option="left" value="<%= left %>">
        <input type="hidden" data-option="settings[id]" value="">

        <ul class="nav mg__0 fc">
            <li data-nav="general" class="mg__0 active"><?php _e('General', 'ciloe-toolkit'); ?></li>
            <li data-nav="icon-settings" class="mg__0"><?php _e('Icon Settings', 'ciloe-toolkit'); ?></li>
            <li data-nav="popup-settings" class="mg__0"><?php _e('Popup Settings', 'ciloe-toolkit'); ?></li>
        </ul>

        <div class="tab-content">
            <div data-tab="general" class="tab-item">
                <div class="radio-group fc mb__25">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="woocommerce" checked="checked">
                            <span></span>
                            <?php _e('WooCommerce', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="image">
                            <span></span>
                            <?php _e('Image', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="text">
                            <span></span>
                            <?php _e('Text', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="link">
                            <span></span>
                            <?php _e('Link', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                </div>

                <!-- WooCommerce Settings -->
                <div class="form-input mb__25" data-pin-type="woocommerce">
                    <label class="db mb__10"><?php _e('Select product', 'ciloe-toolkit'); ?></label>
                    <input type="text" data-option="settings[product]" class="input-text input-large product-selector"
                           value="">
                </div>
                <div class="checkbox-group fc mb__25" data-pin-type="woocommerce">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-thumbnail]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php _e('Show thumbnail', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-description]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php _e('Show description', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-rate]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php _e('Show rate', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                </div>
                <!-- End WooCommerce Settings -->

                <!-- Image / Text / Link Settings -->
                <div class="form-input mb__25" data-pin-type="image|text|link">
                    <label class="db mb__10"><?php _e('Popup title', 'ciloe-toolkit'); ?></label>
                    <input type="text" data-option="settings[popup-title]" class="input-text input-large" value=""
                           placeholder="<?php
                               _e('Input a title for the popup here...', 'ciloe-toolkit');
                           ?>">
                </div>

                <!-- Image Settings -->
                <div class="input-group mb__25" data-pin-type="image">
                    <label class="db mb__10"><?php _e('Select image', 'ciloe-toolkit'); ?></label>
                    <div class="pr">
                        <input type="text" class="input-image input-large" data-option="settings[image]" value="">
                        <a href="#" class="pa image-selector"><i class="fa fa-upload" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="row mb__25" data-pin-type="image|link">
                    <div class="cm-7">
                        <div class="form-input">
                            <label class="db mb__10"><?php _e('Link To', 'ciloe-toolkit'); ?></label>
                            <input type="text" data-option="settings[image-link-to]" class="input-text input-large"
                                   value="">
                        </div>
                    </div>
                    <div class="cm-5">
                        <label class="db mb__10"><?php _e('Target', 'ciloe-toolkit'); ?></label>
                        <div class="select-styled pr">
                            <select data-option="settings[image-link-target]" class="slt select-large">
                                <option value="_self"><?php _e('Default', 'ciloe-toolkit'); ?></option>
                                <option value="_blank"><?php _e('New Tab', 'ciloe-toolkit'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End Image Settings -->

                <!-- Text Settings -->
                <div class="form-input" data-pin-type="text">
                    <label class="db mb__10"><?php _e('Text Content', 'ciloe-toolkit'); ?></label>
                    <textarea data-option="settings[text]" rows="6" placeholder="<?php
                        _e('Input some content for the popup here...', 'ciloe-toolkit');
                    ?>"></textarea>
                </div>
                <!-- End Text Settings -->
            </div>

            <div data-tab="icon-settings" class="tab-item hidden">
                <div class="radio-group fc mb__25">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[icon-type]" value="icon-area">
                            <span></span>
                            <?php _e('Area Text', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[icon-type]" value="icon-image" checked="checked">
                            <span></span>
                            <?php _e('Image', 'ciloe-toolkit'); ?>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="input-group mb__25" data-icon-type="icon-image">
                    <label class="db mb__10"><?php _e('Select image', 'ciloe-toolkit'); ?></label>
                    <div class="pr">
                        <input type="text" class="input-image input-large" data-option="settings[image-template]"
                               value="">
                        <a href="#" class="pa image-selector"><i class="fa fa-upload" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div data-icon-type="icon-area">
                    <div class="row mb__20">
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Text', 'ciloe-toolkit' ); ?></label>
                            <input type="text" data-option="settings[area-text]" class="text-custom input-text input-large" value="">
                        </div>
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Font Size', 'ciloe-toolkit' ); ?></label>
                            <div class="input input--number pr">
                                <input type="number" data-option="settings[area-text-size]" class="input-text input-large" value="13">
                            </div>
                        </div>
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Text Color', 'ciloe-toolkit' ); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-text-color]" class="color-picker" data-default-color="#2091c9" value="#2091c9">
                            </div>
                        </div>
                    </div>
                    <div class="row mb--20">
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Width', 'ciloe-toolkit' ); ?></label>
                            <div class="input input--number pr">
                                <input type="number" data-option="settings[area-width]" class="input-text input-large" value="32">
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Height', 'ciloe-toolkit' ); ?></label>
                            <div class="input input--number pr">
                                <input type="number" data-option="settings[area-height]" class="input-text input-large" value="32">
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Border Width', 'ciloe-toolkit' ); ?></label>
                            <div class="input input--number pr">
                                <input type="number" data-option="settings[area-border-width]" class="input-text input-large" value="0">
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Border Radius', 'ciloe-toolkit' ); ?></label>
                            <div class="input input--number pr">
                                <input type="number" data-option="settings[area-border-radius]" class="input-text input-large" value="50">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cm-6 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Background', 'ciloe-toolkit' ); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-bg-color]" class="w__150 color-picker" data-default-color="rgba(101, 175, 250, .5)" value="rgba(101, 175, 250, .5)">
                            </div>
                        </div>
                        <div class="cm-6 mb__25">
                            <label class="db mb__10"><?php esc_html_e( 'Border Color', 'ciloe-toolkit' ); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-border-color]" class="w__150 color-picker" data-default-color="rgba(0, 0, 0, .5)" value="rgba(0, 0, 0, .5)">
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div data-tab="popup-settings" class="tab-item hidden">
                <label class="db mb__10"><?php _e('Custom popup size', 'ciloe-toolkit'); ?></label>
                <div class="row">
                    <div class="cm-4">
                        <label class="db mb__10"><?php _e('Width', 'ciloe-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[popup-width]" class="input-text input-large"
                                   value="">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                    <div class="cm-4">
                        <label class="db mb__10"><?php _e('Height', 'ciloe-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[popup-height]" class="input-text input-large"
                                   value="">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                    <div class="cm-4">
                        <label class="db mb__10"><?php _e('Position', 'ciloe-toolkit'); ?></label>
                        <div class="select-styled pr">
                            <select data-option="settings[popup-position]" class="slt select-large">
                                <option value="right"><?php _e('Right', 'ciloe-toolkit'); ?></option>
                                <option value="left"><?php _e('Left', 'ciloe-toolkit'); ?></option>
                                <option value="top"><?php _e('Top', 'ciloe-toolkit'); ?></option>
                                <option value="bottom"><?php _e('Bottom', 'ciloe-toolkit'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<?php $fonts = array(
    array( 'fa-glass' => 'Fa Glass' ),
    array( 'fa-music' => 'Fa Music' ),
    array( 'fa-search' => 'Fa Search' ),
    array( 'fa-envelope-o' => 'Fa Envelope O' ),
    array( 'fa-heart' => 'Fa Heart' ),
    array( 'fa-star' => 'Fa Star' ),
    array( 'fa-star-o' => 'Fa Star O' ),
    array( 'fa-user' => 'Fa User' ),
    array( 'fa-film' => 'Fa Film' ),
    array( 'fa-th-large' => 'Fa Th Large' ),
    array( 'fa-th' => 'Fa Th' ),
    array( 'fa-th-list' => 'Fa Th List' ),
    array( 'fa-check' => 'Fa Check' ),
    array( 'fa-remove' => 'Fa Remove' ),
    array( 'fa-close' => 'Fa Close' ),
    array( 'fa-times' => 'Fa Times' ),
    array( 'fa-search-plus' => 'Fa Search Plus' ),
    array( 'fa-search-minus' => 'Fa Search Minus' ),
    array( 'fa-power-off' => 'Fa Power Off' ),
    array( 'fa-signal' => 'Fa Signal' ),
    array( 'fa-gear' => 'Fa Gear' ),
    array( 'fa-cog' => 'Fa Cog' ),
    array( 'fa-trash-o' => 'Fa Trash O' ),
    array( 'fa-home' => 'Fa Home' ),
    array( 'fa-file-o' => 'Fa File O' ),
    array( 'fa-clock-o' => 'Fa Clock O' ),
    array( 'fa-road' => 'Fa Road' ),
    array( 'fa-download' => 'Fa Download' ),
    array( 'fa-arrow-circle-o-down' => 'Fa Arrow Circle O Down' ),
    array( 'fa-arrow-circle-o-up' => 'Fa Arrow Circle O Up' ),
    array( 'fa-inbox' => 'Fa Inbox' ),
    array( 'fa-play-circle-o' => 'Fa Play Circle O' ),
    array( 'fa-rotate-right' => 'Fa Rotate Right' ),
    array( 'fa-repeat' => 'Fa Repeat' ),
    array( 'fa-refresh' => 'Fa Refresh' ),
    array( 'fa-list-alt' => 'Fa List Alt' ),
    array( 'fa-lock' => 'Fa Lock' ),
    array( 'fa-flag' => 'Fa Flag' ),
    array( 'fa-headphones' => 'Fa Headphones' ),
    array( 'fa-volume-off' => 'Fa Volume Off' ),
    array( 'fa-volume-down' => 'Fa Volume Down' ),
    array( 'fa-volume-up' => 'Fa Volume Up' ),
    array( 'fa-qrcode' => 'Fa Qrcode' ),
    array( 'fa-barcode' => 'Fa Barcode' ),
    array( 'fa-tag' => 'Fa Tag' ),
    array( 'fa-tags' => 'Fa Tags' ),
    array( 'fa-book' => 'Fa Book' ),
    array( 'fa-bookmark' => 'Fa Bookmark' ),
    array( 'fa-print' => 'Fa Print' ),
    array( 'fa-camera' => 'Fa Camera' ),
    array( 'fa-font' => 'Fa Font' ),
    array( 'fa-bold' => 'Fa Bold' ),
    array( 'fa-italic' => 'Fa Italic' ),
    array( 'fa-text-height' => 'Fa Text Height' ),
    array( 'fa-text-width' => 'Fa Text Width' ),
    array( 'fa-align-left' => 'Fa Align Left' ),
    array( 'fa-align-center' => 'Fa Align Center' ),
    array( 'fa-align-right' => 'Fa Align Right' ),
    array( 'fa-align-justify' => 'Fa Align Justify' ),
    array( 'fa-list' => 'Fa List' ),
    array( 'fa-dedent' => 'Fa Dedent' ),
    array( 'fa-outdent' => 'Fa Outdent' ),
    array( 'fa-indent' => 'Fa Indent' ),
    array( 'fa-video-camera' => 'Fa Video Camera' ),
    array( 'fa-photo' => 'Fa Photo' ),
    array( 'fa-image' => 'Fa Image' ),
    array( 'fa-picture-o' => 'Fa Picture O' ),
    array( 'fa-pencil' => 'Fa Pencil' ),
    array( 'fa-map-marker' => 'Fa Map Marker' ),
    array( 'fa-adjust' => 'Fa Adjust' ),
    array( 'fa-tint' => 'Fa Tint' ),
    array( 'fa-edit' => 'Fa Edit' ),
    array( 'fa-pencil-square-o' => 'Fa Pencil Square O' ),
    array( 'fa-share-square-o' => 'Fa Share Square O' ),
    array( 'fa-check-square-o' => 'Fa Check Square O' ),
    array( 'fa-arrows' => 'Fa Arrows' ),
    array( 'fa-step-backward' => 'Fa Step Backward' ),
    array( 'fa-fast-backward' => 'Fa Fast Backward' ),
    array( 'fa-backward' => 'Fa Backward' ),
    array( 'fa-play' => 'Fa Play' ),
    array( 'fa-pause' => 'Fa Pause' ),
    array( 'fa-stop' => 'Fa Stop' ),
    array( 'fa-forward' => 'Fa Forward' ),
    array( 'fa-fast-forward' => 'Fa Fast Forward' ),
    array( 'fa-step-forward' => 'Fa Step Forward' ),
    array( 'fa-eject' => 'Fa Eject' ),
    array( 'fa-chevron-left' => 'Fa Chevron Left' ),
    array( 'fa-chevron-right' => 'Fa Chevron Right' ),
    array( 'fa-plus-circle' => 'Fa Plus Circle' ),
    array( 'fa-minus-circle' => 'Fa Minus Circle' ),
    array( 'fa-times-circle' => 'Fa Times Circle' ),
    array( 'fa-check-circle' => 'Fa Check Circle' ),
    array( 'fa-question-circle' => 'Fa Question Circle' ),
    array( 'fa-info-circle' => 'Fa Info Circle' ),
    array( 'fa-crosshairs' => 'Fa Crosshairs' ),
    array( 'fa-times-circle-o' => 'Fa Times Circle O' ),
    array( 'fa-check-circle-o' => 'Fa Check Circle O' ),
    array( 'fa-ban' => 'Fa Ban' ),
    array( 'fa-arrow-left' => 'Fa Arrow Left' ),
    array( 'fa-arrow-right' => 'Fa Arrow Right' ),
    array( 'fa-arrow-up' => 'Fa Arrow Up' ),
    array( 'fa-arrow-down' => 'Fa Arrow Down' ),
    array( 'fa-mail-forward' => 'Fa Mail Forward' ),
    array( 'fa-share' => 'Fa Share' ),
    array( 'fa-expand' => 'Fa Expand' ),
    array( 'fa-compress' => 'Fa Compress' ),
    array( 'fa-plus' => 'Fa Plus' ),
    array( 'fa-minus' => 'Fa Minus' ),
    array( 'fa-asterisk' => 'Fa Asterisk' ),
    array( 'fa-exclamation-circle' => 'Fa Exclamation Circle' ),
    array( 'fa-gift' => 'Fa Gift' ),
    array( 'fa-leaf' => 'Fa Leaf' ),
    array( 'fa-fire' => 'Fa Fire' ),
    array( 'fa-eye' => 'Fa Eye' ),
    array( 'fa-eye-slash' => 'Fa Eye Slash' ),
    array( 'fa-warning' => 'Fa Warning' ),
    array( 'fa-exclamation-triangle' => 'Fa Exclamation Triangle' ),
    array( 'fa-plane' => 'Fa Plane' ),
    array( 'fa-calendar' => 'Fa Calendar' ),
    array( 'fa-random' => 'Fa Random' ),
    array( 'fa-comment' => 'Fa Comment' ),
    array( 'fa-magnet' => 'Fa Magnet' ),
    array( 'fa-chevron-up' => 'Fa Chevron Up' ),
    array( 'fa-chevron-down' => 'Fa Chevron Down' ),
    array( 'fa-retweet' => 'Fa Retweet' ),
    array( 'fa-shopping-cart' => 'Fa Shopping Cart' ),
    array( 'fa-folder' => 'Fa Folder' ),
    array( 'fa-folder-open' => 'Fa Folder Open' ),
    array( 'fa-arrows-v' => 'Fa Arrows V' ),
    array( 'fa-arrows-h' => 'Fa Arrows H' ),
    array( 'fa-bar-chart-o' => 'Fa Bar Chart O' ),
    array( 'fa-bar-chart' => 'Fa Bar Chart' ),
    array( 'fa-twitter-square' => 'Fa Twitter Square' ),
    array( 'fa-facebook-square' => 'Fa Facebook Square' ),
    array( 'fa-camera-retro' => 'Fa Camera Retro' ),
    array( 'fa-key' => 'Fa Key' ),
    array( 'fa-gears' => 'Fa Gears' ),
    array( 'fa-cogs' => 'Fa Cogs' ),
    array( 'fa-comments' => 'Fa Comments' ),
    array( 'fa-thumbs-o-up' => 'Fa Thumbs O Up' ),
    array( 'fa-thumbs-o-down' => 'Fa Thumbs O Down' ),
    array( 'fa-star-half' => 'Fa Star Half' ),
    array( 'fa-heart-o' => 'Fa Heart O' ),
    array( 'fa-sign-out' => 'Fa Sign Out' ),
    array( 'fa-linkedin-square' => 'Fa Linkedin Square' ),
    array( 'fa-thumb-tack' => 'Fa Thumb Tack' ),
    array( 'fa-external-link' => 'Fa External Link' ),
    array( 'fa-sign-in' => 'Fa Sign In' ),
    array( 'fa-trophy' => 'Fa Trophy' ),
    array( 'fa-github-square' => 'Fa Github Square' ),
    array( 'fa-upload' => 'Fa Upload' ),
    array( 'fa-lemon-o' => 'Fa Lemon O' ),
    array( 'fa-phone' => 'Fa Phone' ),
    array( 'fa-square-o' => 'Fa Square O' ),
    array( 'fa-bookmark-o' => 'Fa Bookmark O' ),
    array( 'fa-phone-square' => 'Fa Phone Square' ),
    array( 'fa-twitter' => 'Fa Twitter' ),
    array( 'fa-facebook-f' => 'Fa Facebook F' ),
    array( 'fa-facebook' => 'Fa Facebook' ),
    array( 'fa-github' => 'Fa Github' ),
    array( 'fa-unlock' => 'Fa Unlock' ),
    array( 'fa-credit-card' => 'Fa Credit Card' ),
    array( 'fa-feed' => 'Fa Feed' ),
    array( 'fa-rss' => 'Fa Rss' ),
    array( 'fa-hdd-o' => 'Fa Hdd O' ),
    array( 'fa-bullhorn' => 'Fa Bullhorn' ),
    array( 'fa-bell' => 'Fa Bell' ),
    array( 'fa-certificate' => 'Fa Certificate' ),
    array( 'fa-hand-o-right' => 'Fa Hand O Right' ),
    array( 'fa-hand-o-left' => 'Fa Hand O Left' ),
    array( 'fa-hand-o-up' => 'Fa Hand O Up' ),
    array( 'fa-hand-o-down' => 'Fa Hand O Down' ),
    array( 'fa-arrow-circle-left' => 'Fa Arrow Circle Left' ),
    array( 'fa-arrow-circle-right' => 'Fa Arrow Circle Right' ),
    array( 'fa-arrow-circle-up' => 'Fa Arrow Circle Up' ),
    array( 'fa-arrow-circle-down' => 'Fa Arrow Circle Down' ),
    array( 'fa-globe' => 'Fa Globe' ),
    array( 'fa-wrench' => 'Fa Wrench' ),
    array( 'fa-tasks' => 'Fa Tasks' ),
    array( 'fa-filter' => 'Fa Filter' ),
    array( 'fa-briefcase' => 'Fa Briefcase' ),
    array( 'fa-arrows-alt' => 'Fa Arrows Alt' ),
    array( 'fa-group' => 'Fa Group' ),
    array( 'fa-users' => 'Fa Users' ),
    array( 'fa-chain' => 'Fa Chain' ),
    array( 'fa-link' => 'Fa Link' ),
    array( 'fa-cloud' => 'Fa Cloud' ),
    array( 'fa-flask' => 'Fa Flask' ),
    array( 'fa-cut' => 'Fa Cut' ),
    array( 'fa-scissors' => 'Fa Scissors' ),
    array( 'fa-copy' => 'Fa Copy' ),
    array( 'fa-files-o' => 'Fa Files O' ),
    array( 'fa-paperclip' => 'Fa Paperclip' ),
    array( 'fa-save' => 'Fa Save' ),
    array( 'fa-floppy-o' => 'Fa Floppy O' ),
    array( 'fa-square' => 'Fa Square' ),
    array( 'fa-navicon' => 'Fa Navicon' ),
    array( 'fa-reorder' => 'Fa Reorder' ),
    array( 'fa-bars' => 'Fa Bars' ),
    array( 'fa-list-ul' => 'Fa List Ul' ),
    array( 'fa-list-ol' => 'Fa List Ol' ),
    array( 'fa-strikethrough' => 'Fa Strikethrough' ),
    array( 'fa-underline' => 'Fa Underline' ),
    array( 'fa-table' => 'Fa Table' ),
    array( 'fa-magic' => 'Fa Magic' ),
    array( 'fa-truck' => 'Fa Truck' ),
    array( 'fa-pinterest' => 'Fa Pinterest' ),
    array( 'fa-pinterest-square' => 'Fa Pinterest Square' ),
    array( 'fa-google-plus-square' => 'Fa Google Plus Square' ),
    array( 'fa-google-plus' => 'Fa Google Plus' ),
    array( 'fa-money' => 'Fa Money' ),
    array( 'fa-caret-down' => 'Fa Caret Down' ),
    array( 'fa-caret-up' => 'Fa Caret Up' ),
    array( 'fa-caret-left' => 'Fa Caret Left' ),
    array( 'fa-caret-right' => 'Fa Caret Right' ),
    array( 'fa-columns' => 'Fa Columns' ),
    array( 'fa-unsorted' => 'Fa Unsorted' ),
    array( 'fa-sort' => 'Fa Sort' ),
    array( 'fa-sort-down' => 'Fa Sort Down' ),
    array( 'fa-sort-desc' => 'Fa Sort Desc' ),
    array( 'fa-sort-up' => 'Fa Sort Up' ),
    array( 'fa-sort-asc' => 'Fa Sort Asc' ),
    array( 'fa-envelope' => 'Fa Envelope' ),
    array( 'fa-linkedin' => 'Fa Linkedin' ),
    array( 'fa-rotate-left' => 'Fa Rotate Left' ),
    array( 'fa-undo' => 'Fa Undo' ),
    array( 'fa-legal' => 'Fa Legal' ),
    array( 'fa-gavel' => 'Fa Gavel' ),
    array( 'fa-dashboard' => 'Fa Dashboard' ),
    array( 'fa-tachometer' => 'Fa Tachometer' ),
    array( 'fa-comment-o' => 'Fa Comment O' ),
    array( 'fa-comments-o' => 'Fa Comments O' ),
    array( 'fa-flash' => 'Fa Flash' ),
    array( 'fa-bolt' => 'Fa Bolt' ),
    array( 'fa-sitemap' => 'Fa Sitemap' ),
    array( 'fa-umbrella' => 'Fa Umbrella' ),
    array( 'fa-paste' => 'Fa Paste' ),
    array( 'fa-clipboard' => 'Fa Clipboard' ),
    array( 'fa-lightbulb-o' => 'Fa Lightbulb O' ),
    array( 'fa-exchange' => 'Fa Exchange' ),
    array( 'fa-cloud-download' => 'Fa Cloud Download' ),
    array( 'fa-cloud-upload' => 'Fa Cloud Upload' ),
    array( 'fa-user-md' => 'Fa User Md' ),
    array( 'fa-stethoscope' => 'Fa Stethoscope' ),
    array( 'fa-suitcase' => 'Fa Suitcase' ),
    array( 'fa-bell-o' => 'Fa Bell O' ),
    array( 'fa-coffee' => 'Fa Coffee' ),
    array( 'fa-cutlery' => 'Fa Cutlery' ),
    array( 'fa-file-text-o' => 'Fa File Text O' ),
    array( 'fa-building-o' => 'Fa Building O' ),
    array( 'fa-hospital-o' => 'Fa Hospital O' ),
    array( 'fa-ambulance' => 'Fa Ambulance' ),
    array( 'fa-medkit' => 'Fa Medkit' ),
    array( 'fa-fighter-jet' => 'Fa Fighter Jet' ),
    array( 'fa-beer' => 'Fa Beer' ),
    array( 'fa-h-square' => 'Fa H Square' ),
    array( 'fa-plus-square' => 'Fa Plus Square' ),
    array( 'fa-angle-double-left' => 'Fa Angle Double Left' ),
    array( 'fa-angle-double-right' => 'Fa Angle Double Right' ),
    array( 'fa-angle-double-up' => 'Fa Angle Double Up' ),
    array( 'fa-angle-double-down' => 'Fa Angle Double Down' ),
    array( 'fa-angle-left' => 'Fa Angle Left' ),
    array( 'fa-angle-right' => 'Fa Angle Right' ),
    array( 'fa-angle-up' => 'Fa Angle Up' ),
    array( 'fa-angle-down' => 'Fa Angle Down' ),
    array( 'fa-desktop' => 'Fa Desktop' ),
    array( 'fa-laptop' => 'Fa Laptop' ),
    array( 'fa-tablet' => 'Fa Tablet' ),
    array( 'fa-mobile-phone' => 'Fa Mobile Phone' ),
    array( 'fa-mobile' => 'Fa Mobile' ),
    array( 'fa-circle-o' => 'Fa Circle O' ),
    array( 'fa-quote-left' => 'Fa Quote Left' ),
    array( 'fa-quote-right' => 'Fa Quote Right' ),
    array( 'fa-spinner' => 'Fa Spinner' ),
    array( 'fa-circle' => 'Fa Circle' ),
    array( 'fa-mail-reply' => 'Fa Mail Reply' ),
    array( 'fa-reply' => 'Fa Reply' ),
    array( 'fa-github-alt' => 'Fa Github Alt' ),
    array( 'fa-folder-o' => 'Fa Folder O' ),
    array( 'fa-folder-open-o' => 'Fa Folder Open O' ),
    array( 'fa-smile-o' => 'Fa Smile O' ),
    array( 'fa-frown-o' => 'Fa Frown O' ),
    array( 'fa-meh-o' => 'Fa Meh O' ),
    array( 'fa-gamepad' => 'Fa Gamepad' ),
    array( 'fa-keyboard-o' => 'Fa Keyboard O' ),
    array( 'fa-flag-o' => 'Fa Flag O' ),
    array( 'fa-flag-checkered' => 'Fa Flag Checkered' ),
    array( 'fa-terminal' => 'Fa Terminal' ),
    array( 'fa-code' => 'Fa Code' ),
    array( 'fa-mail-reply-all' => 'Fa Mail Reply All' ),
    array( 'fa-reply-all' => 'Fa Reply All' ),
    array( 'fa-star-half-empty' => 'Fa Star Half Empty' ),
    array( 'fa-star-half-full' => 'Fa Star Half Full' ),
    array( 'fa-star-half-o' => 'Fa Star Half O' ),
    array( 'fa-location-arrow' => 'Fa Location Arrow' ),
    array( 'fa-crop' => 'Fa Crop' ),
    array( 'fa-code-fork' => 'Fa Code Fork' ),
    array( 'fa-unlink' => 'Fa Unlink' ),
    array( 'fa-chain-broken' => 'Fa Chain Broken' ),
    array( 'fa-question' => 'Fa Question' ),
    array( 'fa-info' => 'Fa Info' ),
    array( 'fa-exclamation' => 'Fa Exclamation' ),
    array( 'fa-superscript' => 'Fa Superscript' ),
    array( 'fa-subscript' => 'Fa Subscript' ),
    array( 'fa-eraser' => 'Fa Eraser' ),
    array( 'fa-puzzle-piece' => 'Fa Puzzle Piece' ),
    array( 'fa-microphone' => 'Fa Microphone' ),
    array( 'fa-microphone-slash' => 'Fa Microphone Slash' ),
    array( 'fa-shield' => 'Fa Shield' ),
    array( 'fa-calendar-o' => 'Fa Calendar O' ),
    array( 'fa-fire-extinguisher' => 'Fa Fire Extinguisher' ),
    array( 'fa-rocket' => 'Fa Rocket' ),
    array( 'fa-maxcdn' => 'Fa Maxcdn' ),
    array( 'fa-chevron-circle-left' => 'Fa Chevron Circle Left' ),
    array( 'fa-chevron-circle-right' => 'Fa Chevron Circle Right' ),
    array( 'fa-chevron-circle-up' => 'Fa Chevron Circle Up' ),
    array( 'fa-chevron-circle-down' => 'Fa Chevron Circle Down' ),
    array( 'fa-html5' => 'Fa Html5' ),
    array( 'fa-css3' => 'Fa Css3' ),
    array( 'fa-anchor' => 'Fa Anchor' ),
    array( 'fa-unlock-alt' => 'Fa Unlock Alt' ),
    array( 'fa-bullseye' => 'Fa Bullseye' ),
    array( 'fa-ellipsis-h' => 'Fa Ellipsis H' ),
    array( 'fa-ellipsis-v' => 'Fa Ellipsis V' ),
    array( 'fa-rss-square' => 'Fa Rss Square' ),
    array( 'fa-play-circle' => 'Fa Play Circle' ),
    array( 'fa-ticket' => 'Fa Ticket' ),
    array( 'fa-minus-square' => 'Fa Minus Square' ),
    array( 'fa-minus-square-o' => 'Fa Minus Square O' ),
    array( 'fa-level-up' => 'Fa Level Up' ),
    array( 'fa-level-down' => 'Fa Level Down' ),
    array( 'fa-check-square' => 'Fa Check Square' ),
    array( 'fa-pencil-square' => 'Fa Pencil Square' ),
    array( 'fa-external-link-square' => 'Fa External Link Square' ),
    array( 'fa-share-square' => 'Fa Share Square' ),
    array( 'fa-compass' => 'Fa Compass' ),
    array( 'fa-toggle-down' => 'Fa Toggle Down' ),
    array( 'fa-caret-square-o-down' => 'Fa Caret Square O Down' ),
    array( 'fa-toggle-up' => 'Fa Toggle Up' ),
    array( 'fa-caret-square-o-up' => 'Fa Caret Square O Up' ),
    array( 'fa-toggle-right' => 'Fa Toggle Right' ),
    array( 'fa-caret-square-o-right' => 'Fa Caret Square O Right' ),
    array( 'fa-euro' => 'Fa Euro' ),
    array( 'fa-eur' => 'Fa Eur' ),
    array( 'fa-gbp' => 'Fa Gbp' ),
    array( 'fa-dollar' => 'Fa Dollar' ),
    array( 'fa-usd' => 'Fa Usd' ),
    array( 'fa-rupee' => 'Fa Rupee' ),
    array( 'fa-inr' => 'Fa Inr' ),
    array( 'fa-cny' => 'Fa Cny' ),
    array( 'fa-rmb' => 'Fa Rmb' ),
    array( 'fa-yen' => 'Fa Yen' ),
    array( 'fa-jpy' => 'Fa Jpy' ),
    array( 'fa-ruble' => 'Fa Ruble' ),
    array( 'fa-rouble' => 'Fa Rouble' ),
    array( 'fa-rub' => 'Fa Rub' ),
    array( 'fa-won' => 'Fa Won' ),
    array( 'fa-krw' => 'Fa Krw' ),
    array( 'fa-bitcoin' => 'Fa Bitcoin' ),
    array( 'fa-btc' => 'Fa Btc' ),
    array( 'fa-file' => 'Fa File' ),
    array( 'fa-file-text' => 'Fa File Text' ),
    array( 'fa-sort-alpha-asc' => 'Fa Sort Alpha Asc' ),
    array( 'fa-sort-alpha-desc' => 'Fa Sort Alpha Desc' ),
    array( 'fa-sort-amount-asc' => 'Fa Sort Amount Asc' ),
    array( 'fa-sort-amount-desc' => 'Fa Sort Amount Desc' ),
    array( 'fa-sort-numeric-asc' => 'Fa Sort Numeric Asc' ),
    array( 'fa-sort-numeric-desc' => 'Fa Sort Numeric Desc' ),
    array( 'fa-thumbs-up' => 'Fa Thumbs Up' ),
    array( 'fa-thumbs-down' => 'Fa Thumbs Down' ),
    array( 'fa-youtube-square' => 'Fa Youtube Square' ),
    array( 'fa-youtube' => 'Fa Youtube' ),
    array( 'fa-xing' => 'Fa Xing' ),
    array( 'fa-xing-square' => 'Fa Xing Square' ),
    array( 'fa-youtube-play' => 'Fa Youtube Play' ),
    array( 'fa-dropbox' => 'Fa Dropbox' ),
    array( 'fa-stack-overflow' => 'Fa Stack Overflow' ),
    array( 'fa-instagram' => 'Fa Instagram' ),
    array( 'fa-flickr' => 'Fa Flickr' ),
    array( 'fa-adn' => 'Fa Adn' ),
    array( 'fa-bitbucket' => 'Fa Bitbucket' ),
    array( 'fa-bitbucket-square' => 'Fa Bitbucket Square' ),
    array( 'fa-tumblr' => 'Fa Tumblr' ),
    array( 'fa-tumblr-square' => 'Fa Tumblr Square' ),
    array( 'fa-long-arrow-down' => 'Fa Long Arrow Down' ),
    array( 'fa-long-arrow-up' => 'Fa Long Arrow Up' ),
    array( 'fa-long-arrow-left' => 'Fa Long Arrow Left' ),
    array( 'fa-long-arrow-right' => 'Fa Long Arrow Right' ),
    array( 'fa-apple' => 'Fa Apple' ),
    array( 'fa-windows' => 'Fa Windows' ),
    array( 'fa-android' => 'Fa Android' ),
    array( 'fa-linux' => 'Fa Linux' ),
    array( 'fa-dribbble' => 'Fa Dribbble' ),
    array( 'fa-skype' => 'Fa Skype' ),
    array( 'fa-foursquare' => 'Fa Foursquare' ),
    array( 'fa-trello' => 'Fa Trello' ),
    array( 'fa-female' => 'Fa Female' ),
    array( 'fa-male' => 'Fa Male' ),
    array( 'fa-gittip' => 'Fa Gittip' ),
    array( 'fa-gratipay' => 'Fa Gratipay' ),
    array( 'fa-sun-o' => 'Fa Sun O' ),
    array( 'fa-moon-o' => 'Fa Moon O' ),
    array( 'fa-archive' => 'Fa Archive' ),
    array( 'fa-bug' => 'Fa Bug' ),
    array( 'fa-vk' => 'Fa Vk' ),
    array( 'fa-weibo' => 'Fa Weibo' ),
    array( 'fa-renren' => 'Fa Renren' ),
    array( 'fa-pagelines' => 'Fa Pagelines' ),
    array( 'fa-stack-exchange' => 'Fa Stack Exchange' ),
    array( 'fa-arrow-circle-o-right' => 'Fa Arrow Circle O Right' ),
    array( 'fa-arrow-circle-o-left' => 'Fa Arrow Circle O Left' ),
    array( 'fa-toggle-left' => 'Fa Toggle Left' ),
    array( 'fa-caret-square-o-left' => 'Fa Caret Square O Left' ),
    array( 'fa-dot-circle-o' => 'Fa Dot Circle O' ),
    array( 'fa-wheelchair' => 'Fa Wheelchair' ),
    array( 'fa-vimeo-square' => 'Fa Vimeo Square' ),
    array( 'fa-turkish-lira' => 'Fa Turkish Lira' ),
    array( 'fa-try' => 'Fa Try' ),
    array( 'fa-plus-square-o' => 'Fa Plus Square O' ),
    array( 'fa-space-shuttle' => 'Fa Space Shuttle' ),
    array( 'fa-slack' => 'Fa Slack' ),
    array( 'fa-envelope-square' => 'Fa Envelope Square' ),
    array( 'fa-wordpress' => 'Fa Wordpress' ),
    array( 'fa-openid' => 'Fa Openid' ),
    array( 'fa-institution' => 'Fa Institution' ),
    array( 'fa-bank' => 'Fa Bank' ),
    array( 'fa-university' => 'Fa University' ),
    array( 'fa-mortar-board' => 'Fa Mortar Board' ),
    array( 'fa-graduation-cap' => 'Fa Graduation Cap' ),
    array( 'fa-yahoo' => 'Fa Yahoo' ),
    array( 'fa-google' => 'Fa Google' ),
    array( 'fa-reddit' => 'Fa Reddit' ),
    array( 'fa-reddit-square' => 'Fa Reddit Square' ),
    array( 'fa-stumbleupon-circle' => 'Fa Stumbleupon Circle' ),
    array( 'fa-stumbleupon' => 'Fa Stumbleupon' ),
    array( 'fa-delicious' => 'Fa Delicious' ),
    array( 'fa-digg' => 'Fa Digg' ),
    array( 'fa-pied-piper-pp' => 'Fa Pied Piper Pp' ),
    array( 'fa-pied-piper-alt' => 'Fa Pied Piper Alt' ),
    array( 'fa-drupal' => 'Fa Drupal' ),
    array( 'fa-joomla' => 'Fa Joomla' ),
    array( 'fa-language' => 'Fa Language' ),
    array( 'fa-fax' => 'Fa Fax' ),
    array( 'fa-building' => 'Fa Building' ),
    array( 'fa-child' => 'Fa Child' ),
    array( 'fa-paw' => 'Fa Paw' ),
    array( 'fa-spoon' => 'Fa Spoon' ),
    array( 'fa-cube' => 'Fa Cube' ),
    array( 'fa-cubes' => 'Fa Cubes' ),
    array( 'fa-behance' => 'Fa Behance' ),
    array( 'fa-behance-square' => 'Fa Behance Square' ),
    array( 'fa-steam' => 'Fa Steam' ),
    array( 'fa-steam-square' => 'Fa Steam Square' ),
    array( 'fa-recycle' => 'Fa Recycle' ),
    array( 'fa-automobile' => 'Fa Automobile' ),
    array( 'fa-car' => 'Fa Car' ),
    array( 'fa-cab' => 'Fa Cab' ),
    array( 'fa-taxi' => 'Fa Taxi' ),
    array( 'fa-tree' => 'Fa Tree' ),
    array( 'fa-spotify' => 'Fa Spotify' ),
    array( 'fa-deviantart' => 'Fa Deviantart' ),
    array( 'fa-soundcloud' => 'Fa Soundcloud' ),
    array( 'fa-database' => 'Fa Database' ),
    array( 'fa-file-pdf-o' => 'Fa File Pdf O' ),
    array( 'fa-file-word-o' => 'Fa File Word O' ),
    array( 'fa-file-excel-o' => 'Fa File Excel O' ),
    array( 'fa-file-powerpoint-o' => 'Fa File Powerpoint O' ),
    array( 'fa-file-photo-o' => 'Fa File Photo O' ),
    array( 'fa-file-picture-o' => 'Fa File Picture O' ),
    array( 'fa-file-image-o' => 'Fa File Image O' ),
    array( 'fa-file-zip-o' => 'Fa File Zip O' ),
    array( 'fa-file-archive-o' => 'Fa File Archive O' ),
    array( 'fa-file-sound-o' => 'Fa File Sound O' ),
    array( 'fa-file-audio-o' => 'Fa File Audio O' ),
    array( 'fa-file-movie-o' => 'Fa File Movie O' ),
    array( 'fa-file-video-o' => 'Fa File Video O' ),
    array( 'fa-file-code-o' => 'Fa File Code O' ),
    array( 'fa-vine' => 'Fa Vine' ),
    array( 'fa-codepen' => 'Fa Codepen' ),
    array( 'fa-jsfiddle' => 'Fa Jsfiddle' ),
    array( 'fa-life-bouy' => 'Fa Life Bouy' ),
    array( 'fa-life-buoy' => 'Fa Life Buoy' ),
    array( 'fa-life-saver' => 'Fa Life Saver' ),
    array( 'fa-support' => 'Fa Support' ),
    array( 'fa-life-ring' => 'Fa Life Ring' ),
    array( 'fa-circle-o-notch' => 'Fa Circle O Notch' ),
    array( 'fa-ra' => 'Fa Ra' ),
    array( 'fa-resistance' => 'Fa Resistance' ),
    array( 'fa-rebel' => 'Fa Rebel' ),
    array( 'fa-ge' => 'Fa Ge' ),
    array( 'fa-empire' => 'Fa Empire' ),
    array( 'fa-git-square' => 'Fa Git Square' ),
    array( 'fa-git' => 'Fa Git' ),
    array( 'fa-y-combinator-square' => 'Fa Y Combinator Square' ),
    array( 'fa-yc-square' => 'Fa Yc Square' ),
    array( 'fa-hacker-news' => 'Fa Hacker News' ),
    array( 'fa-tencent-weibo' => 'Fa Tencent Weibo' ),
    array( 'fa-qq' => 'Fa Qq' ),
    array( 'fa-wechat' => 'Fa Wechat' ),
    array( 'fa-weixin' => 'Fa Weixin' ),
    array( 'fa-send' => 'Fa Send' ),
    array( 'fa-paper-plane' => 'Fa Paper Plane' ),
    array( 'fa-send-o' => 'Fa Send O' ),
    array( 'fa-paper-plane-o' => 'Fa Paper Plane O' ),
    array( 'fa-history' => 'Fa History' ),
    array( 'fa-circle-thin' => 'Fa Circle Thin' ),
    array( 'fa-header' => 'Fa Header' ),
    array( 'fa-paragraph' => 'Fa Paragraph' ),
    array( 'fa-sliders' => 'Fa Sliders' ),
    array( 'fa-share-alt' => 'Fa Share Alt' ),
    array( 'fa-share-alt-square' => 'Fa Share Alt Square' ),
    array( 'fa-bomb' => 'Fa Bomb' ),
    array( 'fa-soccer-ball-o' => 'Fa Soccer Ball O' ),
    array( 'fa-futbol-o' => 'Fa Futbol O' ),
    array( 'fa-tty' => 'Fa Tty' ),
    array( 'fa-binoculars' => 'Fa Binoculars' ),
    array( 'fa-plug' => 'Fa Plug' ),
    array( 'fa-slideshare' => 'Fa Slideshare' ),
    array( 'fa-twitch' => 'Fa Twitch' ),
    array( 'fa-yelp' => 'Fa Yelp' ),
    array( 'fa-newspaper-o' => 'Fa Newspaper O' ),
    array( 'fa-wifi' => 'Fa Wifi' ),
    array( 'fa-calculator' => 'Fa Calculator' ),
    array( 'fa-paypal' => 'Fa Paypal' ),
    array( 'fa-google-wallet' => 'Fa Google Wallet' ),
    array( 'fa-cc-visa' => 'Fa Cc Visa' ),
    array( 'fa-cc-mastercard' => 'Fa Cc Mastercard' ),
    array( 'fa-cc-discover' => 'Fa Cc Discover' ),
    array( 'fa-cc-amex' => 'Fa Cc Amex' ),
    array( 'fa-cc-paypal' => 'Fa Cc Paypal' ),
    array( 'fa-cc-stripe' => 'Fa Cc Stripe' ),
    array( 'fa-bell-slash' => 'Fa Bell Slash' ),
    array( 'fa-bell-slash-o' => 'Fa Bell Slash O' ),
    array( 'fa-trash' => 'Fa Trash' ),
    array( 'fa-copyright' => 'Fa Copyright' ),
    array( 'fa-at' => 'Fa At' ),
    array( 'fa-eyedropper' => 'Fa Eyedropper' ),
    array( 'fa-paint-brush' => 'Fa Paint Brush' ),
    array( 'fa-birthday-cake' => 'Fa Birthday Cake' ),
    array( 'fa-area-chart' => 'Fa Area Chart' ),
    array( 'fa-pie-chart' => 'Fa Pie Chart' ),
    array( 'fa-line-chart' => 'Fa Line Chart' ),
    array( 'fa-lastfm' => 'Fa Lastfm' ),
    array( 'fa-lastfm-square' => 'Fa Lastfm Square' ),
    array( 'fa-toggle-off' => 'Fa Toggle Off' ),
    array( 'fa-toggle-on' => 'Fa Toggle On' ),
    array( 'fa-bicycle' => 'Fa Bicycle' ),
    array( 'fa-bus' => 'Fa Bus' ),
    array( 'fa-ioxhost' => 'Fa Ioxhost' ),
    array( 'fa-angellist' => 'Fa Angellist' ),
    array( 'fa-cc' => 'Fa Cc' ),
    array( 'fa-shekel' => 'Fa Shekel' ),
    array( 'fa-sheqel' => 'Fa Sheqel' ),
    array( 'fa-ils' => 'Fa Ils' ),
    array( 'fa-meanpath' => 'Fa Meanpath' ),
    array( 'fa-buysellads' => 'Fa Buysellads' ),
    array( 'fa-connectdevelop' => 'Fa Connectdevelop' ),
    array( 'fa-dashcube' => 'Fa Dashcube' ),
    array( 'fa-forumbee' => 'Fa Forumbee' ),
    array( 'fa-leanpub' => 'Fa Leanpub' ),
    array( 'fa-sellsy' => 'Fa Sellsy' ),
    array( 'fa-shirtsinbulk' => 'Fa Shirtsinbulk' ),
    array( 'fa-simplybuilt' => 'Fa Simplybuilt' ),
    array( 'fa-skyatlas' => 'Fa Skyatlas' ),
    array( 'fa-cart-plus' => 'Fa Cart Plus' ),
    array( 'fa-cart-arrow-down' => 'Fa Cart Arrow Down' ),
    array( 'fa-diamond' => 'Fa Diamond' ),
    array( 'fa-ship' => 'Fa Ship' ),
    array( 'fa-user-secret' => 'Fa User Secret' ),
    array( 'fa-motorcycle' => 'Fa Motorcycle' ),
    array( 'fa-street-view' => 'Fa Street View' ),
    array( 'fa-heartbeat' => 'Fa Heartbeat' ),
    array( 'fa-venus' => 'Fa Venus' ),
    array( 'fa-mars' => 'Fa Mars' ),
    array( 'fa-mercury' => 'Fa Mercury' ),
    array( 'fa-intersex' => 'Fa Intersex' ),
    array( 'fa-transgender' => 'Fa Transgender' ),
    array( 'fa-transgender-alt' => 'Fa Transgender Alt' ),
    array( 'fa-venus-double' => 'Fa Venus Double' ),
    array( 'fa-mars-double' => 'Fa Mars Double' ),
    array( 'fa-venus-mars' => 'Fa Venus Mars' ),
    array( 'fa-mars-stroke' => 'Fa Mars Stroke' ),
    array( 'fa-mars-stroke-v' => 'Fa Mars Stroke V' ),
    array( 'fa-mars-stroke-h' => 'Fa Mars Stroke H' ),
    array( 'fa-neuter' => 'Fa Neuter' ),
    array( 'fa-genderless' => 'Fa Genderless' ),
    array( 'fa-facebook-official' => 'Fa Facebook Official' ),
    array( 'fa-pinterest-p' => 'Fa Pinterest P' ),
    array( 'fa-whatsapp' => 'Fa Whatsapp' ),
    array( 'fa-server' => 'Fa Server' ),
    array( 'fa-user-plus' => 'Fa User Plus' ),
    array( 'fa-user-times' => 'Fa User Times' ),
    array( 'fa-hotel' => 'Fa Hotel' ),
    array( 'fa-bed' => 'Fa Bed' ),
    array( 'fa-viacoin' => 'Fa Viacoin' ),
    array( 'fa-train' => 'Fa Train' ),
    array( 'fa-subway' => 'Fa Subway' ),
    array( 'fa-medium' => 'Fa Medium' ),
    array( 'fa-yc' => 'Fa Yc' ),
    array( 'fa-y-combinator' => 'Fa Y Combinator' ),
    array( 'fa-optin-monster' => 'Fa Optin Monster' ),
    array( 'fa-opencart' => 'Fa Opencart' ),
    array( 'fa-expeditedssl' => 'Fa Expeditedssl' ),
    array( 'fa-battery-4' => 'Fa Battery 4' ),
    array( 'fa-battery-full' => 'Fa Battery Full' ),
    array( 'fa-battery-3' => 'Fa Battery 3' ),
    array( 'fa-battery-three-quarters' => 'Fa Battery Three Quarters' ),
    array( 'fa-battery-2' => 'Fa Battery 2' ),
    array( 'fa-battery-half' => 'Fa Battery Half' ),
    array( 'fa-battery-1' => 'Fa Battery 1' ),
    array( 'fa-battery-quarter' => 'Fa Battery Quarter' ),
    array( 'fa-battery-0' => 'Fa Battery 0' ),
    array( 'fa-battery-empty' => 'Fa Battery Empty' ),
    array( 'fa-mouse-pointer' => 'Fa Mouse Pointer' ),
    array( 'fa-i-cursor' => 'Fa I Cursor' ),
    array( 'fa-object-group' => 'Fa Object Group' ),
    array( 'fa-object-ungroup' => 'Fa Object Ungroup' ),
    array( 'fa-sticky-note' => 'Fa Sticky Note' ),
    array( 'fa-sticky-note-o' => 'Fa Sticky Note O' ),
    array( 'fa-cc-jcb' => 'Fa Cc Jcb' ),
    array( 'fa-cc-diners-club' => 'Fa Cc Diners Club' ),
    array( 'fa-clone' => 'Fa Clone' ),
    array( 'fa-balance-scale' => 'Fa Balance Scale' ),
    array( 'fa-hourglass-o' => 'Fa Hourglass O' ),
    array( 'fa-hourglass-1' => 'Fa Hourglass 1' ),
    array( 'fa-hourglass-start' => 'Fa Hourglass Start' ),
    array( 'fa-hourglass-2' => 'Fa Hourglass 2' ),
    array( 'fa-hourglass-half' => 'Fa Hourglass Half' ),
    array( 'fa-hourglass-3' => 'Fa Hourglass 3' ),
    array( 'fa-hourglass-end' => 'Fa Hourglass End' ),
    array( 'fa-hourglass' => 'Fa Hourglass' ),
    array( 'fa-hand-grab-o' => 'Fa Hand Grab O' ),
    array( 'fa-hand-rock-o' => 'Fa Hand Rock O' ),
    array( 'fa-hand-stop-o' => 'Fa Hand Stop O' ),
    array( 'fa-hand-paper-o' => 'Fa Hand Paper O' ),
    array( 'fa-hand-scissors-o' => 'Fa Hand Scissors O' ),
    array( 'fa-hand-lizard-o' => 'Fa Hand Lizard O' ),
    array( 'fa-hand-spock-o' => 'Fa Hand Spock O' ),
    array( 'fa-hand-pointer-o' => 'Fa Hand Pointer O' ),
    array( 'fa-hand-peace-o' => 'Fa Hand Peace O' ),
    array( 'fa-trademark' => 'Fa Trademark' ),
    array( 'fa-registered' => 'Fa Registered' ),
    array( 'fa-creative-commons' => 'Fa Creative Commons' ),
    array( 'fa-gg' => 'Fa Gg' ),
    array( 'fa-gg-circle' => 'Fa Gg Circle' ),
    array( 'fa-tripadvisor' => 'Fa Tripadvisor' ),
    array( 'fa-odnoklassniki' => 'Fa Odnoklassniki' ),
    array( 'fa-odnoklassniki-square' => 'Fa Odnoklassniki Square' ),
    array( 'fa-get-pocket' => 'Fa Get Pocket' ),
    array( 'fa-wikipedia-w' => 'Fa Wikipedia W' ),
    array( 'fa-safari' => 'Fa Safari' ),
    array( 'fa-chrome' => 'Fa Chrome' ),
    array( 'fa-firefox' => 'Fa Firefox' ),
    array( 'fa-opera' => 'Fa Opera' ),
    array( 'fa-internet-explorer' => 'Fa Internet Explorer' ),
    array( 'fa-tv' => 'Fa Tv' ),
    array( 'fa-television' => 'Fa Television' ),
    array( 'fa-contao' => 'Fa Contao' ),
    array( 'fa-500px' => 'Fa 500px' ),
    array( 'fa-amazon' => 'Fa Amazon' ),
    array( 'fa-calendar-plus-o' => 'Fa Calendar Plus O' ),
    array( 'fa-calendar-minus-o' => 'Fa Calendar Minus O' ),
    array( 'fa-calendar-times-o' => 'Fa Calendar Times O' ),
    array( 'fa-calendar-check-o' => 'Fa Calendar Check O' ),
    array( 'fa-industry' => 'Fa Industry' ),
    array( 'fa-map-pin' => 'Fa Map Pin' ),
    array( 'fa-map-signs' => 'Fa Map Signs' ),
    array( 'fa-map-o' => 'Fa Map O' ),
    array( 'fa-map' => 'Fa Map' ),
    array( 'fa-commenting' => 'Fa Commenting' ),
    array( 'fa-commenting-o' => 'Fa Commenting O' ),
    array( 'fa-houzz' => 'Fa Houzz' ),
    array( 'fa-vimeo' => 'Fa Vimeo' ),
    array( 'fa-black-tie' => 'Fa Black Tie' ),
    array( 'fa-fonticons' => 'Fa Fonticons' ),
);
?>
<script type="text/html" id="ciloe_mapper_icon_selector_tmpl">
    <div class="icon-selector select-styled pr bdgr br__3">
        <div class="icon-selected"><i class="fa fa-%SELECTED%"></i></div>
        <div class="icon-wrap pa bdgr">
            <h5><?php _e('Select icon', 'ciloe-toolkit'); ?><i class="close fa fa-close"></i></h5>
            <div class="ciloe-icon-list bgw bdgr fc fcw">
                <?php foreach ($fonts as $font => $key ):
                    $class_key = key($key);
                    ?>
                    <a data-value="<?php echo esc_attr($class_key);?>" href="#"><i class="fa <?php echo esc_attr($class_key);?>"></i></a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    jQuery(function ($) {
        $(window).load(function () {
            // Override default UI.
            var form = $($('#ciloe_mapper_tmpl').text()).prepend($('#post').children('input[type="hidden"]'));

            $('#screen-meta, #screen-meta-links').remove();

            $('#post-body > div#post-body-content').replaceWith(form);

            // Trigger event to initialize application.
            setTimeout(function () {
                $(document).trigger('init_ciloe_mapper');
            }, 500);

            // Pass data to client-side.
            window.ciloe_mapper_settings = <?php echo json_encode($settings ? $settings : new stdClass()); ?>;
            window.ciloe_mapper_pins = <?php echo json_encode($pins ? array_values($pins) : array()); ?>;
        });
    });
</script>
