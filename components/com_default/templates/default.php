<?php
/**
 * @version     $Id: default.php 631 2010-11-09 00:37:56Z stian $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Template
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateDefault extends KTemplateDefault
{ 
	/**
	 * Load a template helper
	 * 
	 * This function merges the elements of the attached view model state with the parameters passed to the helper
	 * so that the values of one are appended to the end of the previous one. 
	 * 
	 * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 */
	public function loadHelper($identifier, $params = array())
	{
		$view = KFactory::get($this->getView());
		
		if(KInflector::isPlural($view->getName())) 
		{
			if($state = KFactory::get($view->getModel())->getState()) {
				$params = array_merge( $state->getData(), $params);
			}
		} 
		else 
		{
			if($item = KFactory::get($view->getModel())->getItem()) {
				$params = array_merge( $item->getData(), $params);
			}
		}	
		
		return parent::loadHelper($identifier, $params);
	}
}