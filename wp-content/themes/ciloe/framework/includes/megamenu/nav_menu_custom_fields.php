<?php
/**
 * Define all custom fields in menu
 *
 */
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
add_action( 'walker_nav_menu_custom_fields', 'ciloe_add_custom_fields', 10, 4 );
function ciloe_add_custom_fields( $item_id, $item, $depth, $args ) {
    if(isset( $item->item_icon_type) && $item->item_icon_type){
        $item_icon_type = $item->item_icon_type;
    }else{
        $item_icon_type ='none';
    }
    ?>
    <div class="clearfix"></div>
    <div class="container-megamenu container-<?php echo esc_attr( $depth ); ?>">
        <p class="item_icon_type">
            <label class="row_label"><strong><?php esc_html_e( 'Icon Settings' ,'ciloe' );?></strong></label><br />
            <input <?php checked( 'none', $item_icon_type, true ); ?> name="menu-item-megamenu-item_icon_type[<?php echo esc_attr( $item_id ); ?>]" id="none_item_icon_type_<?php echo esc_attr( $item_id );?>" type="radio" value="none"><label for="none_item_icon_type_<?php echo esc_attr( $item_id );?>"><?php esc_html_e('None' , 'ciloe') ?></label>
            <input <?php checked( 'fonticon', $item_icon_type, true ); ?> name="menu-item-megamenu-item_icon_type[<?php echo esc_attr( $item_id ); ?>]" id="fonticon_item_icon_type_<?php echo esc_attr( $item_id );?>" type="radio" value="fonticon"><label for="fonticon_item_icon_type_<?php echo esc_attr( $item_id );?>"><?php esc_html_e('Font icon' , 'ciloe') ?></label>
            <input <?php checked( 'image', $item_icon_type, true ); ?> name="menu-item-megamenu-item_icon_type[<?php echo esc_attr( $item_id ); ?>]" id="img_item_icon_type_<?php echo esc_attr( $item_id );?>" type="radio" value="image"><label for="img_item_icon_type_<?php echo esc_attr( $item_id );?>"><?php esc_html_e('Image' , 'ciloe') ?></label>
            <input <?php checked( 'logo', $item_icon_type, true ); ?> name="menu-item-megamenu-item_icon_type[<?php echo esc_attr( $item_id ); ?>]" id="img_item_icon_type_<?php echo esc_attr( $item_id );?>" type="radio" value="logo"><label for="img_item_icon_type_<?php echo esc_attr( $item_id );?>"><?php esc_html_e('Logo' , 'ciloe') ?></label>
        </p>
        <p class="field-fonticon" <?php if( $item_icon_type == 'fonticon'):?> style="display:block;"<?php endif;?>>
            <?php
            $font_icon = false;
            if( $item->font_icon ){
                $font_icon = $item->font_icon;
            }
            ?>
            <span class="font-icon-preview" id="font-icon-preview-<?php echo esc_attr( $item_id ); ?>">
            <?php if($font_icon ):?>
                <span class="icon <?php echo esc_attr( $font_icon );?>"></span>
            <?php endif;?>
            </span>
            <a data-id="<?php echo esc_attr( $item_id ); ?>" class="button-select-icon" href="#"><?php esc_html_e( 'Select Icon', 'ciloe' );?></a>
            <input type="hidden" value="<?php echo esc_attr( $item->font_icon ); ?>" name="menu-item-megamenu-font_icon[<?php echo esc_attr( $item_id ); ?>]" id="menu-item-font-icon-<?php echo esc_attr( $item_id ); ?>" class="" />
        </p>

        <p class="group-image description description-wide" <?php if( $item_icon_type == 'image'):?> style="display:block;"<?php endif;?>>
            <?php
            $preview = false;
            $img_preview = get_template_directory_uri() . '/framework/includes/megamenu/images/placeholder.png';
            if($item->img_icon){
                $img_preview = wp_get_attachment_url( $item->img_icon );
                $preview = true;
            }
            $preview_hover = false;
            $img_preview_hover = get_template_directory_uri() . '/framework/includes/megamenu/images/placeholder.png';
            if($item->img_icon_hover){
                $img_preview_hover = wp_get_attachment_url( $item->img_icon_hover );
                $preview_hover = true;
            }
            $img_note_preview = false;
            $img_note = get_template_directory_uri() . '/framework/includes/megamenu/images/placeholder.png';
            if($item->img_note){
                $img_note = wp_get_attachment_url( $item->img_note );
                $img_note_preview = true;
            }
            ?>
            <!-- Image Icon -->
            <span class="image-field">
                <label for="menu-item-image-<?php echo esc_attr( $item_id ); ?>">
                <input class="image_input" type="hidden" value="<?php echo esc_attr( $item->img_icon ); ?>" name="menu-item-megamenu-img_icon[<?php echo esc_attr( $item_id ); ?>]" id="menu-item-imgicon-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-image" />
                </label>
                <span class="clearfix"></span>
                <span class="image_icon" style="<?php if( $preview ){ echo "display: inline-block;";} ?>">
                    <img class="preview" src="<?php echo esc_url( $img_preview ); ?>" alt="<?php esc_attr_e( 'Image preview', 'ciloe' ) ?>" title="<?php esc_attr_e( 'Image preview', 'ciloe' ) ?>" />
                </span>
                <a class="ciloe_image_menu icon_image" data-item_id="<?php echo esc_attr( $item_id );?>" href="#"><?php esc_html_e('Icon Image ','ciloe');?></a>
            </span>

        </p>
        <?php  if( 'megamenu' == $item->object): ?>
            <p class="mega-menu-setting">
                <label class="row_label" ><strong><?php esc_html_e('Mega menu settings','ciloe');?></strong></label>
                <br />
                <label>Menu width <small><i>(Unit px)</i></small></label><br>
                <input type="number" value="<?php echo esc_attr( $item->mega_menu_width ); ?>" name="menu-item-megamenu-mega_menu_width[<?php echo esc_attr( $item_id ); ?>]" id="menu-item-mega_menu_width-<?php echo esc_attr( $item_id ); ?>">
                <br />
                <label>Menu custom URL</label><br>
                <input class="widefat" type="text" value="<?php echo esc_attr( $item->mega_menu_url ); ?>" name="menu-item-megamenu-mega_menu_url[<?php echo esc_attr( $item_id ); ?>]" id="menu-item-mega_menu_url-<?php echo esc_attr( $item_id ); ?>" placeholder ="http://">
            </p>
        <?php endif;?>
    </div><!-- .container-megamenu -->
<?php }