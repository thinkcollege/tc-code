<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: form_docedit.tpl.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

/**
 * Default DOCman Theme
 *
 * Creator:  The DOCman Development Team
 * Website:  http://www.joomlatools.org/
 * Email:    support@joomlatools.org
 * Revision: 1.4
 * Date:     February 2007
 **/

/*
* Display the move document form (required)
*
* This template is called when u user preform a move operation on a document.
*
* General variables  :
*	$this->theme->path (string) : template path
*	$this->theme->name (string) : template name
*	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon
*   $this->theme->png  (boolean): browser png transparency support
*
*/
?>
onunload = WarnUser;
var folderimages = new Array;

function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	form.goodexit.value=1
	try {
		form.onsubmit();
	}
	catch(e){}

	msg = '';
	if (form.dmname.value == '') {
  		msg += '\n <?php echo _DML_ENTRY_NAME;?>';
	} if (form.dmdate_published.value == '') {
     	msg += '\n <?php echo _DML_ENTRY_DATE;?>';
	} if (form.dmfilename.value == '') {
     	msg += '\n<?php echo _DML_ENTRY_DOC;?>' ;
	} if (form.catid.value == '0') {
     	msg += '\n <?php echo _DML_ENTRY_CAT;?>' ;
	} if (form.dmowner.value == '<?php echo _DM_PERMIT_NOOWNER;?>' || form.dmowner.value == '' ) {
     	msg += '\n <?php echo _DML_ENTRY_OWNER; ?>' ;
	} if (form.dmmantainedby.value == '<?php echo _DM_PERMIT_NOOWNER;?>' || form.dmmantainedby.value == '' ) {
     	msg += '\n <?php echo _DML_ENTRY_MAINT; ?>' ;
	} if( form.document_url ){
		if( form.document_url.value != '' ){
			if( form.dmfilename.value != '<?php echo _DM_DOCUMENT_LINK;?>'){
				if( form.dmfilename.value != '' ){
					msg += "\n<?php echo _DML_ENTRY_DOCLINK;?>";
				}
			}
		}
	}

	if ( msg != '' ){
		msghdr = '<?php echo _DML_ENTRY_ERRORS;?>';
		msghdr += '\n=================================';
		alert( msghdr + msg + '\n' );
	} else {
		/* for static content */
		<?php getEditorContents('editor1', 'dmdescription') ; ?>
		submitform(pressbutton);
	}
}

function setgood() {
	document.adminForm.goodexit.value=1;
}

function WarnUser() {

}
<?php die(); ?>