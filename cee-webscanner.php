<?php
/**
* Plugin Name: CEE Webscanner
* Plugin URI: www.cee-app.com
* Description: The CEE Platform is the first all-in-one mobile marketing platform in the world dedicated to mobile tagging. 
* Version: 1.1
* Author: cee-platform.com
* Author URI: 
* License: GPLv2
*/

// define plugin name constant to use loading of files - if not, issues with symlink
define('CEE_PLUGIN_NAME', basename(dirname(__FILE__)) . '/' . basename(__FILE__));

//load template page loader
require __DIR__.'/pagetemplater.php';


// Hook the 'admin_menu' action hook, run the function named 'cee_add_menu_settings_page()'
add_action( 'admin_menu', 'cee_add_menu_settings_page' );
 
 
// Add a new top level menu link to the ACP


function cee_add_menu_settings_page()
{
	
		
		
      add_menu_page(
        'Cee Web Scanner Settings', // Title of the page
        'Cee Web Scanner', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'cee-webscanner/includes/settings.php', // The 'slug' - file to display when clicking the link
		'', //calback function
		plugins_url('/cee-webscanner/cee-template/images/scan-icon-white-small.png') //icon url
    );
}


// Add shortcode

//add_shortcode( 'webscanner', 'cee_showScanner' );
 
 
 // add template to list of templates to choose
 add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
 
 
 
 // set default options on plugin activation
 register_activation_hook( __FILE__, 'cee_set_defaults');
 register_deactivation_hook( __FILE__, 'cee_set_defaults');
 
 function cee_set_defaults()
 {
	 //Set default options
	 update_option('CEE-webscanner-token','');
	 update_option('CEE-webscanner-color','#9acc13');
	 update_option('CEE-webscanner-starttext','Start Scanning');
	 update_option('CEE-webscanner-stoptext','Stop Scanning');
	 update_option('CEE-webscanner-noresulttext','No results found, point to an object');
	 update_option('CEE-webscanner-backgroundcolor','#333333');
	 update_option('CEE-webscanner-logo-file',plugins_url('cee-webscanner/cee-template/images/testlogo.png'));
	 
	 
	 //set default options for PWA file
	 update_option('CEE-webscanner-pwa-short_name', 'CEE');
	 update_option('CEE-webscanner-pwa-description', 'CEE Webscanner');
	 update_option('CEE-webscanner-pwa-start_url', '/');
	 
	 update_option('CEE-webscanner-pwa-icon_144',plugins_url('cee-template/images/pwa_icon_144.png', CEE_PLUGIN_NAME));
	 update_option('CEE-webscanner-pwa-icon_192',plugins_url('cee-template/images/pwa_icon_192.png', CEE_PLUGIN_NAME));
	 update_option('CEE-webscanner-pwa-icon_512',plugins_url('cee-template/images/pwa_icon_512.png', CEE_PLUGIN_NAME));
	 
	 
	 
	 
	
	 
 }
 


	 

?>