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
			$count = 0;
	    	foreach ( $cpt_slug as $cpt ) {
				add_filter( 'manage_edit-'.$cpt.'_columns', array( &$this, 'add_cpt_columns') );
				add_action( 'manage_'.$cpt.'_posts_custom_column', array( &$this, 'display_cpt_columns' ) );
				add_filter( 'manage_edit-'.$cpt.'_sortable_columns', array( &$this, 'cpt_column_register_sortable' ) );
				$count++;
			}

			//  Tax Filters
			add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filters' ) );

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
				if ( isset($cpt_tax[$count]) && !empty($cpt_tax[$count]) ) :


					// // sp($cpt_tax);
					// if ( is_array($cpt_tax) ) {
					// 	foreach ($cpt_tax as $key => $value) {
					// 		// sp($value);
					// 		if ( !is_array($value) ) {
					// 			sp($value);
					// 		} else {
					// 			foreach ($value as $key => $value) {
					// 				sp($value);
					// 			}
					// 		}
					// 	}
					// }

					if ( !is_array( $cpt_tax[$count] ) ) {
						// sp($cpt_tax);
						$label = preg_replace("/\W/", "_", strtolower($cpt_tax[$count]) );
						$custom_tax[] = array(
							$label    => array(
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
					} else {
						foreach ( $cpt_tax[$count] as $cpt_tax ) {
							$label = preg_replace("/\W/", "_", strtolower($cpt_tax) );
							// sp($cpt_tax);
							// sp($label);
							$custom_tax[] = array(
								$label    => array(
										'object_type'                   => $cpt_slug[$count],
										'label'                         => $cpt_tax,
										'labels'                        => array(
										'name'                      => $cpt_tax,
										'singluar_name'             => substr_replace( $cpt_tax.'s', "", -1 ),
									),
									'public'                        => true,
									'show_in_nav_menus'             => false,
									'show_ui'                       => true,
									'show_tagcloud'                 => false,
									'hierarchical'                  => true,
									'rewrite'                       => array('slug' => preg_replace("/\W/", "-", strtolower($cpt_tax) ) ),
									'link_to_post_type'             => false,
									'post_type_link'                => null,
									'has_archive'                   => true
								)
							);
						}
					}


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

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	        $columns = array(
				'cb'                         => '<input type="checkbox" />',
				'title'                      => __( 'Name' ),
				'thumbnail'                  => __( 'Thumbnail' ),
	        );

			$columns[ get_post_type() . '_categories'] = __( ucfirst( get_post_type() . ' Categories') );
			$columns[ get_post_type() . '_tags']       = __( ucfirst( get_post_type() . ' Tags') );

			$check = array();

			foreach ( $cpt_slug as $key => $value ) {
				$check[] = $value;
			}

			foreach ( $cpt_tax as $key => $value ) {

				if ( $key == array_search(get_post_type(), $check) ) {

					$columns[$value] = __( ucfirst($cpt_tax[array_search(get_post_type(), $check)]) );

					if ( is_array($cpt_tax) ) {
						foreach ( $cpt_tax as $key => $value ) {
							if ( is_array($value) ) {
								foreach ($value as $key => $value) {
									// sp($value);
									$columns[$value] = __( ucfirst($value) );
								}
							}
						}
					}

				}

			}

			$columns['date']       = __( 'Date' );

	        return $columns;

	    }

	    //  Add data to column
	    function display_cpt_columns( $column ) {

	        global $post;

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	        if ( $column == 'thumbnail' ) {
				$thumb = get_the_post_thumbnail( $post->ID, array(35, 35) );
				echo $thumb;
	        }

	        foreach ( $cpt_slug as $cpt_slug ) {

	        	if ( $column == $cpt_slug . '_categories' ) {
					$args = array(
						'taxonomy' => $cpt_slug . '_category_labels',
						'postID'   => $post->ID
					);
					do_action('simple_list_terms', $args);
	        	}

				if ( $column == $cpt_slug . '_tags' ) {
					$args = array(
						'taxonomy' => $cpt_slug . '_tag_labels',
						'postID'   => $post->ID
					);
					do_action('simple_list_terms', $args);
				}


	        }

			if ( is_array($cpt_tax) ) {
				// sp($cpt_tax);
				foreach ( $cpt_tax as $key => $value ) {
					if ( !is_array($value) ) {
						// sp($value);
						if ( $column == $value ) :
							$args = array(
								'taxonomy' => strtolower($value),
								'postID'   => $post->ID
							);
							do_action('simple_list_terms', $args);
						endif;
					} else {
						foreach ($value as $key => $value) {
							// sp($value);
							if ( $column == $value ) :
								$args = array(
									'taxonomy' => strtolower($value),
									'postID'   => $post->ID
								);
								do_action('simple_list_terms', $args);
							endif;
						}
					}
				}
			}

	    }

	    //  Register the column as sortable
	    function cpt_column_register_sortable( $columns ) {

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

	        foreach ( $cpt_slug as $cpt_slug ) {
		        $columns[$cpt_slug . '_categories']  	= $cpt_slug . '_categories';
		        $columns[$cpt_slug . '_tags']        	= $cpt_slug . '_tags';
	        }

			if ( is_array($cpt_tax) ) {
				foreach ($cpt_tax as $key => $value) {
					if ( !is_array($value) ) {
						// sp($value);
						$columns[$value] = __( ucfirst($value) );
					} else {
						foreach ( $value as $key => $value ) {
							// sp($value);
							$columns[$value] = __( ucfirst($value) );
						}
					}
				}
			}

	        $columns['thumbnail']   					= 'thumbnail';

	        return $columns;

	    }

	    //  Add Tax Filter Dropdowns to the Admin - http://pippinsplugins.com
	    function add_taxonomy_filters() {

	        global $typenow, $taxonomies;

	        $count			= 0;
			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

			// $taxonomies = [];

			$check = array();

			foreach ( $cpt_slug as $key => $value ) {
				$check[] = $value;
			}

			foreach ( $cpt_tax as $key => $value ) {

				if ( isset( $check[$key] ) ) {

					// var_dump($key);
					// var_dump($value);
					// var_dump($check[$key]);

					if ( $check[$key] == get_post_type() ) {
						// var_dump($value);
						// Use tax name or slug
		        		$taxonomies[] = $check[$key] . '_category_labels';
		        		$taxonomies[] = $check[$key] . '_tag_labels';
						if ( is_array($value) ) {
							foreach ($value as $key => $value) {
								if ( !is_array($value) ) {
									// sp($value);
									$taxonomies[] = preg_replace("/\W/", "_", strtolower($value) );
								} else {
									foreach ($value as $key => $value) {
										// sp($value);
										$taxonomies[] = preg_replace("/\W/", "_", strtolower($value) );
									}
								}
							}
						}
					}

				}

			}

			foreach ( $taxonomies as $tax ) {

				if ( !empty($tax) ) :

			        $current_tax = isset( $tax ) ? $tax : false;

					if ( $tax ) {
						if ( is_array($tax) ) {
							$tax = array_keys($tax);
							foreach ( $tax as $tax ) {
								$tax_obj = get_taxonomy( strtolower($tax) );
							}
						} else {
							$tax_obj = get_taxonomy( strtolower($tax) );
						}
					}

			        if ( is_array($tax_obj) ) {
						foreach ($tax_obj as $key => $value) {
							if ( !is_array($tax_obj) ) {
								// sp($value);
								$tax_name = $value;
							} else {
								foreach ($tax_obj as $key => $value) {
									// sp($value);
									$tax_name = $value;
								}
							}
						}
					} else {
			        	$tax_name = $tax_obj->labels->name;
					}

					$termArgs = array(
						'hide_empty' => 0,
						'post_type' => get_post_type(),
					);

					$terms = get_terms($tax, $termArgs);

					if ( count( $terms ) > 0 ) {
					    echo "<select name='$tax' id='$tax' class='postform'>";
					    echo "<option value=''>$tax_name</option>";
					    foreach ( $terms as $term ) {
					        echo '<option value=' . $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					    }
					    echo "</select>";
					}

			    endif;

		    }

	    }

	}

	new Simple_Multi_Cpts_Post_Type;

endif;