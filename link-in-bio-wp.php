<?php
/**
 * Template code for a WordPress plugin.
 *
 * @package linkinbio
 */

/*
-------------------------------------------------------------------------------
	Plugin Name: Link In Bio WP
	Plugin URI: 
	Description: Add a link in bio page for use on social media pages
	Text Domain: linkinbio
	Author: sfgarza
	Author URI: https://profiles.wordpress.org/sgarza/
	Contributors: imforza
	License: GPLv3 or later
	License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
	Version: 1.0.0
------------------------------------------------------------------------------
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Include dependencies */
include_once( 'includes.php' );
/** Instantiate the plugin. */
WP_LinkInBio::get_instance();

/**
 * WP_LinkInBio class.
 *
 * @package linkinbio
 * @todo Change class name to be unique to your plugin.
 **/
class WP_LinkInBio {

	/**
	 * Plugin Basename.
	 *
	 * IE: wordpress-plugin-template/plugin-template.php
	 *
	 * @var [String]
	 */
	public static $plugin_base_name;

	/**
	 * Path to current plugin directory.
	 *
	 * @var [String]
	 */
	public static $plugin_base_dir;

	/**
	 * Path to plugin base file.
	 *
	 * @var [String]
	 */
	public static $plugin_file;

	/**
	 * Plugin Constructor.
	 */
	private function __construct() {
		/* Define Constants */
		static::$plugin_base_name = plugin_basename( __FILE__ );
		static::$plugin_base_dir = plugin_dir_path( __FILE__ );
		static::$plugin_file = __FILE__;

		$this->init();
	}

	public static function get_plugin_base_name(){

		if ( ! isset( static::$plugin_base_name ) ) {
			static::$plugin_base_name = plugin_basename( __FILE__ );
		}

		return static::$plugin_base_name;

	}

	public static function get_plugin_base_dir(){

		if ( ! isset( static::$plugin_base_dir ) ) {
			static::$plugin_base_dir = plugin_dir_path( __FILE__ );
		}

		return static::$plugin_base_dir;

	}

	public static function get_plugin_file(){

		if ( ! isset( static::$plugin_file ) ) {
			static::$plugin_file = __FILE__;
		}

		return static::$plugin_file;

	}

	/**
	 * Singleton instantiator.
	 *
	 * @return WP_LinkInBio Single instance of plugin class.
	 */
	public static function get_instance() {
		static $instance;

		if ( ! isset( $instance ) ) {
			$instance = new WP_LinkInBio();
		}

		return $instance;
	}

	/**
	 * Initialize Plugin.
	 */
	private function init() {
		/* Language Support */
		load_plugin_textdomain( 'linkinbio', false, dirname( static::$plugin_base_name ) . '/languages' );

		/* Plugin Activation/De-Activation. */
		register_activation_hook( static::$plugin_file, array( $this, 'activate' ) );
		register_deactivation_hook( static::$plugin_file, array( $this, 'deactivate' ) );

		add_image_size( 'image_link', 250, 250, true );

		/** Enqueue css and js files */
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		/* Add link to settings in plugins admin page */
		add_filter( 'plugin_action_links_' . static::$plugin_base_name , array( $this, 'plugin_links' ) );

		add_action( 'init', array( $this, 'create_post_type' ) );
		add_action( 'init', array( $this, 'register_post_meta' ) );
		add_filter( 'template_include', array($this, 'include_template') );
		add_filter( 'pre_get_posts', array( $this, 'posts_per_page' ) );
		// No need to define metaboxes/save post function unless it's accessible.
		if ( is_admin() ) {
			//add_filter( 'manage_edit-link-in-bio_columns', array( $this, 'columns_filter' ) );
			//add_action( 'manage_link-in-bio_posts_custom_column', array( $this, 'columns_data' ) );
			add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
			add_action( 'save_post_link-in-bio', array( $this, 'metabox_save' ), 1, 2 );
		}
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function create_post_type() {
		$args = array(
			'labels'                => array(
			'name'                  => __( 'Links', 'linkinbio' ),
			'singular_name'         => __( 'Link', 'linkinbio' ),
			'add_new'               => __( 'Add New', 'linkinbio' ),
			'add_new_item'          => __( 'Add New Link', 'linkinbio' ),
			'edit'                  => __( 'Edit', 'linkinbio' ),
			'edit_item'             => __( 'Edit Link', 'linkinbio' ),
			'new_item'              => __( 'New Link', 'linkinbio' ),
			'view'                  => __( 'View Link', 'linkinbio' ),
			'view_item'             => __( 'View Link', 'linkinbio' ),
			'search_items'          => __( 'Search Link', 'linkinbio' ),
			'not_found'             => __( 'No Link found', 'linkinbio' ),
			'not_found_in_trash'    => __( 'No Link found in Trash', 'linkinbio' ),
			'filter_items_list'     => __( 'Filter Link', 'linkinbio' ),
			'items_list_navigation' => __( 'Link navigation', 'linkinbio' ),
			'items_list'            => __( 'Link list', 'linkinbio' ),
			),
			'supports'              => array( 'title', 'thumbnail' ), // Removes content field. To remove title, set entire thing to be false rather than an array.
			'label'                 => __( 'Links', 'linkinbio' ),
			'description'           => __( 'A Link', 'linkinbio' ),
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'publicly_queryable'    => true,
			'public'                => true,
			'query_var'             => true,
			'show_in_rest'          => true,
			'rest_base'             => 'links',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-links',
			'has_archive'           => true,
			'rewrite'               => array(
				'slug' => 'links',
            	'feeds' => false
			),
		);

		register_post_type( 'link-in-bio', $args );
	}

	public function register_post_meta(){
		register_post_meta( 'link-in-bio', '_linkinbio_redirect_link', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		) );
	}

	/**
	 * Register meta boxes.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box( 'link-in-bio_general', __( 'Information', 'linkinbio' ), array( $this, 'general_metabox' ), 'link-in-bio', 'advanced', 'high' );
	}

	/**
	 * The general metabox.
	 *
	 * @return void
	 */
	public function general_metabox() {
		global $post;

		wp_nonce_field( 'linkinbio_metabox_save', 'linkinbio_metabox_nonce' );

		echo '<p><label>Redirect Link:</label><br /><input type="text" name="linkinbio_redirect_link" value="' . esc_attr( get_post_meta( $post->ID, "_linkinbio_redirect_link", true ) ) .'"/></p>';
	}

	/**
	 * Save metabox data.
	 *
	 * Note: fasi data cannot be modified through metaboxes.
	 *
	 * @param  mixed   $post_id The ID of the post.
	 * @param  WP_Post $post    The WP Post.
	 * @return void
	 */
	public function metabox_save( $post_id, $post ) {

		// Check nonce.
		if ( ! isset( $_POST['linkinbio_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['linkinbio_metabox_nonce'], 'linkinbio_metabox_save' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

        // Security.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
		}
		
		// Validate and sanitize redirect url.
		$redirect_link = wp_http_validate_url( esc_url_raw( trim( $_POST['linkinbio_redirect_link'] ) ) );
		$redirect_link = (false !== $redirect_link ) ? $redirect_link : '';
		
		// Perform save.
		update_post_meta( $post_id, "_linkinbio_redirect_link", $redirect_link );

	}

	// Add a filter to 'template_include' hook
	function include_template( $template ) {
		// If the current url is an archive of any kind
		if( 'link-in-bio' === get_post_type() ){
			if( is_archive() ) {
				// Set this to the template file inside your plugin folder
				$template = static::$plugin_base_dir .'/templates/archive-link-in-bio.php';
			}
			if( is_single() ){
				$template = static::$plugin_base_dir .'/templates/single-link-in-bio.php';
			}
		}
			
    	// Always return, even if we didn't change anything
   		return $template;
	}

	/**
	 * Enqueue Scripts and styles for Frontend.
	 */
	public function frontend_scripts() {
		global $wp;

		// Only load Style and Scripts where needed where needed
		if( 'link-in-bio' === get_post_type() ){
			wp_register_style( 'linkinbio-css', plugins_url( 'assets/css/link-in-bio.css', static::$plugin_file ) );
			wp_enqueue_style( 'linkinbio-css' );
	
			wp_enqueue_script( 'linkinbio-infinite-scroll',plugins_url( 'assets/js/infinite-scroll.min.js', static::$plugin_file ), null, null, true );
			wp_localize_script( 'linkinbio-infinite-scroll', 'linkinbio_scroll', array( "url" => site_url('/wp-json/wp/v2/links') ) );
		}
	}

	/**
	 * Method that executes on plugin activation.
	 */
	public function activate() {
		add_action( 'plugins_loaded', 'flush_rewrite_rules' );
	}

	/**
	 * Method that executes on plugin de-activation.
	 */
	public function deactivate() {
		//remove_image_size( 'image_link' );
		add_action( 'plugins_loaded', 'flush_rewrite_rules' );
	}

	/**
	 * Add Tools link on plugin page.
	 *
	 * @param  [Array] $links : Array of links on plugin page.
	 * @return [Array]        : Array of links on plugin page.
	 */
	public function plugin_links( $links ) {
		$settings_link = '<a href="customize.php?autofocus%5Bpanel%5D=linkinbio">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	function posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
		   return;
		}
	
		if ( is_post_type_archive( 'link-in-bio' ) ) {
		   $query->set( 'posts_per_page', 6 );
		}
	}
}
