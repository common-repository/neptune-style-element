<?php
/**
 * Change fonts in the Customizer.
 *
 * Customizer class.
 */
class Neptune_Style_Element_Customizer {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Register typography settings in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register( $wp_customize ) {
		if ( empty( Neptune_Style_Element_Elements::instance()->elements ) ) {
			return;
		}

		// Custom control for font family.
		require_once Neptune_Style_Element::instance()->dir . 'inc/class-neptune-style-element-font-family-control.php';

		// Typography section.
		$wp_customize->add_panel( 'neptune-style-element', array(
			'title' => esc_html__( 'Neptune Element Style', 'neptune-style-element' ),
		) );

		foreach ( Neptune_Style_Element_Elements::instance()->elements as $element ) {
			$key = isset( $element['id'] ) ? $element['id'] : sanitize_title( $element['label'] );

			// Section
			$wp_customize->add_section( $key . '_font', array(
				'title' => $element['label'],
				'panel' => 'neptune-style-element',
			) );

			// Font Color
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_color]", array(
				'type'				=> 'option',
				'capability' 		=> 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh'
			) );
			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				"neptune_style_element_customize[{$key}_color]",
				array(
					'label'    => esc_html__( 'Color','neptune-style-element'),
					'section'  => $key . '_font',
					'panel'   =>  'neptune-style-element',
					'settings' => "neptune_style_element_customize[{$key}_color]"
				)
			) );
			// Background Color
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_background_color]", array(
				'type'				=> 'option',
				'capability' 		=> 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh'
			) );
			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				"neptune_style_element_customize[{$key}_background_color]",
				array(
					'label'    => esc_html__( 'Background Color','neptune-style-element'),
					'section'  => $key . '_font',
					'panel'   =>  'neptune-style-element',
					'settings' => "neptune_style_element_customize[{$key}_background_color]"
				)
			) );
			// Margin
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_margin]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_margin]", array(
				'label'      => esc_html__( 'Margin', 'neptune-style-element' ),
				'description'=> esc_html__('top right bottom left (examle: 2px 3px 4px 5px)', 'neptune-style-element'),
				'type'       => 'text',
				'section'    => $key . '_font',
				'panel'      => 'neptune-style-element',
			) );

			// Margin
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_padding]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_padding]", array(
				'label'      => esc_html__( 'Padding', 'neptune-style-element' ),
				'description'=> esc_html__('top right bottom left (examle: 2px 3px 4px 5px)', 'neptune-style-element'),
				'type'       => 'text',
				'section'    => $key . '_font',
				'panel'      => 'neptune-style-element',
			) );
			// Font family
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_font_family]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( new Neptune_Style_Element_Font_Family_Control(
				$wp_customize,
				"neptune_style_element_customize[{$key}_font_family]",
				array(
					'label'   => esc_html__( 'Font family', 'neptune-style-element' ),
					'section' => $key . '_font',
					'panel'   => 'neptune-style-element',
				)
			) );

			// Font style
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_font_style]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_font_style]", array(
				'label'   => esc_html__( 'Font style', 'neptune-style-element' ),
				'type'    => 'select',
				'choices' => array(
					''          => esc_html__( '- No change -', 'neptune-style-element' ),
					'100'       => esc_html__( 'Thin 100', 'neptune-style-element' ),
					'100italic' => esc_html__( 'Thin 100 Italic', 'neptune-style-element' ),
					'200'       => esc_html__( 'Extra-Light 200', 'neptune-style-element' ),
					'200italic' => esc_html__( 'Extra-Light 200 Italic', 'neptune-style-element' ),
					'300'       => esc_html__( 'Light 300', 'neptune-style-element' ),
					'300italic' => esc_html__( 'Light 300 Italic', 'neptune-style-element' ),
					'400'       => esc_html__( 'Normal 400', 'neptune-style-element' ),
					'400italic' => esc_html__( 'Normal 400 Italic', 'neptune-style-element' ),
					'500'       => esc_html__( 'Medium 500', 'neptune-style-element' ),
					'500italic' => esc_html__( 'Medium 500 Italic', 'neptune-style-element' ),
					'600'       => esc_html__( 'Semi-Bold 600', 'neptune-style-element' ),
					'600italic' => esc_html__( 'Semi-Bold 600 Italic', 'neptune-style-element' ),
					'700'       => esc_html__( 'Bold 700', 'neptune-style-element' ),
					'700italic' => esc_html__( 'Bold 700 Italic', 'neptune-style-element' ),
					'800'       => esc_html__( 'Extra-Bold 800', 'neptune-style-element' ),
					'800italic' => esc_html__( 'Extra-Bold 800 Italic', 'neptune-style-element' ),
					'900'       => esc_html__( 'Ultra-Bold 900', 'neptune-style-element' ),
					'900italic' => esc_html__( 'Ultra-Bold 900 Italic', 'neptune-style-element' ),
				),
				'section' => $key . '_font',
				'panel'   => 'neptune-style-element',
			) );

			// Font size
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_font_size]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_font_size]", array(
				'label'      => esc_html__( 'Font size', 'neptune-style-element' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'neptune-style-element',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_font_size_unit]", array(
				'type'              => 'option',
				'capability'        => 'manage_options',
				'default'           => 'px',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_font_size_unit]", array(
				'type'    => 'select',
				'choices' => array(
					'px'  => esc_html__( 'px', 'neptune-style-element' ),
					'em'  => esc_html__( 'em', 'neptune-style-element' ),
					'rem' => esc_html__( 'rem', 'neptune-style-element' ),
					'%'   => esc_html__( '%', 'neptune-style-element' ),
				),
				'section' => $key . '_font',
				'panel'   => 'neptune-style-element',
			) );

			// Line height
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_line_height]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_line_height]", array(
				'label'      => esc_html__( 'Line height', 'neptune-style-element' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'neptune-style-element',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_line_height_unit]", array(
				'default'           => '',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_line_height_unit]", array(
				'type'    => 'select',
				'choices' => array(
					''    => esc_html__( '-', 'neptune-style-element' ),
					'px'  => esc_html__( 'px', 'neptune-style-element' ),
					'em'  => esc_html__( 'em', 'neptune-style-element' ),
					'rem' => esc_html__( 'rem', 'neptune-style-element' ),
					'%'   => esc_html__( '%', 'neptune-style-element' ),
				),
				'section' => $key . '_font',
				'panel'   => 'neptune-style-element',
			) );

			// Letter spacing
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_letter_spacing]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_letter_spacing]", array(
				'label'      => esc_html__( 'Letter spacing', 'neptune-style-element' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'neptune-style-element',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_letter_spacing_unit]", array(
				'type'              => 'option',
				'capability'        => 'manage_options',
				'default'           => 'px',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_letter_spacing_unit]", array(
				'type'    => 'select',
				'choices' => array(
					'px'  => esc_html__( 'px', 'neptune-style-element' ),
					'em'  => esc_html__( 'em', 'neptune-style-element' ),
					'rem' => esc_html__( 'rem', 'neptune-style-element' ),
					'%'   => esc_html__( '%', 'neptune-style-element' ),
				),
				'section' => $key . '_font',
				'panel'   => 'neptune-style-element',
			) );

			// Text transform
			$wp_customize->add_setting( "neptune_style_element_customize[{$key}_text_transform]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "neptune_style_element_customize[{$key}_text_transform]", array(
				'label'   => esc_html__( 'Text transform', 'neptune-style-element' ),
				'type'    => 'select',
				'choices' => array(
					''           => esc_html__( '- No change -', 'neptune-style-element' ),
					'normal'     => esc_html__( 'None', 'neptune-style-element' ),
					'lowercase'  => esc_html__( 'lowercase', 'neptune-style-element' ),
					'uppercase'  => esc_html__( 'UPPERCASE', 'neptune-style-element' ),
					'capitalize' => esc_html__( 'Capitalize', 'neptune-style-element' ),
				),
				'section' => $key . '_font',
				'panel'   => 'neptune-style-element',
			) );
		}
	}

	/**
	 * Select sanitization callback.
	 *
	 * @param string $input Slug to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
	 */
	public function sanitize_select( $input, $setting ) {
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return isset( $choices[ $input ] ) ? $input : $setting->default;
	}
	/**
	 * Enqueue scripts and styles for the customizer.
	 */
	public function enqueue() {
		wp_enqueue_style( 'neptune-style-element-customizer', Neptune_Style_Element::instance()->url . 'css/customizer.css' );
		wp_enqueue_script( 'neptune-style-element-customizer', Neptune_Style_Element::instance()->url . 'js/customizer.js', array( 'jquery' ), '1.0.0', true );
		
	}
}