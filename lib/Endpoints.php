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
    public function __construct() {
      add_action( 'wp_ajax_ajax_form', array($this, 'ajax_form')  ); //admin side
      add_action( 'wp_ajax_nopriv_ajax_form', array($this, 'ajax_form') ); //for frontend

      // add_action( 'wp_ajax_ajax_get', array($this, 'ajax_get')  ); //admin side
      // add_action( 'wp_ajax_nopriv_ajax_get', array($this, 'ajax_get') ); //for frontend
    }

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
    //
    // function ajax_get(){
    //       global $wpdb;
    //
    //       // $query = new WP_Query( array( 'p' => $_POST[ 'post_id' ] ) );
    //
    //       // $wpdb->insert('wp_lfs_favourites', array(
    //       //     'time'  => $date,
    //       //     'user_id' => $_POST[ 'user_id' ],
    //       //     'post_id' => $_POST[ 'post_id' ]
    //       // ));
    //       return $_POST[ 'post_id' ] ;
    // }
}

$lfs_Endpoints = new lfs_Endpoints();
// add_action( 'wp_ajax_ajax_form', ('ajax_form')  ); //admin side
// add_action( 'wp_ajax_nopriv_ajax_form', ('ajax_form') ); //for frontend
