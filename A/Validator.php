<?php

class A_Validator {
	protected $chain = array();
	protected $excludes = array();		// array of names not to be validated
	protected $errorMsg = array();
	protected $dir = 'A_Rule_';
	
	public function addRule($rule) {
		if (is_string($rule)) {
			$rule = func_get_args();
		}
		$this->chain[] = $rule;
	}
		
	public function exclude($names=array()) {
		if (is_string($names)) {
			$names = array($names);
		}
		$this->excludes = $names;
	}
		
	public function validate ($container, $rule=null) {
		if ($rule) {
			$this->chain = $rule;
		}
		$this->errorMsg = array();
		foreach ($this->chain as $key => $rule) {
			if ($this->excludes && (in_array($rule->getName(), $this->excludes))) {
				continue;
			}
			// class names with params are added as arrays
			if (is_array($rule)) {
				$name = array_shift($rule);
				// can use built-in rules and $this->dir will be used
				if(strstr($name, '_') === false) {
					$name = $this->dir . ucfirst($name);
				}
				include_once str_replace('_', '/', $name) . '.php';
				$ref = new ReflectionClass($name);
				$rule = $ref->newInstanceArgs($rule);
				$this->chain[$key] = $rule;
				unset($ref);
			}
			if (! $this->chain[$key]->isValid($container)) {
				$this->errorMsg[] = $this->chain[$key]->getErrorMsg();
			}
		}
		return $this->isValid();
	}
	
	public function isError() {
		return ! empty($this->errorMsg);
	}
	
	public function isValid() {
		return empty($this->errorMsg);
	}
	
	public function getErrorMsg() {
		return $this->errorMsg;
	}

}



