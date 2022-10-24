<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://khairat
 * @since             1.0.0
 * @package           Khairat
 *
 * @wordpress-plugin
 * Plugin Name:       khairat
 * Plugin URI:        https://khairat
 * Description:       khairat
 * Version:           1.0.0
 * Author:            khairat
 * Author URI:        https://khairat
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       khairat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KHAIRAT_VERSION', '1.0.0' );
define( 'KHAI_PATH', plugin_dir_path( __FILE__ ) );
define( 'KHAI_URL', plugins_url('khairat') );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-khairat-activator.php
 */
function activate_khairat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-khairat-activator.php';
	Khairat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-khairat-deactivator.php
 */
function deactivate_khairat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-khairat-deactivator.php';
	Khairat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_khairat' );
register_deactivation_hook( __FILE__, 'deactivate_khairat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-khairat.php';
require_once plugin_dir_path( __FILE__ ) . 'public/api-khairat.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_khairat() {

	$plugin = new Khairat();
	$plugin->run();

}
run_khairat();

function deb($data){
	print_r("<pre>");
	print_r($data);
}

