<?php
/*
Plugin Name: EC-Anmeldung
Plugin URI: 
Description: 
Author: EC-Nordbund Developer (Tobias Krause)
Author URI: https://bitbucket.org/ecnb-devs/
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

// Some definitions
define('ECA_PLUGIN', __FILE__);
define('ECA_PLUGIN_URL', plugins_url('', plugin_basename(__FILE__)) . '/');
define('ECA_PLUGIN_LIB_URL', ECA_PLUGIN_URL . 'lib/');
define('ECA_PLUGIN_DIR', untrailingslashit(dirname(ECA_PLUGIN)));

// Some includes & requirements
require_once ECA_PLUGIN_DIR . '/lib/database.php';
require_once ECA_PLUGIN_DIR . '/lib/shortcode.php';


// Register scripts and styles
add_action( 'wp_enqueue_scripts', 'eca_register_scripts_and_styles');

// Declare action to delete all expired registration data in database
add_action('eca_delete_expired_registration', 'eca_delete_expired_registration');

// Adds shortcode for the registration formular to Wordpress
add_shortcode('ec-anmeldung', 'eca_anmeldung_shortcode');


register_activation_hook(__FILE__, 'eca_activate');
register_deactivation_hook(__FILE__, 'eca_deactivate');

// called by plugin activation
function eca_activate()
{
    eca_registration_setup_db();
}

// called by plugin deactivation 
function eca_deactivate()
{
    eca_registration_delete_cron_job();
}