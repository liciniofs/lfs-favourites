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
     * Inserts data.
     *
     * Inserts data to plugin database.
     *
     * @since      1.0.0
     * @package    Licinio Sousa
     * @author     Licinio Sousa <licinio@ocubo.org>
     */

    function ajax_form(){
          global $wpdb;

          $timezone = date_default_timezone_get();
          $date = date('Y/m/d h:i:s a', time());

          $wpdb->insert('wp_lfs_favourites', array(
              'time'  => $date,
              'user_id' => $_POST[ 'user_id' ],
              'post_id' => $_POST[ 'post_id' ]
          ));
    }
}

$lfs_Endpoints = new lfs_Endpoints();
