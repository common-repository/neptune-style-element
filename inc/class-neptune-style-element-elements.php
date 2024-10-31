<?php
/**
 * A collection of helper functions for elements.
 * Elements class.
 */
class Neptune_Style_Element_Elements {
	/**
	 * @var object The reference to singleton instance of this class
	 */
	private static $instance;

	/**
	 * List of elements.
	 * @var array
	 */
	public $elements;

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
	 * Parse font configuration file.
	 * Protected constructor to prevent creating a new instance of the singleton via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->setup();
	}

	/**
	 * Get all registered elements.
	 */
	protected function setup() {
		$option         = get_option( 'neptune-style-element' );
		$this->elements = ! empty( $option['elements'] ) && is_array( $option['elements'] ) ? $option['elements'] : array();
		$this->elements = apply_filters( 'neptune_style_element_elements', $this->elements );
		$this->elements = array_map( array( $this, 'normalize' ), $this->elements );
	}

	/**
	 * Set default options for element.
	 *
	 * @param array $element
	 *
	 * @return array|bool
	 */
	protected function normalize( $element ) {
		$element = wp_parse_args( $element, array(
			'label'    => '',
			'selector' => '',
		) );

		return $element['label'] && $element['selector'] ? $element : false;
	}
}