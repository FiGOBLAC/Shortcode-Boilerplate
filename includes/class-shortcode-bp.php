<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Author URI
 * @since      1.0.0
 *
 * @package    Shortcode_BP
 * @subpackage Shortcode_BP/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Shortcode_BP
 * @subpackage Shortcode_BP/includes
 * @author     Author Name <youremail@example.com>
 */
class Shortcode_BP {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Shortcode_BP_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The path to the plugin folder
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_path    The path to the plugin folder.
	 */
	protected $plugin_path;

	/**
	 * The path to the shorcodes directory.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The path to the template directory.
	 */
	protected $shortcodes_dir_path;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'shortcode-bp';
		$this->version = '1.0.0';

		$this->set_plugin_paths();
		$this->load_dependencies();
		$this->load_shortcode_files();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shortcode_BP_Loader. Orchestrates the hooks of the plugin.
	 * - Shortcode_BP_i18n. Defines internationalization functionality.
	 * - Shortcode_BP_Admin. Defines all hooks for the admin area.
	 * - Shortcode_BP_Public. Defines all hooks for the public side of the site.
	 * - Shortcode_BP_Shortcode_Base Base Class for all shortcodes.
	 * - Shortcode_BP_Configurations. Pairs shorcodes with their active files.
	 * - Shortcode_BP_Template_loader. Loads templates used by shortcodes.s
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcode-bp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcode-bp-i18n.php';

		/**
		 * The class responsible for maping shortcodes with their respective file names.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcode-bp-configurations.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shortcode-bp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-bp-public.php';

		/**
		 * Abstract class that defines various functionalites to be shared by all shortcodes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode-bp-shortcode-base.php';

		/**
		 * The class responsible for loading temlates for shortcodes.
		 */
		require_once $this->plugin_path . 'public/class-shortcode-bp-template-loader.php';

		$this->loader = new Shortcode_BP_Loader();

	}

	/**
	 * Load the shortcode files
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	private function load_shortcode_files(){

		$shortcode_files = Shortcode_BP_Configurations::get_shortcode_file_configs();

		foreach( $shortcode_files as $shortcode => $file ) {

			// Add extension if it doesn't exists.
			$file = ( false === strpos( $file, '.php' ) ) ? "{$file}.php" : $file ;

			if ( file_exists( $this->shortcodes_dir_path . '/' . $file ) ) {

				require_once $this->shortcodes_dir_path .'/' . $file;
			}
		}
	}

	/**
	 * Defines the paths to the plugin and shortcodes directory
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_plugin_paths() {

        $this->plugin_path = plugin_dir_path( dirname( __FILE__ ) );

        $this->shortcodes_dir_path  = plugin_dir_path( dirname( __FILE__ ) ) .'public/shortcodes/';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Shortcode_BP_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

//		$plugin_i18n = new Shortcode_BP_i18n();
//
//		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Shortcode_BP_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Shortcode_BP_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

    /**
	 * Register all shortcodes.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		$shortcode_files = Shortcode_BP_Configurations::get_shortcode_file_configs();

		foreach( $shortcode_files as $shortcode => $file ) {

         $class = str_replace( array( 'class-', 'class_', '.php', '-' ), array( '', '', '', '_' ), $file );

			if( class_exists( ucwords( $class ,'\-\_') ) ) {

				$this->shortcode_class = new $class();

                $this->loader->add_shortcode( $shortcode, $this->shortcode_class, 'render_shortcode' );

			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Shortcode_BP_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
