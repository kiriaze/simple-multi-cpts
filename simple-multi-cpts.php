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

// Setup ACF
// Check if plugin is activated, if not set up lite version included with simple or fallback to plugin acf
if ( ! class_exists('Acf') ) {
    define( 'ACF_LITE' , true );
    include_once( plugin_dir_path(__DIR__) . '/advanced-custom-fields-pro/acf.php' );
}

// load simple multi cpt acf settings
require_once( plugin_dir_path( __FILE__ ) . 'simple-multi-acf.php' );

// Simple Multi Custom Post Type Settings Page
if ( function_exists('acf_add_options_sub_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'SMCPT Settings',
        'menu_title'    => 'SMCPT Settings',
        'menu_slug'     => 'smcpt-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

}

// acf settings icon font awesome plugin
add_action( 'tgmpa_register', 'simple_multi_cpts_require_plugins' );
function simple_multi_cpts_require_plugins() {

    $plugins = array(
        array(
            'name'          => 'Advanced Custom Fields: Font Awesome',
            'slug'          => 'advanced-custom-fields-font-awesome',
            'required'      => true,
            'force_activation' => true, // activate this plugin when the user switches to another theme
            'force_deactivation' => true, // deactivate this plugin when the user switches to another theme
        )
    );

    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'notice_can_install_required'     => _n_noop( 'This plugin requires the following plugin: %1$s.', 'This plugin requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This plugin recommends the following plugin: %1$s.', 'This plugin recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
        )
    );

    tgmpa( $plugins, $config );

}

//  Wrapped in after_setup_theme to utilize options
add_action('after_setup_theme', 'simple_multi_cpts_plugin_init', 12);
function simple_multi_cpts_plugin_init(){

    global
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
    $rewriteUrl = array_map('strtolower', $rewriteUrl);
    $hide       =
        isset( apply_filters('simple_multi_cpts_plugin_init', $hide)[4] )
        ? apply_filters('simple_multi_cpts_plugin_init', $hide)[4]
        : [];
    $cpt_icon   =
        isset( apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[5] )
        ? apply_filters('simple_multi_cpts_plugin_init', $cpt_icon)[5]
        : [];


    // ACF Settings Field
    $cpt = get_field('custom_post_type', 'option');

    if ( $cpt ) :

        while ( has_sub_field('custom_post_type', 'option') ) :

            $cpt_name[]    = ucfirst( get_sub_field('cpt_name') );
            $cpt_plural[]  = ucfirst( get_sub_field('cpt_plural') );
            $rewriteUrl[]  = ucfirst( get_sub_field('rewrite_url') );
            $hide[]        = get_sub_field('hide_cpt');
            $cpt_icon[]    = get_sub_field('cpt_icon') ? '\\' . substr(get_sub_field('cpt_icon'), 3, -1) : '';

            $cpt_array = [];

            while ( has_sub_field('cpt_tax', 'option') ) :

                $cpt_array[] = ucfirst( get_sub_field('tax_name') );

            endwhile;

            $cpt_tax[] = $cpt_array;


        endwhile;

    endif;

    // sp($cpt_name);
    // sp($cpt_plural);
    // sp($cpt_tax);
    // sp($rewriteUrl);
    // sp($hide);
    // sp($cpt_icon);

    $plugin_name        =   preg_replace( "/\W/", "-", strtolower($plugin_name) );
    $prefix             =   preg_replace( "/\W/", "_", strtolower($plugin_name) );
    $plugin_url         =   plugin_dir_url( __FILE__ );
    $plugin_path        =   plugin_dir_path( __FILE__ );
    $plugin_basename    =   plugin_basename( __FILE__ );

    //  Set globals if constants not defined
    $cpt_slug           = preg_replace("/\W/", "-", array_map('strtolower', $cpt_name) );

    $heirarchial        = true;
    $has_archive        = true;

    //  Load class
    require_once( $plugin_path . 'class-'.$plugin_name.'.php' );
}