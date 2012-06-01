<?php
/**
 * @package     Joomla.Tutorials
 * @subpackage  Components
 * components/com_literature/search.php
 * @link        http://docs.joomla.org/Category:Development
 * @license     GNU/GPL
 */
 
// no direct access
 defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller
 require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'media.php');

define('LIVE', $_SERVER['SERVER_ADDR'] == '69.20.125.203');
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

define('COM_URI', '/?option=com_programsdatabase&');

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
$classname    = 'ProgramsDatabaseController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute(JRequest::getVar('task'));
 
// Redirect if set by the controller
$controller->redirect();

?>