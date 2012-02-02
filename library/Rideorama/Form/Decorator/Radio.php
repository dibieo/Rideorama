<?php
/**
     * Description of Radio
 */
class Rideorama_Form_Decorator_Radio
    extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $placement = $this->getPlacement();
        $text = $this->getOption('text');
        $output = '<p class="more_information">' . $text . '</p>';
        switch($placement)
        {
            case 'PREPEND':
                return $output . $content;
            case 'APPEND':
            default:
                return $content . $output;
        }
    }

}
