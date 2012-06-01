<?php
/**
 * Reports Model for ThinkCollege Programs Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Reports Model
 *
 */
class TtaDbModelReports extends JModel {
	
	/**
	 * Reports data array
	 *
	 * @var array
	 */
	private $_data;
	
	/**
	 * Report Number
	 * @var int	report number
	 */
	private $_id;
	
	function __construct() {
		parent::__construct();
		$id = JRequest::get('cid', 0, '', 'array');
		$this->setId($id[0]);
	}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$tItem		= $this->getTable('Item');
		$tAttr		= $this->getTable('Attr');
		$tChoice	= $this->getTable('Choice');
		$tType		= $this->getTable('Type');
		
		
	}
 
	/**
	 * Retrieves the programs data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
				return false;
			}
		}
 
		return $this->_data;
	}
}