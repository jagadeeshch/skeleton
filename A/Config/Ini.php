<?php
include_once 'A/Config/Abstract.php';

class A_Config_Ini extends A_Config_Abstract {
   protected function _loadFile() {
      return parse_ini_file($this->_filename, true);
   }
}