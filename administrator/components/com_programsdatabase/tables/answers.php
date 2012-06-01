<?php
/**
 * Programs table class
 * 
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Programs Table class
 *
 */
class TableAnswers extends JTable {

	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * ProgramId
     * 
     * @var int
     */
    public $programId = null;
 
 	/**
     * Question ID
     * 
     * @var int
     */
    public $questionId = null;
 
 	/**
     * Answer to the question
     * 
     * @var int
     */
    public $answer = null;
      
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#___programsdb_answers', 'id', $db);
    }
    
    function bind($from, $ignore = array()) {
    	if (!parent::bind($from, $ignore)) {
    		$this->setError('Failed to bind!');
    		return false;
    	}
    	$question = self::getInstance('Questions', 'Table');
    	if (!$question->load($this->questionId)) {
    		$this->setError('Failed to load question with id: ' . $this->questionId);
    		return false;
    	}
    	$this->questionId = intval($this->questionId);
    	
    	if ($question->typeId == 3) { // for testing PF print "<br /> <h2>" . $this->questionId ." Wha?:</h2> " . abs(intval($this->answer));
    		$this->answer = $this->answer ? abs(intval($this->answer)) : '';


    	} else if ($question->typeId == 4) {
    		$this->answer = is_array($this->answer) ? $this->answer : array($this->answer);
    		$total = 0;
			foreach ($this->answer as $ans) {
				$log = log($ans, 2);
				if ($log . '' == intval($log) . '') {
					$total += intval($ans);
				}
			}
			$this->answer = $total > 0 ? $total : '';
		}
		else if ($question->typeId == 6) { 
		$filter =	array_filter($this->answer);
			$secondarr =array();
			foreach ($filter as $k => $v) {
				$secondarr[] = $v;
			}
		$sort = sort($secondarr);
		$this->answer = implode("||", $secondarr );
		
		} else if ($question->typeId == 7) {
							if ($this->answer[0]['name'] && ($this->answer[0]['delete'] != 1)) { 
						$this->answer[0]['name'] =	date('y-m-d-H-i-s') . $this->answer[0]['name'];	
    		JRequest::setVar('Filedata', $this->answer[0], 'files');
    		JRequest::setVar('folder', 'files');
    		$media = new MediaControllerFile();
    		$media->upload();
    		$queue = JFactory::getApplication()->getMessageQueue();
    		end($queue);
    		$msg = current($queue);
			
			$this->answer = $this->answer[0]['name']; 
    		if ($msg['type'] == 'notice') {
    			$this->setError('Failed to upload file: ' . $this->answer);
    			return false;
    		}
}

}
	 else {$this->answer = htmlentities(trim($this->answer), ENT_COMPAT, 'UTF-8');
    	}
    	
    	return true;
    }
    
    function check() {
    	if (strlen($this->id) > 0 && ($this->id . '') !== (intval($this->id) . '')) {
    		$this->setError('Invalid answer ID');
    		return false;
    	} else if (strlen($this->id) == 0) {
    		$this->id = 0;
    	} else {
    		$this->id = intval($this->id);
    	}
    	
    	if ($this->questionId == 0) {
    		$this->setError('Unknown Question ID.');
    		return false;
    	}
    	
    	$questionModel = JModel::getInstance('Question', 'ProgramsDatabaseModel');
    	$questionModel->setId($this->questionId);
    	$question = $questionModel->getData();
    	if (!$question->typeId) {
    		$this->setError('Question type ID unknown.');
    		return false;
    	} else if ($question->typId == 2 && !$question->validation) {
    		$this->setError('Question validation unknown. ' . print_r($question, true));
    		return false;
    	} else if ($question->required === false && $question->required === false) {
    		$this->setError('Question required unknown.');
    		return false;
    	}


    	$link = "<a href=\"http://" . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] . "#q$question->id\">$question->inputLabel</a>";
    	if ($question->required && !$this->answer) {
    		$this->setError("\"$link\" is required, please answer it.");
    		return false;
    	} else if (!$question->required && !$this->answer) {
    		return true;
    	}
    	if ($question->typeId == 2) {
    		switch ($question->validation) {
    			case V_INT:		$this->answer = floatval($this->answer); break;
    			case V_FLOAT:	$this->answer = intval($this->answer);	 break;
    			case V_EMAIL:
    				$email = $this->answer;
    				$pos = strpos($email, '<');
					$pos2 = strpos($email, '>');
					if ($pos !== false && $pos2 !== false) {
						$email = substr($email, $pos + 1, -1);
					} else if ($pos xor $pos2) {
						$this->setError('Poorly formed email address "' . $this->answer . '" for question "' . $link . '".');
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
						$this->setError('Invalid E-mail Address "' . $this->answer . '" for question "' . $link . '".');
						return false;
					}
					break;
    			case V_PHONE:
    				$usPhone = "/^(?:\+?1[\-\s]?)?(\(\d{3}\)|\d{3})[\-\s\.]?" // area code
							 . "(\d{3})[\-\.\s]?(\d{4})"						// seven digits
							 . "(?:\s?x|\s|\s?ext(?:\.|\s)?)?(\d*)?$/";		// any extension

					if (!preg_match($usPhone, $this->answer, $match)) {
						$this->setError('Invalid Phone number "' . $this->answer . '" for question "' . $link . '".');
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
					$this->answer = $tmp;
					break;
    			case V_NAME:
    				if (preg_match('/[^a-z\d\s()+&.,;:\/\'-]/i', $this->answer)) {
    					$this->setError("\"$link\" contains invalid characters.");
    					return false;
    				}
    				break;
    			case V_ADDR:	
    				if (preg_match('/[^a-z\d\s#\'.,-]/i', $this->answer)) {
    					$this->setError("\"$link\" contains invalid characters.");
    					return false;
    				}
    				break;
    			case V_STATE:
    				$this->answer = strtoupper($this->answer);
    				if (!preg_match('/^[A-Z]{2}$/', $this->answer)) {
    					$this->setError('Invalid State "' . $this->answer . '" for question "' . $link . '".');
    					return false;
    				}
    				break;
    			case V_ZIP:								   
    				if (!preg_match('/^(\d{5}(?:-\d{4})?$|[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVXWYZ] *\d[ABCEGHJKLMNPRSTVXWYZ]\d)$/', $this->answer)) {
    					$this->setError('Invalid zip code for "' . $link . '"');
    					return false;
    				}
    				break;
    			case V_URL:
    				$parse = parse_url($this->answer);
    				if ($parse === false) {
    					$this->setError('Unrecognized URL for "' . $link . '".');
    					return false;
    				}
    				break;
    			case V_TEXT:
    			default:		
    		}
    	} else if ($question->typeId == 3 || $question->typeId == 4) {
    		$max = $question->typeId == 3 ? count($question->choices) : pow(2, count($question->choices)) - 1;
    		if ($max < $this->answer) {
    			$this->setError('Invalid choice ' . $this->answer . ':' . $max . ' for question "<a href="#' . $question->id . '">' . $question->inputLabel . '</a>".');
    			return false;
    		}
    	}
    	return true;
    }
    
    function deleteBy($field, $equals) {
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
    }
    
}