<?php
function programsdatabaseBuildRoute(&$query) {
	$segments = array();
	if (isset($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	}
	if (isset($query['cid'])) {
		$segments[] = $query['cid'];
		unset($query['cid']);
	}
	return $segments;
}

function programsdatabaseParseRoute($segments) {
	$vars = array();
	$count = count($segments);
	//Handle View and Identifier
	if ($count >= 1) {
		$vars['task'] = $segments[0];
	}
	if ($count == 2) {
		$vars['cid'] = intval($segments[1]); 
	}
	return $vars;
}