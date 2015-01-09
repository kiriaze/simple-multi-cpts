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
			global
			$cpt_slug,
			$cpt_name,
			$cpt_plural,
			$cpt_tax,
			$heirarchial,
			$has_archive,
			$rewriteUrl,
			$hide,
			$cpt_icon,
			$cpt_supports,
			$defaultStyles;

			//  Set them relative to function
			$this->cpt_slug      = $cpt_slug;
			$this->cpt_name      = $cpt_name;
			$this->cpt_plural    = $cpt_plural;
			$this->cpt_tax       = $cpt_tax;
			$this->heirarchial   = $heirarchial;
			$this->has_archive   = $has_archive;
			$this->rewrite       = $rewriteUrl;
			$this->hide          = $hide;
			$this->icon			 = $cpt_icon;
			$this->supports		 = $cpt_supports;
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

			// Scripts / Styles
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts' ) );

		}

		//  Flush Rewrite Rules
		function plugin_activation() {
			flush_rewrite_rules();
		}

		//  CUSTOM ICON FOR POST TYPE
		function cpt_icon() {
			$count			= 0;
			foreach ( $this->cpt_slug as $key => $value ) {

				$icon = !empty($this->icon[$count]) ? $this->icon[$count] : '\f109';
				$font = !empty($this->icon[$count]) ? 'FontAwesome' : 'dashicons';

				echo '<style>
					#adminmenu #menu-posts-'. $value .' div.wp-menu-image:before {
						font-family: ' . $font . ';
						content: "' . $icon . '";
					}
				</style>';
				$count++;
			}
		}

		// Load scripts
		function load_scripts() {
			wp_enqueue_script( 'admin-simple-multi-cpts-js', plugins_url( 'assets/js/admin.js', __FILE__ ) );
			wp_enqueue_style( 'admin-simple-multi-cpts', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );

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
			$hide			= $this->hide;
			$cpt_supports	= $this->supports;
			$post_types		= '';
			$taxonomies		= '';

			//  Rewrite checking values and serializing rewrite array
			$rewrite		= $this->rewrite;
			$fields			= array( 'slug' );

			$check = array();

			foreach ( $cpt_slug as $key => $value ) {

				$check[] = $value;

				if ( isset($rewrite[$count]) ) {
					$str                		= "$rewrite[$count]";
					$rewrite[$count]            = ( $rewrite[$count] != 'false' ) ? serialize(array_combine( $fields, explode ( ", ", $str ) )) : 'false';
					$rewrite[$count] 			= unserialize($rewrite[$count]);
				}

				$post_types[] = array(
					$value =>  array(
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
						'supports'                  => $cpt_supports[$count],
						'capability_type'           => 'post',
						'menu_position'             => '15',
						'hierarchical'              => $heirarchial[$count],
						'has_archive'               => $has_archive[$count],
						'rewrite'                   => isset($rewrite[$count]) ? $rewrite[$count] : '',
						'taxonomies' 				=> array( 'category', 'post_tag') // this is IMPORTANT
					),
				);

				$count++;

			}

			foreach ( $cpt_tax as $key1 => $value1 ) :

				if ( array_intersect_key($cpt_tax, $check) ) :

					// taxes
					global $taxonomies;

					// sp($key1);
					// sp($check[$key1]);

					$tax_name = ucfirst($check[$key1]);

					$taxonomies[] = array();

					// NOTE: General Tags/Cats that are attached are global, and if unique simply add tax through interface

					if ( isset($value1) && !empty($value1) ) :

						if ( !is_array($value1) ) :

							$label    = preg_replace("/\W/", "_", strtolower($value1) );
							$tax_name = ucfirst($value1);

							// sp($value1);

							$custom_tax[] = array(
								$label    => array(
										'object_type'                   => $check[$key1],
										'label'                         => $tax_name,
										'labels'                        => array(
											'name'                      => $tax_name,
											'singluar_name'             => substr_replace( $tax_name .'s', "", -1 ),
										),
									'public'                        => true,
									'show_in_nav_menus'             => true,
									'show_admin_column'             => true,
									'show_ui'                       => true,
									'show_tagcloud'                 => false,
									'hierarchical'                  => true,
									'rewrite'                       => array(
										'slug' => preg_replace("/\W/", "-", strtolower($value1) )
									),
									'link_to_post_type'             => false,
									'post_type_link'                => null,
									'has_archive'                   => true
								)
							);

						else :

							foreach ( $value1 as $key => $value2 ) {

								$label    = preg_replace("/\W/", "_", strtolower($value2) );
								$tax_name = ucfirst($value2);

								// sp($value2);

								$custom_tax[] = array(
									$label    => array(
											'object_type'                   => $check[$key1],
											'label'                         => $tax_name,
											'labels'                        => array(
												'name'                      => $tax_name,
												'singluar_name'             => substr_replace( $tax_name .'s', "", -1 ),
											),
										'public'                        => true,
										'show_in_nav_menus'             => true,
										'show_admin_column'             => true,
										'show_ui'                       => true,
										'show_tagcloud'                 => false,
										'hierarchical'                  => true,
										'rewrite'                       => array(
											'slug' => preg_replace("/\W/", "-", strtolower($value2) )
										),
										'link_to_post_type'             => false,
										'post_type_link'                => null,
										'has_archive'                   => true
									)
								);

							}


						endif;

						$taxonomies = array_merge($taxonomies, $custom_tax);

					endif;

				endif;

			endforeach;

			// cpts
			if ( $post_types ) {
				foreach ( $post_types as $post_type ) {
					foreach ( $post_type as $key => $value ) {
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

						register_taxonomy( $key, $value['object_type'], $value );

						if ( $value['link_to_post_type'] ) {
							$association_array[$taxonomy] = $value['post_type_link'];
						}

					}

				}

			}

		}

		//  Add Columns
		function add_cpt_columns( $columns ) {

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;
			$hide			= $this->hide;

			foreach ( $columns as $key => $title ) {
				
				if ( $key=='title' ) // Put the Thumbnail column before the title column
					$new_columns['thumbnail'] = 'Thumbnail';
					$new_columns[$key] = $title;
			}

			return $new_columns;

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

		}

		//  Register the column as sortable
		function cpt_column_register_sortable( $columns ) {

			$cpt_slug		= $this->cpt_slug;
			$cpt_name		= $this->cpt_name;
			$cpt_tax		= $this->cpt_tax;

			if ( is_array($cpt_tax) ) {

				foreach ( $cpt_tax as $key => $value ) {

					if ( !is_array($value) ) {

						$termSlug = preg_replace( "/\W/", "_", strtolower($value) );
						$columns['taxonomy-' . $termSlug ] = 'taxonomy-' . $termSlug;

					} else {
						
						foreach ( $value as $key => $value ) {
							$termSlug = preg_replace( "/\W/", "_", strtolower($value) );
							$columns['taxonomy-' . $termSlug ] = 'taxonomy-' . $termSlug;
						}

					}

				}

			}

			$columns['thumbnail']   					= 'thumbnail';

			return $columns;

		}

		//  Add Tax Filter Dropdowns to the Admin - http://pippinsplugins.com
		function add_taxonomy_filters() {

			// auto generate all taxes
			global $typenow;

			$args        = array( 'public' => true, '_builtin' => false );
			$post_types  = get_post_types($args);


			// custom function fix for getting terms tied to post types for add_taxonomy_filters
			function get_terms_by_post_type( $taxonomies, $post_types ) {

				global $wpdb;

				$query = $wpdb->prepare(
					"SELECT t.*, count from $wpdb->terms AS t
					INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
					INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
					INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
					WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
					GROUP BY t.term_id",
					join( "', '", $post_types ),
					join( "', '", $taxonomies )
					);

				$results = $wpdb->get_results( $query );

				return $results;

			}

			if ( in_array( $typenow, $post_types ) ) {

				$filters = get_object_taxonomies($typenow);

				foreach ( $filters as $tax_slug ) {

					$tax_obj     = get_taxonomy($tax_slug);
					$current_tax = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;

					$terms       = get_terms_by_post_type(array($tax_slug), array($typenow));

					if ( count( $terms ) > 0 ) {
						echo "<select name='".$tax_obj->name."' id='".$tax_obj->name."' class='postform'>";
						echo "<option value=''>View all ".$tax_obj->label."</option>";

						foreach ( $terms as $term ) {
							sp($term);
							echo '<option value=' . $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
						}

						echo "</select>";
					}

				}
			}

			// global $typenow, $taxonomies;

			// $count			= 0;
			// $cpt_slug		= $this->cpt_slug;
			// $cpt_name		= $this->cpt_name;
			// $cpt_tax		= $this->cpt_tax;
			// $hide			= $this->hide;

			// $result			= array();

			// foreach ( $cpt_slug as $key => $value ) {

			// 	$result[$value] = array(
			// 		'cpt_tax' => $cpt_tax[$key],
			// 		'hide'    => $hide[$key],
			// 	);

			// }

			// foreach ( $result as $key => $value ) {

			// 	if ( $key == $typenow ) {

			// 		foreach ( $result[$key] as $tax  ) {

			// 			if ( is_array($tax) )

			// 			foreach ( $tax as $key => $value ) {

			// 				if ( $value ) {

			// 					$termSlug = preg_replace( "/\W/", "_", strtolower($value) );

			// 					$current_tax = isset( $_GET[$termSlug] ) ? $_GET[$termSlug] : false;

			// 					$termArgs = array(
			// 						'hide_empty' => 0,
			// 						'post_type'  => $typenow,
			// 					);

			// 					$terms = get_terms($termSlug, $termArgs);

			// 					if ( count( $terms ) > 0 ) {

			// 						echo "<select name='".$termSlug."' id='".$termSlug."' class='postform'>";
			// 						echo "<option value=''>View all ".$value."</option>";

			// 						foreach ( $terms as $term ) {
			// 							echo '<option value=' . $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
			// 						}

			// 						echo "</select>";
			// 					}

			// 				}

			// 			}

			// 		}

			// 	}

			// }

		}

	}

	new Simple_Multi_Cpts_Post_Type;

endif;