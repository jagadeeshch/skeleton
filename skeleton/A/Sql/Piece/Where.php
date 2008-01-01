<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_Where extends A_Sql_Piece_Abstract {
	protected $glue = ' AND ';
	protected $equation;
		
	public function __construct($equation) {
		$this->equation = $equation;
	}
	
	public function setLogic($logic=null) {
		if ($logic !== null) {
			$this->glue = ' '. trim($logic) .' ';
		}
	}
	
	public function render() {	
		return implode($this->glue, $this->equation->render());
	}
}

?>