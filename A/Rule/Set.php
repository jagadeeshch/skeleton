<?php
/**
 * Contains multiple rules that must all pass for this to be isValid 
 * 
 * @package A_Rule 
 */

class A_Rule_Set {

    protected $chain = array();
    protected $excludes = array();		// array of names not to be validated
    protected $errorMsg = array();
    protected $dir = 'A_Rule_';
    
    public function addRule($rule) {
		if (is_string($rule)) {
		    $rule = func_get_args();
		}
		$this->chain[] = $rule;
		return $this;
    }

    public function exclude($names=array()) {
		if (is_string($names)) {
		    $names = array($names);
		}
		$this->excludes = $names;
		return $this;
    }
    
    /**
     * Sets whether fields allow null values or not
     * 
     * @param boolean
     * @return instance of this object (for fluent interface)
     */
	public function setOptional($names, $tf=true) {
		if (! is_array($names)) {
			$names = array($names);
		}
		foreach ($names as $name) {
			foreach ($this->chain as $key => $rule) {
				if ($key == $name) {
					$rule->setOptional($tf);
				}
			}
		}
		return $this;
	}
    /**
     * Whether field is allows null values or not
     * 
     * @return boolean value of optional flag
     */
	public function isOptional($name) {
		$optional = true;
		foreach ($this->chain as $key => $rule) {
			if (($key == $name) && ! $rule->isOptional()) {
				$optional = false;
				break;
			}
		}
		return $optional;
	}

    /**
     * Tells whether this rule passes or not
     * 
     * @return boolean true if pass, fail otherwise
     */
	public function isValid($container) {
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
				$this->errorMsg[$this->chain[$key]->getName()][] = $this->chain[$key]->getErrorMsg();
		    }
		}
		return empty($this->errorMsg);
    }
    
    public function getErrorMsg($separator=null) {
 		return $separator === null ? $this->errorMsg : implode($separator, $this->errorMsg);
    }

}