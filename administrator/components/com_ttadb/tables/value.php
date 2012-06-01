<?php
/**
 * Value table class
 * 
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Value Table class
 *
 */
class TableValue extends JTable {
 
	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * itemId
     * 
     * @var int
     */
    public $itemId = null;
 
 	/**
     * Attribute ID
     * 
     * @var int
     */
    public $attrOfId = null;
 
    /**
     * If an attribute can have multiple values than be stored and sorted.
     * @var unknown_type
     */
    public $count = null;
    
 	/**
     * Value to the attribue
     * 
     * @var int
     */
    public $value = null;
      
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__ttadb_value', 'id', $db);
    }
    
    function bind(&$from, $ignore = array()) {
    	if (!parent::bind($from, $ignore)) {
    		$this->setError('Failed to bind Value for attribute!');
    		return false;
    	}
    	$attrOf = self::getInstance('AttrOf', 'Table');
    	if (!$attrOf->load($this->attrOfId)) {
    		$this->setError('Failed to load type-attribute with id: ' . $this->attrOfId);
    		return false;
    	}
    	$this->attrOfId	= $this->attrOfId !== null ? intval($this->attrOfId) : null;
    	$this->count	= $this->count !== null ? intval($this->count) : null;
    	
    	$attr = self::getInstance('Attr', 'Table');
    	$attr->load($attrOf->attrId);
    	if ($attr->typeId == 3) {
    		$this->value = abs(intval($this->value));
    	} else if ($attr->typeId == 4) {
    		$this->value = is_array($this->value) ? $this->value : array($this->value);
			$total = 0;
			foreach ($this->value as $val) {
				$log = log($val, 2);
				// checking to make sure $val is a power of 2.
				if ($log . '' == intval($log) . '') {
					$total += intval($val);
				}
			}
			$this->value = $total;

		} else if ($attr->typeId == 5) { 
			//	Hack **  to keep file upload field from causing an error if empty
			if ($this->value['name'] && empty($this->value['delete'])) { 
    		JRequest::setVar('Filedata', $this->value, 'files');
    		JRequest::setVar('folder', 'files');
    		$media = new MediaControllerFile();
    		$media->upload();
    		$queue = JFactory::getApplication()->getMessageQueue();
    		end($queue);
    		$msg = current($queue);
			
			$this->value = $this->value['name']; 
    		if ($msg['type'] == 'notice') {
    			$this->setError('Failed to upload file: ' . $attr->inputtLabel);
    			return false;
    		}
}
    		
    	} else {
	// Hack **  $this->value = htmlspecialchars(preg_replace('/[\x80-\xFF]/', '', trim($this->value)), ENT_COMPAT, 'UTF-8');
    		$this->value = $this->value;
    	}
    	
    	return true;
    }
    
    function check() {
    	if (strlen($this->id) > 0 && ($this->id . '') !== (intval($this->id) . '')) {
    		$this->setError('Invalid value ID');
    		return false;
    	} else if (strlen($this->id) == 0) {
    		$this->id = 0;
    	} else {
    		$this->id = intval($this->id);
    	}
    	
    	if ($this->attrOfId == 0) {
    		$this->setError('Unknown type-attribute ID.');
    		return false;
    	}
    	
    	$attr	= JModel::getInstance('attrs', 'TtaDbModel')->getAttrOfById($this->attrOfId);
    	if ($attr->typeId <= 0) {
    		$this->setError('Attribute type ID unknown.');
    		return false;
    	} else if ($attr->typId == 2 && !$attr->validation) {
    		$this->setError('Attribute validation unknown.');
    		return false;
    	}/* else if ($attrOf->required === false && $attrOf->required === false) {
    		$this->setError('Attribute required unknown.');
    		return false;
    	}*/
    	$link = "<a href=\"#a$attr->attrId\">$attr->inputLabel</a>";
    	if ($attr->required && !$this->value) {
    		$this->setError("\"$link\" is required, please answer it.");
    		return false;
    	} else if (!$attr->required && !$this->value) {
    		return true;
    	}
    	if ($attr->typeId == 2) {
    		switch ($attr->validation) {
    			case V_INT:		$this->value = floatval($this->value);	break;
    			case V_FLOAT:	$this->value = intval($this->value);	break;
    			case V_EMAIL:
    				$email = $this->value;
    				$pos = strpos($email, '<');
					$pos2 = strpos($email, '>');
					if ($pos !== false && $pos2 !== false) {
						$email = substr($email, $pos + 1, -1);
					} else if ($pos xor $pos2) {
						$this->setError('Poorly formed email address "' . $this->value . '" for attribute "' . $link . '".');
						return false;
					}
					$atom = '[-a-z0-9!#$%&\'*+\/=?^_`{|}~]';	 // allowed characters for part before "at" character
					$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // allowed characters for part after "at" character
					$TDL = '([a-z]([-a-z0-9]*[a-z0-9]+)?)';
					$regex = '/^' . $atom . '+' 			// One or more atom characters.
						   . '(\.' . $atom . '+)*'			// Followed by zero or more dot separated sets of one or more atom characters.
						   . '@'							// Followed by an "at" character.
						   . '(' . $domain . '{1,63}\.)+'	// Followed by one or max 63 domain characters (dot separated).
						   . $TDL . '{2,63}'				// Must be followed by one set consisting a period of two
						   . '$/i';
					if (!preg_match($regex, $email)) {
						$this->setError('Invalid E-mail Address "' . $this->value . '" for attribute "' . $link . '".');
						return false;
					}
					break;
    			case V_PHONE:
    				$usPhone = "/^(?:\+?1[\-\s]?)?(\(\d{3}\)|\d{3})[\-\s\.]?" // area code
							 . "(\d{3})[\-\.\s]?(\d{4})"						// seven digits
							 . "(?:\s?x|\s|\s?ext(?:\.|\s)?)?(\d*)?$/";		// any extension

					if (!preg_match($usPhone, preg_replace('/\D+/', '', $this->value), $match)) {
						$this->setError('Invalid Phone number "' . $this->value . '" for attribute "' . $link . '".');
						return false;
					}
					$tmp = "";
					if (substr($match[1], 0, 1) == "(") {
						$tmp .= substr($match[1], 1, 3);
					} else {
						$tmp .= $match[1];
					}
					$tmp .= "." . $match[2] . "." . $match[3];
					if ($match[4] != '') {
						$tmp .= " x" . $match[4];
					}
					$this->value = $tmp;
					break;
    			case V_NAME:
    				$this->value = preg_replace('/\s+/', ' ', $this->value);
    				if (preg_match('/[^a-z0-9 ()+&.,;:\/\'-]/i', $this->value)) {
    					#print "erroring on name: $this->value<br />";
    					$this->setError("\"$link\" contains invalid characters.");
    					return false;
    				}
    				break;
    			case V_ADDR:	
    				if (preg_match('/[^a-z\d\s#\'.,-]/i', $this->value)) {
    					$this->setError("\"$link\" contains invalid characters.");
    					return false;
    				}
    				break;
    			case V_STATE:
    				$this->value = strtoupper($this->value);
    				if (!preg_match('/^[A-Z]{2}$/', $this->value)) {
    					$this->setError('Invalid State "' . $this->value . '" for attribute "' . $link . '".');
    					return false;
    				}
    				break;
    			case V_ZIP:								   
    				if (!preg_match('/^(\d{5}(?:-\d{4})?$|[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVXWYZ] *\d[ABCEGHJKLMNPRSTVXWYZ]\d)$/', $this->value)) {
    					$this->setError('Invalid zip code for "' . $link . '"');
    					return false;
    				}
    				break;
    			case V_URL:
    				$parse = parse_url($this->value);
    				if ($parse === false) {
    					$this->setError('Unrecognized URL for "' . $link . '".');
    					return false;
    				}
    				break;
    			case V_DATE:
    				$date = strtotime($this->value);
    				if ($date === false) {
    					$this->setError('Unrecognized date for "' . $link . '".');
    					return false;
    				} else {
    					$this->value = $date;
    				}
    				break;
    			case V_TEXT:
    			default:		
    		}
    	} else if ($attr->typeId == 3 || $attr->typeId == 4) {
    		$max = $attr->typeId == 3 ? count($attr->choices) : pow(2, count($attr->choices)) - 1;
    		if ($max < $this->value) {
    			$this->setError("Invalid choice $this->value:$max for attribute \"$link\".");
    			return false;
    		}
    	}
    	return true;
    }
    
    
    function delete($oid = null) {
    	if ($oid != null) {
    		$this->load($oid);
    	}
    	if (parent::delete($oid)) {
	// Hack ** --media manager was not removing file
unlink(JPATH_ADMINISTRATOR . DS . 'components' . DS . strtolower(JRequest::getWord('option')) .DS. 'files' . DS . strtolower($this->value)); print_r($this);
// end hack   		
return false;
    	}
    	// Media Manager 1.5 hack to prevent redirect after delettion;
    	global $mainframe;
    	$mainframe = new TtaDbObjectWrapper($mainframe);
    	$mainframe->override('redirect', null);
    	// End hack
    	JRequest::setVar('rm', array($this->value));
    	JRequest::setVar('folder', 'files');
    	$media->delete();
    	// Clearn up heac replace mainframe object
    	$mainframe = $mainframe->getObject();
    	//End Hack
    	return true;
    }
    
    /*function deleteBy($field, $equals) {
    	if (!$field || !$equals) {
    		$this->setError("Invalid call to JTable::deleteBy($field, $equals).");
    		return false;
    	}
    	$field	= $this->getDBO()->nameQuote($field);	
    	$equals = $this->getDBO()->quote($equals);
    	$this->getDBO()->setQuery('DELETE FROM ' . $this->getDBO()->nameQuote($this->_tbl) . " WHERE $field = $equals");
    	
    	if (!$this->getDBO()->query()) {
    		$this->setError($this->getDBO()->getErrorMsg());
    		return false;
    	}
    	return true;
    }*/
    
}