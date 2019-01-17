<?php

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class FamiWPBakeryShortCode_Prdctfltr_Sc_Products extends WPBakeryShortCode {
	}
}

$presets = array(
	esc_html__( 'Default', 'ciloe-toolkit' ) => ''
);

$saved_presets = get_option( 'prdctfltr_templates', array() );

if ( is_array( $saved_presets ) ) {
	foreach ( $saved_presets as $k => $v ) {
		$presets[ $k ] = $k;
	}
}

$choices_columns[1] = '1';
$choices_columns[2] = '2';
$choices_columns[3] = '3';
$choices_columns[4] = '4';
$choices_columns[5] = '5';
$choices_columns[6] = '6';
$choices_columns[7] = '7';
$choices_columns[8] = '8';

$choices_orderby['menu_order title'] = 'menu_order title';
$choices_orderby['ID']               = 'ID';
$choices_orderby['author']           = 'author';
$choices_orderby['title']            = 'title';
$choices_orderby['name']             = 'name';
$choices_orderby['date']             = 'date';
$choices_orderby['modified']         = 'modified';
$choices_orderby['rand']             = 'rand';
$choices_orderby['comment_count']    = 'comment_count';
$choices_orderby['menu_order title'] = 'menu_order title';
$choices_orderby['post__in']         = 'post__in';

$choices_order['DESC'] = 'DESC';
$choices_order['ASC']  = 'ASC';

vc_map( array(
	        'name'        => esc_html__( 'Fami - WooCommerce Product Filter', 'ciloe-toolkit' ),
	        'base'        => 'fami_prdctfltr_sc_products',
	        'class'       => '',
	        'category'    => esc_html__( 'Ciloe Elements', 'ciloe-toolkit' ),
	        'icon'        => CILOE_TOOLKIT_URL . '/includes/shortcodes/icons/fami-filter-icon.png',
	        //Prdctfltr()->plugin_url() . '/lib/images/pficon.png',
	        'description' => esc_html__( 'All in one Product Filter for WooCommerce!', 'ciloe-toolkit' ),
	        'params'      => array(
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Show Filter', 'ciloe-toolkit' ),
			        'param_name'  => 'use_filter',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'yes'
		        ),
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Filter Preset', 'ciloe-toolkit' ),
			        'param_name'  => 'preset',
			        'value'       => $presets,
			        'description' => '',
			        'std'         => ''
		        ),
		
		        /*
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Show Categories', 'ciloe-toolkit' ),
			        'param_name'  => 'show_categories',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'no'
		        ),
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Show Categories Thumbnails', 'ciloe-toolkit' ),
			        'param_name'  => 'show_cat_thumbs',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'no'
		        ),
		        
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Category Columns', 'ciloe-toolkit' ),
			        'param_name'  => 'cat_columns',
			        'value'       => $choices_columns,
			        'description' => '',
			        'std'         => 6
		        ),
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Show Products (Step Filter mode when set to NO)', 'ciloe-toolkit' ),
			        'param_name'  => 'show_products',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'yes'
		        ),
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Product Columns', 'ciloe-toolkit' ),
			        'param_name'  => 'columns',
			        'value'       => $choices_columns,
			        'description' => '',
			        'std'         => 4
		        ),
		
		        array(
			        'type'        => 'textfield',
			        'class'       => '',
			        'heading'     => esc_html__( 'Product Rows', 'ciloe-toolkit' ),
			        'param_name'  => 'rows',
			        'value'       => '',
			        'description' => '',
			        'std'         => 4
		        ),
		        */
		
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Pagination', 'ciloe-toolkit' ),
			        'param_name'  => 'pagination',
			        'value'       => array(
				        'yes',
				        'no',
				        'loadmore',
				        'infinite'
			        ),
			        'description' => '',
			        'std'         => 'yes',
			        'dependency'  => array(
				        'element' => 'use_filter',
				        'value'   => array( 'yes' ),
			        ),
		        ),
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Ajax', 'ciloe-toolkit' ),
			        'param_name'  => 'ajax',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'no'
		        ),
		        /*
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Order By', 'ciloe-toolkit' ),
					'param_name'  => 'orderby',
					'value'       => $choices_orderby,
					'description' => '',
					'std'         => 'menu_order title'
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Order', 'ciloe-toolkit' ),
					'param_name'  => 'order',
					'value'       => $choices_order,
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Min Price', 'ciloe-toolkit' ),
					'param_name'  => 'min_price',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Max Price', 'ciloe-toolkit' ),
					'param_name'  => 'max_price',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Product Category', 'ciloe-toolkit' ),
					'param_name'  => 'product_cat',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Product Tag', 'ciloe-toolkit' ),
					'param_name'  => 'product_tag',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Product Characteristics', 'ciloe-toolkit' ),
					'param_name'  => 'product_characteristics',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Sale Products', 'ciloe-toolkit' ),
					'param_name'  => 'sale_products',
					'value'       => array(
						'Default' => '',
						'on',
						'off'
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Instock Products', 'ciloe-toolkit' ),
					'param_name'  => 'instock_products',
					'value'       => array(
						'Default' => '',
						'in',
						'out',
						'both'
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textarea',
					'class'       => '',
					'heading'     => esc_html__( 'HTTP Query', 'ciloe-toolkit' ),
					'param_name'  => 'http_query',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => esc_html__( 'Custom Action', 'ciloe-toolkit' ),
					'param_name'  => 'action',
					'value'       => '',
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Show Loop Title', 'ciloe-toolkit' ),
					'param_name'  => 'show_loop_title',
					'value'       => array(
						'Default' => '',
						'no',
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Show Loop Price', 'ciloe-toolkit' ),
					'param_name'  => 'show_loop_price',
					'value'       => array(
						'Default' => '',
						'no',
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Show Loop Rating', 'ciloe-toolkit' ),
					'param_name'  => 'show_loop_rating',
					'value'       => array(
						'Default' => '',
						'no',
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Show Loop Add to Cart', 'ciloe-toolkit' ),
					'param_name'  => 'show_loop_add_to_cart',
					'value'       => array(
						'Default' => '',
						'no',
					),
					'description' => '',
					'std'         => ''
				),
		
				array(
					'type'        => 'dropdown',
					'class'       => '',
					'heading'     => esc_html__( 'Fallback CSS (If columns option is not working)', 'ciloe-toolkit' ),
					'param_name'  => 'fallback_css',
					'value'       => array(
						'yes',
						'no',
					),
					'description' => '',
					'std'         => 'no'
				),
				*/
		
		        /*			array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => esc_html__( 'Disable Filtering for WC Shortcodes', 'ciloe-toolkit' ),
								'param_name'  => 'disable_woo_filter',
								'value'       => array(
									'yes',
									'no',
								),
								'description' => '',
								'std'         => 'no'
							),
				*/
		        /*
		        array(
			        'type'        => 'dropdown',
			        'class'       => '',
			        'heading'     => esc_html__( 'Disable Preset Overrides', 'ciloe-toolkit' ),
			        'param_name'  => 'disable_overrides',
			        'value'       => array(
				        'yes',
				        'no',
			        ),
			        'description' => '',
			        'std'         => 'yes'
		        ),
		
		        array(
			        'type'        => 'textfield',
			        'class'       => '',
			        'heading'     => esc_html__( 'Bottom Margin', 'ciloe-toolkit' ),
			        'param_name'  => 'bot_margin',
			        'value'       => '',
			        'description' => '',
			        'std'         => 40
		        ),
		        */
		
		        array(
			        'type'        => 'textfield',
			        'class'       => '',
			        'heading'     => esc_html__( 'Products Per Page', 'ciloe-toolkit' ),
			        'param_name'  => 'posts_per_page',
			        'value'       => 12,
			        'description' => '',
			        'std'         => 12
		        ),
		        
		        array(
			        'type'        => 'taxonomy',
			        'taxonomy'    => 'product_cat',
			        'class'       => '',
			        'heading'     => esc_html__( 'Categories', 'ciloe-toolkit' ),
			        'param_name'  => 'categories_display',
			        "value"       => '',
			        'parent'      => '',
			        'multiple'    => true,
			        'hide_empty'  => false,
			        'placeholder' => esc_html__( 'Choose category', 'ciloe-toolkit' ),
			        "description" => esc_html__( 'Select the categories you want to display', 'ciloe-toolkit' ),
			        'std'         => '',
			        'group'       => esc_html__( 'Products Options', 'ciloe-toolkit' ),
		        ),
		
		        array(
			        'type'        => 'textfield',
			        'class'       => '',
			        'heading'     => esc_html__( 'Shortcode ID', 'ciloe-toolkit' ),
			        'param_name'  => 'shortcode_id',
			        'value'       => '',
			        'description' => '',
			        'std'         => ''
		        ),
		
		        array(
			        'type'        => 'textfield',
			        'class'       => '',
			        'heading'     => esc_html__( 'Class', 'ciloe-toolkit' ),
			        'param_name'  => 'class',
			        'value'       => '',
			        'description' => '',
			        'std'         => ''
		        ),
	
	        )
        ) );

$shortcodes = array(
	'products',
	'recent_products',
	'sale_products',
	'best_selling_products',
	'top_rated_products',
	'featured_products',
	'product_category',
	'product_attribute'
);

$choices_pagination['no']       = 'no';
$choices_pagination['yes']      = 'yes';
$choices_pagination['loadmore'] = 'loadmore';
$choices_pagination['infinite'] = 'infinite';

foreach ( $shortcodes as $shortcode ) {
	$params = array(
		array(
			'type'        => 'dropdown',
			'class'       => '',
			'heading'     => esc_html__( 'Product Filter - Activate', 'ciloe-toolkit' ),
			'param_name'  => 'ciloe-toolkit',
			'value'       => array(
				'yes',
				'widget',
				'no',
			),
			'description' => '',
			'std'         => 'no'
		),
		array(
			'type'        => 'dropdown',
			'class'       => '',
			'heading'     => esc_html__( 'Product Filter - Ajax', 'ciloe-toolkit' ),
			'param_name'  => 'ajax',
			'value'       => array(
				'yes',
				'no',
			),
			'description' => '',
			'std'         => 'no'
		),
		array(
			'type'        => 'dropdown',
			'class'       => '',
			'heading'     => esc_html__( 'Product Filter - Select Preset', 'ciloe-toolkit' ),
			'param_name'  => 'preset',
			'value'       => $presets,
			'description' => '',
			'std'         => ''
		),
		array(
			'type'        => 'dropdown',
			'class'       => '',
			'heading'     => esc_html__( 'Product Filter - Pagination', 'ciloe-toolkit' ),
			'param_name'  => 'pagination',
			'value'       => $choices_pagination,
			'description' => '',
			'std'         => 'no'
		),
	);
	foreach ( $params as $param ) {
		vc_add_param( $shortcode, $param );
	}
}