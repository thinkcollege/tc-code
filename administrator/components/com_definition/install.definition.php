<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */


defined('_JEXEC') or die('Restricted access');

function changeIcon($name,$option,$icon) {
  $database =& JFactory::getDBO();
  $database->setQuery( "UPDATE #__components"
  ."\n SET admin_menu_img = '".$icon."'"
  ."\n WHERE name = '".$name."' AND `option` = '".$option."'");
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }	
}

function com_install() {
	$database =& JFactory::getDBO();
	$config =& JFactory::getConfig();
	$language =& JFactory::getLanguage();
	
	$siteroot = JPATH_SITE;
	$lang = $language->getBackwardLang();
	$mosConfig_live_site = JURI::root();	
	if (file_exists($siteroot.'/components/com_definition/languages/'.$lang.'.php')) {
		require_once($siteroot.'/components/com_definition/languages/'.$lang.'.php');
		include_once($siteroot.'/components/com_definition/languages/english.php');
	} else {
		require_once($siteroot.'/components/com_definition/languages/english.php');
	}
	
	$sql = "ALTER TABLE #__definition  ADD `tletter` char(1) NOT NULL default '' AFTER `id`;";
	$database->setQuery($sql);
	$database->query();
	$sql = "ALTER TABLE #__definition CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
	$database->setQuery($sql);
	// $database->query();
	$sql = "UPDATE #__definition SET tletter = UPPER(SUBSTRING(tterm,1,1))";
	$database->setQuery($sql);
	$database->query();

	$sql = "SELECT COUNT(id) FROM #__categories WHERE section='com_definition' AND published=1";
	$database->setQuery($sql);
	if ($database->loadResult() == 0) {
		$cat_title = _DEFINITION_DEFAULT_CATEGORY;
    	$sql = "INSERT INTO `#__categories`
          (`title`, `name`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`) VALUES
          ('Definition', 'Definition', 'com_definition', 'left', '$cat_title', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0)";
		$database->setQuery($sql);
		$database->query();
	}
	
	echo "Correcting images... ";
	changeIcon("View Terms","com_definition","content.png");
	changeIcon("Categories","com_definition","category.png");
	changeIcon("Edit Config","com_definition","config.png");
	changeIcon("Definition","com_definition","../administrator/components/com_definition/images/icon.png");
	echo "<b>OK</b><br />";

# Show installation result to user
?>
<center>
<table width="100%" border="0">
  <tr>
    <td><img src="components/com_definition/images/logo.png"></td>&nbsp;
    <td>
      <strong>Definition Component</strong><br/>
      <br/>
    </td>
  </tr>
  <tr>
    <td>
      <code>Installation: <font color="green">successful</font></code>
	  <br />
	  Support : <a href="http://www.granholmcms.com" target="_new">http://www.granholmcms.com</a>
	  Have you remembered to donate? <a href="http://granholmcms.com/index.php?option=com_content&view=article&id=3&Itemid=8" target="_new">Donate here</a>
    </td>
  </tr>
  <tr><td>
  	Definition Component &copy; Copyright 2008 by Granholmcms.com
	<br />
	<br />
	This component is released under the terms and conditions of the GNU General Public License.
  </td></tr>
</table>
</center>
<?php
}
?>