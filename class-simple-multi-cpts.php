<?php
/**
* Simple Multi CPTS.
*
* @package   Simple_Multi_Cpts_Post_Type
* @author    Constantine Kiriaze, hello@kiriaze.com
* @license   GPL-2.0+
* @link      http://getsimple.io
* @copyright 2013 Constantine Kiriaze
*
*
*/


if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Simple_Multi_Cpts_Post_Type' ) ) :

	class Simple_Multi_Cpts_Post_Type {

	    function __construct() {

	        //  Grab globals passed from init
	        global $cpt_slug, $cpt_name, $cpt_plural, $cpt_tax, $heirarchial, $has_archive, $rewriteUrl, $defaultStyles;

	        //  Set them relative to function
	        $this->cpt_slug = $cpt_slug;
	        $this->cpt_name = $cpt_name;
	        $this->cpt_plural = $cpt_plural;
	        $this->cpt_tax = $cpt_tax;
	        $this->heirarchial = $heirarchial;
	        $this->has_archive = $has_archive;
	        $this->rewrite = $rewriteUrl;
	        $this->defaultStyles = $defaultStyles;

	        //  Plugin Activation
	        register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

	        //  Translation
	        load_plugin_textdomain( 'simple', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	        //  Thumbnails
			add_theme_support( 'post-thumbnails' );

	        //  CPT
	        add_action( 'init', array( &$this, 'cpt_init' ) );

			//  Columns
			// $count = 0;
	  //   	foreach ( $cpt_slug as $cpt ) {
			// 	add_filter( 'manage_edit-'.$cpt.'_columns', array( &$this, 'add_cpt_columns') );
			// 	add_action( 'manage_'.$cpt.'_posts_custom_column', array( &$this, 'display_cpt_columns' ) );
			// 	add_filter( 'manage_edit-'.$cpt.'_sortable_columns', array( &$this, 'cpt_column_register_sortable' ) );
			// 	$count++;
			// }

			//  Tax Filters
			// add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filters' ) );

			//  Add Dashboard Counter
			add_action( 'right_now_content_table_end', array( &$this, 'add_cpt_counts' ) );

			//  Add Icon
			add_action( 'admin_head', array( &$this, 'cpt_icon' ) );

			//  Custom Thumbnail Sizes
			add_action( 'after_setup_theme', array($this, 'custom_thumbnail_size') );

			// Scripts / Styles
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts' ) );

	    }

	    //  Flush Rewrite Rules
	    function plugin_activation() {
	        flush_rewrite_rules();
	    }

	    //  CUSTOM ICON FOR POST TYPE
	    function cpt_icon() {
	        wp_enqueue_style( 'admin-simple-multi-cpts-css', plugins_url( 'assets/css/admin-simple-multi-cpts.css', __FILE__ ) );
	    }

	    //  Load Template Styles (front end styles)
	    function load_styles() {
	        // if ( is_post_type_archive($this->cpt_slug) )
	        // foreach ( $this->cpt_slug as $cpt ) {
	        // 	wp_enqueue_style( $cpt .'-template', plugins_url( 'assets/css/'. $cpt .'.css', __FILE__ ) );
	        // }
	    }

	    // Load scripts
	    function load_scripts() {
	        wp_enqueue_script( 'admin-simple-multi-cpts-js', plugins_url( 'assets/js/admin.js', __FILE__ ) );
	    }

	    //  Custom Thumbnail Size
	    function custom_thumbnail_size() {
	        // sp($this->cpt_slug);
	        foreach ( $this->cpt_slug as $cpt ) {
	        	add_image_size($cpt . '-thumb', 100, 100, true); // 100px x 100px with hard crop enabled
	        }
	    }

	    //  Posttype / Taxonomy Registration
		function cpt_init() {

	        // Register cpt / tax
			$count			= 0;
			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_plural		= $this->cpt_plural;
			$heirarchial	= $this->heirarchial;
			$has_archive	= $this->has_archive;
			$cpt_tax		= $this->cpt_tax;
			$post_types		= '';
			$taxonomies		= '';

			//  Rewrite checking values and serializing rewrite array
			$rewrite		= $this->rewrite;
			$fields			= array( 'slug' );

			foreach ( $cpt_slug as $cpt ) {

				if ( isset($rewrite[$count]) ) {
					$str                		= "$rewrite[$count]";
					$rewrite[$count]            = ( $rewrite[$count] != 'false' ) ? serialize(array_combine( $fields, explode ( ", ", $str ) )) : 'false';
					$rewrite[$count] 			= unserialize($rewrite[$count]);
					// sp($rewrite[$count]);
				}

				$post_types[] = array(
					$cpt =>  array(
		                'labels'                    => array(
		                    'name'                      => __( $cpt_plural[$count] ),
		                    'singular_name'             => __( $cpt_name[$count] ),
		                    'add_new'                   => __( 'Add New ' . $cpt_name[$count] ),
		                    'add_new_item'              => __( 'Add New ' . $cpt_name[$count] ),
		                    'edit_item'                 => __( 'Edit ' . $cpt_name[$count] ),
		                    'new_item'                  => __( 'Add New ' . $cpt_name[$count] ),
		                    'view_item'                 => __( 'View ' . $cpt_name[$count] ),
		                    'search_items'              => __( 'Search ' . $cpt_plural[$count] ),
		                    'not_found'                 => __( 'No '. $cpt_plural[$count] . ' found' ),
		                    'not_found_in_trash'        => __( 'No '. $cpt_plural[$count] . ' found in trash' )
		                ),
		                'public'                    => true,
		                'supports'                  => array( 'title', 'editor','thumbnail'),
		                'capability_type'           => 'post',
		                'menu_position'             => '15',
						'hierarchical'              => $heirarchial,
						'has_archive'               => $has_archive,
						'rewrite'                   => isset($rewrite[$count]) ? $rewrite[$count] : '',
						'taxonomies' 				=> array('category', 'post_tag') // this is IMPORTANT
		            ),
				);

				// taxes
				global $taxonomies;
		        $taxonomies[] = array(

		            $cpt . '_tag_labels'         => array(
		                'object_type'                   => $cpt,
		                'label'                         => $cpt_name[$count]. ' Tags',
		                'labels'                        => array(
		                        'name'                      => $cpt_name[$count]. ' Tags',
		                        'singluar_name'             => substr_replace( $cpt_name[$count]. ' Tags', "", -1 ),
		                    ),
		                'public'                        => true,
		                'show_in_nav_menus'             => false,
		                'show_ui'                       => true,
		                'show_tagcloud'                 => false,
		                'hierarchical'                  => true,
		                'rewrite'                       => array('slug' => $cpt . '_tag'),
		                'link_to_post_type'             => false,
		                'post_type_link'                => null,
		                'has_archive'                   => true
		            ),

		            $cpt . '_category_labels'    => array(
		                'object_type'                   => $cpt,
		                'label'                         => $cpt_name[$count]. ' Categories',
		                'labels'                        => array(
		                        'name'                      => $cpt_name[$count]. ' Categories',
		                        'singluar_name'             => substr_replace( $cpt_name[$count]. ' Categories', "", -1 ),
		                    ),
		                'public'                        => true,
		                'show_in_nav_menus'             => false,
		                'show_ui'                       => true,
		                'show_tagcloud'                 => false,
		                'hierarchical'                  => true,
		                'rewrite'                       => array('slug' => $cpt . '_category'),
		                'link_to_post_type'             => false,
		                'post_type_link'                => null,
		                'has_archive'                   => true
		            ),

		        );

				// conditional check if custom tax set
				if ( isset($cpt_tax[$count]) ) :
					$custom_tax[] = array(
						preg_replace("/\W/", "_", strtolower($cpt_tax[$count]) )    => array(
								'object_type'                   => $cpt_slug[$count],
								'label'                         => $cpt_tax[$count],
								'labels'                        => array(
								'name'                      => $cpt_tax[$count],
								'singluar_name'             => substr_replace( $cpt_tax[$count].'s', "", -1 ),
							),
							'public'                        => true,
							'show_in_nav_menus'             => false,
							'show_ui'                       => true,
							'show_tagcloud'                 => false,
							'hierarchical'                  => true,
							'rewrite'                       => array('slug' => preg_replace("/\W/", "-", strtolower($cpt_tax[$count]) ) ),
							'link_to_post_type'             => false,
							'post_type_link'                => null,
							'has_archive'                   => true
						)
					);

					$taxonomies = array_merge($taxonomies, $custom_tax);

				endif;

				$count++;

			}

			// cpts
			if ( $post_types ) {
				foreach ( $post_types as $post_type ) {
					foreach ( $post_type as $key => $value ) {
						// sp($key);
						// sp($value);
				    	register_post_type( $key, $value );
					}
				}
			}

			// taxes
	        global $association_array;
	        $association_array = array();

	        if ( $taxonomies ) {

		        foreach ( $taxonomies as $taxonomy ) {

		        	foreach ( $taxonomy as $key => $value ) {

						// sp($key);
						// sp($value);

			            register_taxonomy( $key, $value['object_type'], $value );

			            if ( $value['link_to_post_type'] )
			            	$association_array[$taxonomy] = $value['post_type_link'];

		        	}

		        }

	        }

	    }









	    //  Add Columns
	    function add_cpt_columns( $columns ) {

	    	// $count			= 0;
			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	    	// foreach ( $cpt_slug as $cpt ) {

		        $columns = array(
		            'cb'            => '<input type="checkbox" />',
		            'title'         => __( 'Name' ),
		            'thumbnail'     => __( 'Thumbnail' ),
		            $cpt_slug[0] . '_categories'    => __( $cpt_name[0] . ' Categories' ),
		            $cpt_slug[0] . '_tags'          => __( $cpt_name[0] . ' Tags' ),
		            'date'          => __( 'Date' )
		        );

		        if( $cpt_tax[0] ) :
		            array_push($columns, $cpt_tax[0]);
		        endif;

		        return $columns;

		        // $count++;

	    	// }
	    }

	    //  Add thumbnail to column
	    function display_cpt_columns( $column ) {

	        global $post;

	        // $count			= 0;
			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	        // foreach ( $cpt_slug as $cpt ) {

		        switch ( $column ) {

		            case 'thumbnail':
		                $thumb = get_the_post_thumbnail( $post->ID, array(35, 35) );
		                echo $thumb;
		                break;

		            case $cpt_tax[0]:
		                do_action('simple_list_terms', $cpt_tax[0]);
		            break;

		            case $cpt_slug[0] . '_categories':
		                do_action('simple_list_terms', $cpt_slug[0] . '_category_labels');
		            break;

		            case $cpt_slug[0] . '_tags':
		                do_action('simple_list_terms', $cpt_slug[0] . '_tag_labels');
		            break;

		            // Just break out of the switch statement for everything else.
		            default :
		                break;
		        }

		        // $count++;

		    // }

	    }

	    //  Register the column as sortable
	    function cpt_column_register_sortable( $columns ) {

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	        $columns['thumbnail']   					= 'thumbnail';
	        $columns[$cpt_slug[0] . '_categories']  	= $cpt_slug[0] . '_categories';
	        $columns[$cpt_slug[0] . '_tags']        	= $cpt_slug[0] . '_tags';

	        if ( $cpt_tax[0] ) :
	            array_push($columns, $cpt_tax[0]);
	        endif;

	        return $columns;

	    }

	    //  Add Tax Filter Dropdowns to the Admin - http://pippinsplugins.com
	    function add_taxonomy_filters() {

	        global $typenow;

	        $count			= 0;
			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	    	foreach ( $cpt_slug as $cpt ) {

		        //  Use tax name or slug
		        $taxonomies = array( $cpt . '_category_labels', $cpt . '_tag_labels', $cpt_tax );

		        if ( $cpt_tax ) :
		            array_push($taxonomies, $cpt_tax);
		        endif;

		        //  Post type for filter
		        if ( $typenow == $cpt ) {

		            foreach ( $taxonomies as $tax_slug ) {

		            	if ( !empty($tax_slug) ) :
			                $current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			                $tax_obj = get_taxonomy( $tax_slug );
			                $tax_name = $tax_obj->labels->name;
			                $terms = get_terms($tax_slug, 'hide_empty=0');
			                if ( count( $terms ) > 0) {
			                    echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			                    echo "<option value=''>$tax_name</option>";
			                    foreach ( $terms as $term ) {
			                        echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
			                    }
			                    echo "</select>";
			                }
			            endif;
		            }

		        }

		        $count++;

		    }

	    }








	}

	new Simple_Multi_Cpts_Post_Type;

endif;