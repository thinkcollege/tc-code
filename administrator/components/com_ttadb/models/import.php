<?php
/**
 * Import Model for ThinkCollege Training and Technical Assistance Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
 
/**
 * Import Model
 *
 */
class TtaDbModelImport extends JModel {

	private $row	= array();
	private $post	= array();
	
	private $index	= 0;
	private $empty	= 0;
	
	private $start = 0;
	
	function import() {
		$this->start = microtime(true);
		print "Start import: 0<br />";
		$this->row		= array();
		$this->post		= array();
		$this->index	= 0;
		$this->empty	= 0;
		
		$typeId = JRequest::getInt('typeId');
		if ($typeId <= 0) {
			JError::raiseWarning(500, 'Invalid Type to import.');
			return false;
		}
		
		$data	= JRequest::getVar('Filedata', null, 'files', 'array');
		if ($data === null) {
			return true;
		}
		if (is_file(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']))) {
			unlink(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']));
		}
		JRequest::setVar('folder', 'tmp');
		$media = new MediaControllerFile();
		$media->upload();
		$queue = JFactory::getApplication()->getMessageQueue();
		end($queue);
		$msg = current($queue);
		if ($msg['type'] == 'notice') {
			JError::raiseWarning(500, 'Failded to upload file.');
			return false;
		}
		print "uploaded file:" . (microtime(true) - $this->start) . "<br />";
		$f		= fopen(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']), 'r');
		if ($f === false) {
			unlink(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']));
			JError::raiseWarning(500, 'Failed to open uploaded file.');
			return false;
		}
		$j 		= 0;
		$attrs	= self::getInstance('Attrs', 'TtaDbModel')->getAttrsOf($typeId);
		$item	= self::getInstance('Item', 'TtaDbModel');
		while (!feof($f)) {
			print "staring row $j: " . (microtime(true) - $this->start) . "<br />";
			$this->row = fgetcsv($f);
			if ($this->row === false) {
				continue;  // handles blank or malformee lines.
			}
			$this->index = 0;
			$this->empty = 0;
			$this->post	 = array('cid' => 0);
			foreach ($attrs as &$a) {
				if (!isset($this->post["a$a->attrOfId"])) {
					$this->post["a$a->attrOfId"] = array();
				}
				if (empty($this->row[$this->index]) && ($a->flags & TableAttrOf::FLAG_REQUIRED) == 0) {
					$this->index++;	// skiping empty derived attributes.
				} else if (empty($this->row[$this->index]) && ($a->flags & TableAttrOf::FLAG_REQUIRED) != 0) {
					JError::raiseWarning(500, "$a->inputLabel is required.");
					JError::raiseWarning(500, 'Error in row ' . $j . '.');
					return false;
				} else if (!empty($a->choices)) {
					$val = $this->importDerivedType($a);
					if (!empty($this->row[$this->index]) && empty($val)) {
						JError::raiseWarning(500, "{$this->row[$this->index]} is not a valid choice for $a->inputLabel.");
						JError::raiseWarning(500, 'Error in row ' . $j . '.');
						return false;
					} else {
						$this->post['a' . $a->attrOfId] = $val;
						$this->index++;
					}
				} else {
					$this->post['a' . $a->attrOfId][] = $this->row[$this->index + $this->empty];
					$this->index++; 
				}
			}
			if ((count($this->post) - 1) != count($this->row)) {
				JError::raiseWarning(500, 'Row count does not match attribute count:(' . count($attrs) . ', ' . count($this->row) . ', ' . count($this->post) . ")\nrow:" . print_r($this->row, true) . ' post:' . print_r($this->post, true));
				unlink(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']));
				return false;
			}
			print "storing row $j: " . (microtime(true) - $this->start) . "<br />";
			$item->setId(0);
			#print '<pre>'; print_r($this->post);
			#exit;
			JRequest::set($this->post, 'post');
			if (!$item->store()) {
				unlink(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']));
				JError::raiseWarning(500, "Error occurred in row " . ($j + 1) . "!");
				return false;
			}
			$j++;	
		}
		JFactory::getApplication()->enqueueMessage("Imported $j records!");
		unlink(COM_MEDIA_BASE . DS . 'tmp' . DS . strtolower($data['name']));
		
		return true;
	}
	
	protected function importDerivedType(&$attr) {
		$pos	 = false;
		$ret	 = array();
		$vals	 = $attr->flags & TableAttrOf::FLAG_MULTIPLE ? explode('||', $this->row[$this->index]) : array($this->row[$this->index]);
		$choices = explode('||', $attr->choices);
		foreach ($vals as $val) {
			$val = preg_replace('/[\x80-\xFF]/', '', $val);
			foreach ($choices as $c) {
				$pos = strpos($c, '$$' . $val);
				$pos = $pos === false ? strpos($c, '$$' . trim($val)) : $pos;
				if ($pos !== false) {
					$ret[] = intval(substr($c, 0, $pos));
					break;
				}
			}
			if ($pos === false) {
				print "<pre>choice:$c\ntypeid:$attr->typeId\n";
				print "val:$val\nchoices:$attr->choices</pre>";
				return false;
			}
		}
		print "</pre>";
		return $ret;
	} 
}