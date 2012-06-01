<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */


class definitionDefinition {
	var $gl_utf8 = 0;
	var $gl_admin_utf8 = 0;
	var $gl_allowentry = '';
	var $gl_autopublish = '';
	var $gl_notify = '';
	var $gl_notify_email = '';
	var $gl_thankuser = '';
	var $gl_perpage = '';
	var $gl_sorting = '';
	var $gl_showrating = '';
	var $gl_anonentry = '';
	var $gl_hideauthor = '';
	var $gl_showcategories = '';
	var $gl_beginwith = '';
	var $gl_shownumberofentries = '';
	var $gl_showcatdescriptions = '';
	var $gl_useeditor = '';
	var $gl_defaultcat = 0;
	var $gl_show_alphabet = 1;
	var $gl_strip_accents = 0;
	var $search;
	var $search_type;
	var	$accented = "ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ";
	var $nonaccent = "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy";
	var $convertor = array();

	function definitionDefinition() {
		require(JPATH_SITE."/administrator/components/com_definition/config.definition.php");
		for ($i = 0, $n = strlen($this->accented); $i < $n; $i++) {
			$convertor[$this->accented[$i]] = $this->nonaccent[$i];
		}
		foreach (get_class_vars(get_class($this)) as $k=>$v) {
			if(isset($$k)) {
				$this->$k = $$k;
			} else {
				$this->$k = '';
			}
		}
	}

	function &getInstance () {
		static $instance;
		if (!is_object($instance)) $instance = new definitionDefinition();
		return $instance;
	}

	function abc ($catid = 0) {
		$database =& JFactory::getDBO();
		if ($this->gl_utf8) $sql = "SELECT DISTINCT tletter COLLATE utf8_bin FROM #__definition";
		else $sql = "SELECT DISTINCT tletter FROM #__definition";
		if ($catid = intval($catid)) $sql .= " WHERE catid=$catid";
		$sql .= " ORDER BY tletter";
		$database->setQuery($sql);
		$letters = $database->loadResultArray();
		if (is_array($letters)) {
			if ($this->gl_strip_accents) {
				$stripped = array();
		    	foreach ($letters as $letter) {
		    		if (isset($this->convertor[$letter])) $letter = $this->convertor[$letter];
		    		if (!in_array($letter, $stripped)) $stripped[] = $letter;
		    	}
				return $stripped;
			}
			return $letters;
		}
		else return array();
	}
	
	function letterSQL ($letter) {
		$sql = "(UPPER(tterm) RLIKE '^$letter'";
		if ($this->gl_strip_accents) foreach ($this->convertor as $accent=>$convert) {
			if ($convert == $letter) $sql .= " OR UPPER(tterm) RLIKE '^$accent'";
		}
		return $sql.')';
	}

	function abcplus ($catid = 0) {
		return array_merge(array(_DEFINITION_ALL),definitionDefinition::abc($catid));
	}

	function abcplus_key ($catid = 0) {
		return array_merge(array('All'),definitionDefinition::abc($catid));
	}
	
	function isUser() {
		$user = &JFactory::getUser();
		if ($user->id) return true;
		else return false;
	}
	
	function isEditor () {
		$user = &JFactory::getUser();
		$type = strtolower($user->usertype);
        return ($type == 'editor' || $type == 'publisher' || $type == 'manager' || $type == 'administrator' || $type == 'super administrator' );
	}
	
	function isAdmin () {
		global $my;
		$type = strtolower($my->usertype);
        return ($type == 'administrator' || $type == 'super administrator' );
	}
}

class definitionEntry {
	var $id=null;
	var $tname=null;
	var $tmail=null;
	var $tpage=null;
	var $tloca=null;
	var $tterm=null;
	var $tdefinition=null;
	var $tdate=null;
	var $tcomment=null;
	var $tedit=null;
	var $teditdate=null;
	var $published=null;
	var $catid=null;
	var $checked_out=null;
}

?>