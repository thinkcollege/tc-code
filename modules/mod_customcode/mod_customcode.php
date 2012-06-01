<?php
/*
* @package Custom Module for Joomla 1.5 from Joomlaspan.com. Based on mod_php from fijiwebdesign.
* @copyright Copyright (C) 2007 Joomlaspan.com/ Fijiwebdesign.com. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software.
* This extension is made for Joomla! 1.5;

** WE LOVE JOOMLA! **
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

if (!class_exists('modCustom')) {

	class modCustom {
		function parsePHPviaFile($custom) {
			$tmpfname = tempnam("/tmp", "html");
			$handle = fopen($tmpfname, "w");
			fwrite($handle, $custom, strlen($custom));
			fclose($handle);
			include_once($tmpfname);
			unlink($tmpfname);
		}
	}
}

$custcss = $params->get('js_custcss');
$custom_code = $params->get( 'custom_code' );
$parse_php = $params->get( 'parse_php' );
$custom_code = str_replace('<br />', '', $custom_code);

if ($custcss) 
	{ 
	echo "<div style=\"" . $custcss . "\">\r\n"; 
	}

if ($parse_php) 
{
	modCustom::parsePHPviaFile($custom_code);
} else {
	echo $custom_code;
}

if ($custcss) 
	{ 
	echo "</div>"; 
	}
?>