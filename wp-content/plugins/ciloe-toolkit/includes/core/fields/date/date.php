<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSFramework_Option_date' ) ) {
  class CSFramework_Option_date extends CSFramework_Options {

    public function __construct( $field, $value = '', $unique = '' ) {
      parent::__construct( $field, $value, $unique );
    }

    public function output(){

      $defaults = array(
        'dateFormat' => ( ! empty( $this->field['format'] ) ) ? $this->field['format'] : 'mm/dd/yy',
      );

      $options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

      $args = wp_parse_args( $options, $defaults );

      echo $this->element_before();
      echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
      echo '<textarea class="cs-datepicker-options hidden">'. json_encode( $args ) .'</textarea>';
      echo $this->element_after();

    }

  }
}
