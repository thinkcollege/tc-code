<?php
/**
* @version		$Id: mod_quickicon.php 10381 2008-06-01 03:35:53Z pasamio $
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
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined( '_JOS_QUICKICONJUGA_MODULE' ))
{
	/** ensure that functions are declared only once */
	define( '_JOS_QUICKICONJUGA_MODULE', 1 );

	function quickiconJugaButton( $link, $image, $text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
				
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}

	$import = JPluginHelper::importPlugin( 'juga', 'checkRights' );
	$dispatcher	=& JDispatcher::getInstance();
	$user		= & JFactory::getUser();
	$juga_userid = $user->id;
	$juga_site 	= 'administrator';

	?>
	<div id="cpanel">
		<?php
		$link = 'index.php?option=com_content&amp;task=add';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			quickiconJugaButton( $link, 'icon-48-article-add.png', JText::_( 'Add New Article' ) );			
		}

		$link = 'index.php?option=com_content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			quickiconJugaButton( $link, 'icon-48-article.png', JText::_( 'Article Manager' ) );
		}

		$link = 'index.php?option=com_frontpage';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			quickiconJugaButton( $link, 'icon-48-frontpage.png', JText::_( 'Frontpage Manager' ) );
		}

		$link = 'index.php?option=com_sections&amp;scope=content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {	
			quickiconJugaButton( $link, 'icon-48-section.png', JText::_( 'Section Manager' ) );
		}

		$link = 'index.php?option=com_categories&amp;section=com_content';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			quickiconJugaButton( $link, 'icon-48-category.png', JText::_( 'Category Manager' ) );
		}

		$link = 'index.php?option=com_media';
		$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
		if ($jugaRights[0]->access == true) {
			quickiconJugaButton( $link, 'icon-48-media.png', JText::_( 'Media Manager' ) );
		}

		// Get the current JUser object
		$user = &JFactory::getUser();

		if ( $user->get('gid') > 23 ) {
			$link = 'index.php?option=com_menus';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				quickiconJugaButton( $link, 'icon-48-menumgr.png', JText::_( 'Menu Manager' ) );
			}
		}

		if ( $user->get('gid') > 24 ) {
			$link = 'index.php?option=com_languages&amp;client=0';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				quickiconJugaButton( $link, 'icon-48-language.png', JText::_( 'Language Manager' ) );
			}
		}

		if ( $user->get('gid') > 23 ) {
			$link = 'index.php?option=com_users';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				quickiconJugaButton( $link, 'icon-48-user.png', JText::_( 'User Manager' ) );
			}
		}

		if ( $user->get('gid') > 24 ) {
			$link = 'index.php?option=com_config';
			$jugaRights = $dispatcher->trigger( 'checkRights', array( $juga_userid, $link, $juga_site ) );
			if ($jugaRights[0]->access == true) {
				quickiconJugaButton( $link, 'icon-48-config.png', JText::_( 'Global Configuration' ) );
			}
		}
		?>
	</div>
	<?php
}