<?php
require_once realpath(dirname(__FILE__)).'/partials/table_extender.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://iv-dev.com
 * @since      1.0.0
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/admin
 * @author     IV-dev.com <contact@iv-dev.com>
 */
class Iv_postman_Admin {
	
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	protected $views = array();

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array(&$this, 'registerPages' ));
		

	}

	public function registerPages() {
		add_menu_page( 
			__( 'PostMan form creator', 'iv_postman' ),
			'PostMan form creator',
			'manage_options',
			'postman_creator',
			function() {
				require realpath(dirname(__FILE__)).'/partials/generator.php';
			},
			'dashicons-chart-pie',
			6
		); 

		add_menu_page( 
			__( 'PostMan redirection creator', 'iv_postman' ),
			'PostMan ReDirector',
			'manage_options',
			'postman_redirector',
			function() {
				require realpath(dirname(__FILE__)).'/partials/redirector.php';
			},
			'dashicons-dashboard',
			7
		); 

		$opts = get_option('iv_dev_questions');
		if (!empty($opts)) {
			$ops = json_decode($opts, true);
			foreach ($ops as $s) {
				if (!empty($s['options']['form_slug'])) {
					if (!empty($s['options']['form_name'])) {
						$title = $s['options']['form_name'].' responses';
					} else {
						$title = $s['options']['form_slug'].' responses';
					}
					$this->filename = "something";

					$view_hook_name = add_submenu_page(
						'postman_creator',
						$title,
						$title,
						'manage_options',
						$s['options']['form_slug'],
						function() {
							$current_views = $this->views[current_filter()];
							
							$opts = get_option('iv_dev_questions');
							if (!empty($opts)) {
								$found = false;
								$ops = json_decode($opts, true);
								foreach ($ops as $s) {
									if ($s['options']['form_slug'] == $current_views) {
										$found = $s;
									}
								}
								if ($found !== false) {
									show_form_table($found);
								}
							}
						}
					);
					$this->views[$view_hook_name] = $s['options']['form_slug'];
				}
			}
		}
		
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Iv_postman_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iv_postman_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style('transition_admin', plugin_dir_url(__FILE__) . 'css/transition.min.css', array(), $this->version, 'all');
		wp_enqueue_style('dropdown_admin', plugin_dir_url(__FILE__) . 'css/dropdown.min.css', array(), $this->version, 'all');

		wp_enqueue_style('bootstrap-grid', plugin_dir_url(__FILE__) . 'css/bootstrap-grid.min.css', array(), $this->version, 'all');
		wp_enqueue_style('toastr', plugin_dir_url(__FILE__) . 'css/toastr.min.css', array(), $this->version, 'all');
		wp_enqueue_style('jquery-confirm', plugin_dir_url(__FILE__) . 'css/jquery-confirm.min.css', array(), $this->version, 'all');
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iv_postman-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Iv_postman_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iv_postman_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script('transition_admin', plugin_dir_url(__FILE__) . 'js/transition.min.js', array( 'jquery' ), $this->version, false);
		wp_enqueue_script('dropdown_admin', plugin_dir_url(__FILE__) . 'js/dropdown.min.js', array( 'jquery' ), $this->version, false);

        wp_enqueue_script('toastr', plugin_dir_url(__FILE__) . 'js/toastr.min.js', array( 'jquery' ), $this->version, false);
		wp_enqueue_script('jquery-confirm', plugin_dir_url(__FILE__) . 'js/jquery-confirm.min.js', array( 'jquery' ), $this->version, false);


		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iv_postman-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-redirector', plugin_dir_url( __FILE__ ) . 'js/iv_postman-redirector.js', array( 'jquery' ), time(), false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iv_postman-admin.js', array( 'jquery' ), time(), false );
	}

}
