<?php
/**
 * OutSide setup
 *
 * @package OutSide
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main OutSide Class.
 *
 * @class   OutSide
 * @version 1.0.0
 */
final class OutSide {

	/**
	 * OutSide version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * The single instance of the class.
	 *
	 * @var   OutSide
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main OutSide Instance.
	 *
	 * Ensures only one instance of OutSide is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @static
	 * @see    outside()
	 * @return OutSide - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		outside_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'outside' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		outside_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'outside' ), '1.0.0' );
	}

	/**
	 * EverestForms Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'outside_plugin_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'init', array( $this, 'create_block_init') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	public function create_block_init() {
		register_block_type( dirname(OUTSIDE_PLUGIN_FILE) . '/build' );
	}

	/**
	* Enqueue scripts.
	*/
   public function enqueue_scripts() {
	   wp_enqueue_style( 'outside-styles', OUTSIDE_URI .'/assets/css/style.css', array(), '1.0.0' );
   }

	/**
	 * Wrapper for outside_doing_it_wrong.
	 *
	 * @since 1.0.0
	 * @param string $function Function used.
	 * @param string $message  Message to log.
	 * @param string $version  Version the message was added in.
	 */
	public function outside_doing_it_wrong( $function, $message, $version ) {
		// @codingStandardsIgnoreStart
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

		if ( is_ajax() ) {
			do_action( 'doing_it_wrong_run', $function, $message, $version );
			error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
		} else {
			_doing_it_wrong( $function, $message, $version );
		}
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Define EVF Constants.
	 */
	private function define_constants() {
		$this->define( 'OUTSIDE_ABSPATH', dirname( OUTSIDE_PLUGIN_FILE ) . '/' );
		$this->define( 'OUTSIDE_PLUGIN_BASENAME', plugin_basename( OUTSIDE_PLUGIN_FILE ) );
		$this->define( 'OUTSIDE_VERSION', $this->version );
		$this->define( 'OUTSIDE_URI', plugins_url( '/', OUTSIDE_PLUGIN_FILE ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		include_once OUTSIDE_ABSPATH . 'includes/outside-functions.php';
		include_once OUTSIDE_ABSPATH . 'includes/class-outside-post.php';
		include_once OUTSIDE_ABSPATH . 'includes/class-outside-api.php';
	}

	/**
	 * Init OutSide when WordPress Initialises.
	 */
	public function init() {
		// Before init action.
		do_action( 'before_outside_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'outside_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/outside/outside-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/outside-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			// @todo Remove when start supporting WP 5.0 or later.
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'outside' );

		unload_textdomain( 'outside' );
		load_textdomain( 'outside', WP_LANG_DIR . '/outside/outside-' . $locale . '.mo' );
		load_plugin_textdomain( 'outside', false, plugin_basename( dirname( OUTSIDE_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Get the plugin url.
	 *
	 * @param String $path Path.
	 *
	 * @return string
	 */
	public function plugin_url( $path = '/' ) {
		return untrailingslashit( plugins_url( $path, OUTSIDE_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( OUTSIDE_PLUGIN_FILE ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'outside_template_path', 'outside/' );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}
}