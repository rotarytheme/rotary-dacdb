<?php
/*
Plugin Name: Rotary DaCDb
Description: This is a plugin for Rotary Clubs to Maintain Membership from DacDB. This plugin auto updates from github.
Version: 1.0
Author: Merrill M. Mayer and Paul Osborn
Author URI: http://www.koolkatwebdesigns.com/
License: GPL2
*/

// Set path to theme specific functions
define( 'ACF_LITE' , true );

define( 'ROTARY_DACDB_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'ROTARY_DACDB_CLASSES_PATH', dirname( __FILE__ ) . '/classes/' );
define( 'ROTARY_DACDB_JAVASCRIPT_PATH', dirname( __FILE__ ) . '/js/' );
define( 'ROTARY_DACDB_CSS_PATH', dirname( __FILE__ ) . '/css/' );
define( 'ROTARY_DACDB_INCLUDES_PATH', dirname( __FILE__ ) . '/includes/' );

define( 'ROTARY_DACDB_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'ROTARY_DACDB_JAVASCRIPT_URL', plugins_url( '/js/', __FILE__ ) );
define( 'ROTARY_DACDB_CSS_URL', plugins_url( '/css/', __FILE__ ) );
define( 'ROTARY_DACDB_PLUGIN_FILE', plugin_basename( __FILE__ ) );

require_once( ROTARY_DACDB_CLASSES_PATH  . 'rotary-dacdb.php');
require_once( ROTARY_DACDB_CLASSES_PATH  . 'rotary-dacdb-soapauth.php');
include_once( ROTARY_DACDB_INCLUDES_PATH . 'rotary-dacdb-pluginupdater.php' );


//give the auto-installer something to call back
function rotary_dacdb_installed() {
	return true;
}

$rotaryDaCDb = new RotaryDaCDb();
?>