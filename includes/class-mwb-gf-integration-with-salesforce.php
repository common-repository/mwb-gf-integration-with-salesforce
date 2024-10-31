<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/includes
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
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/includes
 * @author     MakeWebBetter <harshitagrawal@makewebbetter.com>
 */
class Mwb_Gf_Integration_With_Salesforce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_Gf_Integration_With_Salesforce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'MWB_GF_INTEGRATION_WITH_SALESFORCE_VERSION' ) ) {
			$this->version = MWB_GF_INTEGRATION_WITH_SALESFORCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mwb-gf-integration-with-salesforce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();
		$this->define_feed_cpt_hooks();
		$this->define_ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Gf_Integration_With_Salesforce_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Gf_Integration_With_Salesforce_I18n. Defines internationalization functionality.
	 * - Mwb_Gf_Integration_With_Salesforce_Admin. Defines all hooks for the admin area.
	 * - Mwb_Gf_Integration_With_Salesforce_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-gf-integration-with-salesforce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-gf-integration-with-salesforce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-gf-integration-with-salesforce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-gf-integration-with-salesforce-public.php';

		/**
		 * The class responsible for handling ajax requests.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-gf-integration-with-salesforce-ajax-handler.php';

		/**
		 * The class responsible for all base api definitions of Salesforce crm in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-mwb-gf-integration-api-base.php';

		/**
		 * The class responsible for all Salesforce api definitions in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-mwb-gf-integration-salesforce-api-base.php';

		/**
		 * The class responsible for handling of feeds module.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-gf-integration-salesforce-feed-module.php';

		/**
		 * The class responsible for defining all the templates that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-gf-integration-salesforce-template-manager.php';

		/**
		 * The class responsible for handling of connect framework.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-gf-integration-connect-framework.php';

		/**
		 * The class reponsible for handling salesforce connect framework.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-gf-integration-connect-salesforce-framework.php';

		/**
		 * The class responsible for handling request module.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-gf-integration-salesforce-request-module.php';

		/**
		 * The class responsible for defining all actions that occur in the onboarding the site data
		 * in the admin side of the site.
		 */
		! class_exists( 'Mwb_Gf_Integration_With_Salesforce_Onboarding' ) && require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-gf-integration-with-salesforce-onboarding.php';
		$this->onboard = new Mwb_Gf_Integration_With_Salesforce_Onboarding();

		$this->loader = new Mwb_Gf_Integration_With_Salesforce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Gf_Integration_With_Salesforce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mwb_Gf_Integration_With_Salesforce_I18n();

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

		$plugin_admin = new Mwb_Gf_Integration_With_Salesforce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Pro Version Compatibility Check.
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'mwb_sf_gf_validate_pro_version_compatibility' );

		// Add submenu.
		$this->loader->add_action( 'gform_addon_navigation', $plugin_admin, 'mwb_sf_gf_submenu' );

		// Admin init process.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mwb_sf_gf_admin_init_process' );

		// Clear log callback.
		$this->loader->add_action( 'mwb_salesforce_gf_clear_log', $plugin_admin, 'mwb_sf_gf_clear_sync_log' );

		// Add onboarding screens.
		$this->loader->add_filter( 'mwb_helper_valid_frontend_screens', $plugin_admin, 'mwb_sf_gf_add_frontend_screens' );

		// Add Deactivation screen.
		$this->loader->add_filter( 'mwb_deactivation_supported_slug', $plugin_admin, 'mwb_sf_gf_add_deactivation_screens' );

		$this->loader->add_filter( 'mwb_salesforce_gf_manage_template_path', 'mwb_sf_gf_template_path', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mwb_Gf_Integration_With_Salesforce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$crm_name = self::mwb_get_current_crm_property( 'name' );
		$crm_slug = self::mwb_get_current_crm_property( 'slug' );
		$admin    = 'Mwb_Gf_Integration_With_' . $crm_name . '_Admin';

		// Grab form data after validation.
		if ( $admin::is_pro_available_and_active() ) {
			$settings = get_option( 'mwb-' . $crm_slug . '-gf-setting' );

			if ( ! empty( $settings ) && is_array( $settings ) ) {
				if ( isset( $settings['instant_sync'] ) && 'yes' == $settings['instant_sync'] ) {  // phpcs:ignore
					$this->loader->add_filter( 'gform_after_submission', $plugin_public, 'mwb_sf_gf_fetch_input_data', 10, 2 );
				}
			}
		} else {
			$this->loader->add_filter( 'gform_after_submission', $plugin_public, 'mwb_sf_gf_fetch_input_data', 10, 2 );
		}

		// Get user data.
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'mwb_sf_gf_logged_user_info' );

	}

	/**
	 * Register all the hooks related to the template manager of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {

		$plugin_template = new Mwb_Gf_Integration_Salesforce_Template_Manager();

		$this->loader->add_action( 'mwb_salesforce_gf_nav_tab', $plugin_template, 'render_navigation_tab' );
		$this->loader->add_action( 'mwb_salesforce_gf_auth_screen', $plugin_template, 'render_authorisation_screen' );
	}

	/**
	 * Register all hooks related to the Feeds cpt of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_feed_cpt_hooks() {

		$feed_module = new Mwb_Gf_Integration_Salesforce_Feed_Module();

		// Register custom post type.
		$this->loader->add_action( 'init', $feed_module, 'register_feeds_post_type' );

		// Save metadata.
		$this->loader->add_action( 'save_post', $feed_module, 'save_feeds_data' );

	}

	/**
	 * Register all hooks related to ajax request of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_ajax_hooks() {

		$plugin_ajax = new Mwb_Gf_Integration_With_Salesforce_Ajax_Handler();

		// All ajax callbacks.
		$this->loader->add_action( 'wp_ajax_mwb_salesforce_gf_ajax_request', $plugin_ajax, 'mwb_sf_gf_ajax_callback' );

		// Data table callback.
		$this->loader->add_action( 'wp_ajax_get_datatable_logs', $plugin_ajax, 'get_datatable_data_cb' );

		// Select2 ajax.
		$this->loader->add_action( 'wp_ajax_search_contact_forms', $plugin_ajax, 'search_contact_forms' );

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
	 * @return    Mwb_Gf_Integration_With_Salesforce_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Returns current CRM Property.
	 *
	 * @param    string $property Property to retrieve.
	 * @since    1.0.0
	 * @return   string
	 */
	public static function mwb_get_current_crm_property( $property = '' ) {

		$slug  = 'salesforce';
		$value = '';
		switch ( $property ) {
			case 'slug':
				$value = $slug;
				break;
			case 'name':
				$value = ucwords( $slug );
				break;
			case 'version':
				$value = '';
				break;
		}
		return apply_filters( 'mwb_sfgf_manage_crm_property', $value, $property );
	}

}
