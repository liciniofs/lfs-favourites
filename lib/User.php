<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Licinio Sousa
 */

namespace LicinioSousa\WP\Plugin\LfsFavourites;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Licinio Sousa
 * @author     Licinio Sousa <licinio@ocubo.org>
 */
class User {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {
    // $this->add_user_meta();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function add_user_meta() {
      global  $post;

      if (is_user_logged_in()) {
        $user_id = get_current_user_id();

        $my_favourite = $post->ID;
        update_user_meta($user_id, 'lfs_my_favourites', $my_favourite);
        // update_user_meta($user_id, 'user_url', $website);
      }

	}
}

$userInfo = new User();
