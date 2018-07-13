<?php
/*
  Plugin Name: Gravity Forms Frontend
  Plugin URI: http://amir-meshkin.com
  Description: Create front end themes for gravity forms
  Version: 1.0
  Author: Amir Meshkin
  Author URI: http://amir-meshkin.com
  License: ?
 */

// first thing, make sure gravity forms is loaded
register_activation_hook( __FILE__, 'check_for_gravityforms' );


function check_for_gravityforms(){
  if (!class_exists('GFForms')) {
    wp_die('Sorry, but this plugin requires the Gravity Forms to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
  }
}

if ( !defined( 'ABSPATH' ) ) die(); //keep from direct access

if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

register_activation_hook(__FILE__, 'Gf_Frontend_activate');

// display error message to users
if (array_key_exists('action', $_GET) && $_GET['action'] == 'error_scrape') {
    //die("This PLUGIN requires PHP 5.0 or higher. Please deactivate.");
}

function Gf_Frontend_activate()
{
    if (version_compare(phpversion(), '5.0', '<')) {
        trigger_error('', E_USER_ERROR);
    }
}

// require this plugin in back end, only captcha class needed in front end
if (version_compare(phpversion(), '5.0', '>=')) {
    define('WR_LOADER', __FILE__);
    require_once(dirname(__FILE__) . '/app.php');
}


function gf_spinner_replace( $image_src, $form ) {
  return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // relative to you theme images folder
}
add_filter( 'gform_ajax_spinner_url', 'gf_spinner_replace', 10, 2 );
