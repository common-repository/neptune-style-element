<?php
/**
 * Plugin Name: Neptune Style Element
 * Plugin URI: http://neptunetheme.com
 * Description: Easy customize element for your website.
 * Version: 1.0
 * Author: NeptuneTheme
 * Author URI: NeptuneTheme
 * License: GPL2+
 * Text Domain: neptune-style-element
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 * @package Neptune Style Element
 * @author  NeptuneTheme
 */
class Neptune_Style_Element {
	/**
	 * @var object The reference to singleton instance of this class
	 */
	private static $instance;

	/**
	 * Plugin dir path.
	 * @var string
	 */
	public $dir;

	/**
	 * Plugin dir URL.
	 * @var string
	 */
	public $url;

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return object The singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set plugin constants.
	 * Protected constructor to prevent creating a new instance of the singleton via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->dir = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		$this->set_default();

		// Helper classes.
		require_once $this->dir . 'inc/class-neptune-style-element-fonts.php';
		require_once $this->dir . 'inc/class-neptune-style-element-elements.php';
		
		// Customizer.
		require_once $this->dir . 'inc/class-neptune-style-element-customizer.php';

		add_action( 'customize_controls_enqueue_scripts', array($this,'neptune_customize_controls_enqueue_scripts' ) );
		add_action( 'customize_preview_init', array($this,'neptune_customize_preview_init' ) );
		add_action( 'wp_ajax_customize-new-object', array($this,'customize_ajax_add' ) );
		add_action( 'wp_enqueue_scripts', array($this,'neptune_wp_enqueue_style'  ));

		$customizer = new Neptune_Style_Element_Customizer;

		if ( ! is_admin() ) {
			// Output custom CSS
			require_once $this->dir . 'inc/class-neptune-style-element-css.php';
			new Neptune_Style_Element_CSS( $customizer );
		} elseif ( ! $this->get_theme_support( 'no_settings' ) ) {
			// Register plugin settings page. Allow themes to disable settings with theme support.
			require_once $this->dir . 'inc/class-neptune-style-element-settings.php';
			new Neptune_Style_Element_Settings;
		}
	}

	/**
	 * Set plugin default option.
	 */
	protected function set_default() {
		$option = get_option( 'neptune-style-element' );

		if ( ! empty( $option ) ) {
			return;
		}
		$option = array(
			'elements' => array(
				array(
					'label'    => esc_html__( 'Site Title', 'neptune-style-element' ),
					'selector' => '.site-title a',
				),
			),
		);

		// Allow theme to setup the default elements via theme support.
		if ( $default_elements = $this->get_theme_support( 'default_elements' ) ) {
			$option['elements'] = $default_elements;
		}
		add_option( 'neptune-style-element', $option );
	}

	/**
	 * Get theme support.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get_theme_support( $name ) {
		$theme_support = get_theme_support( 'neptune-style-element' );
		if ( ! $theme_support || empty( $theme_support[0] ) || empty( $theme_support[0][ $name ] ) ) {
			return false;
		}

		return $theme_support[0][ $name ];
	}


	/**
	 * This function enqueues scripts and styles in the Customizer.
	 */

	public function neptune_customize_controls_enqueue_scripts() {
		/*
		 * Our Customizer script
		 *
		 * Dependencies: Customizer Controls script (core)
		 */
		wp_enqueue_script( 'neptune-customizer-script', $this->url . '/js/customizer-script.js', array( 'customize-controls' ) );

		wp_localize_script(
			'neptune-customizer-script',
			'NeptuneCustomData',
			array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	

	/**
	 * Ajax storing of newly selected objects to style
	 *
	 * @return void
	 */
	public function customize_ajax_add() {

		$option = get_option( 'neptune-style-element' );
		$elem_slug =  sanitize_title( filter_input( INPUT_POST, 'label') );
		array_push( $option['elements'],
			array(
				'label'    => filter_input( INPUT_POST, 'label'),
				'selector' => filter_input( INPUT_POST, 'selector'),
			)			
		);

		/**
		 * Check our default value for RGB value and convert to hex if found
		 */
		update_option( 'neptune-style-element', $option );
		setcookie( 'focus', 1, time()+ 10);
		setcookie( 'elem' , $elem_slug, time()+ 10);
		wp_die();
	}

	/**
	 * This function is triggered on the initialization of the Previewer in the Customizer. We add actions
	 * that pertain to the Previewer window here. The actions added here are triggered only in
	 * the Previewer and not in the Customizer.
	 */

	public function neptune_customize_preview_init() {
		// Enqueue previewer scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'neptune_wp_enqueue_scripts' ) );

	}
	/**
	 * This function is called only on the Previwer and enqueues scripts and styles.
	 */
	public function neptune_wp_enqueue_scripts() {
		/*
		 * Our Customizer script
		 *
		 * Dependencies: Customizer Preview Widgets script (core)
		 */
		wp_enqueue_script( 'neptune-customizer-previewer', $this->url . '/js/customizer-previewer.js', array( 'customize-preview-widgets' ) );
				
	}
	public function neptune_wp_enqueue_style(){
		wp_enqueue_style( 'neptune-customizer-style', plugin_dir_url( __FILE__ ) . '/css/style.css', '', '1.0.0' );
	}

}

add_action( 'init', array( Neptune_Style_Element::instance(), 'init' ) );



