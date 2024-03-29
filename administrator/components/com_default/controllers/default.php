<?php
/**
 * @version     $Id: default.php 840 2011-01-19 23:59:02Z stian $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default View Controller
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends KControllerView
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Register command callbacks
		$this->registerCallback(array('after.save', 'after.delete'), array($this, 'setMessage'));
	}
	
	/**
	 * Set the request information
	 * 
	 * This function translates 'limitstart' to 'offset' for compatibility with Joomla
	 *
	 * @param array	An associative array of request information
	 * @return KControllerBread
	 */
	public function setRequest(array $request = array())
	{
		if(isset($request['limitstart'])) {
			$request['offset'] = $request['limitstart'];
		}
		
		$this->_request = new KConfig($request);
		return $this;
	}
	
	/**
	 * Display the view
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function displayView(KCommandContext $context)
	{
		//Load the language file for HMVC requests who are not routed through the dispatcher
		if($this->_request->option != $this->getIdentifier()->package) {
			KFactory::get('lib.joomla.language')->load($this->_request->option); 
		}
		
		parent::displayView($context);
	}

 	/**
	 * Filter that creates a redirect message based on the action
	 * 
	 * This function takes the row(set) status into account. If the status is STATUS_FAILED the status message information 
	 * us used to generate an appropriate redirect message and set the redirect to the referrer. Otherwise, we generate the 
	 * message based on the action and identifier name.
	 *
	 * @param KCommandContext	The active command context
	 * @return void
	 */
	public function setMessage(KCommandContext $context)
	{
		$action	= KRequest::get('post.action', 'cmd');
		$name	= $this->_identifier->name;
		$rowset	= ($context->result instanceof KDatabaseRowAbstract) ? array($context->result) : $context->result;
		$suffix = ($action == 'add' || $action == 'edit') ? 'ed' : 'd'; 
		$failed	= false;

		foreach($rowset as $row)
		{
			if($row->getStatus() == KDatabase::STATUS_FAILED)
			{
				$this->_redirect		= KRequest::referrer();
				$this->_redirect_type	= 'error';

				if($row->getStatusMessage()) {
					$this->_redirect_message = $row->getStatusMessage();
				} else {
					$this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.' failed');
				}

				$failed = true;
				break;
			}
		}

		if(!$failed)
		{
			if(count($rowset) > 1) {
				$this->_redirect_message = JText::sprintf('%s ' . strtolower(KInflector::pluralize($name)) . ' ' . $action.$suffix, $count);
			} else {
				$this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.$suffix);
			}
		}
	}

	/**
	 * Browse a list of items
	 *
	 * This function set the default list limit if the limit state is 0
	 *
	 * @return KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse(KCommandContext $context)
	{
		if($this->getModel()->getState()->limit ===  null) {
			$this->getModel()->limit(KFactory::get('lib.joomla.application')->getCfg('list_limit'));
		}

		return parent::_actionBrowse($context);
	}

	/**
	 * Display a single item
	 *
	 * This functions implements an extra check to hide the main menu is the view name
	 * is singular (item views)
	 *
	 *  @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead(KCommandContext $context)
	{
		//Perform the read action
		$row = parent::_actionRead($context);
		
		//Add the notice if the row is locked
		if(isset($row))
		{
			if(!isset($this->_request->layout) && $row->isLockable() && $row->locked()) {
				KFactory::get('lib.koowa.application')->enqueueMessage($row->lockMessage(), 'notice');
			}
		}

		return $row;
	}
}