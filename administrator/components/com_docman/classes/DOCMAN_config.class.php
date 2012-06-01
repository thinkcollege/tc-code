<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_config.class.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_config')) {
    return true;
} else {
    define('_DOCMAN_config', 1);
}

class DOCMAN_Config {
    /**
    * *
    *
    * @var string The path to the configuaration file
    */
    var $_path = null;

    /**
    * *
    *
    * @var string The name of the configuaration class
    */
    var $_name = null;

    /**
    * *
    *
    * @var object An object of configuration variables
    */
    var $_config = null;

    function DOCMAN_Config($name, $path)
    {
        $this->_path = $path;
        $this->_name = $name;
        $this->_loadConfig();
    }

    /**
    *
    * @param string $ The name of the variable
    * @return mixed The value of the configuration variable or null if not found
    */
    function getCfg($varname , $default = null)
    {
        if (isset($this->_config->$varname)) {
            return $this->_config->$varname;
        } else {
            if (! is_null($default)) {
                $this->_config->$varname = $default;
            }
            return $default;
        }
    }

    /**
    *
    * @param string $ The name of the variable
    * @param string $ The new value of the variable
    * @return bool True if succeeded, otherwise false.
    */
    function setCfg($varname, $value, $create = false)
    {
        if ($create || isset($this->_config->$varname)) {
            $this->_config->$varname = $value;
            return true;
        } else {
            return false;
        }
    }

    /**
    * Loads the configuration file and creates a new class
    */
    function _loadConfig()
    {
        if (file_exists($this->_path)) {
            require_once($this->_path);
            if( class_exists($this->_name)) {
                $this->_config = new $this->_name();
            } else {
            	$this->_config = new StdClass();
            }
        } else {
            $this->_config = new StdClass();
        }
    }

    /**
    * Saves the configuration object
    */
    function saveConfig()
    {
        global $my;

        $this->check();

        $config = "<?php\n";
        $config .= "if(defined('_" . $this->_name . "')) {\nreturn true;\n} else { \ndefine('_" . $this->_name . "',1); \n\n";
        $config .= "class " . $this->_name . "\n{\n";
        $config .= "// Last Edit: " . strftime("%a, %Y-%b-%d %R") . "\n";
        $config .= "// Edited by: " . $my->username . "\n";

        $vars = get_object_vars($this->_config);
        ksort($vars);
        foreach($vars as $key => $value) {
            $config .= 'var $' . $key . ' = ' . var_export($value , true) . ";\n" ;
        }

        $config .= "}\n}";


        if ($fp = fopen($this->_path, "w")) {
            if( fputs($fp, $config) !== false AND fclose($fp) !==false) {
                return true;
            }
        }

        return false;
    }

    function check(){
        /**
         * Handle single and double quotes
         */
    	$search  = array( "\'", '\"' );
        $replace = array( "'", '"' );

        $vars = get_object_vars($this->_config);
        foreach( $vars as $key=>$var ) {
            $this->_config->$key = str_replace( $search, $replace, $var );
        }


        return true;
    }
}