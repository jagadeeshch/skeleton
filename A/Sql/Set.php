<?php
#require_once 'A/Sql/Statement.php';
/**
 * Set.php
 *
 * @package  A_Sql
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Sql_Columns
 * 
 * Generate SQL SET clause
 */
class A_Sql_Set extends A_Sql_Statement
{
	public function addExpression($arg1, $arg2) {
		#require_once 'A/Sql/Expression.php';
		$this->escapeListeners[] = $expression = new A_Sql_Expression($arg1, $arg2);
		$this->data[] = $expression;;
	}
	
	public function render() {
		$this->notifyListeners();
		
		if ($this->data) {
			$sets = array();
			if (count($this->data)) {
				foreach ($this->data as $data) {
					$sets[] = $data->render(',');
				}
			}	
			$set = implode(', ', $sets);
			return ' SET '. $set;
		}
	}
}
