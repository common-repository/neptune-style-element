<?php
/**
 * Output CSS for custom fonts in the <head>.
 * CSS output class.
 */
class Neptune_Style_Element_CSS {
	/**
	 * List of elements that need typography options.
	 * @var array
	 */
	protected $elements;

	/**
	 * List of fonts.
	 * @var array
	 */
	protected $fonts;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->elements = Neptune_Style_Element_Elements::instance()->elements;
		$this->fonts    = Neptune_Style_Element_Fonts::instance()->fonts;
		add_action( 'wp_head', array( $this, 'output' ), 20 );
	}

	/**
	 * Output custom CSS of typography.
	 */
	public function output() {
		$css   = array();
		$css[] = $this->import();
		foreach ( $this->elements as $element ) {
			$css[] = $this->element_css( $element );
		}

		if ( $css ) {
			echo "\n<!-- This site uses the Neptune Style Element plugin v1.0 to customize fonts - https://neptunetheme.com -->\n";
			echo "<style>\n" . wp_strip_all_tags( implode( "\n", array_filter( $css ) ) ) . "\n</style>\n";
		}
	}

	/**
	 * Add @import rules for Google Fonts.
	 * @return string
	 */
	protected function import() {
		$fonts     = array();
		$subset    = array();
		$customize = get_option( 'neptune_style_element_customize', array() );
		foreach ( $this->elements as $element ) {
			$key    = isset( $element['id'] ) ? $element['id'] : sanitize_title( $element['label'] );
			$family = isset( $customize["{$key}_font_family"] ) ? $customize["{$key}_font_family"] : '';

			// Don't import system fonts.
			if ( ! $family || 'system' == $this->fonts[ $family ]['source'] ) {
				continue;
			}
			$fonts[ $family ] = sprintf( "%s:%s", $family, $this->fonts[ $family ]['styles'] );
			$subset           = array_merge( $subset, $this->fonts[ $family ]['subset'] );
		}
		if ( ! $fonts ) {
			return '';
		}

		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( implode( ',', array_unique( $subset ) ) ),
		), 'https://fonts.googleapis.com/css' );

		return "@import url($fonts_url);";
	}

	/**
	 * Get CSS for a single element.
	 *
	 * @param  array $element Element parameter
	 *
	 * @return string
	 */
	protected function element_css( $element ) {
		$rules      = array();
		$selector   = $element['selector'];
		$properties = array(
			'color',
			'margin',
			'padding',
			'background_color',
			'font_family',
			'font_style',
			'font_size',
			'line_height',
			'letter_spacing',
			'text_transform',
		);
		$key        = isset( $element['id'] ) ? $element['id'] : sanitize_title( $element['label'] );
		$customize  = get_option( 'neptune_style_element_customize', array() );

		foreach ( $properties as $property ) {

			$value = isset( $customize["{$key}_{$property}"] ) ? $customize["{$key}_{$property}"] : '';

			if ( ! $value ) {
				continue;
			}

			switch ( $property ) {

				case 'font_family':
					$value = sprintf( '"%s", %s', $value, $this->fonts[ $value ]['fallback'] );
					break;
				case 'color':
					break;				
				case 'background_color':
					break;
				case 'margin':
					break;
				case 'padding':
					break;
				case 'font_style':
					$font_weight = $value;
					$font_style  = '';
					if ( false !== strpos( $value, 'italic' ) ) {
						$font_weight = str_replace( 'italic', '', $value );
						$font_style  = 'italic';
					}
					$rules[] = "font-weight: $font_weight;";
					if ( $font_style ) {
						$rules[] = "font-style: $font_style;";
					}
					continue 2;
				case 'font_size':
				case 'letter_spacing':
					$unit = isset( $customize["{$key}_{$property}_unit"] ) ? $customize["{$key}_{$property}_unit"] : 'px';
					$value .= $unit;
					break;
				case 'line_height':
					$unit = isset( $customize["{$key}_{$property}_unit"] ) ? $customize["{$key}_{$property}_unit"] : '';
					$value .= $unit;
					break;
			}
			$rules[] = sprintf( "%s: %s !important;", str_replace( '_', '-', $property ), $value );
		}

		return $rules ? "$selector { " . implode( ' ', $rules ) . ' }' : '';
	}
}