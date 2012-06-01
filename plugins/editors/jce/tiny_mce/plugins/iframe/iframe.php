<?php
/**
* @version $Id: iframe.php 2008-11-10 Ryan Demmer $
* @package JCE
* @copyright Copyright (C) 2006-2007 Ryan Demmer. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

$version = "1.5.0";

require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'editor.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'plugin.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'utils.php' );

require_once( dirname( __FILE__ ) .DS. 'classes' .DS. 'iframe.php' );

$iframe =& IFrame::getInstance();

$iframe->checkPlugin() or die( 'Restricted access' );
// Load Plugin Parameters
$params	= $iframe->getPluginParams();

$iframe->_debug = false;
$version .= $iframe->_debug ? ' - debug' : '';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $iframe->getLanguageTag();?>" lang="<?php echo $iframe->getLanguageTag();?>" dir="<?php echo $iframe->getLanguageDir();?>" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo JText::_('PLUGIN TITLE');?> : <?php echo $version;?></title>
<?php
$iframe->printScripts();
$iframe->printCss();
?>
	<script type="text/javascript">
		function initIFrame(){
			return new IFrame({
				lang: '<?php echo $iframe->getLanguage(); ?>',
				alerts: <?php echo $iframe->getAlerts();?>,
				params: {
					defaults : {	
						'width': 		'<?php echo $params->get( 'iframe_width', '100' );?>',
						'height': 		'<?php echo $params->get( 'iframe_height', '100' );?>',
						'width_type':	'<?php echo $params->get( 'iframe_width_type', 'pct' );?>',
						'height_type':	'<?php echo $params->get( 'iframe_height_type', 'pct' );?>',
						'align': 		'<?php echo $params->get( 'iframe_align', 'default' );?>',
						'frameborder': 	'<?php echo $params->get( 'iframe_frameborder', '0' );?>',
						'scrolling': 	'<?php echo $params->get( 'iframe_scrolling', 'auto' );?>',
						'margin_top': 	'<?php echo $params->get( 'iframe_margin_top', 'default' );?>',
						'margin_right': '<?php echo $params->get( 'iframe_margin_right', 'default' );?>',
						'margin_bottom':'<?php echo $params->get( 'iframe_margin_bottom', 'default' );?>',
						'margin_left': 	'<?php echo $params->get( 'iframe_margin_left', 'default' );?>',
						'marginwidth': 	'<?php echo $params->get( 'iframe_marginwidth', 'default' );?>',
						'marginheight': '<?php echo $params->get( 'iframe_marginheight', 'default' );?>'
					}
				}
			});
		}
	</script>
    <?php echo $iframe->printHead();?>
</head>
<body style="display: none">
    <form onSubmit="IFrameDialog.insert();return false;" action="#">
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onMouseDown="return false;"><?php echo JText::_('Properties');?></a></span></li>
				<li id="advanced_tab"><span><a href="javascript:mcTabs.displayTab('advanced_tab','advanced_panel');" onMouseDown="return false;"><?php echo JText::_('Advanced');?></a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<table>
                	<tr>
                    	<td style="vertical-align:top;width:75%;">
                        <fieldset>
                            <legend><?php echo JText::_('Properties');?></legend>
                            <table cellpadding="3" cellspacing="0" border="0" style="height:175px;">
                                    <tr>
                                        <td><label for="src" class="hastip" title="<?php echo JText::_('URL DESC');?>"><?php echo JText::_('URL');?></label></td>
                                        <td colspan="3"><input id="src" type="text" value="" class="required" /></td>
                                    </tr>
                                    <tr>
                                        <td nowrap="nowrap"><label class="hastip" title="<?php echo JText::_('DIMENSIONS DESC');?>"><?php echo JText::_('DIMENSIONS');?></label></td>
                                        <td nowrap="nowrap" colspan="3">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <input type="text" id="width" value="" onChange="IFrameDialog.setDimensions('width', 'height');" />&nbsp;<select id="width_type" onChange="IFrameDialog.setDimensionType('width', 'height');"><option value="px">px</option><option value="pct">%</option></select> x 
                                                    <input type="text" id="height" value="" onChange="IFrameDialog.setDimensions('height', 'width');" />&nbsp;<select id="height_type" onChange="IFrameDialog.setDimensionType('height', 'width');"><option value="px">px</option><option value="pct">%</option></select>
                                                 </td>
                                                <input type="hidden" id="tmp_width" value=""  />
                                                <input type="hidden" id="tmp_height" value="" />
                                                <td>&nbsp;&nbsp;<input id="constrain" type="checkbox" class="checkbox" /></td>
                                                <td><label id="constrainlabel" for="constrain"><?php echo JText::_('Proportional');?></label></td>
                                                <td><div id="dim_loader"></div></td>
                                            </tr>
                                        </table>
                                    </tr>
                                    <tr>
                                        <td><label for="margin" class="hastip" title="<?php echo JText::_('MARGIN DESC');?>"><?php echo JText::_('MARGIN');?></label></td>
                                        <td colspan="3">
                                            <label for="margin_top"><?php echo JText::_('Top');?></label><input type="text" id="margin_top" value="" size="3" maxlength="3" onChange="IFrameDialog.setMargins();" />
                                            <label for="margin_right"><?php echo JText::_('Right');?></label><input type="text" id="margin_right" value="" size="3" maxlength="3" onChange="IFrameDialog.setMargins();" />
                                            <label for="margin_bottom"><?php echo JText::_('Bottom');?></label><input type="text" id="margin_bottom" value="" size="3" maxlength="3" onChange="IFrameDialog.setMargins();" />
                                            <label for="margin_left"><?php echo JText::_('Left');?></label><input type="text" id="margin_left" value="" size="3" maxlength="3" onChange="IFrameDialog.setMargins();" />
                                            <input type="checkbox" class="checkbox" id="margin_check" onClick="IFrameDialog.setMargins();"><label><?php echo JText::_('Equal Values');?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="align" class="hastip" title="<?php echo JText::_('ALIGN DESC');?>"><?php echo JText::_('ALIGN');?></label></td>
                                        <td>
                                            <select id="align" onChange="IFrameDialog.updateStyles();">
                                                <option value=""><?php echo JText::_('Align Default');?></option>
                                                <option value="top"><?php echo JText::_('Align Top');?></option>
                                                <option value="middle"><?php echo JText::_('Align Middle');?></option>
                                                <option value="bottom"><?php echo JText::_('Align Bottom');?></option>
                                                <option value="left"><?php echo JText::_('Align Left');?></option>
                                                <option value="right"><?php echo JText::_('Align Right');?></option>
                                            </select>
                                        </td>
                                        <td><label for="style" class="hastip" title="<?php echo JText::_('SCROLLING DESC');?>"><?php echo JText::_('SCROLLING');?></td>
                                        <td>
                                            <select id="scrolling">
                                                <option value="yes"><?php echo JText::_('Yes');?></option>
                                                <option value="no"><?php echo JText::_('No');?></option>
                                                <option value="auto"><?php echo JText::_('Auto');?></option>
                                            </select>
                                        </td>
                                   </tr>
                                    <tr>
                                        <td nowrap="nowrap"><label for="style" class="hastip" title="<?php echo JText::_('MARGINWIDTH DESC');?>"><?php echo JText::_('MARGINWIDTH');?></label></td>
                                        <td nowrap="nowrap">
                                            <input id="marginwidth" type="text"  value="" style="width: 50px" />
                                        </td>
                                        <td nowrap="nowrap"><label for="style" class="hastip" title="<?php echo JText::_('MARGINHEIGHT DESC');?>"><?php echo JText::_('MARGINHEIGHT');?></label></td>
                                        <td nowrap="nowrap">
                                            <input id="marginheight" type="text"  value="" style="width: 50px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="style" class="hastip" title="<?php echo JText::_('FRAMEBORDER DESC');?>"><?php echo JText::_('FRAMEBORDER');?></td>
                                        <td colspan="3">
                                            <select id="frameborder">
                                                <option value="1"><?php echo JText::_('Yes');?></option>
                                                <option value="0"><?php echo JText::_('No');?></option>
                                            </select>
                                        </td>
                                    </tr>
                            </table>
                        </fieldset>
                		</td>
                        <td style="vertical-align:top;">
                            <fieldset>
                                <legend><?php echo JText::_('Preview');?></legend>
                                <table cellpadding="3" cellspacing="0" border="0" style="height:175px;">
                                    <tr>
                                        <td style="vertical-align:top;">
                                            <div class="preview">
                                                <img id="sample" src="<?php echo $iframe->image('sample.jpg', 'plugins');?>" alt="sample" />
                                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
            		</tr>
           		</table>
			</div>
			<div id="advanced_panel" class="panel">
			<fieldset>
					<legend><?php echo JText::_('Advanced');?></legend>
					<table cellpadding="3" cellspacing="0" border="0" style="height:175px;">
						<tr>
                            <td><label for="style" class="hastip" title="<?php echo JText::_('STYLE DESC');?>"><?php echo JText::_('STYLE');?></label></td>
                            <td colspan="2"><input id="style" type="text" value="" onChange="IFrameDialog.setStyles();" /></td>
                        </tr>
                        <tr>
                            <td><label for="classlist" class="hastip" title="<?php echo JText::_('CLASS LIST DESC');?>"><?php echo JText::_('CLASS LIST');?></label></td>
                            <td colspan="2">
                                <select id="classlist" onChange="IFrameDialog.setClasses(this.value);">
                                    <option value=""><?php echo JText::_('NOT SET');?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                             <td><label for="title" class="hastip" title="<?php echo JText::_('CLASSES DESC');?>"><?php echo JText::_('CLASSES');?></label></td>
                             <td colspan="2"><input id="classes" type="text" value="" /></td>
                        </tr>
                        <tr>
                             <td><label for="title" class="hastip" title="<?php echo JText::_('TITLE DESC');?>"><?php echo JText::_('TITLE');?></label></td>
                             <td colspan="2"><input id="title" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap"><label for="name" class="hastip" title="<?php echo JText::_('NAME DESC');?>"><?php echo JText::_('NAME');?></label></td>
                            <td colspan="3"><input id="name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td><label for="id" class="hastip" title="<?php echo JText::_('ID DESC');?>"><?php echo JText::_('ID');?></label></td>
                            <td colspan="2"><input id="id" type="text" value="" /></td>
                        </tr>    
                        <tr>
                            <td><label for="longdesc" class="hastip" title="<?php echo JText::_('LONGDESC DESC');?>"><?php echo JText::_('LONGDESC');?></label></td>
                            <td><input id="longdesc" type="text" value="" /></td>
                            <td id="longdesccontainer">&nbsp;</td>
                        </tr>
					</table>
			</fieldset>
			</div>
		</div>
		<div class="mceActionPanel">
			<div style="float: left">
				<input type="button" id="insert" value="<?php echo JText::_('Insert');?>" onClick="IFrameDialog.insert();" />
			</div>
			<div style="float: right">
				<input type="button" id="cancel" value="<?php echo JText::_('Cancel');?>" onClick="tinyMCEPopup.close();" />
			</div>
		</div>
	</form>
</body>
</html>
