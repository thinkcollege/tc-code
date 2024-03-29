<?php
/**
* @version $Id:$
* @author Daniel Ecer
* @package exmenu
* @copyright (C) 2005-2008 Daniel Ecer (de.siteof.de)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
if (!defined('EXTENDED_MENU_HOME')) {
	die('Restricted access');
}

/**
 * @since 1.0.0
 */
class PluginExtendedMenuLoader extends AbstractExtendedMenuLoader {

	function loadBySourceValues($sourceValues) {
		return $this->loadByPluginAndSourceName($sourceValues);
	}

	function loadByPluginAndSourceName($sourceValues) {
		global $_MAMBOTS;
		$_MAMBOTS->loadBotGroup('exmenu');
		$pluginName	= implode(',', $sourceValues);
		$results		= $_MAMBOTS->trigger('onLoadMenu', array(&$this, $pluginName), FALSE);
		$result		= FALSE;
		foreach($results as $r) {
			$result		|= $r;
		}
		if (!$result) {
			trigger_error('exmenu: no plugin found for the name "'.$pluginName.'"', E_USER_NOTICE);
		}
		return $result;
	}
}

?>