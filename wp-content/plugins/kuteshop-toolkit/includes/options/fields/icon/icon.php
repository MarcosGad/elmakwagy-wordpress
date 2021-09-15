<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists( 'OVIC_Field_icon' ) ) {
	class OVIC_Field_icon extends OVIC_Fields
	{
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, array(
					'button_title' => esc_html__( 'Add Icon', 'ovic-addon-toolkit' ),
					'remove_title' => esc_html__( 'Remove Icon', 'ovic-addon-toolkit' ),
				)
			);

			echo $this->field_before();

			$nonce  = wp_create_nonce( 'ovic_icon_nonce' );
			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo '<div class="ovic-icon-select">';
			echo '<span class="ovic-icon-preview' . $hidden . '"><i class="' . $this->value . '"></i></span>';
			echo '<a href="#" class="button button-primary ovic-icon-add" data-nonce="' . $nonce . '">' . $args['button_title'] . '</a>';
			echo '<a href="#" class="button ovic-warning-primary ovic-icon-remove' . $hidden . '">' . $args['remove_title'] . '</a>';
			echo '<input type="text" name="' . $this->field_name() . '" value="' . $this->value . '" class="ovic-icon-value"' . $this->field_attributes() . ' />';
			echo '</div>';

			echo $this->field_after();
		}
	}
}
