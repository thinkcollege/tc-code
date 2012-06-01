<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: mod_docman_logs.php 629 2008-02-25 00:45:17Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

global $_DOCMAN;
$_DOCMAN->setType(_DM_TYPE_MODULE);
$_DOCMAN->loadLanguage('modules');
require_once($_DOCMAN->getPath('classes', 'utils'));


/*
$query = "SELECT l.log_docid, l.log_ip, l.log_datetime, l.log_user, d.dmname, u.name"
        ."\n FROM #__docman_log l, #__docman d, #__users u"
        ."\n WHERE l.log_docid = d.id"
        ."\n AND (l.log_user = u.id OR l.log_user=0)"
        ."\n ORDER BY l.log_datetime DESC";
        */
$query = "SELECT l.log_docid, l.log_ip, l.log_datetime, l.log_user, d.dmname, u.name"
        ."\n FROM (jos_docman_log AS l LEFT JOIN jos_docman AS d ON l.log_docid = d.id)"
        ."\n LEFT JOIN jos_users AS u ON l.log_user = u.id"
        ."\n ORDER BY l.log_datetime DESC";
$database->setQuery($query, 0, $params->get('limit', 10));
$rows = $database->loadObjectList();

?>
<table class="adminlist">
	<tr>
	    <th><?php echo _DML_MOD_LOGS_TITLE;?></th>
        <th><?php echo _DML_MOD_LOGS_USER;?></th>
        <th><?php echo _DML_MOD_LOGS_IP;?></th>
        <th><?php echo _DML_MOD_LOGS_DATE;?></th>

	</tr><?php
    if (!$_DOCMAN->getCfg('log')) echo '<tr><td colspan="4">' . _DML_MOD_LOGS_DISABLED . '</td></tr>';
    foreach ($rows as $row) {
        if(0==$row->log_user) { $row->name =_DML_MOD_GUEST;}
        ?>
    	<tr>
    	    <td>
                <a href="index2.php?option=com_docman&amp;section=documents&amp;task=edit&amp;cid[]=<?php echo $row->log_docid;?>">
                <?php echo $row->dmname;?>
                </a>
    	    </td>
            <td align="right"><?php echo $row->name;?></td>
    	    <td align="right"><a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput=<?php echo $row->log_ip;?>" target="_blank"><?php echo $row->log_ip;?></a></td>
    	    <td align="right"><?php echo $row->log_datetime;?></td>
    	</tr><?php
    }?>
    <tr><th colspan="4"><?php DOCMAN_Utils::getModuleButtons( $name ) ?></th></tr>
</table>