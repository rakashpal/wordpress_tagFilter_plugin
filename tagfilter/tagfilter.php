<?php
/*
Plugin Name: Divi Tag Search & Filter
Plugin URI: 
Description: 
Version:1.0
Author: eWeb A1 Professionals Organization
Author URI: http://a1professionals.com/
License: 
Text Domain: tagfilter
*/


define( 'TAGFILTER_VERSION', '1.0' );
define( 'TAGFILTER__MINIMUM_WP_VERSION', '3.7' );
define( 'TAGFILTER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAGFILTER_DELETE_LIMIT', 100000 );

register_activation_hook( __FILE__, array( 'TagFilter', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'TagFilter', 'plugin_deactivation' ) );

require_once( TAGFILTER__PLUGIN_DIR . 'class.tagfilter.php' );


add_action( 'init', array( 'TagFilter', 'init' ) );


if ( is_admin()){
	require_once( TAGFILTER__PLUGIN_DIR . 'class.tagfilter-admin.php' );
	add_action( 'init', array( 'TagFilter_Admin', 'init' ) );
	
}
