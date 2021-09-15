<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Select Preview
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists( 'OVIC_Field_select_preview' ) ) {
	class OVIC_Field_select_preview extends OVIC_Fields
	{
		public function __construct( $field, $value = '', $unique = '', $where = '' )
		{
			parent::__construct( $field, $value, $unique, $where );
		}

		public function render()
		{
			echo $this->field_before();
			if ( isset( $this->field['options'] ) ) {
				echo '<div class="container-select_preview">';
				$options    = $this->field['options'];
				$class      = $this->field_class();
				$options    = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options ) );
				$extra_name = ( isset( $this->field['attributes']['multiple'] ) ) ? '[]' : '';
				$chosen_rtl = ( is_rtl() && strpos( $class, 'chosen' ) ) ? 'chosen-rtl' : '';
				echo '<select name="' . $this->field_name( $extra_name ) . '"' . $this->field_class( $chosen_rtl ) . $this->field_attributes() . ' class="ovic_select_preview">';
				echo ( isset( $this->field['default_option'] ) ) ? '<option value="">' . $this->field['default_option'] . '</option>' : '';
				if ( !empty( $options ) ) {
					foreach ( $options as $key => $value ) {
						$data_url = !empty( $value['url'] ) ? $value['url'] : 'javascript:void(0);';
						echo '<option data-preview="' . $value['preview'] . '" data-url="' . $data_url . '" value="' . $key . '" ' . selected( $this->value, $key ) . '>' . $value['title'] . '</option>';
					}
				}
				echo '</select>';
				$url     = 'javascript:void(0);';
				$target  = '_self';
				$preview = '#';
				if ( !empty( $this->field['options'][$this->value]['preview'] ) ) {
					$preview = $this->field['options'][$this->value]['preview'];
				}
				if ( !empty( $this->field['options'][$this->value]['url'] ) ) {
					$url    = $this->field['options'][$this->value]['url'];
					$target = '_blank';
				}
				echo '<div class="image-preview" style="margin-top:10px;display:inline-block;width:100%;">';
				echo '<a href="' . $url . '" target="' . $target . '" style="display:inline-block;"><img src="' . $preview . '" alt="Preview"></a>';
				echo '</div>';
				echo '</div>';
			}
			echo $this->field_after();
		}
	}
}