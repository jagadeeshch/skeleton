<?php
include_once 'A/Pagination/Adapter/Abstract.php';

/**
 * Datasource access class for pager using Skeleton database connection  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_Mysql extends A_Pagination_Adapter_Abstract	{
	protected $numrows = 0;

    public function getNumItems() {
    	if ($this->numrows == 0) {
	        $query = preg_replace('#SELECT\s+(.*?)\s+FROM#i', 'SELECT COUNT(*) AS count FROM', $this->query);
	        $rs = mysql_query($query, $this->db);
	
	        if ($rs && $row = mysql_fetch_assoc($rs)) {
	            $this->numrows = $row['count'];
	        } else {
	            $this->numrows = 0;
	        }
    	}
        return $this->numrows;    
    }

    public function getItems($start, $length) {
        $query = $this->query . " LIMIT {$length} OFFSET {$start}";
        $rs = mysql_query($query, $this->db);

        $rows = array();
        while ($row = mysql_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
