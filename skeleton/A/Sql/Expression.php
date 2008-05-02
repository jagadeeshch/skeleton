<?php

class A_Sql_Expression {

	/**
	 * data
	*/		
	protected $data = array();
	/**
	 * operators
	*/	
	protected $operators = array('>', '<', '>=', '<=', '=', '<>', 'IN', 'NOT IN', ' LIKE ', ' NOT LIKE ');

	/**
	 * db
	*/		
	protected $db;
	
	/**
	 * __construct()
	*/	
	public function __construct($data, $value=null) {
		if ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $data;
		}	
	}

	/**
	 * setEscapeCallback()
	*/		
	public function setDb($db) {
		$this->db = $db;
		return $this;
	}			

	/**
	 * render()
	*/		
	public function render($logic='AND') {
		if (is_string($this->data)) {
			$this->data = array($this->data);
		}
		return implode(' '. $logic.' ', array_map(array($this, 'buildExpression'), array_keys($this->data), array_values($this->data)));
	}

	/**
	 * escape()
	*/		
	public function quoteEscape($value) {
		$value = $this->db ? $this->db->escape($value) : addslashes($value);
		return "'" . $value . "'";
	}

	/**
	 * buildExpression()
	*/	
	protected function buildExpression($key, $value) {
		if (is_int($key)) {
			$key = $value;
			$value = null;
		}
		if (preg_match('!('. implode('|', $this->operators).')$!i', $key, $matches)) { //operator detected
			if (is_array($value)) {
				$value = '('. implode(', ', array_map(array($this, 'quoteEscape'), $value)) .')';
			} else {
				$value = $this->quoteEscape($value);
			}
			return str_replace($matches[1], '', $key) . $matches[1] .$value;
		} elseif ($value !== null) {
			return $key .'='. $this->quoteEscape($value);
		}
		return $key;
	}

	public function __toString() {
		return $this->render();
	}

}
