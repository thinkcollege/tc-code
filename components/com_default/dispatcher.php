<?php
/**
 * @version     $Id: dispatcher.php 631 2010-11-09 00:37:56Z stian $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Dispatcher
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultDispatcher extends KDispatcherDefault
{ 
	/**
	 * Dispatch the controller and redirect
	 * 
	 * This function divert the standard behavior and will redirect if no view
	 * information can be found in the request.
	 * 
	 * @param	string		The view to dispatch. If null, it will default to
	 * 						retrieve the controller information from the request or
	 * 						default to the component name if no controller info can
	 * 						be found.
	 *
	 * @return	KDispatcherDefault
	 */
	protected function _actionDispatch(KCommandContext $context)
	{
		//Redirect if no view information can be found in the request
		if(!KRequest::has('get.view')) 
		{
			$view = $context->data ? $context->data : $this->_controller_default;
			
			KFactory::get('lib.koowa.application')
				->redirect('index.php?option=com_'.$this->_identifier->package.'&view='.$view);
		}
		
		return parent::_actionDispatch($context);
	}
	
	/**
	 * Push the controller data into the document
	 * 
	 * This function divert the standard behavior and will push specific controller data
	 * into the document
	 *
	 * @return	KDispatcherDefault
	 */
	protected function _actionRender(KCommandContext $context)
	{
		$controller = KFactory::get($this->getController());
		$view       = KFactory::get($controller->getView());
	
		$document = KFactory::get('lib.joomla.document');
		$document->setMimeEncoding($view->mimetype);
		
		return parent::_actionRender($context);
	}
}