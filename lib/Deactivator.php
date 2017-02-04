<?php

/**
 * Fired during plugin deactivation
 *
 * @link
 * @since      1.0.0
 *
 * @package    Licinio Sousa
 */

namespace LicinioSousa\WP\Plugin\LfsFavourites;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Licinio Sousa
 * @author     Licinio Sousa <licinio@ocubo.org>
 */

class Deactivator {

	/**
	 * Deletes the plugin table and option.
	 *
	 * Deletes the plugin table and plugin version from the db
	 *
	 * @since    1.0.0
	 */

	public static function deactivate() {
    global $wpdb;
		$table_name = $wpdb->prefix . 'lfs_favourites';
		if ($table_name) {
	    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	    delete_option("lfs_favourites_db_version");
		}
	}
}
