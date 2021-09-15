<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'OVIC_Field_group' ) ) {
	class OVIC_Field_group extends OVIC_Fields
	{
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, array(
					'max'                    => 0,
					'min'                    => 0,
					'fields'                 => array(),
					'button_title'           => esc_html__( 'Add New', 'ovic-addon-toolkit' ),
					'accordion_title_prefix' => '',
					'accordion_title_number' => false,
					'accordion_title_auto'   => true,
				)
			);

			$field_unique = ( ! empty( $this->unique ) ) ? '[' . $this->field['id'] . ']' : $this->field['id'];
			$title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
			$title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
			$title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

			if ( ! empty( $this->parent ) && preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->parent ) ) {
				echo '<div class="ovic-notice ovic-notice-danger">' . esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'ovic-addon-toolkit' ) . '</div>';
			} else {
				echo $this->field_before();

				echo '<div class="ovic-cloneable-item ovic-cloneable-hidden">';

				echo '<div class="ovic-cloneable-helper">';
				echo '<i class="ovic-cloneable-sort fa fa-arrows"></i>';
				echo '<i class="ovic-cloneable-clone fa fa-clone"></i>';
				echo '<i class="ovic-cloneable-remove ovic-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'ovic-addon-toolkit' ) . '"></i>';
				echo '</div>';

				echo '<h4 class="ovic-cloneable-title">';
				echo '<span class="ovic-cloneable-text">';
				echo ( $title_number ) ? '<span class="ovic-cloneable-title-number"></span>' : '';
				echo ( $title_prefix ) ? '<span class="ovic-cloneable-title-prefix">' . $title_prefix . '</span>' : '';
				echo ( $title_auto ) ? '<span class="ovic-cloneable-value"><span class="ovic-cloneable-placeholder"></span></span>' : '';
				echo '</span>';
				echo '</h4>';

				echo '<div class="ovic-cloneable-content">';
				foreach ( $this->field['fields'] as $field ) {
					$field_parent  = $this->parent . '[' . $this->field['id'] . ']';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

					echo OVIC::field( $field, $field_default, '_nonce', 'field/group', $field_parent );
				}
				echo '</div>';

				echo '</div>';

				echo '<div class="ovic-cloneable-wrapper ovic-data-wrapper" data-title-number="' . $title_number . '" data-unique-id="' . $this->unique . '" data-field-id="' . $field_unique . '" data-max="' . $args['max'] . '" data-min="' . $args['min'] . '">';

				if ( ! empty( $this->value ) && is_array( $this->value ) ) {

					$num = 0;

					foreach ( $this->value as $value ) {
						$first_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';
						$first_value = ( isset( $value[ $first_id ] ) ) ? $value[ $first_id ] : '';

						echo '<div class="ovic-cloneable-item">';

						echo '<div class="ovic-cloneable-helper">';
						echo '<i class="ovic-cloneable-sort fa fa-arrows"></i>';
						echo '<i class="ovic-cloneable-clone fa fa-clone"></i>';
						echo '<i class="ovic-cloneable-remove ovic-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'ovic-addon-toolkit' ) . '"></i>';
						echo '</div>';

						echo '<h4 class="ovic-cloneable-title">';
						echo '<span class="ovic-cloneable-text">';
						echo ( $title_number ) ? '<span class="ovic-cloneable-title-number">' . ( $num + 1 ) . '.</span>' : '';
						echo ( $title_prefix ) ? '<span class="ovic-cloneable-title-prefix">' . $title_prefix . '</span>' : '';
						echo ( $title_auto ) ? '<span class="ovic-cloneable-value">' . $first_value . '</span>' : '';
						echo '</span>';
						echo '</h4>';

						echo '<div class="ovic-cloneable-content">';

						foreach ( $this->field['fields'] as $field ) {
							$field_parent = $this->parent . '[' . $this->field['id'] . ']';
							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $value[ $field['id'] ] ) ) ? $value[ $field['id'] ] : '';

							echo OVIC::field( $field, $field_value, $field_unique, 'field/group', $field_parent );
						}

						echo '</div>';

						echo '</div>';

						$num ++;
					}
				}

				echo '</div>';

				echo '<div class="ovic-cloneable-alert ovic-cloneable-max">' . esc_html__( 'You can not add more than', 'ovic-addon-toolkit' ) . ' ' . $args['max'] . '</div>';
				echo '<div class="ovic-cloneable-alert ovic-cloneable-min">' . esc_html__( 'You can not remove less than', 'ovic-addon-toolkit' ) . ' ' . $args['min'] . '</div>';

				echo '<a href="#" class="button button-primary ovic-cloneable-add">' . $args['button_title'] . '</a>';

				echo $this->field_after();
			}
		}

		public function enqueue()
		{
			if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}
	}
}
