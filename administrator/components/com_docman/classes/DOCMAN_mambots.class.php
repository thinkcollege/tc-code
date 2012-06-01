<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_mambots.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_MAMBOT')) {
    return;
} else {
    define('_DOCMAN_MAMBOT', 1);
}

/**
* DOCMAN Mambots Class
*
* @desc class purpose is to handle Mambot interactions
*/

class DOCMAN_mambot {
    var $_parms;
    var $_ds;
    var $_error;
    var $_errmsg;
    var $_group;
    var $_trigger;
    var $_pub;
    var $_return;

    function DOCMAN_mambot($trigger = null, $group = 'docman', $pub = false)
    {
        $this->_ds = array();
        $this->_group = $group;
        $this->_trigger = $trigger;
        $this->_pub = $pub;
        $this->_error = null;

        $this->_parms = array();
    }
    // Set all values to array
    function setParmArray(&$name)
    {
        foreach($name as $key => $value) {
            $this->_parms[ $key ] = &$name[$key];
        }
    }
    function setParm($name, &$getFirst)
    {
        if (is_array($name)) {
            $this->_parms += $name ;
        } else {
            if ($getFirst == null) {
                $this->_parms[ $name ] = null ;
            } else {
                $this->_parms[ $name ] = &$getFirst ;
            }
        }
    }

    function copyParm($name, $getFirst = null)
    {
        if (is_array($name)) {
            $this->_parms += $name ;
        } else {
            if ($getFirst == null) {
                $this->_parms[ $name ] = null ;
            } else {
                $this->_parms[ $name ] = $getFirst ;
            }
        }
    }

    function &getParm($name = null)
    {
        if (is_null($name)) {
            return $this->_parms;
        }
        return $this->_parms[ $name ];
    }

    function getError()
    {
        return (is_null($this->_error) ? 0: $this->_error);
    }
    function getErrorMsg()
    {
        return (is_null($this->_errmsg) ? 0: $this->_errmsg);
    }

    /**
    *
    * @desc Get the first occurance of a key
    * @param string $name the name to look for
    * @return Single value
    */
    function getFirst($name)
    {
        if (is_array($this->_return)) {
            foreach($this->_return as $row) {
                if (is_array($row) &&
                        array_key_exists($name , $row)) {
                    return $row[$name];
                }
            }
        }
        return null;
    }

    /**
    *
    * @desc This returns all the strings from all mambots
    * 		that returned a value of 'name'
    * @param string $name the name to look for
    *                 int the category id
    * @return array An array of ALL the matching entrys
    */

    function getAll($name)
    {
        if (is_array($this->_return)) {
            $all = array();
            foreach($this->_return as $row) {
                if (is_array($row) &&
                        array_key_exists($name , $row)) {
                    $all[] = $row[$name];
                }
            }
            return count($all)? $all: null;
        }
        return null;
    }

    /**
    *
    * @desc This performs the MAMBOT call and interfaces
    * 		what we want with what Mambo does
    * @param string $trigger the trigger to call.
    * @param boolean $pub - whether to call unpublished routines
    * @return boolean True or false if an error occured
    */

    function trigger($trigger = null , $pub = false) {

        global $_MAMBOTS, $mainframe;

        $trigger = $trigger ? $trigger : $this->_trigger;
        if ($trigger == null || ! $this->_group) {
            $this->_error = 1;
            $this->_errmsg = _DML_INTERRORMAMBOT;
            return false;
        }

        $task =  isset($_GET['task']) ? $_GET['task'] : 'unknow';

        // Set required parms
        $this->_parms += array('content_src' => 'docman',
            'task' => $task,
            'mambo_ds' => &$this->_ds);

        if(defined('_DM_J15')) {
            JPluginHelper::importPlugin('docman');
            $this->_return = $mainframe->triggerEvent( $trigger, array($this->_parms), $pub);
        } else {
            $_MAMBOTS->loadBotGroup($this->_group);
            $this->_return = $_MAMBOTS->trigger($trigger, array($this->_parms), $pub);
        }

        $this->_error = $this->getFirst('_error');
        $errmsg = $this->getAll('_errmsg');
        if ($errmsg) {
            $this->_errmsg = implode('\n' , $errmsg);
        }
        return $this->getError();
    }

    function & getReturn() {
    	return $this->_return;
    }
}

