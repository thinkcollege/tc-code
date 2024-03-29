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


class de_siteof_exmenu_SiteHelper {

	var $_absolutePath = FALSE;
	var $_rootUri = FALSE;
	var $_siteTemplate = FALSE;

	function __construct() {
		if (function_exists('jimport')) {
			global $mainframe;
			$this->_absolutePath = JPATH_SITE;
			$this->_rootUri = JURI::root();
			$this->_siteTemplate = ''.$mainframe->getTemplate();
		} else {
			$this->_absolutePath = $GLOBALS['mosConfig_absolute_path'];
			$this->_rootUri = $GLOBALS['mosConfig_live_site'];
			if ($this->_rootUri != '') {
				$this->_rootUri .= '/';
			}
			$this->_siteTemplate = $GLOBALS['cur_template'];
		}
	}


	function de_siteof_exmenu_SiteHelper() {
		$this->__construct();
	}


	function getAbsolutePath($name = '') {
		$path = $this->_absolutePath;
		if ($name != '') {
			$path = $path.'/'.$name;
		}
		return $path;
	}


	function getUri($name = FALSE) {
		$uri = $this->_rootUri;
		if ($name != '') {
			$uri = $uri.$name;
		}
		return $uri;
	}


	function getSiteTemplateName() {
		return $this->_siteTemplate;
	}


	function getSiteTemplateUri($name = FALSE) {
		$uri = $this->getUri('templates/'.$this->_siteTemplate);
		if ($name != '') {
			$uri = $uri.'/'.$name;
		}
		return $uri;
	}
}


?>