<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.linkedin.com
 * @since             1.0.0
 * @package           Lyra
 *
 * @wordpress-plugin
 * Plugin Name:       WindIOT-display
 * Plugin URI:        lyrawaters.org
 * Description:       Wind and temperature data from WindIOT device.
 * Version:           1.0.0
 * Author:            Scott Nichols
 * Author URI:        www.linkedin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lyra
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WindIOT_Display_VERSION', '1.1.0');
global $lyra_db_version;
$lyra_db_version = '1.0';



function WindIOTdisplay()
{
    wp_register_style('WindIOT-display', plugins_url('css/windIOT-display_styles.css', __FILE__));
    wp_enqueue_style('WindIOT-display');
  
 


}

add_action('init', 'WindIOTdisplay');





require_once('inc/displayTable.php'); // List temps as table.
require_once('inc/CurrentConditions.php'); // List temps as simple form.
