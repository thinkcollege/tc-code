<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_compat.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');


if (defined('_DOCMAN_compat')) {
    return true;
} else {
    define('_DOCMAN_compat', 1);
}

$v10    = version_compare($GLOBALS['_VERSION']->RELEASE, '1.0', '==');
$v15    = version_compare($GLOBALS['_VERSION']->RELEASE, '1.5', '>=');
$vmambo = version_compare($GLOBALS['_VERSION']->RELEASE, '4', '>=');

if($vmambo) { // Using Mambo v4.x or higher
	die('<a href="http://www.joomlatools.org" title="DOCman Official Site"><img border="0" src="components/com_docman/images/dm_logo.png" title="DOCman Logo" /></a><h1>Please upgrade to <a href="http://www.joomla.org" target="_blank" title="Joomla!">Joomla!</a> for the best document management experience!</h1>');
} elseif ($v10){ // Using Joomla! 1.0.x
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'DOCMAN_compat10.class.php');
} elseif($v15) { // Using Joomla! v1.5 or higher
    define('_DM_J15', true);
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'DOCMAN_compat15.class.php');
} else {
    die('Joomla! / Mambo / Elxis /... version '.$GLOBALS['_VERSION']->RELEASE.' is not supported by DOCman');
}

// ob_end_clean() doesn't exist in php < 4.3.0
if (!function_exists('ob_get_clean')) {
    function ob_get_clean() {
        $ob_contents = ob_get_contents();
        ob_end_clean();
        return $ob_contents;
    }
}