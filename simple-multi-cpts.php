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
    $cpt_plural,
    $cpt_tax,
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

    // Required: post type singular - e.g. Person
    $cpt_name           =   [];

    // Required: post type plural - e.g. People
    $cpt_plural         =   [];

    // Optional: post type custom tax - e.g. Hobbies
    $cpt_tax            =   [];

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
    $cpt_plural =
        isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_plural)[1] )
        ? apply_filters('simple_multi_cpts_plugin_init', $cpt_plural)[1]
        : [];
    $cpt_tax    =
        isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2] )
        ? apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2]
        : [];
    $rewriteUrl =
        isset( apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[3] )
        ? apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[3]
        : [];
    $hide       =
        isset( apply_filters('simple_multi_cpts_plugin_init', $hide)[4] )
        ? apply_filters('simple_multi_cpts_plugin_init', $hide)[4]
        : [];
    $cpt_icon   =
        isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[5] )
        ? apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[5]
        : [];
    $cpt_supports = 
        isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_supports)[6] )
        ? apply_filters('simple_multi_cpts_plugin_init', $cpt_supports)[6]
        : [];

    // temp
    $cpt_supports = array(
        'title', 
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats'
    );


    if ( class_exists('acf') ) :

        // ACF Settings Field
        $cpt = get_field('custom_post_type', 'option');

        if ( $cpt ) :

            while ( has_sub_field('custom_post_type', 'option') ) :

                $cpt_name[]    = ucfirst( get_sub_field('cpt_name') );
                $cpt_plural[]  = ucfirst( get_sub_field('cpt_plural') );
                $rewriteUrl[]  = ucfirst( get_sub_field('rewrite_url') );
                $cpt_icon[]    = get_sub_field('cpt_icon') ? '\\' . substr(get_sub_field('cpt_icon'), 3, -1) : '';

                $cpt_array  = [];
                $hide_array = [];

                while ( has_sub_field('cpt_tax', 'option') ) :

                    $cpt_array[]    = ucfirst( get_sub_field('tax_name') );
                    $hide_array[]   = get_sub_field('hide_tax');

                endwhile;

                $cpt_tax[] = $cpt_array;
                $hide[]    = $hide_array;

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

    $heirarchial        = true;
    $has_archive        = true;


    // sp($cpt_name);
    // sp($cpt_plural);
    // sp($cpt_tax);
    // sp($rewriteUrl);
    // sp($hide);
    // sp($cpt_icon);
    // sp($cpt_supports);

    // $result         = array();
    // foreach ( $cpt_slug as $key => $value ) {

    //     $result[$value] = array(
    //         'cpt_plural' => $cpt_plural[$key],
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