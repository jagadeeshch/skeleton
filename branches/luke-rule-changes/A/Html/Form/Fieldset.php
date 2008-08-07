<?php
include_once 'A/Html/Tag.php';
/**
 * Generate HTML form field set
 *
 * @package A_Html
 */

class A_Html_Form_Fieldset extends A_Html_Tag {

	/*
	 * name=string, value=string
	 */
	public function render($attr=array(), $str='') {	// $str not null to force end tag
		parent::mergeAttr($attr);
		if (!$str && isset($attr['value'])) {
			$str = $attr['value'];
			parent::removeAttr($attr, 'value');
		}
		parent::removeAttr($attr, 'type');
		return A_Html_Tag::render('fieldset', $attr, $str);
	}

}
