<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_theme.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_SAVANT')) {
    return true;
} else {
    define('_DOCMAN_SAVANT', 1);
}

/**
* Savant2 needs the PATH_SEPARATOR
*/

if (!defined('PATH_SEPARATOR')) {
    define('PATH_SEPARATOR', substr(PHP_OS, 0, 3) == 'WIN' ? ';' : ':');
}

/**
* Permission constants.
*/

define('DM_TPL_NOT_LOGGED_IN', -1);
define('DM_TPL_NOT_AUTHORIZED', 0);
define('DM_TPL_AUTHORIZED', 1);

$savant_path = $_DOCMAN->getPath('contrib', 'savant2');
include_once($savant_path . "Savant2.php");

class DOCMAN_Theme extends Savant2
{
    /** @var string The name of the active theme */
    var $name = null;

    /** @var string The absolute theme path  */
    var $path = null;

     /** @var object An object of configuartion variables  */
    var $theme = null;

    function DOCMAN_theme()
    {
        global $_DOCMAN, $savant_path;

        $this->name = $_DOCMAN->getCfg('icon_theme');
        $this->path = $_DOCMAN->getPath('themes', $this->name);

        $conf = array();
        $conf['template_path'] = $this->path . "templates/";
        $conf['resource_path'] = $savant_path . "Savant2/";

        parent::Savant2($conf);

        //set the theme variables
		$this->_setConfig();

		//set the language
		$this->_setLanguage();

    }

    function _setConfig()
    {
    	global $_DOCMAN;

    	// Get the configuartion object
    	require_once($this->path . "themeConfig.php");

        $this->setError('docman');

        $theme = new StdClass();
        $theme->conf = &new themeConfig();
        $theme->name = $this->name;
        $theme->path = $_DOCMAN->getPath('themes', $this->name, 1);
        $theme->icon = DOCMAN_Utils::pathIcon(null, 1);
        $theme->png  = DOCMAN_Utils::supportPng();

        $this->theme = &$theme;
    }

    function _setLanguage()
    {
    	global $mosConfig_lang;

		// Get the right language if it exists
		if (file_exists($this->path.'language/'.$mosConfig_lang.'.php')) {
    		include_once ($this->path.'language/'.$mosConfig_lang.'.php');
		} else {
    		include_once ($this->path.'language/english.php');
		}
    }

}