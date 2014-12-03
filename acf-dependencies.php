<?php

// tgm plugin activation
require_once( plugin_dir_path( __FILE__ ) . '/tgm-plugin-activation/class-tgm-plugin-activation.php' );

// acf settings icon font awesome plugin
add_action( 'tgmpa_register', 'simple_multi_cpts_require_plugins' );
function simple_multi_cpts_require_plugins() {

    $plugins = array(
        array(
            'name'               => 'Advanced Custom Fields Pro',
            'slug'               => 'advanced-custom-fields-pro',
            'required'           => true,
            'force_activation'   => true, // activate this plugin when the user switches to another theme
            'force_deactivation' => true // deactivate this plugin when the user switches to another theme
        ),
        array(
            'name'               => 'Advanced Custom Fields: Font Awesome',
            'slug'               => 'advanced-custom-fields-font-awesome',
            'required'           => true,
            'force_activation'   => true, // activate this plugin when the user switches to another theme
            'force_deactivation' => true // deactivate this plugin when the user switches to another theme
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

if ( class_exists('acf') ) :

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

endif;