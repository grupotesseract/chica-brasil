<?php
/*
 * ALM_NOSCRIPT
 * Class that generates a wp_query for injection into <noscript />.
 *
 * @author   Darren Cooney
 * @since    3.7
 */

if (!defined( 'ABSPATH')){
	exit;
}

if(!class_exists('ALM_NOSCRIPT')):

   class ALM_NOSCRIPT {
	   
	   static $element = 'noscript';
      
      
      /*
	    * alm_get_noscript
	    * This function will return a generated query for the noscript.
   	 *
   	 * @since            1.8
   	 * @param $q  		   array
   	 * @param $container string
   	 * @return           <noscript>
   	 */      
      public static function alm_get_noscript($q, $container = 'ul', $css_classes = '', $transition_container_classes = ''){
         
         $paged = ($q['paged']) ? $q['paged'] : 1;
         
         
         // Comments
         if($q['comments']){          
         	if(has_action('alm_comments_installed') && $q['comments']){
            	// SEO does not support comments at this time
            }
         }
         
         
         // Users
         elseif($q['users']){    
	                  
            if(has_action('alm_users_preloaded') && $q['users']){	
               
               // Encrypt User Role
			      if(!empty($q['users_role']) && function_exists('alm_role_encrypt')){
			         $q['users_role'] = alm_role_encrypt($q['users_role']);
			      }
			      
			      // Update offset
			      $q['offset'] = ALM_NOSCRIPT::set_offset($paged, $q['users_per_page'], $q['offset']);
			      
			      // Build output
			      $output = apply_filters('alm_users_preloaded', $q, $q['users_per_page'], $q['repeater'], $q['theme_repeater']); // located in Users add-on
               
               return ALM_NOSCRIPT::render($output['data'], $container, '', $css_classes, $transition_container_classes);
            }
         }        
         

         // Advanced Custom Fields (Repeater, Gallery, Flex Content
         elseif($q['acf'] && ($q['acf_field_type'] !== 'relationship')){   
            if(has_action('alm_acf_installed') && $q['acf']){
	            
	            // Update offset
			      $q['offset'] = ALM_NOSCRIPT::set_offset($paged, $q['posts_per_page'], $q['offset']);
			      
			      // Build output
               $output = apply_filters('alm_acf_preloaded', $q, $q['repeater'], $q['theme_repeater']); //located in ACF add-on
               
               return ALM_NOSCRIPT::render($output, $container, '', $css_classes, $transition_container_classes);
            }
         }
         
         
         // Standard ALM
         else {  
            
            
            // Build the $args array to use with this WP_Query
            $query_args = ALM_QUERY_ARGS::alm_build_queryargs($q, false);
            
            
            /*
         	 *	alm_query_args_[id]
         	 *
         	 * ALM Core Filter Hook
         	 *
         	 * @return $query_args;
         	 */
            $query_args = apply_filters('alm_query_args_'.$q['id'], $query_args, $q['post_id']);
            
            
            // Get Per Page param                     
            $posts_per_page = $query_args['posts_per_page'];
            
            
            // Get Repeater Template type
            $type = alm_get_repeater_type($q['repeater']);
            
            
            // Update offset   
            $query_args['paged'] = $paged;
            $query_args['offset'] = ALM_NOSCRIPT::set_offset($paged, $posts_per_page, $q['offset']);                  
                        
            $output = '';
            $i = 0;
            
            $noscript_query = new WP_Query($query_args);
            
            if($noscript_query->have_posts()) :    
            
               $alm_found_posts = $noscript_query->found_posts;
               $alm_page = $paged;           
               
               while ($noscript_query->have_posts()) : $noscript_query->the_post();
                  $i++;
                  $alm_current = $i;
                  $alm_item = $query_args['offset'] + $i;
                  
      	   	   $output .= alm_loop($q['repeater'], $type, $q['theme_repeater'], $alm_found_posts, $alm_page, $alm_item, $alm_current);  
      
               endwhile; wp_reset_query();
               
            endif;     
            
            $paging = ALM_NOSCRIPT::build_noscript_paging($noscript_query); 
                  
            return ALM_NOSCRIPT::render($output, $container, $paging, $css_classes, $transition_container_classes);                        
            
         }
         
      }      



		/*
		*  alm_paging_no_script
		*  Create paging navigation
		*
		*  @return html;
		*  @updated 3.7
		*  @since 2.8.3
		*/
		public static function build_noscript_paging($query){	
			$paged = get_query_var('paged');
			if(empty(get_query_var('paged')) || get_query_var('paged') == 0) {
		      $paged = 1;
		   }
		   $numposts = $query->found_posts;
		   $max_page = $query->max_num_pages;   
		   $pages_to_show = 8;
		   $pages_to_show_minus_1 = $pages_to_show-1;
		   $half_page_start = floor($pages_to_show_minus_1/2);
		   $half_page_end = ceil($pages_to_show_minus_1/2);
		   $start_page = $paged - $half_page_start;
		   if($start_page <= 0) {
		      $start_page = 1;
		   }
		   $end_page = $paged + $half_page_end;
		   if(($end_page - $start_page) != $pages_to_show_minus_1) {
		      $end_page = $start_page + $pages_to_show_minus_1;
		   }
		   if($end_page > $max_page) {
		      $start_page = $max_page - $pages_to_show_minus_1;
		      $end_page = $max_page;
		   }
		   if($start_page <= 0) {
		      $start_page = 1;
		   }
		   $content = '';
		   if ($max_page > 1) {
		      $content .= '<div class="alm-paging" style="opacity: 1">';
		      $content .= __('Pages: ', 'ajax-load-more');
		      if ($start_page >= 2 && $pages_to_show < $max_page) {
		         $first_page_text = "&laquo;";
		         $content .= '<span class="page"><a href="'.get_pagenum_link().'">'.$first_page_text.'</a></span>';
		      }
		      // Loop pages
		      for($i = $start_page; $i  <= $end_page; $i++) {
		         $content .= ' <span class="page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
		   	}
		   	
			   if ($end_page < $max_page) {
			      $last_page_text = "&raquo;";
			      $content .= '<span><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></span>';
			   }
		      $content .= '</div>';
		   }
		   
		   return $content;
		}
      
      
      
      /*
	    * render
	    * This function will return the HTML output of the <noscript/>
   	 *
   	 * @since            1.8
   	 * @param $output    string
   	 * @param $container string
   	 * @param $paging		string
   	 * @return           <noscript>
   	 */ 
      public static function render($output, $container, $paging = '', $css_classes, $transition_container_classes){
	      return (!empty($output)) ? '<'. self::$element .'><'. $container .' class="alm-listing alm-noscript'. $css_classes .'"><div class="alm-reveal'. $transition_container_classes .'">'. $output .'</div></'. $container .'>'. $paging .'</'. self::$element .'>' : '';	      
      }    
      
      
      
      /*
	    * set_offset
	    * This function will set the offset of the noscript query
   	 *
   	 * @since         	1.8
   	 * @param $paged 		string
   	 * @param $per_page 	string
   	 * @param $offset 	string
   	 * @return        	int
   	 */
      public static function set_offset($paged, $per_page, $offset){
	      return ($paged * $per_page) - $per_page + $offset;
      }          
   
   }
      
endif;
