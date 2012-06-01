<?php
/**
* @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
/**
 * Usage:
 * 
 * // install and enable the juga 'checkRights' plugin
 * 
 * // insert the following lines where you want to check access
 * $import 		= JPluginHelper::importPlugin( 'juga', 'checkRights' );
 * $dispatcher	=& JDispatcher::getInstance();
 * 
 * // $userid 	= user id
 * // $query 	= full URL or the query after the '?' in an internal URL
 * // $site 	= 'site' or 'administrator'
 * $jugaRights 	= $dispatcher->trigger( 'checkRights', array( $userid, $query, $site ) );
 * 
 * // $jugaRights returns these values:
 * // $jugaRights->access 		// Access Boolean 
 * // $jugaRights->error 		// Error Boolean
 * // $jugaRights->errorMsg 	// Error Message, 'DEFAULT' if access was undefined and default access level was used
 * // $jugaRights->ce_url 		// Error URL for Site Item
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'menujuga.php');

class modMenuJugaHelper
{
	/**
	 * Show the menu
	 * @param string The current user type
	 */
	function buildMenu()
	{
		global $mainframe;
		
		$lang		= & JFactory::getLanguage();
		$user		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$usertype	= $user->get('usertype');

		$import = JPluginHelper::importPlugin( 'juga', 'checkRights' );
		$dispatcher	=& JDispatcher::getInstance();
		$juga_userid = $user->id;
		$juga_site 	= 'administrator';

		// cache some acl checks
		$canCheckin			= $user->authorize('com_checkin', 'manage');
		$canConfig			= $user->authorize('com_config', 'manage');
		$manageTemplates	= $user->authorize('com_templates', 'manage');
		$manageTrash		= $user->authorize('com_trash', 'manage');
		$manageMenuMan		= $user->authorize('com_menus', 'manage');
		$manageLanguages	= $user->authorize('com_languages', 'manage');
		$installModules		= $user->authorize('com_installer', 'module');
		$editAllModules		= $user->authorize('com_modules', 'manage');
		$installPlugins		= $user->authorize('com_installer', 'plugin');
		$editAllPlugins		= $user->authorize('com_plugins', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');
		$canMassMail		= $user->authorize('com_massmail', 'manage');
		$canManageUsers		= $user->authorize('com_users', 'manage');

		// Menu Types
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
		$menuTypes 	= MenusHelper::getMenuTypelist();

		/*
		 * Get the menu object
		 */
		$menu = new JAdminCSSMenuJuga();

		/*
		 * Site SubMenu
		 */
		$menu->addChild(new JMenuJugaNode(JText::_('Site')), true);
		$menu->addChild(new JMenuJugaNode(JText::_('Control Panel'), 'index.php', 'class:cpanel'));
		$menu->addSeparator();
		if ($canManageUsers) {
			$query = 'index.php?option=com_users&task=view';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('User Manager'), $query, 'class:user'));	
			}
		}
			$query = 'index.php?option=com_media';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Media Manager'), $query, 'class:media'));
			}
		$menu->addSeparator();
		if ($canConfig) {
			$query = 'index.php?option=com_config';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Configuration'), $query, 'class:config'));
			}
			$menu->addSeparator();
		}

		$menu->addChild(new JMenuJugaNode(JText::_('Logout'), 'index.php?option=com_login&task=logout', 'class:logout'));

		$menu->getParent();

		/*
		 * Menus SubMenu
		 */
		$menu->addChild(new JMenuJugaNode(JText::_('Menus')), true);
		if ($manageMenuMan) {
			$query = 'index.php?option=com_menus';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Menu Manager'), $query, 'class:menu'));
			}
		}
		if ($manageTrash) {
			$query = 'index.php?option=com_trash&task=viewMenu';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Menu Trash'), $query, 'class:trash'));
			}
		}

		if($manageTrash || $manageMenuMan) {
			$menu->addSeparator();
		}
		/*
		 * SPLIT HR
		 */
		if (count($menuTypes)) {
			foreach ($menuTypes as $menuType) {
				$query = 'index.php?option=com_menus&task=view&menutype='.$menuType->menutype;
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode($menuType->title.($menuType->home ? ' *' : ''), $query, 'class:menu'));
				}
			}
		}

		$menu->getParent();
		
		/*
		 * Content SubMenu
		 */
		$menu->addChild(new JMenuJugaNode(JText::_('Content')), true);
		$query = 'index.php?option=com_content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('Article Manager'), $query, 'class:article'));
		}
		if ($manageTrash) {
			$query = 'index.php?option=com_trash&task=viewContent';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Article Trash'), $query, 'class:trash'));
			}
		}
		$menu->addSeparator();
		$query = 'index.php?option=com_sections&scope=content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('Section Manager'), $query, 'class:section'));
		}
		$query = 'index.php?option=com_categories&section=com_content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('Category Manager'), $query, 'class:category'));
		}
		$menu->addSeparator();
		$query = 'index.php?option=com_frontpage';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('Frontpage Manager'), $query, 'class:frontpage'));
		}

		$menu->getParent();

		/*
		 * Components SubMenu
		 */
		if ($editAllComponents)
		{
			$menu->addChild(new JMenuJugaNode(JText::_('Components')), true);

			$query = 'SELECT *' .
				' FROM #__components' .
				' WHERE '.$db->NameQuote( 'option' ).' <> "com_frontpage"' .
				' AND '.$db->NameQuote( 'option' ).' <> "com_media"' .
				' AND enabled = 1' .
				' ORDER BY ordering, name';
			$db->setQuery($query);
			$comps = $db->loadObjectList(); // component list
			$subs = array(); // sub menus
			$langs = array(); // additional language files to load

			// first pass to collect sub-menu items
			foreach ($comps as $row)
			{
				if ($row->parent)
				{
					if (!array_key_exists($row->parent, $subs)) {
						$subs[$row->parent] = array ();
					}
					$subs[$row->parent][] = $row;
					$langs[$row->option.'.menu'] = true;
				} elseif (trim($row->admin_menu_link)) {
					$langs[$row->option.'.menu'] = true;
				}
			}

			// Load additional language files
			if (array_key_exists('.menu', $langs)) {
				unset($langs['.menu']);
			}
			foreach ($langs as $lang_name => $nothing) {
				$lang->load($lang_name);
			}

			foreach ($comps as $row)
			{
				if ($editAllComponents | $user->authorize('administration', 'edit', 'components', $row->option))
				{
					if ($row->parent == 0 && (trim($row->admin_menu_link) || array_key_exists($row->id, $subs)))
					{
						$text = $lang->hasKey($row->option) ? JText::_($row->option) : $row->name;
						$link = $row->admin_menu_link ? "index.php?$row->admin_menu_link" : "index.php?option=$row->option";
						if (array_key_exists($row->id, $subs)) {
							$query = $link;
							$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
							if ($jugaRights[0]->access == true) {
								$menu->addChild(new JMenuJugaNode($text, $link, $row->admin_menu_img), true);
								foreach ($subs[$row->id] as $sub) {
									$key  = $row->option.'.'.$sub->name;
									$text = $lang->hasKey($key) ? JText::_($key) : $sub->name;
									$link = $sub->admin_menu_link ? "index.php?$sub->admin_menu_link" : null;
									$query = $link;
									$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
									if ($jugaRights[0]->access == true) {
										$menu->addChild(new JMenuJugaNode($text, $link, $sub->admin_menu_img));
									}
								}
								$menu->getParent();
							}
						} else {
							$query = $link;
							$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
							if ($jugaRights[0]->access == true) {
								$menu->addChild(new JMenuJugaNode($text, $link, $row->admin_menu_img));
							}
						}
					}
				}
			}
			$menu->getParent();
		}

		/*
		 * Extensions SubMenu
		 */
		if ($installModules)
		{
			$menu->addChild(new JMenuJugaNode(JText::_('Extensions')), true);
			$query = 'index.php?option=com_installer';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Install/Uninstall'), $query, 'class:install'));
			}
			$menu->addSeparator();
			if ($editAllModules) {
				$query = 'index.php?option=com_modules';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Module Manager'), $query, 'class:module'));
				}
			}
			if ($editAllPlugins) {
				$query = 'index.php?option=com_plugins';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Plugin Manager'), $query, 'class:plugin'));
				}
			}
			if ($manageTemplates) {
				$query = 'index.php?option=com_templates';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Template Manager'), $query, 'class:themes'));
				}
			}
			if ($manageLanguages) {
				$query = 'index.php?option=com_languages';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Language Manager'), $query, 'class:language'));
				}
			}
			$menu->getParent();
		}

		/*
		 * System SubMenu
		 */
		if ($canConfig || $canCheckin)
		{
			$menu->addChild(new JMenuJugaNode(JText::_('Tools')), true);

			if ($canConfig) {
				$query = 'index.php?option=com_messages';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Read Messages'), $query, 'class:messages'));
				}
				$query = 'index.php?option=com_messages&task=add';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Write Message'), $query, 'class:messages'));
				}
				$menu->addSeparator();
			}
			if ($canMassMail) {
				$query = 'index.php?option=com_massmail';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Mass Mail'), $query, 'class:massmail'));
				}
				$menu->addSeparator();
			}
			if ($canCheckin) {
				$query = 'index.php?option=com_checkin';
				$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
				if ($jugaRights[0]->access == true) {
					$menu->addChild(new JMenuJugaNode(JText::_('Global Checkin'), $query, 'class:checkin'));
				}
				$menu->addSeparator();
			}
			$query = 'index.php?option=com_cache';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				$menu->addChild(new JMenuJugaNode(JText::_('Clean Cache'), $query, 'class:config'));
			}
			$menu->getParent();
		}

		/*
		 * Help SubMenu
		 */
		$menu->addChild(new JMenuJugaNode(JText::_('Help')), true);
		$query = 'index.php?option=com_admin&task=help';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('Joomla! Help'), $query, 'class:help'));
		}	
		$query = 'index.php?option=com_admin&task=sysinfo';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $query, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			$menu->addChild(new JMenuJugaNode(JText::_('System Info'), $query, 'class:info'));
		}
		$menu->getParent();

		$menu->renderMenu('menu', '');
	}

	/**
	 * Show an disbaled version of the menu, used in edit pages
	 *
	 * @param string The current user type
	 */
	function buildDisabledMenu()
	{
		$lang	 =& JFactory::getLanguage();
		$user	 =& JFactory::getUser();
		$usertype = $user->get('usertype');

		$canConfig			= $user->authorize('com_config', 'manage');
		$installModules		= $user->authorize('com_installer', 'module');
		$editAllModules		= $user->authorize('com_modules', 'manage');
		$installPlugins		= $user->authorize('com_installer', 'plugin');
		$editAllPlugins		= $user->authorize('com_plugins', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');
		$canMassMail			= $user->authorize('com_massmail', 'manage');
		$canManageUsers		= $user->authorize('com_users', 'manage');

		$text = JText::_('Menu inactive for this Page', true);

		// Get the menu object
		$menu = new JAdminCSSMenuJuga();

		// Site SubMenu
		$menu->addChild(new JMenuJugaNode(JText::_('Site'), null, 'disabled'));

		// Menus SubMenu
		$menu->addChild(new JMenuJugaNode(JText::_('Menus'), null, 'disabled'));

		// Content SubMenu
		$menu->addChild(new JMenuJugaNode(JText::_('Content'), null, 'disabled'));

		// Components SubMenu
		if ($installComponents) {
			$menu->addChild(new JMenuJugaNode(JText::_('Components'), null, 'disabled'));
		}

		// Extensions SubMenu
		if ($installModules) {
			$menu->addChild(new JMenuJugaNode(JText::_('Extensions'), null, 'disabled'));
		}

		// System SubMenu
		if ($canConfig) {
			$menu->addChild(new JMenuJugaNode(JText::_('Tools'),  null, 'disabled'));
		}

		// Help SubMenu
		$menu->addChild(new JMenuJugaNode(JText::_('Help'),  null, 'disabled'));

		$menu->renderMenu('menu', 'disabled');
	}

	/**
	 * Method to dump the structure of a variable for debugging purposes
	 *
	 * @param	mixed	A variable
	 * @param	boolean	True to ensure all characters are htmlsafe
	 * @return	string
	 * @since	1.5
	 * @static
	 */
	function dump( &$var, $htmlSafe = true ) {
		$result = print_r( $var, true );
		return '<pre>'.( $htmlSafe ? htmlspecialchars( $result ) : $result).'</pre>';
	}	
}
?>
