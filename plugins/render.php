<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
class render {
    function __construct($param = false) {
        if ($param) {
            foreach($param as $arg => $value) {
                $this->$arg = $value;
            }
        }
    }

    function rende($tpl, $tpl_values = '', $return = false) {
        if (file_exists($tpl)) {
            ob_start();
            
            if (!empty($tpl_values)) {
                extract($tpl_values);
            }
            include($tpl);

            if ($return) {
                $buffer = ob_get_clean();
                return $buffer;
            } else {
                ob_end_flush();
                return true;
            }
        } else {
            return false;
        }
    }
}
?>
