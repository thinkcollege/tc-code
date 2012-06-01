<?php 
// no direct access
 defined('_JEXEC') or die('Restricted access');
 
// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'media.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'application.php');

define('LIVE', php_uname('n') == 'communityinclusion.org');

define('V_TEXT',	1);
define('V_INT',		2);
define('V_FLOAT',	3);
define('V_EMAIL',	4);
define('V_PHONE',	5);
define('V_NAME',	6);
define('V_ADDR',	7);
define('V_STATE',	8);
define('V_ZIP',		9);
define('V_URL',		10);
define('V_DATE',	11);
	
	
$u = JURI::getInstance();
define('COM_URI', './?option=' . JRequest::getVar('option') . '&');
if (!isset($_SESSION['item'])) {
	$_SESSION['item'] = array();
}
// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
    	require_once $path;
    } else {
        $controller = '';
    }
}
// Create the controller
$classname    = 'TtaDbController'.$controller;
$controller   = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

/*foreach( JFolder::files(JPATH_COMPONENT . DS . 'lib') as $file ) {
	JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
}*/ ?>