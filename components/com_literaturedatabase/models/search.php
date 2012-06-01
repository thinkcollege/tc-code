<?php
/**
 * Hello Model for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class LiteraturedatabaseModelSearch extends JModel {
	public function search() {
		$this->_year = JRequest::getInt('year', -999);
		$this->next = JRequest::getInt('next', 0);
		$fields = array('contributor', 'type', 'audience', 'subject');
		foreach ($fields as $field) {
			$attr = '_' . $field;
			$this->$attr = JRequest::getVar($field);
			if ($this->$attr !== null) {
				$this->$attr = is_array($this->$attr) ? $this->$attr : array($this->$attr);
				foreach ($this->$attr as $key => $val) {
					if (intval($val) < 0) {
						unset($this->{$attr}[$key]);
					} else {
						$this->{$attr}[$key] = intval($val);
					}
				}
			} else {
				$this->$attr = array(-1);
			}
			#sort($this->$attr);
		}
		
		if ($this->_contributor == array(-1) && $this->_subject == array(-1) && $this->_type == array(-1)
			&& $this->_audience == array(-1) && $this->_year === -999) {
			JRequest::setVar('all', '');
		}
		$query = 'SELECT DISTINCT l.LitID FROM '.TBL_LITERATURE . ' l ';
		if (JRequest::getVar('all') === null) {
			$where = "WHERE DATE_ADD(NOW(), INTERVAL $this->_year YEAR) < STR_TO_DATE(CONCAT(IF(l.IPYear = '',YEAR(NOW()), l.IPYear),'-',IF(l.IPMonth = '', 1, l.IPMonth),'-',IFNULL(l.IPDay,1)), '%Y-%c-%e')";
			$url = $this->_year > -999 ? "&year=$this->_year" : '';
			if (reset($this->_type) > 0) {
				$where .= ' AND l.TypeID IN (' . implode(',', $this->_type) . ')';
				$url = '&type[]=' . implode('&type[]=', $this->_type);
			}
			if (reset($this->_audience) > 0) {
				$query .= 'INNER JOIN ' . TBL_LITERATURE_AUDIENCE . ' la ON la.LitID = l.LitID ';
				$where	.= ' AND la.AudienceID IN (' . implode(',', $this->_audience) . ')';
				$url .= '&audience[]=' . implode('&audience[]=', $this->_audience);
			}
			if (reset($this->_subject) > 0) {
				$query .= 'INNER JOIN ' . TBL_LITERATURE_SUBJECT . ' ls ON ls.LitID = l.LitID ';
				$where .= ' AND ls.SubjectID IN (' . implode(',', $this->_subject) . ')';
				$url .= '&subject[]=' . implode('&subject[]=', $this->_subject);
			}
			if (reset($this->_contributor) > 0) {
				$query .= 'INNER JOIN ' . TBL_LITERATURE_CONTRIBUTOR . ' lc ON lc.LitID = l.LitID ';
				$where .= ' AND lc.ContributorID IN (' . implode(',', $this->_contributor) . ')';
				$url .= '&contributor[]=' . implode('&contributor[]=', $this->_contributor);
			}
			$query .= $where;
		
		}
		$db = $this->getDBO();
		$db->setQuery($query . ' ORDER BY IPYear,IPMonth,IPDay LIMIT ' . $this->next . ', 25');
		$results = $db->loadAssocList();
		$db->execute('SELECT 1 '.stristr($query, ' FROM ') . ' GROUP BY l.LitID ORDER BY IPYear,IPMonth,IPDay');
		$this->total = $db->getAffectedRows();
		$this->url = '/?option=com_literaturedatabase&view=search&task=search' . ($url ? $url : '&all');
		return $this->total < 1 ? array() : $results; 
	}
	
	public function getTotal() {
		return $this->total;
	}
	
	public function getSearchUrl() {
		return $this->url;
	}
	
	public function getNext() {
		#if (!isset($this->next)) { throw new Exception('Boo!'); }
		return $this->next;
	}
	
	public function getContributors($LitID = 0) {
		$LitID = intval($LitID);
		$q = 'SELECT DISTINCT c.ID AS Value,
							CONCAT(IFNULL(LastNameCorp, \'\'), \', \', IFNULL(FirstName, \'\'), \' \',
								   IFNULL(c.Middle, \'\'), \' \',IFNULL(c.Suffix, \'\')) AS `Option`
				FROM ' . TBL_CONTRIBUTOR . ' c
		  INNER JOIN ' . TBL_LITERATURE_CONTRIBUTOR . ' lc ON c.ID = lc.ContributorID'
		  . ($LitID > 0 ? ' WHERE lc.LitID = ' . $LitID : '') . ' ORDER BY LastNameCorp, FirstName, c.Middle';
		print "<!-- q:" . str_replace('#__', 'jos_', $q) . ' -->';
		$db = $this->getDBO();
		$db->setQuery($q);
		if ($db->getAffectedRows() == 0) {
			$this->setError($db->getErrorMsg());
			return array();
		}
		return $db->loadAssocList();
	}
	
	public function getPublicationTypes($LitID = 0) {
		$q = 'SELECT DISTINCT t.ID AS Value, t.Label AS `Option` FROM ' . TBL_TYPE . ' t
		  INNER JOIN ' . TBL_LITERATURE . ' l ON l.TypeID = t.ID ORDER BY t.Label';
		$db = $this->getDBO();
		$db->setQuery($q);
		return $db->loadAssocList();
	}
	
	public function getTopics($LitID = 0) {
			$q = 'SELECT DISTINCT t.ID AS Value, t.Label AS `Option` FROM ' . TBL_SUBJECT . ' t
		  INNER JOIN ' . TBL_LITERATURE_SUBJECT . ' l ON l.SubjectID = t.ID ORDER BY t.Label';
	/*	$q = 'SELECT ID AS `Value`, Label AS `Option` FROM ' . TBL_SUBJECT . ' ORDER BY `Option`'; */
		$db = $this->getDBO();
		$db->setQuery($q);
		return $db->loadAssocList();
	}
	
	public function getAudiences($LitID = 0) {
		$q = 'SELECT DISTINCT t.ID AS Value, t.Label AS `Option` FROM ' . TBL_AUDIENCE . ' t
		  INNER JOIN ' . TBL_LITERATURE_AUDIENCE . ' l ON l.AudienceID = t.ID ORDER BY t.Label';
		/* $q = 'SELECT ID AS `Value`, Label AS `Option` FROM ' . TBL_AUDIENCE . ' ORDER BY `Option`'; */
		$db = $this->getDBO();
		$db->setQuery($q);
		return $db->loadAssocList();
	}
}