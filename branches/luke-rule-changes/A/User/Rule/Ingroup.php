<?php
include_once 'A/Rule.php';

/*
 * Checks if the group(s) passed to the constructor are group(s) that the user 
 * is a member of. 
 * $group can be an array or string of comma separated group names
 * if $group is a string, it is split into an array on $this->delimiter
 * special case: if null group (array('')) is passed allow access
 */
class A_User_Rule_Ingroup extends A_Rule {
	protected $groups;
	protected $field = 'access';
	protected $delimiter = '|';
	
	// parameter that is usually errormsg is the action
	public function __construct($groups, $errorMsg) {
		$this->groups = $groups;
		$this->errorMsg = $errorMsg;
	}
	
	public function setField($field) {
		$this->field = $field;
		return $this;
	}
	
	public function setDelimiter($delimiter) {
		$this->delimiter = $delimiter;
		return $this;
	}
	
	public function setGroups($groups) {
		$this->groups = $groups;
		return $this;
	}
	
	public function isValid($user) {
		if (is_string($this->groups)) {
			$this->groups = explode ($this->delimiter, $this->groups);
		}
// special case: if null group is passed allow access
		if ($this->groups && ($this->groups[0] == '')) {
			return true;
		}
		$allow = false;
		if ($user->isSignedIn()) {
	
			if ($this->groups) {
				$usergroups = $user->get($this->field);
				if ($usergroups) {
	// split if not an array
					if (! is_array($usergroups) ) {
						$usergroups = explode ($this->delimiter, $usergroups);
					}
				} else {
					$usergroups = array();
				}
				if (array_intersect ($this->groups, $usergroups) )  {
					$allow = true;
				}
			} else {
				$allow = true;
			}
		}
		return $allow;
	}

}
