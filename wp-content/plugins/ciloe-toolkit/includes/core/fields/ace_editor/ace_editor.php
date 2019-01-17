<?php if (!defined('ABSPATH')) die; // Cannot access pages directly.

/**
 *
 * Field: ACE Editor
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_ace_editor extends CSFramework_Options
{
    public function __construct($field, $value = '', $unique = '')
    {
        parent::__construct($field, $value, $unique);
    }

    public function output()
    {

        echo $this->element_before();
        echo '<div class="ace-editor-wrapper">';
        echo '<textarea name="' . $this->element_name() . '"' . $this->element_class() . $this->element_attributes() . '>' . $this->element_value() . '</textarea>';
        echo '<div class="ace-editor-container" ' . $this->element_attributes() . '></div>';
        echo '</div>';
        echo $this->element_after();

    }

}