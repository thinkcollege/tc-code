<?php
/**
* @version $Id: admin.joominvite.html.php 
* @package JoomInvite
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_joominvite' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}
global $mosConfig_lang,$mosConfig_live_site;
	// set up language
	$lang = dirname( __FILE__) . '/lang/admin.' . strtolower($mosConfig_lang) . '.php';
	$help_with_languages = false;
	if(!file_exists($lang)){
	    $help_with_languages=true;
	    $lang = dirname( __FILE__) . '/lang/admin.english.php';
	}
	require_once($lang);

class HTML_joominvite{
	function MailForm( $option ){
		mosCommonHTML::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			// do field validation
			if (form.subject.value == ""){
				alert( "<?php echo _SEND_AFTER_DESC?>" );
			} 
			else {
				 <?php getEditorContents( 'editor1', 'msg' ); ?>
				submitform( pressbutton );
			}
		}
		</script>
		<table><tr>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=show">Main Menu</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=send">Mass Mail</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=config">Configuration</a></td>
		</tr>
		<tr><td>
		Like JoomInvite? Please rate it at <a href="http://extensions.joomla.org/component/option,com_mtree/task,viewlink/link_id,5301/Itemid,35/" target="_new">Joomla Extensions</a>
		</td></tr></table>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminlist">
		<tr>
		<td><?php echo _SUBJECT?></td>
		<td><input type="text" size="100" maxlength="150" name="subject" value=""></td>
		</tr><tr>
		<td><?php echo _MSG_BODY?></td>
		<td><?php editorArea( 'editor1','', 'msg','100%','350','80','30' ); ?></td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
	
	function configWindow( $option, $conf){
		mosCommonHTML::loadOverlib();
		?>
			<script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
			var form = document.adminForm;

			// do field validation
			if (form.send_after.value == ""){
				alert( "<?php echo _SEND_AFTER_DESC?>" );
			} 
			else {
				 <?php getEditorContents( 'editor1', 'msg' ); ?>
				submitform( pressbutton );
			}
		}
	
		</script>
		<table><tr>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=show">Main Menu</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=send">Mass Mail</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=config">Configuration</a></td>
		</tr></table>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _JOOMINVITE_CONFIG ?>
			</th>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
		<td width="20%"><?php echo _SEND_AUTO_INVITES?></td>
		<td width="60%"><select name="auto_invites"><option value="1" <?php echo ($conf->auto_invites ? "selected" : ""); ?>>Yes</option><option value="0" <?php echo ($conf->auto_invites ? "" : "selected"); ?>>No</option></select></td>
		<td width="20%"><?php echo mosToolTip( _HELP_SEND_AUTO_INVITES, _SEND_AUTO_INVITES ); ?></td>
		</tr><tr>
		<td width="20%"><?php echo _SEND_AFTER?></td>
		<td width="60%"><input type="text" name="send_after" value="<?php echo $conf->send_after?>" /></td>
		<td width="20%"><?php echo mosToolTip( _HELP_SEND_AFTER, _SEND_AFTER ); ?></td>
		</tr><tr>
		<td width="20%"><?php echo _CUSTOM_SUBJECT?></td>
		<td width="60%"><input type="text" size="100" maxlength="150" name="custom_subject" value="<?php echo $conf->custom_subject?>" /></td>
		<td width="20%"><?php echo mosToolTip( _HELP_CUSTOM_SUBJECT, _CUSTOM_SUBJECT ); ?></td>
		</tr><tr>
		<td width="20%"><?php echo _EMAIL_FROM_USER?></td>
		<td width="60%"><select name="email_from_user"><option value="1" <?php echo ($conf->email_from_user ? "selected" : ""); ?>>Yes</option><option value="0" <?php echo ($conf->email_from_user ? "" : "selected"); ?>>No</option></select></td>
		<td width="20%"><?php echo mosToolTip( _HELP_EMAIL_FROM_USER, _EMAIL_FROM_USER ); ?></td>
		</tr><tr>
		<td width="20%"><?php echo _BCC_ADMIN?></td>
		<td width="60%"><select name="bcc_admin"><option value="1" <?php echo ($conf->bcc_admin ? "selected" : ""); ?>>Yes</option><option value="0" <?php echo ($conf->bcc_admin ? "" : "selected"); ?>>No</option></select></td>
		<td width="20%"><?php echo mosToolTip( _HELP_BCC_ADMIN, _BCC_ADMIN ); ?></td>
		</tr>
		<tr>
		<td width="20%"><?php echo _USE_CUSTOM_MSG?></td>  
		<td width="60%"><select name="use_custom_msg"><option value="1" <?php echo ($conf->use_custom_msg ? "selected" : ""); ?>>Yes</option><option value="0" <?php echo ($conf->use_custom_msg ? "" : "selected"); ?>>No</option></select></td>
		<td width="20%"><?php echo mosToolTip( _HELP_USE_CUSTOM_MSG, _USE_CUSTOM_MSG ); ?></td>
		</tr><tr>
		<td><?php echo _MSG?></td>
		<td>
		<?php $conf->msg = stripslashes($conf->msg); ?>
		<?php editorArea( 'editor1',  "$conf->msg" , 'msg', '100%;', '350', '75', '20' ) ; ?>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value=<?php echo $conf->id ?> />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
	
	function front( $option, $rows, $search, $pageNav ){
		mosCommonHTML::loadOverlib();
		?>
		
		<table><tr>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=show">Main Menu</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=send">Mass Mail</a></td>
		<td><a href="index2.php?option=<?php echo $option;?>&amp;task=config">Configuration</a></td>
		</tr></table>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _JOOMINVITE_MAIN ?>
			</th>
			<td>
			Filter:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title">
			Name
			</th>
			<th width="100" align="left">
			Email
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php echo $row->invitee_name; ?>
				</td>
				<td>
				<?php echo $row->invitee_email; ?>
				</td>	
				</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
		</form>
		<?php
	}
}
?>