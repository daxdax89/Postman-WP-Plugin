<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://iv-dev.com
 * @since      1.0.0
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/includes
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
 * @package    Iv_postman
 * @subpackage Iv_postman/includes
 * @author     IV-dev.com <contact@iv-dev.com>
 */
class Iv_postman {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Iv_postman_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'iv_postman_VERSION' ) ) {
			$this->version = iv_postman_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'iv_postman';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->bind_ajax();

		
		//delay options creation
		add_action('init', array(&$this, 'create_options'));


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Iv_postman_Loader. Orchestrates the hooks of the plugin.
	 * - Iv_postman_i18n. Defines internationalization functionality.
	 * - Iv_postman_Admin. Defines all hooks for the admin area.
	 * - Iv_postman_Public. Defines all hooks for the public side of the site.
	 * - Plugin_name_Ajax. Defines all ajax hooks and methods
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-iv_postman-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-iv_postman-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-iv_postman-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-iv_postman-public.php';
		
		/**
		 * The class responsible for defining all ajax actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/class-iv_postman-ajax.php';

		$this->loader = new Iv_postman_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Iv_postman_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Iv_postman_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Iv_postman_Admin( $this->get_plugin_name(), $this->get_version() );

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

		$plugin_public = new Iv_postman_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Registers all ajax calls and callbacks
	 * 
	 * @since 1.0.0
	 * @access private
	 */
	private function bind_ajax() {
		$plugin_ajax = new Iv_postman_Ajax( $this->get_plugin_name(), $this->get_version() );	
	}


	/**
	 * Creates admin options
	 * 
	 * @since 1.0.0
	 */
	
    public function create_options()
    {

        define('iv_postman_OPTIONS_FRAMEWORK_DIRECTORY', plugin_dir_url(dirname(__FILE__)). 'options/');
        //  If user can't edit theme options, exit
        if (! current_user_can('manage_options')) {
            return;
        }

        // Loads the required Options Framework classes.
        require plugin_dir_path(dirname(__FILE__)). 'options/includes/class-options-framework.php';
        require plugin_dir_path(dirname(__FILE__)) . 'options/includes/class-options-framework-admin.php';
        require plugin_dir_path(dirname(__FILE__)) . 'options/includes/class-options-interface.php';
        require plugin_dir_path(dirname(__FILE__)) . 'options/includes/class-options-media-uploader.php';
        require plugin_dir_path(dirname(__FILE__)). 'options/includes/class-options-sanitization.php';


        // Instantiate the options page.
        $options_framework_admin = new iv_postman_Options_Framework_Admin($this->get_plugin_name(), $this->get_version());
        $options_framework_admin->init();

        // Instantiate the media uploader class
        $options_framework_media_uploader = new iv_postman_Options_Framework_Media_Uploader;
        $options_framework_media_uploader->init();
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
	 * @return    Iv_postman_Loader    Orchestrates the hooks of the plugin.
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






/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
 //Before loading load the options
 require_once plugin_dir_path(dirname(__FILE__)) . 'options/options.php';
if (! function_exists('of_get_option')) :
function of_get_option($name, $default = false)
{
    $option_name = '';

    // Gets option name as defined in the theme
    if (function_exists('iv_postman_optionsframework_option_name')) {
        $option_name = iv_postman_optionsframework_option_name();
    }

    // Fallback option name
    if ('' == $option_name) {
        $option_name = get_option('stylesheet');
        $option_name = preg_replace("/\W/", "_", strtolower($option_name));
    }

    // Get option settings from database
    $options = get_option($option_name);

    // Return specific option
    if (isset($options[$name])) {
        return $options[$name];
    }

    return $default;
}
endif;
