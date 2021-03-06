<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists( 'OVIC_Field_text' ) ) {
	class OVIC_Field_text extends OVIC_Fields
	{
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$type = ( !empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

			echo $this->field_before();

			echo '<input type="' . $type . '" name="' . $this->field_name() . '" value="' . $this->value . '"' . $this->field_attributes() . ' />';

			echo $this->field_after();
		}
	}
}
