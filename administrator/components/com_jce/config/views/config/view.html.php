<?php
/**
 * @version		$Id: view.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');


/**
 * Extension Manager Default View
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class ConfigViewConfig extends JView
{
	function display( $tpl = null )
	{
		$db		=& JFactory::getDBO();
		
		$lang =& JFactory::getLanguage();
		$lang->load( 'plg_editors_jce', JPATH_SITE );
	
		$client = JRequest::getWord( 'client', 'site' );
	
		$lists 	= array();
		$row 	=& JTable::getInstance('plugin');
		
		$query = 'SELECT id'
		. ' FROM #__plugins'
		. ' WHERE element = "jce"'
		;
		$db->setQuery( $query );
		$id = $db->loadResult();
	
		// load the row from the db table
		$row->load( intval( $id ) );
	
		// get params definitions
		$params = new JParameter( $row->params, JApplicationHelper::getPath( 'plg_xml', $row->folder .DS. $row->element ), 'plugin' );
		$params->addElementPath( JPATH_COMPONENT . DS . 'elements' );
		$this->assignRef('params', $params);
		$this->assignRef('client', $client);

		parent::display($tpl);
	}
}