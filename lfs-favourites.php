<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link
 * @since             1.0.0
 * @package           Licinio Sousa
 *
 * @wordpress-plugin
 * Plugin Name:       Just another favourites plugin by LFS
 * Plugin URI:
 * Description:       Just another favourites plugin
 * Version:           1.0.0
 * Author:            Licínio Sousa
 * Author URI:        http://ocubo.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lfs-favourites
 * Domain Path:       /languages
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

global $plugin_dir_path;
$plugin_dir_path = plugin_dir_url( __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in lib/Activator.php
 */
register_activation_hook( __FILE__, 'LicinioSousa\WP\Plugin\LfsFavourites\Activator::activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in lib/Deactivator.php
 */
register_deactivation_hook( __FILE__, 'LicinioSousa\WP\Plugin\LfsFavourites\Deactivator::deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
add_action( 'plugins_loaded', function () {
  $plugin = new LicinioSousa\WP\Plugin\LfsFavourites\Plugin( 'lfs-favourites', '1.0.0' );
  $plugin->run();
} );
