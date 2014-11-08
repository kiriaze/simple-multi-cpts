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

//  Wrapped in after_setup_theme to utilize options
add_action('after_setup_theme', 'simple_multi_cpts_plugin_init', 12);
function simple_multi_cpts_plugin_init(){

    global $plugin_name, $prefix, $plugin_url, $plugin_path, $plugin_basename, $cpt_slug, $cpt_name, $cpt_plural, $cpt_tax, $heirarchial, $has_archive, $rewriteUrl, $defaultStyles, $child_cpts;

    //  Define Globals
    $plugin_name        =   'Simple Multi CPTS';   // change this - always prefix e.g. Simple Multi CPTS

    // Required: post type singular - e.g. Person
    $cpt_name           =   array(
                                // '',
                            );

    // Required: post type plural - e.g. People
    $cpt_plural         =   array(
                                // '',
                            );

    // Optional: post type custom tax - e.g. Hobbies
    $cpt_tax            =   array(
                                // '',
                            );

    // Optional: post type rewrite slug - e.g. People
    $rewriteUrl         =   array(
                                // '',
                            );


    // allow filtering in child themes
    $cpt_name   = isset(apply_filters('simple_multi_cpts_plugin_init', $cpt_name)[0]) ? apply_filters('simple_multi_cpts_plugin_init', $cpt_name)[0] : [];
    $cpt_plural = isset(apply_filters('simple_multi_cpts_plugin_init', $cpt_plural)[1]) ? apply_filters('simple_multi_cpts_plugin_init', $cpt_plural)[1] : [];
    $cpt_tax    = isset(apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2]) ? apply_filters('simple_multi_cpts_plugin_init', $cpt_tax)[2] : [];
    $rewriteUrl = isset(apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[3]) ? apply_filters('simple_multi_cpts_plugin_init', $rewriteUrl)[3] : [];
    $rewriteUrl = array_map('strtolower', $rewriteUrl);

    // sp($cpt_name);
    // sp($cpt_plural);
    // sp($cpt_tax);
    // sp($rewriteUrl);

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