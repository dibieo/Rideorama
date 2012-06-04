<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cellphone
 *
 * @author ovo
 */
class Application_Model_PhoneValidator extends Zend_Validate_Abstract {
    //put your code here
     const NOT_PHONE = 'notPhone';
    const INVALID_PHONE = 'invalidCellPhone';
    const STRING_EMPTY = 'stringEmpty';

    protected $_messageTemplates = array(
        self::NOT_PHONE => "'%value%' is not a phone number",
        self::INVALID_PHONE => "'%value%' is not a cell phone number, starting with 123,213 or 231",
        self::STRING_EMPTY => "please provide a cell phone number"
    );

    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value))
        {
            $this->_error(self::NOT_PHONE);
            return false;
        }
        $this->_setValue((string) $value);

        $numbersOnly = ereg_replace("[^0-9]","", str_replace("-", "", $value));
        if (strlen($numbersOnly) != 10)
        {
            $this->_error(self::NOT_PHONE);
            return false;
        }
        //Cell phones have an area code of 123,213 or 23
        return true;
    }
}

?>
