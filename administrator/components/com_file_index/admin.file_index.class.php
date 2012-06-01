<?php
/**
* PDF Index - A Mambo/Joomla PDF Indexing Component

* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @version 2.4
* @package File Index
* @copyright (C) 2008 by Nate Maxfield
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mosFileIndex extends mosDBTable {
	
	/** @var int Primary key */
	var $id	= null;
	var $title = "";
	var $location = "";
	var $description = "";
	var $restricted = "";
	var $filesize = "";
	var $username = "";
	var $password = "";
	var $checked_out = "";
	var $checked_out_time = "";
	
		
	/**
	* @param database A database connector object
	*/

	function mosFileIndex( $database ) {
		$this->mosDBTable( '#__com_file_index', 'id', $database );
	}
}



class indexManager {
/** @var config Configuration of the map */
	var $_config=null;

	/**
	 * Standard constructor
	 * @param database A database connection object
	 * @param string The path of the mos directory
	*/
	function indexManager( $basePath, $adminPath ) {
		$this->_loadConfiguration( $adminPath );
	}
	
	/**
	* Loads the configuration.php file and assigns values to the internal variable
	* @param string The base path from which to load the configuration file
	*/
	function _loadConfiguration( $adminPath='.' ) {
		$this->_config = new stdClass();
		require( "$adminPath/components/com_file_index/config.file_index.php" );
		$this->_config->index_Public = $file_index_Public;
		$this->_config->index_Private = $file_index_Private;
		
	}

	/**
	* @param string The name of the variable (from configuration.php)
	* @return mixed The value of the configuration variable or null if not found
	*/
	function getCfg( $varname ) {
		if (isset( $this->_config->$varname )) {
			return $this->_config->$varname;
		} else {
			return null;
		}
	}
	
	/**
	* @param string The name of the variable (from configuration.php)
	* @param mixed The value of the configuration variable
	*/
	function setCfg( $varname, $newValue ) {
		if (isset( $this->_config->$varname )) {
			$this->_config->$varname = $newValue;
		}
	}

	function saveConfiguration ($adminPath=".") {
		$option = $_POST['option'];
		$configfile = "$adminPath/components/com_file_index/config.file_index.php";
		@chmod ($configfile, 0777);
		$permission = is_writable($configfile);
		if (!$permission) {
			$mosmsg = "Config file not writeable!";
			mosRedirect("index2.php?option=$option&act=config",$mosmsg);
			break;
		}
		
		$config = "<?php\n";
		
		$config .= "/** @var Public Reference to the Directories to be Indexed that are Public */\n";
		$config .= "\$file_index_Public =  '" .$this->_config->index_Public. "';\n";
		$config .= "/** @var Private Reference to the Directories to be Indexed that are Private */\n";
		$config .= "\$file_index_Private =  '" .$this->_config->index_Private. "';\n";
		
		$config .= "?>";
		

		if ($fp = fopen("$configfile", "w")) {
			if (fwrite($fp, $config, strlen($config)) === FALSE) {
				echo "Cannot write to file ($config)";
			    exit;
			}

			fclose ($fp);
			$this->_loadConfiguration( $adminPath );
			mosRedirect("index2.php?option=$option&act=config&task=config", "Settings saved");
		}else{
			$this->_loadConfiguration( $adminPath );
			mosRedirect("index2.php?option=$option&act=config&task=config", "Settings not saved");
		}
		
	}


}
?>