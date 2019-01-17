<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

$data_meta = new Ciloe_ThemeOption();
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// META BOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options = array();
// -----------------------------------------
// Page Meta box Options                   -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_metabox_theme_options',
	'title'     => esc_html__( 'Custom Options', 'ciloe-toolkit' ),
	'post_type' => 'page',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'header_footer_theme_options', // !??
			'title'  => esc_html__( 'Header Settings', 'ciloe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'type'    => 'subheading',
					'content' => esc_html__( 'Header Settings', 'ciloe-toolkit' ),
				),
				array(
					'id'      => 'enable_custom_header',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Custom Header', 'ciloe-toolkit' ),
					'default' => false,
					'desc'    => esc_html__( 'The default is off. If you want to use separate custom page header, turn it on.', 'ciloe-toolkit' ),
				),
				array(
					'id'         => 'enable_sticky_menu',
					'type'       => 'select',
					'title'      => esc_html__( 'Sticky Header', 'ciloe-toolkit' ),
					'options'    => array(
						'none'  => esc_html__( 'Disable', 'ciloe-toolkit' ),
						'smart' => esc_html__( 'Sticky Header', 'ciloe-toolkit' ),
					),
					'default'    => 'none',
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'enable_topbar',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Enable topbar', 'ciloe-toolkit' ),
					'default'    => false,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'topbar-text',
					'type'       => 'text',
					'title'      => esc_html__( 'Text Topbar', 'ciloe-toolkit' ),
					'dependency' => array( 'enable_custom_header|enable_topbar', '==', 'true|true' ),
				),
				array(
					'id'         => 'metabox_ciloe_logo',
					'type'       => 'image',
					'title'      => esc_html__( 'Custom Logo', 'ciloe-toolkit' ),
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'ciloe_metabox_used_header',
					'type'       => 'select_preview',
					'title'      => esc_html__( 'Header Layout', 'ciloe-toolkit' ),
					'desc'       => esc_html__( 'Select a header layout', 'ciloe-toolkit' ),
					'options'    => $data_meta->header_options,
					'default'    => 'logo_l_menu_c_icons_r_bg_trans',
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'header_shadow',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Enable Shadow', 'ciloe-toolkit' ),
					'default'    => false,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'header_text_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Header Text Color', 'ciloe-toolkit' ),
					'default'    => '#000',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),

				array(
					'id'         => 'header_bg_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Header Background Color', 'ciloe-toolkit' ),
					'default'    => 'rgba(0,0,0,0)',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'header_position',
					'type'       => 'select',
					'title'      => esc_html__( 'Header Type', 'ciloe-toolkit' ),
					'options'    => array(
						'relative' => esc_html__( 'Header No Transparent', 'ciloe-toolkit' ),
						'absolute' => esc_html__( 'Header Transparent', 'ciloe-toolkit' ),
					),
					'default'    => 'relative',
					'dependency' => array( 'enable_custom_header|ciloe_metabox_used_header', '==|!=', 'true|sidebar' ),
				),
			
			)
		),
		array(
			'name'   => 'page_banner_settings',
			'title'  => esc_html__( 'Page Banner Settings', 'ciloe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'      => 'enable_custom_banner',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Page Custom Banner', 'ciloe-toolkit' ),
					'default' => false,
					'desc'    => esc_html__( 'The default is off. If you want to use separate custom page banner, turn it on.', 'ciloe-toolkit' ),
				),
				array(
					'id'         => 'hero_section_type',
					'type'       => 'select',
					'title'      => esc_html__( 'Banner Type', 'ciloe-toolkit' ),
					'options'    => array(
						'disable'        => esc_html__( 'Disable', 'ciloe-toolkit' ),
						'has_background' => esc_html__( 'Has Background', 'ciloe-toolkit' ),
						'no_background'  => esc_html__( 'No Background ', 'ciloe-toolkit' ),
						'rev_background' => esc_html__( 'Revolution', 'ciloe-toolkit' ),
					),
					'default'    => 'no_background',
					'dependency' => array( 'enable_custom_banner', '==', true ),
				),
				array(
					'id'         => 'bg_banner_page',
					'type'       => 'background',
					'title'      => esc_html__( 'Background Banner', 'ciloe-toolkit' ),
					'default'    => array(
						'image'      => '',
						'repeat'     => 'repeat-x',
						'position'   => 'center center',
						'attachment' => 'fixed',
						'size'       => 'cover',
						'color'      => '#ffbc00',
					),
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'colortext_banner_page',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Banner Text Color', 'ciloe-toolkit' ),
					'default'    => '#ffffff',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'ciloe_metabox_header_rev_slide',
					'type'       => 'select',
					'options'    => ciloe_rev_slide_options(),
					'title'      => esc_html__( 'Revolution', 'ciloe-toolkit' ),
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|rev_background' ),
				),
				array(
					'id'         => 'page_banner_full_width',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Banner Background Full Width', 'ciloe-toolkit' ),
					'default'    => 0,
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'page_banner_breadcrumb',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Enable Breadcrumb', 'ciloe-toolkit' ),
					'default'    => 0,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
					'desc'       => esc_html__( 'This option has no effect on front page and blog page', 'ciloe-toolkit' )
				),
				array(
					'id'         => 'page_height_banner',
					'type'       => 'number',
					'title'      => esc_html__( 'Banner Height', 'ciloe-toolkit' ),
					'default'    => 200,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'page_margin_top',
					'type'       => 'number',
					'title'      => esc_html__( 'Margin Top', 'ciloe-toolkit' ),
					'default'    => 55,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'page_margin_bottom',
					'type'       => 'number',
					'title'      => esc_html__( 'Margin Bottom', 'ciloe-toolkit' ),
					'default'    => 100,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'show_hero_section_on_header_mobile',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Show Header Banner On Mobile', 'ciloe-toolkit' ),
					'default'    => false,
					'desc'       => esc_html__( 'If enabled, the "Header Banner" is still displayed on the mobile. This option only works when the mobile header is enabled in Theme Options', 'ciloe-toolkit' ),
					'dependency' => array( 'enable_custom_banner', '==', 'true' ),
				),
			),
		),
		array(
			'name'   => 'footer_settings',
			'title'  => esc_html__( 'Footer Settings', 'ciloe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'      => 'enable_custom_footer',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Custom Footer', 'ciloe-toolkit' ),
					'default' => false,
				),
				array(
					'id'         => 'ciloe_metabox_footer_options',
					'type'       => 'select',
					'title'      => esc_html__( 'Select Footer Builder', 'ciloe-toolkit' ),
					'options'    => 'posts',
					'query_args' => array(
						'post_type'      => 'footer',
						'orderby'        => 'post_date',
						'order'          => 'ASC',
						'posts_per_page' => - 1
					),
					'dependency' => array( 'enable_custom_footer', '==', true ),
				),
			)
		),
	),
);

// -----------------------------------------
// Product Meta box Options
// -----------------------------------------
$global_product_style      = ciloe_toolkit_get_option( 'ciloe_woo_single_product_layout', 'default' );
$all_product_styles        = array(
	'default'           => esc_html__( 'Default', 'ciloe-toolkit' ),
	'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'ciloe-toolkit' ),
	'sticky_detail'     => esc_html__( 'Sticky Detail', 'ciloe-toolkit' ),
	'gallery_detail'    => esc_html__( 'Gallery Detail', 'ciloe-toolkit' ),
	'big_images'        => esc_html__( 'Big Images', 'ciloe-toolkit' ),
);
$global_product_style_text = isset( $all_product_styles[ $global_product_style ] ) ? $all_product_styles[ $global_product_style ] : $global_product_style;
$options[]                 = array(
	'id'        => '_custom_product_metabox_theme_options',
	'title'     => esc_html__( 'Custom Options', 'ciloe-toolkit' ),
	'post_type' => 'product',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'product_options',
			'title'  => esc_html__( 'Product Configure', 'ciloe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'         => 'size_guide',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Size guide', 'ciloe-toolkit' ),
					'desc'       => esc_html__( 'On or Off Size guide', 'ciloe-toolkit' ),
					'default'    => false,
				),
				array(
					'id'         => 'ciloe_sizeguide_options',
					'type'       => 'select',
					'title'      => esc_html__( 'Select Size Guide Builder', 'ciloe-toolkit' ),
					'options'    => 'posts',
					'dependency' => array( 'size_guide', '==', true ),
					'query_args' => array(
						'post_type'      => 'sizeguide',
						'orderby'        => 'post_date',
						'order'          => 'ASC',
						'posts_per_page' => - 1
					),
				),
				array(
					'id'      => 'product_style',
					'type'    => 'select',
					'title'   => esc_html__( 'Choose Style', 'ciloe-toolkit' ),
					'desc'    => esc_html__( 'Choose Product Style', 'ciloe-toolkit' ),
					'options' => array(
						'global'            => sprintf( esc_html__( 'Use Theme Options Style: %s', 'ciloe-toolkit' ), $global_product_style_text ),
						'default'           => esc_html__( 'Default', 'ciloe-toolkit' ),
						'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'ciloe-toolkit' ),
						'sticky_detail'     => esc_html__( 'Sticky Detail', 'ciloe-toolkit' ),
						'gallery_detail'    => esc_html__( 'Gallery Detail', 'ciloe-toolkit' ),
						'big_images'        => esc_html__( 'Big Images', 'ciloe-toolkit' ),
					),
					'default' => 'global',
				),
				array(
					'id'         => 'product_img_bg_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Image Background Color', 'ciloe-toolkit' ),
					'default'    => 'rgba(0,0,0,0)',
					'rgba'       => true,
					'dependency' => array(
						'product_style',
						'==',
						'big_images'
					),
					'desc'       => esc_html__( 'For "Big Images" style only. Default: transparent', 'ciloe-toolkit' ),
				),
				array(
					'id'         => 'product_sum_border',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Summary Border', 'ciloe-toolkit' ),
					'default'    => false,
					'dependency' => array(
						'product_style',
						'any',
						'default,vertical_thumnail,sticky_detail'
					),
				),
				array(
					'id'         => 'title_price_stars_outside_sum',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Title, Price And Stars Outside Sumary', 'ciloe-toolkit' ),
					'default'    => false,
					'dependency' => array(
						'product_style',
						'any',
						'default,vertical_thumnail,sticky_detail'
					),
				),
			)
		),
	)
);
// -----------------------------------------
// Page Footer Meta box Options            -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_footer_options',
	'title'     => esc_html__( 'Custom Footer Options', 'ciloe-toolkit' ),
	'post_type' => 'footer',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => esc_html__( 'FOOTER STYLE', 'ciloe-toolkit' ),
			'fields' => array(
				array(
					'id'       => 'ciloe_footer_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Footer Style', 'ciloe-toolkit' ),
					'subtitle' => esc_html__( 'Select a Footer Style', 'ciloe-toolkit' ),
					'options'  => $data_meta->footer_options,
					'default'  => 'default',
				),
			),
		),
	),
);
// -----------------------------------------
// Page Testimonials Meta box Options      -
// -----------------------------------------
if ( class_exists( 'WooCommerce' ) ) {
	$options[] = array(
		'id'        => '_custom_post_woo_options',
		'title'     => esc_html__( 'Post Meta Data', 'ciloe-toolkit' ),
		'post_type' => 'post',
		'context'   => 'normal',
		'priority'  => 'high',
		'sections'  => array(
			array(
				'name'   => 'post-products',
				'title'  => esc_html__( 'Products', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-picture-o',
				'fields' => array(
					array(
						'id'         => 'ciloe_product_options',
						'type'       => 'select',
						'title'      => esc_html__( 'Select products', 'ciloe-toolkit' ),
						'options'    => 'posts',
						'query_args' => array(
							'post_type'      => 'product',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),
						'class'      => 'chosen',
						'attributes' => array(
							'placeholder' => 'Select product',
							'multiple'    => 'multiple',
							'style'       => 'width: 600px;'
						),
						'desc'       => esc_html__( 'Select product for post. It will display slide in loop.' ),
					),
				),
			),
			array(
				'name'   => 'post-format-setting',
				'title'  => esc_html__( 'Post Format Settings', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-picture-o',
				'fields' => array(
					array(
						'id'    => 'audio-video-url',
						'type'  => 'text',
						'title' => esc_html__( 'Upload Video or Audio Url', 'ciloe-toolkit' ),
						'desc'  => esc_html__( 'Using when you choose post format video or audio.' ),
					),
					array(
						'id'          => 'post-gallery',
						'type'        => 'gallery',
						'title'       => esc_html__( 'Gallery', 'ciloe-toolkit' ),
						'desc'        => esc_html__( 'Using when you choose post format gallery.' ),
						'add_title'   => esc_html__( 'Add Images', 'ciloe-toolkit' ),
						'edit_title'  => esc_html__( 'Edit Images', 'ciloe-toolkit' ),
						'clear_title' => esc_html__( 'Remove Images', 'ciloe-toolkit' ),
					),
					array(
						'id'    => 'page_extra_class',
						'type'  => 'text',
						'title' => esc_html__( 'Extra Class', 'ciloe-toolkit' ),
					),
				),
			),
		),
	);
	$options[] = array(
		'id'        => '_custom_product_woo_options',
		'title'     => esc_html__( 'Product Options', 'ciloe-toolkit' ),
		'post_type' => 'product',
		'context'   => 'side',
		'priority'  => 'high',
		'sections'  => array(
			array(
				'name'   => 'meta_product_option',
				'fields' => array(
					array(
						'id'          => '360gallery',
						'type'        => 'gallery',
						'title'       => esc_html__( 'Gallery 360', 'ciloe-toolkit' ),
						'add_title'   => esc_html__( 'Add Images', 'ciloe-toolkit' ),
						'edit_title'  => esc_html__( 'Edit Images', 'ciloe-toolkit' ),
						'clear_title' => esc_html__( 'Remove Images', 'ciloe-toolkit' ),
					),
					array(
						'id'    => 'youtube_url',
						'type'  => 'text',
						'title' => esc_html__( 'Product Video', 'ciloe-toolkit' ),
						'desc'  => esc_html__( 'Supported video Youtube, Vimeo .' ),
					),
				),
			),
		
		
		),
	);
}
// -----------------------------------------
// Page Side Meta box Options              -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_page_side_options',
	'title'     => esc_html__( 'Custom Page Side Options', 'ciloe-toolkit' ),
	'post_type' => 'page',
	'context'   => 'side',
	'priority'  => 'default',
	'sections'  => array(
		array(
			'name'   => 'page_option',
			'fields' => array(
				array(
					'id'      => 'sidebar_page_layout',
					'type'    => 'image_select',
					'title'   => esc_html__( 'Single Post Sidebar Position', 'ciloe-toolkit' ),
					'desc'    => esc_html__( 'Select sidebar position on Page.', 'ciloe-toolkit' ),
					'options' => array(
						'left'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
						'right' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
						'full'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
					),
					'default' => 'left',
				),
				array(
					'id'         => 'page_sidebar',
					'type'       => 'select',
					'title'      => esc_html__( 'Page Sidebar', 'ciloe-toolkit' ),
					'options'    => $data_meta->sidebars,
					'default'    => 'blue',
					'dependency' => array( 'sidebar_page_layout_full', '==', false ),
				),
				array(
					'id'    => 'page_extra_class',
					'type'  => 'text',
					'title' => esc_html__( 'Extra Class', 'ciloe-toolkit' ),
				),
			),
		),
	
	),
);
// -----------------------------------------
// Post Side Meta box Options              -
// -----------------------------------------

CSFramework_Metabox::instance( $options );
