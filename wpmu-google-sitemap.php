<?php
/*
Plugin Name: WPMU Google Sitemap
Plugin URI: http://wordpress.org/extend/plugins/wpmu-google-sitemap/
Description: Adds functionallity to WPMU to generate a Google XML Sitemap
Author: Robert Lord, Codepeak AB <robert@codepeak.se>
Version: 0.2.3
Author URI: http://www.codepeak.se
*/

// Define the root directory of the plugin
define('WPMUGS_BASE', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);

// Uncomment this to have the plugin translated into your language
# load_plugin_textdomain('wpmugs', WPMUGS_BASE); // Swedish

// Include our class
require_once( WPMUGS_BASE . 'wpmu-google-sitemap.class.php');

// Make sure the URL is set
if ( wpmugs::get('urlname') == '' ) wpmugs::store('urlname','google-sitemap.xml');

// Add menu for administrator
add_action( 'admin_menu', array('wpmugs','page_settings_init') );

// Hook the URL
add_action('template_redirect', array('wpmugs','custom_sub_page') );

// Support for the old version
if ( $_REQUEST['sitemap'] == 'wpmu-gs' ) { wpmugs::showsitemap(); }