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
class CSFramework_Option_select_preview extends CSFramework_Options
{

	public function __construct( $field, $value = '', $unique = '' )
	{
		parent::__construct( $field, $value, $unique );
	}

	public function output()
	{

		echo $this->element_before();

		if ( isset( $this->field[ 'options' ] ) ) {
			echo '<div class="container-select-preview">';
			$options    = $this->field[ 'options' ];
			$class      = $this->element_class();
			$options    = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
			$extra_name = ( isset( $this->field[ 'attributes' ][ 'multiple' ] ) ) ? '[]' : '';
			$chosen_rtl = ( is_rtl() && strpos( $class, 'chosen' ) ) ? 'chosen-rtl' : '';

			echo '<select name="' . $this->element_name( $extra_name ) . '"' . $this->element_class( $chosen_rtl ) . $this->element_attributes() . ' class="cs-select-images">';

			echo ( isset( $this->field[ 'default_option' ] ) ) ? '<option value="">' . $this->field[ 'default_option' ] . '</option>' : '';

			if ( !empty( $options ) ) {
				foreach ( $options as $key => $value ) {
					echo '<option data-preview="'.$value['preview'].'" value="' . $key . '" ' . $this->checked( $this->element_value(), $key, 'selected' ) . '>' . $value['title'] . '</option>';
				}
			}

			echo '</select>';
			$url = '';
			if ( isset( $this->field[ 'options' ][ $this->value ] ) ) {
				$url = $this->field[ 'options' ][ $this->value ][ 'preview' ];
			}
			echo '<div class="preview" style="margin-top: 10px;display: inline-block;width: 100%;"><img src="' . esc_url( $url ) . '"></div>';
			echo "</div>";
		}

		echo $this->element_after();

	}

}
