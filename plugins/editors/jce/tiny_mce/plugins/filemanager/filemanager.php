<?php
/**
* @version $Id: manager.php 2006-07-25 09:23:43Z Ryan Demmer $
* @package JCE
* @copyright Copyright (C) 2006 Ryan Demmer. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

$version = "1.5.2";

require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'editor.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'plugin.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'utils.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'manager.php' );

require_once( dirname( __FILE__ ) .DS. 'classes' .DS. 'filemanager.php' );

$manager =& FileManager::getInstance();

$manager->setXHR( array( &$manager, 'getProperties' ) );

$manager->script( array( 'sortables' ) );
$manager->script( array( 'filemanager' ), 'plugins' );
$manager->css( array( 'filemanager' ), 'plugins' );
// Load extensions if any
$manager->loadExtensions();
// Process requests
$manager->processXHR();

$manager->_debug = false;
$session = &JFactory::getSession();
$version .= $manager->_debug ? ' - debug' : '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $manager->getLanguageTag();?>" lang="<?php echo $manager->getLanguageTag();?>" dir="<?php echo $manager->getLanguageDir();?>" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo JText::_('PLUGIN TITLE').' : '.$version;?></title>
<?php
$manager->printScripts();
$manager->printCss();	
?>
	<link href="<?php echo $manager->getSkin();?>/window.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		function initManager(src){
			return new FileManager(src, {
				// Global parameters
				actions: <?php echo $manager->getActions();?>,
				buttons: <?php echo $manager->getButtons();?>,
				lang: '<?php echo $manager->getLanguage();?>',
				alerts: <?php echo $manager->getAlerts();?>,
				// Uploader options
				upload: {
					method: '<?php echo $manager->getEditorParam('editor_upload_method', 'flash'); ?>',
					conflict: '<?php echo $manager->getSharedParam('upload_conflict', 'overwrite|unique'); ?>',
					size: <?php echo $manager->getSharedParam('max_size', '1024'); ?>,
					types: <?php echo $manager->mapUploadFileTypes(); ?>
				},
				tree: <?php echo $manager->getEditorParam('editor_folder_tree', '1'); ?>,
				// Plugin parameters
				params: {
					'base': '<?php echo $manager->getBase(); ?>',
					'icon_map': '<?php echo $manager->getIconMap(); ?>',
					'icon_path': '<?php echo $manager->getIconPath(); ?>',
					'icon_prefix': '<?php echo $manager->getIconPrefix(); ?>',
					'viewable': '<?php echo $manager->getViewable(); ?>'
				} 
			});	
		}
	</script>
    <?php echo $manager->printHead();?>
</head>
<body lang="<?php echo $manager->getLanguage(); ?>" style="display: none;">
    <div class="panel_wrapper">
            <fieldset>
                <legend><?php echo JText::_('Link');?></legend>
                <table class="properties" border="0">
                    <tr>
                        <td class="column1"><label for="href" class="hastip" title="<?php echo JText::_('URL DESC');?>"><?php echo JText::_('URL');?></label></td>
                        <td colspan="3"><input type="text" id="href" value="" class="required" /></td>
                    </tr>
                    <tr>
                        <td><label for="targetlist" class="hastip" title="<?php echo JText::_('TARGET DESC');?>"><?php echo JText::_('TARGET');?></label></td>
                        <td colspan="3"><select id="targetlist" name="targetlist">
                        <option value=""><?php echo JText::_('NOT SET');?></option>
                        <option value="_self"><?php echo JText::_('TARGET SELF');?></option>
                        <option value="_blank"><?php echo JText::_('TARGET BLANK');?></option>
                        <option value="_parent"><?php echo JText::_('TARGET PARENT');?></option>
                        <option value="_top"><?php echo JText::_('TARGET TOP');?></option>								
                        </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend><?php echo JText::_('OPTIONS');?></legend>
                <div id="options-enabled">
                    <table class="properties" border="0">
                        <tr>
                            <td><label for="title" class="hastip" title="<?php echo JText::_('TEXT DESC');?>"><?php echo JText::_('TEXT');?></label></td>
                            <td colspan="3"><input id="title" type="text" value="" class="required" /></td>
                        </tr>
                        <tr>
                            <td><label class="hastip" title="<?php echo JText::_('LAYOUT DESC');?>"><?php echo JText::_('LAYOUT');?></label></td>
                            <td colspan="3"><ul id="sortGroup"></ul></td>
                        </tr>
                        <tr>
                            <td><label for="date-class" class="hastip" title="<?php echo JText::_('DATE CLASS DESC');?>"><?php echo JText::_('DATE CLASS');?></label></td>
                            <td>
                            <select id="date-class" class="mceEditableSelect">
                            	<option value=""><?php echo JText::_('NOT SET');?></option>
                            </select>
                            </td>
                            <td><label for="size-class" class="hastip" title="<?php echo JText::_('SIZE CLASS DESC');?>"><?php echo JText::_('SIZE CLASS');?></label></td>
                            <td>
                            <select id="size-class" class="mceEditableSelect">
                            	<option value=""><?php echo JText::_('NOT SET');?></option>
                            </select>
                            </td>
                        </tr>
                    </table>
            	</div>
                <div id="options-disabled"><?php echo JText::_('OPTIONS DISABLED');?></div>
            </fieldset>
    </div>
	<?php $manager->loadBrowser();?>
	<div class="mceActionPanel">
		<div style="float: right">
    		<input type="button" class="button "id="refresh" value="<?php echo JText::_('Refresh');?>" />
			<input type="button" id="insert" value="<?php echo JText::_('Insert');?>" onClick="FileManagerDialog.insert();" />
			<input type="button" id="cancel" value="<?php echo JText::_('Cancel');?>" onClick="tinyMCEPopup.close();" />
		</div>
	</div>
</body> 
</html> 
