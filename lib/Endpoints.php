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

class lfs_Endpoints {

  /**
   * Triggers actions
   *
   * Makes method available for ajax requests.
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */
    public function __construct() {
      add_action( 'wp_ajax_ajax_form', array($this, 'ajax_form')  );
      add_action( 'wp_ajax_nopriv_ajax_form', array($this, 'ajax_form') );
    }

    /**
     * Updates user metadata.
     *
     * Insers and removes data from the user metadata.
     *
     * @since      1.0.0
     * @package    Licinio Sousa
     * @author     Licinio Sousa <licinio@ocubo.org>
     */

    public function ajax_form() {
        global $post;

        if (is_user_logged_in()) {
          $user_id = get_current_user_id(); // set current user ID
          $current_post = $_POST[ 'post_id' ]; // set post to favourite
          $current_favourites = []; // declare array

          $current_favourites = get_user_meta($user_id,"lfs_my_favourites",TRUE); // get current favourites

          $favourites_array = explode(',',$current_favourites); // transform comma separated values in aray

          if (in_array($current_post, $favourites_array) ) { // if posts is favourited, remove it from favourites

            $favourites_array = array_diff($favourites_array, [$current_post]); // remove favourite from array

            $current_favourites = implode(", ", $favourites_array); // transform array back to csv

            update_user_meta($user_id, "lfs_my_favourites", $current_favourites);// update with new data

          } else {

            $current_favourites .= ",".intval($_POST[ 'post_id' ]); // add current post ID to existing data

            update_user_meta($user_id,"lfs_my_favourites",$current_favourites); // update with new data
          }

        }

  	}
}

$lfs_Endpoints = new lfs_Endpoints();
