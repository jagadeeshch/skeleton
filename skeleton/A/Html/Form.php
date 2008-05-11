<?php
#include_once 'A/Html/Tag.php';

class A_Html_Form {
	protected $_attr = array(
					'action' => '',
					'method' => 'post',
					); 
	protected $_elements = array();
	protected $_helpers = array();
	protected $_wrapper = null;
	protected $_wrapperAttr = array();
	
	/*
	 * name=string, value=string or renderer
	 */
	public function render($attr=array(), $content=null) {
		if (isset($this)) {
			$content = $this->partial($attr);
		}
		
		A_Html_Tag::setDefaults($attr, array('method'=>'post', 'action'=>'', ));
		return A_Html_Tag::render('form', $attr, $content);
	}

	public function partial($attr=array()) {
		$out = '';
		foreach ($this->_elements as $name => $element) {
			$str = '';
			if (isset($element['label'])) {
				$str .= $element['label']->render();
			}
			$str .= $element['renderer']->render();
			// if we wrap elements in a tag
			if ($element['wrapper']) {
				$str = $element['wrapper']->render($element['wrapperAttr'], $str);
			}
			$out .= $str;
		}
		return $out;
#		return implode("\n", $this->_elements);
	}

	// Set the method. Is there a setter for the action?
	public function setAction($action='') {
		$this->attr['action'] = $action;
		return $this;
	}
                             // Optional method to set the Model
	public function setMethod($method='post') {
		$this->attr['method'] = $method;
		return $this;
	}
                             // Optional method to set the Model
	public function setModel($model) {
		$this->model = $model;
		return $this;
	}

	public function setWrapper($obj, $attr=array()) {
		if (is_string($obj)) {
			include_once str_replace('_', '/', $obj) . '.php';
			$this->_wrapper = new $obj();
		} else {
			$this->_wrapper = $obj;
		}
		$this->_wrapperAttr = $attr;
		return $this;
	}

	protected function getHelperClass($type) {
		return isset($this->_helpers[$type]) ? $this->_helpers[$type] : 'A_Html_Form_' . ucfirst($type);
	}

	protected function setHelperClass($type, $class) {
		$this->_helpers[$type] = $class;
		return $this;
	}

	protected function getHelper($type, $attr=array()) {
		$class = $this->getHelperClass($type);
		include_once str_replace('_', '/', $class) . '.php';
		if (class_exists($class)) {
			$element = new $class($attr);
			return $element;
		}
	}

	public function reset() {
		$this->_elements = array(); 
		return $this;
	}
	
	public function __call($type, $args) {
		$params = array();
		// allow (args), (name), (name, label), (name, args)
		if(is_array($args[0])) {
			$params = $args[0];				// all params in array
		} else {
			if (isset($args[1])) {
				if(is_array($args[1])) {
					$params = $args[1];		// array of params in 2nd arg
				} else {
					// fieldset is the exception that does not get a label
					if ($type == 'fieldset') {
						$params['content'] = $args[1];
					} else {
						$params['label'] = $args[1];
					}
				}
			}
			$params['name'] = $args[0];
		}
	
		if ($type == 'fieldset') {
			$this->_elements[] = $params['content'];
		} elseif (isset($params['name']) && $params['name']) {
			$element = $this->getHelper($type, $params);
			// set the value from the model if it is set
			if (isset($this->model)) {
				if (is_array($this->model)) {
					if (isset($this->model[$params['name']])) {
						$params['value'] = $this->model[$params['name']];
					}
				} elseif (is_object($this->model)) {
					if ($this->model->has($params['name'])) {
						$params['value'] = $this->model->get($params['name']);
					}
				}
			}
			// if this field has a label then wrap in a label tag
			if (isset($params['label'])) {
				$str = $params['label'];
				unset($params['label']);
				$label = $this->getHelper('label', array('for'=>$params['name'], 'content'=>$str));
				$this->_elements[$params['name']]['label'] = $label;
#				$str = $label->render() . $element->render($params);
#			} else {
#				$str = $element->render();
			}
			// if we wrap elements in a tag
			if ($this->_wrapper) {
				$this->_elements[$params['name']]['wrapper'] = $this->_wrapper;
				$this->_elements[$params['name']]['wrapperAttr'] = $this->_wrapperAttr;
			}
			$this->_elements[$params['name']]['renderer'] = $element;
		}
		return $this;
	}

}