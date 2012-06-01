<?php

class TtaDbObjectWrapper {
	
	protected $obj = null;
	protected $overriden = array();
	function __construct($obj) {
		$this->obj = $obj;
	}
	
	function getObject() {
		return $this->obj;
	}
	
	function override($func) {
		if (func_num_args() == 1) {
			unset($this->funcs[$func]);
		} else {
			$ret = func_get_arg(1);
			$this->funcs[$func] = $ret;
		}
	}
	
	function __call($func, $args) {
		return isset($this->funcs[$func]) ? $this->funcs[$func] : call_user_func_array(array($this->obj, $func), $args);
	}
}