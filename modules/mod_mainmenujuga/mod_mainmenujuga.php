<?php
/**
* @version		$Id: mod_mainmenu.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load the JUGA class
// require_once(JPATH_SITE.DS.'components'.DS.'com_juga'.DS.'juga.class.php');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$params->def('menutype', 			'mainmenu');
$params->def('class_sfx', 			'');
$params->def('menu_images', 		0);
$params->def('menu_images_align', 	0);
$params->def('expand_menu', 		0);
$params->def('activate_parent', 	0);
$params->def('indent_image', 		0);
$params->def('indent_image1', 		'indent1.png');
$params->def('indent_image2', 		'indent2.png');
$params->def('indent_image3', 		'indent3.png');
$params->def('indent_image4', 		'indent4.png');
$params->def('indent_image5', 		'indent5.png');
$params->def('indent_image6', 		'indent.png');
$params->def('spacer', 				'');
$params->def('end_spacer', 			'');
$params->def('full_active_id', 		0);

// Added in 1.5
$params->def('startLevel', 			0);
$params->def('endLevel', 			0);
$params->def('showAllChildren', 	0);

require(JModuleHelper::getLayoutPath('mod_mainmenujuga'));