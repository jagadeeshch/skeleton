<?php
/**
 * A_Pagination_Standard
 *
 * 
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

class A_Pagination_Standard extends A_Pagination_View_Standard	{

	public function __construct ($adapter, $pageSize, $currentPage)	{
		parent::__construct (new A_Pagination_Request ($adapter, $pageSize, $currentPage));
	}

}