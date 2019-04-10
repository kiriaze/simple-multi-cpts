<?php
/*
Plugin Name:     Simple Multi CPTS
Plugin URI:
Description:     Simple multi custom post types/tax plugin yo.
Version:         1.0.0
Author:          Constantine Kiriaze (@kiriaze)
Author URI:      http://getsimple.io/about
License:         GNU General Public License v2 or later
License URI:     http://www.gnu.org/licenses/gpl-2.0.html
Copyright:       (c) 2013, Constantine Kiriaze
Text Domain:     simple
*/

/*
	Copyright (C) 2013  Constantine Kiriaze (hello@kiriaze.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// load simple multi cpt acf settings - if acf exists/active
require_once( plugin_dir_path( __FILE__ ) . 'acf-dependencies.php' );

//  Wrapped in after_setup_theme to utilize options
add_action('after_setup_theme', 'simple_multi_cpts_plugin_init', 12);
function simple_multi_cpts_plugin_init(){

	global
	$acf,
	$plugin_name,
	$prefix,
	$plugin_url,
	$plugin_path,
	$plugin_basename,
	$cpt_slug,
	$cpt_name,
	$cpt_name_singular,
	$cpt_tax,
	$cpt_tax_singular,
	$cats_and_tags,
	$heirarchial,
	$has_archive,
	$rewriteUrl,
	$hide,
	$cpt_icon,
	$cpt_supports,
	$defaultStyles,
	$child_cpts;

	//  Define Globals
	$plugin_name        =   'Simple Multi CPTS';   // change this - always prefix e.g. Simple Multi CPTS

	// Required: post type plural - e.g. People
	$cpt_name           =   [];

	// Required: post type singular - e.g. Person
	$cpt_name_singular  =   [];

	// Optional: post type custom tax plural - e.g. Hobbies
	$cpt_tax            =   [];

	// Optional: post type custom tax singular - e.g. Hobby
	$cpt_tax_singular   =   [];

	// Optional: post type rewrite slug - e.g. People
	$rewriteUrl         =   [];

	// Optional: post type columns to hide - all default to true
	$hide               =   [];

	// Optional: post type icons, e.g. unicode stripped to \f037
	$cpt_icons          =   [];

	// Optional: defaults to all currently
	$cpt_supports       =   [];

	// allow filtering in child themes
	$cpt_name   =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_name)[0] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_name)[0]
		: [];
	$cpt_name_singular =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_name_singular)[1] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_name_singular)[1]
		: [];
	$cpt_tax    =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2]
		: [];
	$cpt_tax_singular =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_tax_singular)[3] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_tax_singular)[3]
		: [];
	$rewriteUrl =
		isset( apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[4] )
		? apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[4]
		: [];
	$hide       =
		isset( apply_filters('simple_multi_cpts_plugin_init', $hide)[5] )
		? apply_filters('simple_multi_cpts_plugin_init', $hide)[5]
		: [];
	$cpt_icon   =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[6] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[6]
		: [];
	$cpt_supports =
		isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_supports)[7] )
		? apply_filters('simple_multi_cpts_plugin_init', $cpt_supports)[7]
		: [];


	if ( class_exists('acf') ) :

		// ACF Settings Field
		$cpt = get_field('custom_post_type', 'option');

		if ( $cpt ) :

			while ( has_sub_field('custom_post_type', 'option') ) :
				
				$cpt_name[]          = ucfirst( get_sub_field('cpt_name') );
				$cpt_name_singular[] = ucfirst( get_sub_field('cpt_name_singular') );
				$rewriteUrl[]        = ucfirst( get_sub_field('rewrite_url') );
				$cats_and_tags[]     = get_sub_field('enable_cats_tags');
				$heirarchial[]       = get_sub_field('enable_heirarchial');
				$has_archive[]       = get_sub_field('enable_archive');
				$cpt_supports[]      = get_sub_field('supports');
				$cpt_icon[]          = get_sub_field('cpt_icon') ? get_sub_field('cpt_icon') : '';
				
				$tax_array           = [];
				$tax_single_array    = [];
				$hide_array          = [];

				while ( has_sub_field('cpt_tax', 'option') ) :

					$tax_array[]        = ucfirst( get_sub_field('tax_name') );
					$tax_single_array[] = ucfirst( get_sub_field('tax_name_singular') );
					$hide_array[]       = get_sub_field('hide_tax');

				endwhile;

				$cpt_tax[]          = $tax_array;
				$cpt_tax_singular[] = $tax_single_array;
				$hide[]             = $hide_array;

			endwhile;

		endif;
	endif;

	$rewriteUrl         =   preg_replace( "/\W/", "-", array_map('strtolower', $rewriteUrl) );
	$plugin_name        =   preg_replace( "/\W/", "-", strtolower($plugin_name) );
	$prefix             =   preg_replace( "/\W/", "_", strtolower($plugin_name) );
	$plugin_url         =   plugin_dir_url( __FILE__ );
	$plugin_path        =   plugin_dir_path( __FILE__ );
	$plugin_basename    =   plugin_basename( __FILE__ );

	//  Set globals if constants not defined
	$cpt_slug           = preg_replace("/\W/", "-", array_map('strtolower', $cpt_name) );

	// sp($cats_and_tags);
	// sp($heirarchial);
	// sp($has_archive);
	// sp($cpt_name);
	// sp($cpt_name_singular);
	// sp($cpt_tax);
	// sp($rewriteUrl);
	// sp($hide);
	// sp($cpt_icon);
	// sp($cpt_supports);

	// $result         = array();
	// foreach ( $cpt_slug as $key => $value ) {

	//     $result[$value] = array(
	//         'cpt_name_singular' => $cpt_name_singular[$key],
	//         'rewriteUrl' => $rewriteUrl[$key],
	//         'cpt_tax'    => $cpt_tax[$key],
	//         'hide'       => $hide[$key],
	//         'cpt_icon'   => $cpt_icon[$key]
	//     );

	// }
	// sp($result);

	//  Load class
	require_once( $plugin_path . 'class-'.$plugin_name.'.php' );
}