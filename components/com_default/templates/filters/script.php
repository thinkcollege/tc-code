<?php
/**
 * @version     $Id: script.php 631 2010-11-09 00:37:56Z stian $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Script Filter
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateFilterScript extends KTemplateFilterScript
{	
   	/**
	 * Render script information
	 * 
	 * @param string	The script information
	 * @param boolean	True, if the script information is a URL.
	 * @param array		Associative array of attributes
	 * @return string 	
	 */
	protected function _renderScript($script, $link, $attribs = array())
	{
		if(KRequest::type() == 'AJAX') {
			return parent::_renderScript($script, $link, $attribs);
		}
		
		$document = KFactory::get('lib.joomla.document');
		
		if($link) {
			$document->addScript($script, 'text/javascript');
		} else {
			$document->addScriptDeclaration($script);
		}
	}
}