<?php
/**
 * @package   iv_postman_Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class iv_postman_Options_Framework_Admin
{

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
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;

    /**
     * Constructor
     *
    * @since 1.7.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init()
    {
        $options = iv_postman_optionsframework();
        // Checks if options are available
        if ($options) {

            // Add the options page and menu item.
            add_action('admin_menu', array( $this, 'add_custom_options_page' ));

            // Add the required scripts and styles
            add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ));
            add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ));

            // Settings need to be registered after admin_init
            add_action('admin_init', array( $this, 'settings_init' ));

            // Adds options menu to the admin bar
            add_action('wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ));
        }
    }

    /**
     * Registers the settings
     *
     * @since 1.7.0
     */
    public function settings_init()
    {

        // Get the option name
        $options_framework = new iv_postman_Options_Framework;
        $name = $options_framework->get_option_name();

        // Registers the settings fields and callback
        register_setting('optionsframework', $name, array( $this, 'validate_options' ));

        // Displays notice after options save
        add_action('optionsframework_after_validate', array( $this, 'save_options_notice' ));
    }

    /*
     * Define menu options
     *
     * Examples usage:
     *
     * add_filter( 'optionsframework_menu', function( $menu ) {
     *     $menu['page_title'] = 'The Options';
     *	   $menu['menu_title'] = 'The Options';
     *     return $menu;
     * });
     *
     * @since 1.7.0
     *
     */
    public function menu_settings()
    {
        $menu = array(

            // Modes: submenu, menu
            'mode' => 'menu',

            // Submenu default settings
            'page_title' => $this->plugin_name.' '.__('Options', $this->plugin_name),
            'menu_title' => $this->plugin_name.' '. __('Options', $this->plugin_name),
            'capability' => 'manage_options',
            'menu_slug' =>  $this->plugin_name.'-options',
            //'parent_slug' => 'themes.php',

            // Menu default settings
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61'

        );

        return apply_filters('optionsframework_menu', $menu);
    }

    /**
     * Add the menu
     *
     * @since 1.7.0
     */
    public function add_custom_options_page()
    {
        $menu = $this->menu_settings();
        switch ($menu['mode']) {
                  case 'menu':
                      // http://codex.wordpress.org/Function_Reference/add_menu_page
                      $this->options_screen = add_menu_page(
                          $menu['page_title'],
                          $menu['menu_title'],
                          $menu['capability'],
                          $menu['menu_slug'],
                          array( $this, 'options_page' ),
                          $menu['icon_url'],
                          $menu['position']
                      );
                      break;

                  default:
                      // http://codex.wordpress.org/Function_Reference/add_submenu_page
                      $this->options_screen = add_submenu_page(
                          $menu['parent_slug'],
                          $menu['page_title'],
                          $menu['menu_title'],
                          $menu['capability'],
                          $menu['menu_slug'],
                          array( $this, 'options_page' )
                      );
                      break;
                 }
    }

    /**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */

    public function enqueue_admin_styles($hook)
    {
        if ($this->options_screen != $hook) {
            return;
        }

        wp_enqueue_style('optionsframework', iv_postman_OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(), iv_postman_Options_Framework::VERSION);
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
    public function enqueue_admin_scripts($hook)
    {
        if ($this->options_screen != $hook) {
            return;
        }

        // Enqueue custom option panel JS
        wp_enqueue_script(
            'options-custom',
            iv_postman_OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js',
            array( 'jquery','wp-color-picker' ),
            iv_postman_Options_Framework::VERSION
        );

        // Inline scripts from options-interface.php
        add_action('admin_head', array( $this, 'of_admin_head' ));
    }

    public function of_admin_head()
    {
        // Hook to add custom scripts
        do_action('optionsframework_custom_scripts');
    }

    /**
     * Builds out the options panel.
     *
     * If we were using the Settings API as it was intended we would use
     * do_settings_sections here.  But as we don't want the settings wrapped in a table,
     * we'll call our own custom optionsframework_fields.  See options-interface.php
     * for specifics on how each individual field is generated.
     *
     * Nonces are provided using the settings_fields()
     *
     * @since 1.7.0
     */
    public function options_page()
    {
        ?>

		<div id="optionsframework-wrap" class="wrap">

		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html($menu['page_title']); ?></h2>

	    <h2 class="nav-tab-wrapper">
	        <?php echo iv_postman_Options_Framework_Interface::optionsframework_tabs(); ?>
	    </h2>

	    <?php settings_errors('options-framework'); ?>

	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields('optionsframework'); ?>
				<?php iv_postman_Options_Framework_Interface::optionsframework_fields(); /* Settings */ ?>
				<div id="optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e('Save Options', 'iv_postman'); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e('Restore Defaults', 'iv_postman'); ?>" onclick="return confirm( '<?php print esc_js(__('Click OK to reset. Any theme settings will be lost!', 'iv_postman')); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
		</div>
		<?php do_action('optionsframework_after'); ?>
		</div> <!-- / .wrap -->

	<?php
    }

    /**
     * Validate Options.
     *
     * This runs after the submit/reset button has been clicked and
     * validates the inputs.
     *
     * @uses $_POST['reset'] to restore default options
     */
    public function validate_options($input)
    {

        /*
         * Restore Defaults.
         *
         * In the event that the user clicked the "Restore Defaults"
         * button, the options defined in the theme's options.php
         * file will be added to the option for the active theme.
         */

        if (isset($_POST['reset'])) {
            add_settings_error('options-framework', 'restore_defaults', __('Default options restored.', 'iv_postman'), 'updated fade');
            return $this->get_default_values();
        }

        /*
         * Update Settings
         *
         * This used to check for $_POST['update'], but has been updated
         * to be compatible with the theme customizer introduced in WordPress 3.4
         */

        $clean = array();
        $options = iv_postman_optionsframework();
        foreach ($options as $option) {
            if (! isset($option['id'])) {
                continue;
            }

            if (! isset($option['type'])) {
                continue;
            }

            $id = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['id']));

            // Set checkbox to false if it wasn't sent in the $_POST
            if ('checkbox' == $option['type'] && ! isset($input[$id])) {
                $input[$id] = false;
            }

            // Set each item in the multicheck to false if it wasn't sent in the $_POST
            if ('multicheck' == $option['type'] && ! isset($input[$id])) {
                foreach ($option['options'] as $key => $value) {
                    $input[$id][$key] = false;
                }
            }

            // For a value to be submitted to database it must pass through a sanitization filter
            if (has_filter('of_sanitize_' . $option['type'])) {
                $clean[$id] = apply_filters('of_sanitize_' . $option['type'], $input[$id], $option);
            }
        }

        // Hook to run after validation
        do_action('optionsframework_after_validate', $clean);

        return $clean;
    }

    /**
     * Display message when options have been saved
     */

    public function save_options_notice()
    {
        add_settings_error('options-framework', 'save_options', __('Options saved.', 'iv_postman'), 'updated fade');
    }

    /**
     * Get the default values for all the theme options
     *
     * Get an array of all default values as set in
     * options.php. The 'id','std' and 'type' keys need
     * to be defined in the configuration array. In the
     * event that these keys are not present the option
     * will not be included in this function's output.
     *
     * @return array Re-keyed options configuration array.
     *
     */
    public function get_default_values()
    {
        $output = array();
        $config = & iv_postman_Options_Framework::_iv_postman_optionsframework();
        foreach ((array) $config as $option) {
            if (! isset($option['id'])) {
                continue;
            }
            if (! isset($option['std'])) {
                continue;
            }
            if (! isset($option['type'])) {
                continue;
            }
            if (has_filter('of_sanitize_' . $option['type'])) {
                $output[$option['id']] = apply_filters('of_sanitize_' . $option['type'], $option['std'], $option);
            }
        }
        return $output;
    }

    /**
     * Add options menu item to admin bar
     */

    public function optionsframework_admin_bar()
    {
        $menu = $this->menu_settings();

        global $wp_admin_bar;

        if ('menu' == $menu['mode']) {
            $href = admin_url('admin.php?page=' . $menu['menu_slug']);
        } else {
            $href = admin_url('themes.php?page=' . $menu['menu_slug']);
        }

        $args = array(
            'parent' => 'appearance',
            'id' => 'of_theme_options',
            'title' => $menu['menu_title'],
            'href' => $href
        );

        $wp_admin_bar->add_menu(apply_filters('optionsframework_admin_bar', $args));
    }
}
