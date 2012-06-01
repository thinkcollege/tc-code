<?php
/**
 * Default Model for ThinkCollege Programs Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Default Model
 *
 */
class programsdatabaseModelProgramsdatabase extends JModel {

	/**
	 * States array of objects.
	 * @var array
	 */
	private $_states = null;
	
	public function getStates() {
		if (!$this->_states) {
			$tPrograms = $this->getTable('Answers');
			$statesraw = $this->_getList("SELECT DISTINCT answer AS `state` FROM `$tPrograms->_tbl` LEFT JOIN `jos__programsdb_programs` on $tPrograms->_tbl.ProgramID = jos__programsdb_programs.id WHERE `QuestionID` = 13 AND `Answer` <> '' AND jos__programsdb_programs.published = 1 ORDER BY `Answer`");
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			}
			
			foreach($statesraw as $indstate) { 
			

				switch( $indstate->state) {
				case 'AL': 
			
$indstate->statename = 'Alabama';
				break;
				case 'AK':
					$indstate->statename = 'Alaska';
					break;
				case 'AZ':
					$indstate->statename = 'Arizona';
					break;
				case 'AR':
					$indstate->statename = 'Arkansas';
					break;
				case 'CA':
					$indstate->statename = 'California';
					break;
				case 'CO':
					$indstate->statename = 'Colorado';
					break;
				case 'CT':
					$indstate->statename = 'Connecticut';
					break;
				case 'DE':
					$indstate->statename = 'Delaware';
					break;
				case 'DC':
					$indstate->statename = 'District Of Columbia';
					break;
				case 'FL':
					$indstate->statename = 'Florida';
					break;
				case 'GA':
					$indstate->statename = 'Georgia';
					break;
				case 'HI':
					$indstate->statename = 'Hawaii';
					break;
				case 'ID':
					$indstate->statename = 'Idaho';
					break;
				case 'IL':
					$indstate->statename = 'Illinois';
					break;
				case 'IN':
					$indstate->statename = 'Indiana';
					break;
				case 'IA':
					$indstate->statename = 'Iowa';
					break;
				case 'KS':
					$indstate->statename = 'Kansas';
					break;
				case 'KY':
					$indstate->statename = 'Kentucky';
					break;
				case 'LA':
					$indstate->statename = 'Louisiana';
					break;
				case 'ME':
					$indstate->statename = 'Maine';
					break;
				case 'MD':
					$indstate->statename = 'Maryland';
					break;
				case 'MA':
					$indstate->statename = 'Massachusetts';
					break;
				case 'MI':
					$indstate->statename = 'Michigan';
					break;
				case 'MN':
					$indstate->statename = 'Minnesota';
					break;
				case 'MS':
					$indstate->statename = 'Mississippi';
					break;
				case 'MO':
					$indstate->statename = 'Missouri';
					break;
				case 'MT':
					$indstate->statename = 'Montana';
					break;
				case 'NE':
					$indstate->statename = 'Nebraska';
					break;
				case 'NV':
					$indstate->statename = 'Nevada';
					break;
				case 'NH':
					$indstate->statename = 'New Hampshire';
					break;
				case 'NJ':
					$indstate->statename = 'New Jersey';
					break;
				case 'NM':
					$indstate->statename = 'New Mexico';
					break;
				case 'NY':
					$indstate->statename = 'New York';
					break;
				case 'NC':
					$indstate->statename = 'North Carolina';
					break;
				case 'ND':
					$indstate->statename = 'North Dakota';
					break;
				case 'OH':
					$indstate->statename = 'Ohio';
					break;
				case 'OK':
					$indstate->statename = 'Oklahoma';
					break;
				case 'OR':
					$indstate->statename = 'Oregon';
					break;
				case 'PA':
					$indstate->statename = 'Pennsylvania';
					break;
				case 'RI':
					$indstate->statename = 'Rhode Island';
					break;
				case 'SC':
					$indstate->statename = 'South Carolina';
					break;
				case 'SD':
					$indstate->statename = 'South Dakota';
					break;
				case 'TN':
					$indstate->statename = 'Tennessee';
					break;
				case 'TX':
					$indstate->statename = 'Texas';
					break;
				case 'UT':
					$indstate->statename = 'Utah';
					break;
				case 'VT':
					$indstate->statename = 'Vermont';
					break;
				case 'VA':
					$indstate->statename = 'Virginia';
					break;
				case 'WA':
					$indstate->statename = 'Washington';
					break;
				case 'WV':
					$indstate->statename = 'West Virginia';
					break;
				case 'WI':
					$indstate->statename = 'Wisconsin';
					break;
				case 'WY':
					$indstate->statename = 'Wyoming';
					break;
				case 'AB':
					$indstate->statename = 'Alberta';
					break;
				case 'BC':
					$indstate->statename = 'British Columbia';
					break;

				
				default:
				echo '';
				break;
				}
			
				$data[] = $indstate;
				
				}
		
		} 	
			$this->_states = $data;
		return $this->_states;
	}
	function arrayToObject($d) {
                if (is_array($d)) {
                        /*
                        * Return array converted to object
                        * Using __FUNCTION__ (Magic constant)
                        * for recursive call
                        */
                        return (object) array_map(__FUNCTION__, $d);
                }
                else {
                        // Return object
                        return $d;
                }
        }
}