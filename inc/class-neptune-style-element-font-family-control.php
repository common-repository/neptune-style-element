<?php
/**
 * Custom control for font family.
 * Font family control class.
 */
class Neptune_Style_Element_Font_Family_Control extends WP_Customize_Control {
	/**
	 * The type of customize control being rendered.
	 * @var string
	 */
	public $type = 'neptune_style_element_font_family';

	/**
	 * Render the control's content.
	 * Allows the content to be overridden without having to rewrite the wrapper.
	 */
	protected function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<select class="neptune-font-family" <?php $this->link(); ?>>
				<option value=""><?php esc_html_e( '- No change -', 'neptune-style-element' ); ?></option>
				<?php
				$sources = Neptune_Style_Element_Fonts::instance()->sources;
				foreach ( $sources as $source ) {
					echo '<optgroup label="' . esc_attr( $source['label'] ) . '">';
					foreach ( $source['fonts'] as $family => $params ) {
						printf(
							'<option value="%s"%s data-styles="%s">%s</option>',
							esc_attr( $family ),
							selected( $this->value(), $family, false ),
							esc_attr( $params['styles'] ),
							esc_html( $family )
						);
					}
					echo '</optgroup>';
				}
				?>
			</select>
		</label>
		<?php
	}
}