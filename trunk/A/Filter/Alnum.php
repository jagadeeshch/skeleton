<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Alnum.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Alnum
 * 
 * Filter a string to leave only alpha-numeric characters
 */
class A_Filter_Alnum extends A_Filter_Base {
	
	public function filter () {
		return preg_replace('/[^[:alnum:]]/', '', $this->getValue());
	}

}
