<?php
function TtaDbBuildRoute(&$query) {
	$segments = array();
	if (!empty($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	}
	if (!empty($query['typeId'])) {
		$segments[] = 't-' . $query['typeId'];
		unset($query['typeId']);
	}
	if (!empty($query['cid'])) {
		$segments[] = $query['cid'];
		unset($query['cid']);
		return $segments;
	}
	
	// PF  This was breaking pagination in filtered search results  In addition, I did a core hack to buildQuery to make multi level arrays work in URLs
/*	foreach ($query as $key => $val) {
		if ($key[0] != 'a') {
			continue;
		}
		$segments[] = $key . '-' . $val;
		unset($query[$key]);
	} */
	return $segments;	
}

function TtaDbParseRoute($segments) {
	$vars = array();
	$count = count($segments);
	if ($count >= 1) {
		$vars['task'] = $segments[0];
		unset($segments[0]);
	}
	if (isset($segments[1]) && $segments[1][0] == 't') {
		$vars['typeId'] = substr($segments[1], 2);
		unset($segments[1]);
	}
	if (isset($segments[2]) && is_numeric($segments[2])) {
		$vars['cid'] = array(abs($segments[2]));
		return $vars;
	}
	foreach ($segments as $seg) {
		$pos = strpos($seg, ':');
		if ($pos > 0) {
			$key = substr($seg, 0, $pos);
			if (!isset($vars[$key])) {
				$vars[$key] = array();
			}
			$vars[$key][] = abs(substr($seg, $pos + 1));
		}
	}
	return $vars;
}