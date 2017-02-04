<?php

/**
 * Fired during plugin activation
 *
 * @link
 * @since      1.0.0
 *
 * @package    Licinio Sousa
 */

namespace LicinioSousa\WP\Plugin\LfsFavourites;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Licinio Sousa
 * @author     licinio  <licinio@ocubo.pt>
 */
class Activator {

	/**
	 * Fires on plugin activation.
	 *
	 * Actions to perform on pugin activation..
	 *
	 * @since    1.0.0
	 */

	public static function activate() {

		$fav = new Activator();

		$fav->create_db_and_option();
		$fav->install_data();

	}

		/**
		 * Database table and plugin version.
		 *
		 * Creates database table and adds plugin version to wp-options table
		 *
		 * @since    1.0.0
		 */

	public function create_db_and_option() {

		global $wpdb;
		$plugin = new Plugin( 'lfs-favourites', '1.0.0' );

		$table_name = $wpdb->prefix . 'lfs_favourites';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			user_id tinytext NOT NULL,
			post_id tinytext NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'lfs_favourites_db_version', $plugin->get_version() );
	}

	/**
	 * Mockup data.
	 *
	 * Creates mockup entry
	 *
	 * @since    1.0.0
	 */

	public function install_data() {
		global $wpdb;

		$welcome_user_id = get_current_user_id();

		$args = array(
			'posts_per_page'   => 1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$posts_array = get_posts( $args );

		foreach ( $posts_array as $post ) : setup_postdata( $post );
			$welcome_post_id = $post->ID;
		endforeach;

		$table_name = $wpdb->prefix . 'lfs_favourites';

		$wpdb->insert(
			$table_name,
			array(
				'time' => current_time( 'mysql' ),
				'user_id' => $welcome_user_id,
				'post_id' => $welcome_post_id,
			)
		);
	}
}
