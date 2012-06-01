<?php
/**
* @author Guillermo Vargas, http://joomla.vargas.co.cr
* @email guille@vargas.co.cr
* @version $Id: com_kunena.php 164 2011-10-15 17:31:28Z guilleva $
* @package Xmap
* @license GNU/GPL
* @description Xmap plugin for Kunena Forum Component. 
*/

/** Handles Kunena forum structure */
class xmap_com_kunena {

	/*
	* This function is called before a menu item is printed. We use it to set the
	* proper uniqueid for the item
	*/
	function prepareMenuItem(&$node,&$params) {
		$link_query = parse_url( $node->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars);
		$catid = intval(JArrayHelper::getValue($link_vars,'catid',0));
		$id = intval(JArrayHelper::getValue($link_vars,'id',0));
		$func = JArrayHelper::getValue( $link_vars, 'func', '', '' );
		if ( $func = 'showcat' && $catid ) {
			$node->uid = 'com_kunenac'.$catid;
			$node->expandible = false;
		} elseif ($func = 'view' && $id) {
			$node->uid = 'com_kunenaf'.$id;
			$node->expandible = false;
		}
	}

	function getTree ( &$xmap, &$parent, &$params ) {
		$catid=0;
		if ( $parent->link == 'index.php?option=com_kunena' || strpos($parent->link, '=showcat') || strpos($parent->link, '=listcat') ) {
			$link_query = parse_url( $parent->link );
			parse_str( html_entity_decode($link_query['query']), $link_vars);
			$catid = $xmap->getParam($link_vars,'catid',0);
		} else {
			return true;
		}

		$include_topics = $xmap->getParam($params,'include_topics',1);
		$include_topics = ( $include_topics == 1
				  || ( $include_topics == 2 && $xmap->view == 'xml') 
				  || ( $include_topics == 3 && $xmap->view == 'html')
				  || $xmap->view == 'navigator');
		$params['include_topics'] = $include_topics;

		$priority = $xmap->getParam($params,'cat_priority',$parent->priority);
		$changefreq = $xmap->getParam($params,'cat_changefreq',$parent->changefreq);
		if ($priority  == '-1')
			$priority = $parent->priority;
		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority = $xmap->getParam($params,'topic_priority',$parent->priority);
		$changefreq = $xmap->getParam($params,'topic_changefreq',$parent->changefreq);
		if ($priority  == '-1')
			$priority = $parent->priority;

		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['topic_priority'] = $priority;
		$params['topic_changefreq'] = $changefreq;

		if ( $include_topics ) {
			$ordering = $xmap->getParam($params,'topics_order','ordering');
			if ( !in_array($ordering,array('id', 'ordering','time','subject','hits')) )
				$ordering = 'id';
			$params['topics_order'] = $ordering;

			$params['limit'] = 0;
			$params['days'] = 0;
			$limit = $xmap->getParam($params,'max_topics','');
			if ( intval($limit) )
				$params['limit'] = intval($limit);

			$days = $xmap->getParam($params,'max_age','');
			if ( intval($days) )
				$params['days'] = $xmap->now - (intval($days)*86400);
		}

        $params['table_prefix'] = xmap_com_kunena::getTablePrefix();

		xmap_com_kunena::getCategoryTree($xmap, $parent, $params, $catid);
	}

	/**
	 * Return category tree
	 */
	function getCategoryTree( &$xmap, &$parent, &$params, $parentCat ) 
	{
		$database =& JFactory::getDBO();
		$list = array();

		// Load categories
		if (self::getKunenaMajorVersion() >= '2.0') {
			// Kunena 2.0+
			$catlink = 'index.php?option=com_kunena&amp;view=category&amp;catid=%s';
			$toplink = 'index.php?option=com_kunena&amp;view=topic&amp;catid=%s&amp;id=%s';

			kimport('kunena.forum.category.helper');
			$categories = KunenaForumCategoryHelper::getChildren($parentCat);
		} else {
			$catlink = 'index.php?option=com_kunena&amp;func=showcat&amp;catid=%s';
			$toplink = 'index.php?option=com_kunena&amp;func=view&amp;catid=%s&amp;id=%s';

			if (self::getKunenaMajorVersion() >= '1.6') {
				// Kunena 1.6+
				kimport('session');
				$session = KunenaFactory::getSession();
				$session->updateAllowedForums();
				$allowed = $session->allowed;
				$query = "SELECT id, name FROM #__kunena_categories WHERE parent={$parentCat} AND id IN ({$allowed}) ORDER BY ordering";
			} else {
				// Kunena 1.0+
				$query = "SELECT id, name FROM {$params['table_prefix']}_categories WHERE parent={$parentCat} AND published=1 AND pub_access=0 ORDER BY ordering";
			}
			$database->setQuery($query);
			$categories = $database->loadObjectList();
		}

		// Create list of categories
		$xmap->changeLevel(1);
		foreach ( $categories as $category ) {
			$node = new stdclass;
			$node->id   = $parent->id;
			$node->browserNav = $parent->browserNav;
			$node->uid   = $parent->uid.'c'.$category->id;
			$node->name = $category->name;
			$node->priority = $params['cat_priority'];
			$node->changefreq = $params['cat_changefreq'];
			$node->link = sprintf($catlink, $category->id);
			$node->expandible = true;
			if ( $xmap->printNode($node) !== FALSE ) {
				xmap_com_kunena::getCategoryTree($xmap,$parent,$params,$category->id);
			}
		}

		// Load topics
		if ( $params['include_topics'] && $parentCat ) {
			if (self::getKunenaMajorVersion() >= '2.0') {
				// Kunena 2.0+
				kimport('kunena.forum.topic.helper');
				// TODO: orderby parameter is missing:
				$topics = KunenaForumtopicHelper::getLatestTopics($parentCat, 0, $params['limit'], array('starttime', $params['days']));
			} else {
				// Kunena 1.0+
				$days = $params['days'] ? "AND time >= {$params['days']}" : '';
				$query = "SELECT id, catid, subject, time 
					FROM {$params['table_prefix']}_messages 
					WHERE catid=$parentCat AND parent=0 {$days} 
					ORDER BY `{$params['topics_order']}` DESC";

				$database->setQuery($query, 0, $params['limit']);
				$topics = $database->loadObjectList();
			}
			// Create list of topics
			foreach($topics as $topic) {
				$node = new stdclass;
				$node->id   = $parent->id;
				$node->browserNav = $parent->browserNav;
				$node->uid = $parent->uid.'t'.$topic->id;
				$node->name = $topic->subject;
				$node->priority = $params['topic_priority'];
				$node->changefreq = $params['topic_changefreq'];
				$node->modified = intval($topic->time);
				$node->link = sprintf($toplink, $topic->catid, $topic->id);
				$node->expandible = false;
				$xmap->printNode($node);
			}
		}
		$xmap->changeLevel(-1);
	}

	/**
	* See: http://docs.kunena.org/index.php/Developing_Kunena_Router
	*/
	function getKunenaMajorVersion() {
		// Make sure that Kunena API (if exists) has been loaded
		$api = JPATH_ADMINISTRATOR . '/components/com_kunena/api.php';
		if (is_file($api)) {
			require_once $api;
		}
		if (class_exists('KunenaForum')) {
			return KunenaForum::versionMajor();
		} elseif (class_exists('Kunena')) {
			return substr(Kunena::version(), 0, 3);
		} elseif (is_file(JPATH_ROOT.'/components/com_kunena/lib/kunena.defines.php')) {
			return '1.5';
		} elseif (is_file(JPATH_ROOT.'/components/com_kunena/lib/kunena.version.php')) {
			return '1.0';
		}
		return false;
	}
	function getTablePrefix() {
		$version = self::getKunenaMajorVersion();
		if ($version <= 1.5) {
			return '#__fb';
		}
		return '#__kunena';
	}
}
