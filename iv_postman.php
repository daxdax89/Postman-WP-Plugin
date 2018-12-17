<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://iv-dev.com
 * @since             1.0.0
 * @package           Iv_postman
 *
 * @wordpress-plugin
 * Plugin Name:       PostMan
 * Plugin URI:        http://iv-dev.com
 * Description:       Custom PostMan plugin to generate custom forms and redirects
 * Version:           1.0.1
 * Author:            IV-dev.com
 * Author URI:        http://iv-dev.com
 * License:           Commercial
 * License URI:       Attached
 * Text Domain:       iv_postman
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
define( 'iv_postman_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-iv_postman-activator.php
 */
function activate_iv_postman() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iv_postman-activator.php';
	Iv_postman_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-iv_postman-deactivator.php
 */
function deactivate_iv_postman() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iv_postman-deactivator.php';
	Iv_postman_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_iv_postman' );
register_deactivation_hook( __FILE__, 'deactivate_iv_postman' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-iv_postman.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_iv_postman() {

	$plugin = new Iv_postman();
	$plugin->run();

}
run_iv_postman();
