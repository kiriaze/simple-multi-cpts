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

    global $plugin_name, $prefix, $plugin_url, $plugin_path, $plugin_basename, $cpt_slug, $cpt_name, $cpt_plural, $cpt_tax, $heirarchial, $has_archive, $rewriteUrl, $defaultStyles;

    //  Define Globals
    $plugin_name        =   'Simple Multi CPTS';   // change this - always prefix e.g. Simple Multi CPTS

    $cpt_name           =   array(
                                // 'Agency',
                                // 'Client',
                                // 'Team',
                                // 'Integration',
                                // 'White Paper',
                                // 'Data Sheet',
                                // 'Video',
                                // 'Webinar',
                                // 'Case Study',
                                // 'Press Release',
                                // 'In The News',
                                // 'Event',
                                // 'FAQ',
                                // 'Media Partners'
                            );
                            // post type singular - e.g. Event

    $cpt_plural         =   array(
                                // 'Agencies',
                                // 'Clients',
                                // 'Team',
                                // 'Integrations',
                                // 'White Papers',
                                // 'Data Sheets',
                                // 'Videos',
                                // 'Webinars',
                                // 'Case Studies',
                                // 'Press Releases',
                                // 'In The News',
                                // 'Events',
                                // 'FAQs',
                                // 'Media Partners'
                            );

    $cpt_tax            =   array(
                                '',
                            );

    $rewriteUrl         =   array(
                                '',
                            );

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