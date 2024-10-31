<?php
/**
 * Create settings page for fonts configuration.
 * Settings page class.
 */
class Neptune_Style_Element_Settings {
	/**
	 * Add hooks to create settings page and register settings.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add plugin settings menu.
	 */
	public function add_menu() {
		$page = add_menu_page(
			esc_html__( 'Neptune Style Element', 'neptune-style-element' ),
			esc_html__( 'Neptune Style Element', 'neptune-style-element' ),
			'manage_options',
			'neptune-style-element',
			array( $this, 'render_page' ),
			'dashicons-admin-customizer'
		);
		add_action( "admin_print_styles-$page", array( $this, 'enqueue' ) );
	}

	/**
	 * Render settings page.
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Neptune Style Element', 'neptune-style-element' ); ?></h1>
			<p><?php esc_html_e( 'Please add and configure elements that you want to style.', 'neptune-style-element' ); ?></p>
			<p><?php echo wp_kses_post( sprintf( __( 'After saving, please <a href="%s">go to the Customizer</a> to change style settings and preview them in real-time.', 'neptune-style-element' ), esc_url( admin_url( 'customize.php' ) ) ) ); ?></p>
			<form method="POST" action="options.php">
				<?php
				settings_fields( 'neptune-style-element' );
				do_settings_sections( 'neptune-style-element' );
				?>
				<p class="submit">
					<?php submit_button( esc_html__( 'Save Changes', 'neptune-style-element' ), 'primary', 'submit', false ); ?>
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize', 'neptune-style-element' ); ?></a>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the settings page.
	 */
	public function enqueue() {
		wp_enqueue_style( 'neptune-style-element-settings', Neptune_Style_Element::instance()->url . 'css/settings.css' );
		wp_enqueue_script( 'neptune-style-element-settings', Neptune_Style_Element::instance()->url . 'js/settings.js', array(
			'jquery',
			'wp-util',
			'backbone',
		), '1.0.0', true );
		wp_localize_script( 'neptune-style-element-settings', 'Neptune_Style_Element', get_option( 'neptune-style-element' ) );
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'neptune-style-element', 'neptune-style-element', array( $this, 'sanitize' ) );
		add_settings_section(
			'default',
			'',
			'',
			'neptune-style-element'
		);
		add_settings_field(
			'elements',
			esc_html__( 'Elements', 'neptune-style-element' ),
			array( $this, 'render_elements' ),
			'neptune-style-element'
		);
	}

	/**
	 * Sanitize options. Save all elements as un no-associate array.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function sanitize( $options ) {
		$options['elements'] = isset( $options['elements'] ) && is_array( $options['elements'] ) ? array_values( $options['elements'] ) : array();

		return $options;
	}

	/**
	 * Render elements field.
	 */
	public function render_elements() { 
		?>
		<div id="neptune-style-element-elements">
			<a href="javascript:;" id="neptune-style-element-add" class="button"><?php esc_html_e( '+ Add Element', 'neptune-style-element' ); ?></a>
		</div>
		<script type="text/template" id="tmpl-neptune-style-element-element">
			<label class="neptune-style-element-element__label">
				<span class="neptune-style-element-element__title"><?php esc_html_e( 'Label' ); ?></span>
				<input type="text" name="<?php echo esc_attr( "neptune-style-element[elements][{{ data.index }}][label]" ); ?>" value="{{ data.label }}">
				<small class="description"><?php esc_html_e( 'The element label displayed in the Customizer.', 'neptune-style-element' ); ?></small>
			</label>
			<label class="neptune-style-element-element__selector">
				<span class="neptune-style-element-element__title"><?php esc_html_e( 'Selector' ); ?></span>
				<input type="text" class="regular-text" name="<?php echo esc_attr( "neptune-style-element[elements][{{ data.index }}][selector]" ); ?>" value="{{ data.selector }}">
				<small class="description"><?php esc_html_e( 'Separate multiple selectors with commas.', 'neptune-style-element' ); ?></small>
			</label>
			<a href="javascript:;" class="neptune-style-element-element__delete" title="<?php esc_attr_e( 'Remove element', 'neptune-style-element' ); ?>"><i class="dashicons dashicons-minus"></i></a>
		</script>
		<?php
	}
}
