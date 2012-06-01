<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class ProgramsDatabaseViewSearch extends JView {
    
	function display($tpl = null) {
		JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
		$results =& $this->get('Data');
		$this->assignRef('results', $results);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		parent::display($tpl);
    }

	function getStateName($state) {
		

				switch( $state) {
				case 'AL': 
			
$statename = 'Alabama';
				break;
				case 'AK':
					$statename = 'Alaska';
					break;
				case 'AZ':
					$statename = 'Arizona';
					break;
				case 'AR':
					$statename = 'Arkansas';
					break;
				case 'CA':
					$statename = 'California';
					break;
				case 'CO':
					$statename = 'Colorado';
					break;
				case 'CT':
					$statename = 'Connecticut';
					break;
				case 'DE':
					$statename = 'Delaware';
					break;
				case 'DC':
					$statename = 'District Of Columbia';
					break;
				case 'FL':
					$statename = 'Florida';
					break;
				case 'GA':
					$statename = 'Georgia';
					break;
				case 'HI':
					$statename = 'Hawaii';
					break;
				case 'ID':
					$statename = 'Idaho';
					break;
				case 'IL':
					$statename = 'Illinois';
					break;
				case 'IN':
					$statename = 'Indiana';
					break;
				case 'IA':
					$statename = 'Iowa';
					break;
				case 'KS':
					$statename = 'Kansas';
					break;
				case 'KY':
					$statename = 'Kentucky';
					break;
				case 'LA':
					$statename = 'Louisiana';
					break;
				case 'ME':
					$statename = 'Maine';
					break;
				case 'MD':
					$statename = 'Maryland';
					break;
				case 'MA':
					$statename = 'Massachusetts';
					break;
				case 'MI':
					$statename = 'Michigan';
					break;
				case 'MN':
					$statename = 'Minnesota';
					break;
				case 'MS':
					$statename = 'Mississippi';
					break;
				case 'MO':
					$statename = 'Missouri';
					break;
				case 'MT':
					$statename = 'Montana';
					break;
				case 'NE':
					$statename = 'Nebraska';
					break;
				case 'NV':
					$statename = 'Nevada';
					break;
				case 'NH':
					$statename = 'New Hampshire';
					break;
				case 'NJ':
					$statename = 'New Jersey';
					break;
				case 'NM':
					$statename = 'New Mexico';
					break;
				case 'NY':
					$statename = 'New York';
					break;
				case 'NC':
					$statename = 'North Carolina';
					break;
				case 'ND':
					$statename = 'North Dakota';
					break;
				case 'OH':
					$statename = 'Ohio';
					break;
				case 'OK':
					$statename = 'Oklahoma';
					break;
				case 'OR':
					$statename = 'Oregon';
					break;
				case 'PA':
					$statename = 'Pennsylvania';
					break;
				case 'RI':
					$statename = 'Rhode Island';
					break;
				case 'SC':
					$statename = 'South Carolina';
					break;
				case 'SD':
					$statename = 'South Dakota';
					break;
				case 'TN':
					$statename = 'Tennessee';
					break;
				case 'TX':
					$statename = 'Texas';
					break;
				case 'UT':
					$statename = 'Utah';
					break;
				case 'VT':
					$statename = 'Vermont';
					break;
				case 'VA':
					$statename = 'Virginia';
					break;
				case 'WA':
					$statename = 'Washington';
					break;
				case 'WV':
					$statename = 'West Virginia';
					break;
				case 'WI':
					$statename = 'Wisconsin';
					break;
				case 'WY':
					$statename = 'Wyoming';
					break;
				case 'AB':
					$statename = 'Alberta';
					break;
				case 'BC':
					$statename = 'British Columbia';
					break;

				
				default:
				echo '';
				break;
				}
			
				
				
			
		
	
			
		return $statename;
	}
} ?>