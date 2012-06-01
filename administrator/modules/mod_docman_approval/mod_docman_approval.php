<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: mod_docman_approval.php 637 2008-03-01 10:36:40Z mjaz $
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
require_once($_DOCMAN->getPath('classes', 'token'));

$query = "SELECT id, dmname, catid, dmdate_published, dmlastupdateon, approved"
        ."\n FROM #__docman"
        ."\n WHERE approved = 0"
        ."\n ORDER BY dmlastupdateon DESC";
$database->setQuery( $query, 0, $params->get('limit', 10));
$rows = $database->loadObjectList();

?>
<table class="adminlist">
    <tbody>
	<tr>
        <th align="center"><?php echo _DML_MOD_APPROVE;?></th>
        <th><?php echo _DML_MOD_UNAPPROVED_DOCUMENTS;?></th>
        <th><?php echo _DML_MOD_LAST_EDIT_DATE;?></th>
	</tr><?php
    if (!count($rows)) echo '<tr><td colspan="3">' . _DML_MOD_NO_UNAPPROVED_DOCUMENTS . '</td></tr>';
    foreach ($rows as $row) {
        ?>
    	<tr>
            <td width="5%" style="text-align:center">
                <a href="index2.php?option=com_docman&amp;section=documents&amp;task=approve&cid[]=<?php echo $row->id?>&amp;<?php echo DOCMAN_Token::get();?>=1&amp;redirect=index2.php%3Foption%3Dcom_docman">
                <img src="images/publish_r.png" border=0 alt="approve" />
                </a>
            </td>
            <td><a href="index2.php?option=com_docman&amp;section=documents&task=edit&amp;cid[]=<?php echo $row->id ?>"><?php echo $row->dmname;?></a></td>
            <td align="right"><?php echo $row->dmlastupdateon;?></td>
    	</tr><?php
    }?>
    <tr><th colspan="3"><?php DOCMAN_Utils::getModuleButtons( $name ) ?></th></tr>
    </tbody>
</table>