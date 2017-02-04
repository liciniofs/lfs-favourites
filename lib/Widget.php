<?php

 /**
  * Builds widget logic.
  *
  * This class defines all code necessary to create the widget to list current user favourites.
  *
  * @since      1.0.0
  * @package    Licinio Sousa
  * @author     licinio  <licinio@ocubo.pt>
  */

  namespace LicinioSousa\WP\Plugin\LfsFavourites;

class lfs_favourites_widget extends \WP_Widget {

	function __construct() {
		// Instantiate the parent object
    $widget_ops = array(
			'classname' => 'my_favourites',
			'description' => 'My Favourites',
		);
		parent::__construct( 'my_favourites', 'My Favourites', $widget_ops );
	}

  /**
   * Creates the front end of the widget.
   *
   * Here we assemble the widget
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */

	function widget( $new_instance, $old_instance ) {

    if ( is_user_logged_in() ) {
      echo '<div class="lfs-favourites-widget">';
      echo '<h3>' . __('Os meus favoritos') . '</h3>';
      echo '<div class="lfs-favourites-widget-response">';
      $this->get_fav_posts();
      echo '</div>';
      echo '</div>';
    } else {
      echo '<div class="lfs-favourites-widget">';
      echo '<h5>' . __('Please register to favourite this post') . '</h5>';
      echo '</div>';
    }
	}

  /**
   * Change widget options.
   *
   * This will allow us to change option in the Dashboard (if necessary and available).
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */

	function update( $new_instance, $old_instance ) {
		// Save widget options

	}

  /**
   * Form with field to update widget.
   *
   * Form fields to update widget in Dashboard.
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */

	function form( $instance ) {

	}

  /**
   * Get post id from plugin database table.
   *
   * Here we get the logged in users favourite posts.
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */

  function get_fav_posts_id(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lfs_favourites';
    $user_id = get_current_user_id();

    $favourites = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $user_id LIMIT 5", OBJECT );

    $favourited_posts = [];

    foreach ($favourites as $favourite) {
      array_push($favourited_posts, $favourite->post_id);
    }

    $my_favourite_posts = implode(",", $favourited_posts);

    return $my_favourite_posts;
  }

  /**
   * Display favourite posts.
   *
   * Here we get favourite posts information from the database.
   *
   * @since      1.0.0
   * @package    Licinio Sousa
   * @author     Licinio Sousa <licinio@ocubo.org>
   */

  function get_fav_posts(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lfs_favourites';
    $posts_id = $this->get_fav_posts_id();


    $favourites = $wpdb->get_results( "SELECT * FROM wp_posts WHERE id in ($posts_id)" );

    $favourited_posts = [];

    foreach ($favourites as $favourite) {
      echo '<h5><a href="' . get_permalink( $favourite->ID ) . '">' . $favourite->post_title . '</h5></a>';
    }

    return $favourited_posts;
  }
}

add_action( 'widgets_init', function(){
 register_widget( 'LicinioSousa\WP\Plugin\LfsFavourites\lfs_favourites_widget' );
});
