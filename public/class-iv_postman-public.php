<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://iv-dev.com
 * @since      1.0.0
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/public
 * @author     IV-dev.com <contact@iv-dev.com>
 */
class Iv_postman_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->RegisterShortcodes();

		add_action('template_redirect', array(&$this, 'redirect_if_option'));
	}

	public function redirect_if_option() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$ref = strtolower($_SERVER['HTTP_REFERER']);
			$url = parse_url($ref);
			$myurl = parse_url(strtolower(get_site_url()));
			if (isset($myurl['host']) && isset($url['host'])) {
				if ($myurl['host'] == $url['host']) {
					return true;
				}
			}
			if (!empty($ref)) {
				$redirects = get_option('iv_dev_redirects');
				if (!empty($redirects)) {
					$redirects = json_decode($redirects, true);
				}
				if (!is_array($redirects)) {
					$redirects = array();
				}
				if (!empty($redirects)) {
					foreach ($redirects as $k => $r) {
						if (strpos($ref, strtolower($k)) !== false) {
							//substring found
							if (get_the_ID() != $r) {
								wp_safe_redirect(get_permalink($r));
								exit;
							}
						}
					}
				}
			}

		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		
		wp_enqueue_style( $this->plugin_name.'-label', plugin_dir_url( __FILE__ ) . 'css/label.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-forms', plugin_dir_url( __FILE__ ) . 'css/form.min.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name.'-input', plugin_dir_url( __FILE__ ) . 'css/input.min.css', array(), $this->version, 'all' );
			
		wp_enqueue_style( $this->plugin_name.'-buttons', plugin_dir_url( __FILE__ ) . 'css/button.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-transition', plugin_dir_url( __FILE__ ) . 'css/transition.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name.'-dropdown', plugin_dir_url( __FILE__ ) . 'css/dropdown.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iv_postman-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		
		
		wp_enqueue_script( 'loadingoverlay', plugin_dir_url( __FILE__ ) . 'js/loadingoverlay.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-transition', plugin_dir_url( __FILE__ ) . 'js/transition.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-forms', plugin_dir_url( __FILE__ ) . 'js/form.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-dropdown', plugin_dir_url( __FILE__ ) . 'js/dropdown.min.js', array( 'jquery' ), $this->version, false );

		

		

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iv_postman-public.js', array( 'jquery' ), $this->version, false );

	}

	   /**
     * Registers the plugin shortcodes
     *
     * @since 1.0.0
     * @access private
     */
    private function RegisterShortcodes()
    {
        //FrontPage question iterator
      	add_shortcode('iv_postman_display', array(&$this, 'ShowQuestion')); //register
    }


	public function ShowQuestion($atts = []) {
		if(isset($atts['form'])) {
			if (!isset($atts['form'])) {
				$atts['slug'] = $atts['slug'];
			}
		}
		if (!isset($atts['slug']) OR empty($atts['slug'])) {
			return false;
		}
		ob_start();
		//slug found
		$opts = get_option('iv_dev_questions');
		if (empty($opts)) {
			//no questions set\
			echo "No forms defined.";
			return false;
		}
		
		$form = false;
		foreach (json_decode($opts, true) as $v) {
			if ($v['options']['form_slug'] == $atts['slug']) {
				$form = $v;
			}
		}
		if ($form == false) {
			echo "Form with the specified slug was not found.";
			return false;
		}
		if (isset($atts['step'])) {
			$step = $atts['step'];
		} else {
			$step = false;
		}
		
		include ( plugin_dir_path( __FILE__ ).'partials/form-block.php' );
		$buff= ob_get_contents();
		ob_end_clean();
		return  $buff;

	}

}
