<?php

class A_Controller_Action {
	protected $locator;
	protected $loader = null;
	protected $helpers = array();
	
	public function __construct($locator=null){
	    $this->locator = $locator;
	}
	 
	protected function dispatch($dir, $class, $method='run', $args=null){
		$dl = new A_DL($dir, $class, $method, $args=null);
		return $dl->run($this->locator);
	}
 
	protected function forward($dir, $class, $method='run', $args=null){
		$forward = new A_DL($dir, $class, $method, $args=null);
		return $forward;
	}
 
	protected function __call($name, $args=null) {
		$args = count($args) ? $args : null;
		if (! isset($this->helpers[$name])) {
		    $class = ucfirst($name);
		    if (in_array($name, array('load', 'flash'))) {
				include_once "A/Controller/Helper/$class.php";
				$class = "A_Controller_Helper_$class";
			// return object from registry
		    } elseif ($obj = $this->locator->get($name)) {
		    	return $obj;
		    }
		    $this->helpers[$name] = new $class($this->locator, $args);
		} else {
			$this->helpers[$name]->__construct($this->locator, $args);
		}
		return $this->helpers[$name];
	}

}
