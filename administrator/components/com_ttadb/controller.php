<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

/**
 * CCK Component Controller
 *
 * @package    CCK
 * @subpackage 
 */
class TtaDbController extends JController {

	function __construct() {
		parent::__construct();
		
		$this->registerTask('addItem', 'editItem');
		$this->registerTask('addType', 'editType');
	}
	
	function listItems() {
		$view =& $this->getView('Items', 'html');
 		$view->setModel($this->getModel('Items'), true);
 		$view->setModel($this->getModel('Attrs'));
 		$view->display();
	}
	
	function editItem() {
		JRequest::setVar('hidemainmenu', 1);
		
		// Due to the pagination in the attributes model these need to be set before it's created.
		$view =& $this->getView('Item', 'html');
 		$view->setModel($this->getModel('Item'), true);
 		$view->setModel($this->getModel('Attrs'));
 		$view->display();
	}
    
	function saveItem() {
		$model	= $this->getModel('Item');
		$ret	= $model->store();
		#var_dump($ret);
		if ($ret === true) {
			$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listItems&typeId=' . JRequest::getVar('typeId'), JText::_('Item Saved!'));
		} else {
			$this->editItem();
		}
	}
	
	function removeItem() {
		$model = $this->getModel('Item');
		if (!$model->delete()) {
			JError::raiseNotice(100, JText::_('Error: One or More items could not be deleted.'));
		} else {
			JError::raiseWarning(500, JText::_('Items deleted!'));
				$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listItems&typeId=' . JRequest::getVar('typeId'));
		}
		$this->listItems();
	}
	
	function publish() {
		$msg	= null;
		$tItem =& $this->getModel('Item')->getTable();
		if ($tItem->publish(JRequest::getVar('cid', 0, '', 'array'))) {
		
				$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listItems&typeId=' . JRequest::getVar('typeId'));
					$msg = 'Item(s) published.';
		}
		$this->listItems();
	}
	
	function unpublish() {
		$msg	= null;
		$tItem	=& $this->getModel('Item')->getTable();
		
		if ($tItem->publish(JRequest::getVar('cid', 0, '', 'array'), 0)) {
				$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listItems&typeId=' . JRequest::getVar('typeId'));
			JError::raiseNotice(100, 'Item(s) unpublished.');
		}
		$this->listItems();
	}
	
	function cancelItemEdit() {
		$typeId = JRequest::getInt('typeId', 0);
		
		if ($typeId == 0) {
			$this->setRedirect('./?option=' . JRequest::getWord('option'), JText::_('Operation Cancelled'));
		} else {
			$this->setRedirect("./?option=" . JRequest::getWord('option') . "&task=listItems&typeId=$typeId", JText::_('Operation Cancelled'));
		}
	}
	
	function cancelListItems() {
		$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listTypes');
	}
	
	function importItems() {
		JRequest::setVar('view', 'import');
		$model = $this->getModel('import');
		if ($model->import()) {
			$this->redirect(JRequest::getVar('returnTo'), 'Import Successful!');
		}
		parent::display();
	}
	
	/**
	 * Type Actions
	 */
	function listTypes() {
		JRequest::setVar('view', 'Types');
		parent::display();
	}
	
	function editType() {
		JRequest::setVar('hidemainmenu', 1);
		
		$view =& $this->getView('Type', 'html');
		$view->setModel($this->getModel('Type'), true);
		$view->setModel($this->getModel('Attrs'));
		$view->display();
 	}
	
	function saveType() {
		$model = $this->getModel('Type');
		
		$ret = $model->store();
		if ($ret === true) {
			$this->setRedirect(COM_URI . 'task=listTypes', JText::_('Type Saved!'));
		} else {
			JRequest::setVar('task', JRequest::getVar('cid', 0, '', 'array') == array(0) ? 'addType' : 'editType');
			$this->editType();
		}
	}
	
	function removeType() {
		$model = $this->getModel('Type');
		if (!$model->delete()) {
			JError::raiseWarning(500, JText::_('Error: One or More types could not be deleted!'));
			$this->listTypes();
		} else {
			$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listTypes', JText::_('Types deleted!'));
		}
	}
	
	function cancelTypeEdit() {
		$this->setRedirect('./?option=' . JRequest::getWord('option') . '&task=listTypes');
	}
	
	function back() {
		$this->setRedirect(COM_URI, JText::_('Operation Cancelled'));
	}
	function extract()
    {
    	
    	
     
      global $mainframe;
    $cid		= JRequest::getVar( 'cid', array(), '', 'array' );
    $cids = implode( ',', $cid );
      ## Make DB connections
      $db    = JFactory::getDBO();
     
      $sql = "SELECT `t112-1`.`id` , `t112-1`.`a61` as `email_address`, `t112-1`.`a64` as `name`, `t112-1`.`a65` as `send_email?`, `t112-1`.`a66` as `date`
 FROM  (SELECT `t112-1i`.id,
	GROUP_CONCAT(IF(`t112-1v`.`attrOfId` = 61, `t112-1v`.`value`, '') SEPARATOR '') AS `a61`,
	GROUP_CONCAT(IF(`t112-1v`.`attrOfId` = 64, `t112-1v`.`value`, '') SEPARATOR '') AS `a64`,
	GROUP_CONCAT(IF(`t112-1v`.`attrOfId` = 65, `t112-1v`.`value`, '') SEPARATOR '') AS `a65`,
	GROUP_CONCAT(IF(`t112-1v`.`attrOfId` = 66, `t112-1v`.`value`, '') SEPARATOR '') AS `a66`, `t112-1v`.`count`, `t112-1i`.`typeId`, `t112-1i`.`published`
FROM `jos_ttadb_item` `t112-1i` LEFT JOIN `jos_ttadb_value` `t112-1v` ON `t112-1i`.`id` = `t112-1v`.`itemId` WHERE `t112-1i`.`typeId` = 112  GROUP BY `t112-1i`.id, `t112-1v`.`count` ORDER BY `t112-1v`.`count`) AS `t112-1` ORDER BY `date` DESC,`t112-1`.`id`";
     
      $db->setQuery($sql);
      $rows = $db->loadAssocList();
  
     
      ## If the query doesn't work..
      if (!$db->query() ){
         echo "<script>alert('Please report your problem.');
         window.history.go(-1);</script>\n";     
      }   
     
      ## Empty data vars
      $data = "" ;
      ## We need tabbed data
      $sep = "\t";
     
      $fields = (array_keys($rows[0]));
     
      ## Count all fields(will be the collumns
      $columns = count($fields);
      ## Put the name of all fields to $out.
      for ($i = 0; $i < $columns; $i++) {
        $data .= $fields[$i].$sep;
      }
     
      $data .= "\n";
     
      ## Counting rows and push them into a for loop
      for($k=0; $k < count( $rows ); $k++) {
         $row = $rows[$k];
         $line = '';
         
         ## Now replace several things for MS Excel
         foreach ($row as $value) {
           $value = str_replace('"', '""', $value);
           $line .= '"' . $value . '"' . "\t";
         }
         $data .= trim($line)."\n";
      }
     
      $data = str_replace("\r","",$data);
     
      ## If count rows is nothing show o records.
      if (count( $rows ) == 0) {
        JError::raiseWarning( 000, JText::_( 'There are no registrants!' ) );
        return false;
      }
     
      ## Push the report now!
      $this->name = 'download_registrants-'.date('Y-m-d');
      header("Content-Type: application/vnd.ms-excel; charset=utf-8");
      header("Content-Disposition: attachment; filename=".$this->name.".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      header("Lacation: excel.htm?id=yes");
      print $data ;
      die();         

    }
} ?>
