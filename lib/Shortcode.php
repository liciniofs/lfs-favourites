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

class lfs_add_favourite {

    /**
    * Build the class.
    *
    * Add shortcode and widget
    *
    * @since      1.0.0
    * @package    Licinio Sousa
    * @author     Licinio Sousa <licinio@ocubo.org>
    */
    public function __construct() {
        add_shortcode('add_favourite', array($this, 'add_to_fav_shortcode'));
        add_filter('widget_text', 'do_shortcode');

        add_shortcode('list_favourites', array($this, 'list_favourites_shortcode'));
        add_filter('widget_text', 'do_shortcode');



        add_action( 'wp_enqueue_scripts', array($this, 'scripts_and_styles'));
        add_action( 'wp_footer', array($this, 'add_js_variables') );
    }

    /**
     * Adds scripts and styles.
     *
     * Custom styles and scripts.
     *
     * @since      1.0.0
     * @package    Licinio Sousa
     * @author     Licinio Sousa <licinio@ocubo.org>
     */

    public function scripts_and_styles() {
        global $plugin_dir_path;

        wp_enqueue_style( 'favourites_core', $plugin_dir_path . 'assets/css/styles.css' );

        wp_enqueue_script( 'favourites_app', $plugin_dir_path . 'assets/scripts/app.js', array( 'jquery' ), '1.0.0', true );
    }

    /**
    * See if post is favourited.
    *
    * HChecks the database to see if current posts is favourited
    *
    * @since      1.0.0
    * @package    Licinio Sousa
    * @author     Licinio Sousa <licinio@ocubo.org>
    */

    public function is_it_faved($post_id, $user_id) {

      global $post;

      $current_post = $post_id; // 

      $current_favourites = get_user_meta($user_id, "lfs_my_favourites", TRUE); // get current favourites

      $favourites_array = explode(',',$current_favourites); // transform comma separated values in aray

      if (in_array($current_post, $favourites_array) ) { // if posts is favourited, remove return true

        return true;

      }

    }

    /**
    * Display add_to_fav_shortcode.
    *
    * Markup to be displpayed.
    *
    * @since      1.0.0
    * @package    Licinio Sousa
    * @author     Licinio Sousa <licinio@ocubo.org>
    */

    public function add_to_fav_shortcode() {
        global  $post,
                $plugin_dir_path;

        $user_id = get_current_user_id();

        $active = $this->is_it_faved($post->ID, $user_id);

        $status = ($active == true ? 'active' : 'inactive');

        if (is_user_logged_in()) {
          return "<div class='add-favourite' data-post='$post->ID'>
            <div class='add-favourite--btn $status'><span>" . __('Adicionar aos favoritos:') . "
              </span><img class='svg' src='" . $plugin_dir_path . "assets/images/heart.svg' />
            </div>
          </div>";
        };
    }

    /**
    * Display user favourite.
    *
    * Shortcode to display user favourites on front end.
    *
    * @since      1.0.0
    * @package    Licinio Sousa
    * @author     Licinio Sousa <licinio@ocubo.org>
    */

    public function list_favourites_shortcode() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'lfs_favourites';
        $user_id = get_current_user_id();

        $favourites = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $user_id LIMIT 5", OBJECT );

        $favourited_posts = [];

        foreach ($favourites as $favourite) {
          array_push($favourited_posts, $favourite->post_id);
        }

        $my_favourite_posts = implode(",", $favourited_posts);


        $posts_id = $my_favourite_posts;

        $favourites = $wpdb->get_results( "SELECT * FROM wp_posts WHERE id in ($posts_id)" );

        $favourited_posts = [];

        echo '<ul class="list_my_favourites">';
        foreach ($favourites as $favourite) {
          // array_push($favourited_posts, (array)$favourite);

          // print_r($favourite);

          echo '<li><a href="' . get_permalink($favourite->ID) . '">' . $favourite->post_title . '</a></li>';
        }
        echo '</ul>';

        // return $favourited_posts;

    }

    /**
    * Adds javascript variables.
    *
    * Adds necessary javascript variables..
    *
    * @since      1.0.0
    * @package    Licinio Sousa
    * @author     Licinio Sousa <licinio@ocubo.org>
    */

    function add_js_variables() {
        $user_id = get_current_user_id();
        $ajaxurl = '"' . admin_url('admin-ajax.php') . '"';
        echo "<script>
        currentUser = $user_id;
        ajaxurl = $ajaxurl;
        </script>";
    }
}

$lfs_add_favourite = new lfs_add_favourite();
