<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class lfs_favourites_widget_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_action( 'admin_menu', array($this, 'add_menu_lfs_favourites_widget' ));
	}

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_lfs_favourites_widget()
    {
        add_menu_page( 'My Favourites', 'MyFavourites', 'manage_options', 'myfavourites.php', array($this, 'list_lfs_favourites') );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_lfs_favourites()
    {
        $listTable = new lfs_favourites_widget_List_Table();
        $listTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>My Favourites Management</h2>
                <?php $listTable->display(); ?>
            </div>
        <?php
    }

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/styles.css', array(), $this->version, 'all' );

	}

}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class lfs_favourites_widget_List_Table extends WP_List_Table
{

    /**
     * Get the table data
     *
     * @return Array
     */
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data(){

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

        foreach ($favourites as $favourite) {
          array_push($favourited_posts, (array)$favourite);
        }

        return $favourited_posts;
    }

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->process_actions();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Define which actions exist for itens
     *
     * @return Array
     */
    public function column_id($item) {
        $actions = array(
                  'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
              );
        return sprintf('%1$s %2$s', $item['ID'], $this->row_actions($actions) );
        return $item;
        // print_r($item['ID ']);
    }

    /**
     * Prepare actions
     *
     * @return Array
     */
    public function process_actions(){
        if( $this->current_action() === 'delete' ) {
            $id = $_GET['id'];
            $this->delete_this($id);
        }
    }

    /**
     * Delete action
     *
     * @return Array
     */

    private function delete_this($id) {
        global $wpdb;
        $wpdb->delete( 'contacts', array( 'ID' => $id ) );
    }


    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('id' => array('id', false));
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'            => 'ID',
            'title'     		   => 'Title'
        );

        return $columns;
        // print_r($columns);
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        // print_r($item['post_title']);
        return '<a href="' . get_edit_post_link( $item['ID'], 'display' ) . '">' . $item['post_title'] . '</a>';
        // switch( $column_name ) {
        //     case 'id':
        //     case 'title':
        //     case 'time':
        //         return $item[ $column_name ];
        //     default:
        //         return print_r( $item, true ) ;
        // }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'desc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        // $result = strnatcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        // return -$result;
    }

}

$lfs_favourites_widget_Admin = new lfs_favourites_widget_Admin('my-favourites', '1.0.0');
