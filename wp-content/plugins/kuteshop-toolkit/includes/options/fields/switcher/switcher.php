<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists( 'OVIC_Field_switcher' ) ) {
	class OVIC_Field_switcher extends OVIC_Fields
	{
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$active     = ( !empty( $this->value ) ) ? ' ovic--active' : '';
			$text_on    = ( !empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'ovic-addon-toolkit' );
			$text_off   = ( !empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'ovic-addon-toolkit' );
			$text_width = ( !empty( $this->field['text_width'] ) ) ? ' style="width: ' . $this->field['text_width'] . 'px;"' : '';

			echo $this->field_before();

			echo '<div class="ovic--switcher' . $active . '"' . $text_width . '>';
			echo '<span class="ovic--on">' . $text_on . '</span>';
			echo '<span class="ovic--off">' . $text_off . '</span>';
			echo '<span class="ovic--ball"></span>';
			echo '<input type="text" name="' . $this->field_name() . '" value="' . $this->value . '"' . $this->field_attributes() . ' />';
			echo '</div>';

			echo ( !empty( $this->field['label'] ) ) ? '<span class="ovic--label">' . $this->field['label'] . '</span>' : '';

			echo '<div class="clear"></div>';

			echo $this->field_after();
		}
	}
}
