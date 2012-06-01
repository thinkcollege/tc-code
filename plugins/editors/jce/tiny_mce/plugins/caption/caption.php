<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$version = "1.5.2";

require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'editor.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'plugin.php' );
require_once( JCE_LIBRARIES .DS. 'classes' .DS. 'utils.php' );

require_once( dirname( __FILE__ ) .DS. 'classes' .DS. 'caption.php' );

$caption =& Caption::getInstance();

$caption->checkPlugin() or die( 'Restricted access' );
// Load Plugin Parameters
$params	= $caption->getPluginParams();

$caption->_debug = false;
$version .= $caption->_debug ? ' - debug' : '';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $caption->getLanguageTag();?>" lang="<?php echo $caption->getLanguageTag();?>" dir="<?php echo $caption->getLanguageDir();?>" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo JText::_('PLUGIN TITLE');?> : <?php echo $version;?></title>
<?php
$caption->printScripts();
$caption->printCss();
?>
	<script type="text/javascript">
		function initCaption(){
			return new Plugin('caption', {
				lang: '<?php echo $caption->getLanguage(); ?>',
				alerts: <?php echo $caption->getAlerts();?>,
				params: {
					'defaults': {
						'text_align'	: '<?php echo $params->get('caption_text_align', 'center');?>',
						'text_padding'	: '<?php echo $params->get('caption_text_padding', '');?>',
						'text_margin'	: '<?php echo $params->get('caption_text_margin', '');?>',
						'text_color'	: '<?php echo $params->get('caption_text_color', '');?>',
						'margin'		: '<?php echo $params->get('caption_margin', '');?>',
						'padding'		: '<?php echo $params->get('caption_padding', '');?>',	
						'border_width'	: '<?php echo $params->get('caption_border_width', '1');?>',	
						'border_style'	: '<?php echo $params->get('caption_border_style', 'solid');?>',
						'border_color'	: '<?php echo $params->get('caption_border_color', '#000000');?>',
						'bgcolor'		: '<?php echo $params->get('caption_bgcolor', '');?>'
					}
				}
			});
		}
	</script>
    <?php echo $caption->printHead();?>
</head>
<body lang="<?php echo $caption->getLanguage();?>" id="advlink" style="display: none">
    <form onSubmit="insertAction();return false;" action="#">
        <div class="tabs">
			<ul>
				<li id="text_tab" class="current"><span><a href="javascript:mcTabs.displayTab('text_tab','text_panel');" onMouseDown="return false;"><?php echo JText::_('TEXT');?></a></span></li>
				<li id="container_tab"><span><a href="javascript:mcTabs.displayTab('container_tab','container_panel');" onMouseDown="return false;"><?php echo JText::_('CAPTION CONTAINER');?></a></span></li>
			</ul>
		</div>
        <div class="panel_wrapper">
            <div id="text_panel" class="panel current">
                 <table cellpadding="2" cellspacing="0" border="0">
                    <tr>
                        <td style="width:20%"><label for="text"><?php echo JText::_('TEXT');?></label></td>
                        <td colspan="2"><input type="text" id="text" onKeyUp="CaptionDialog.updateText(this.value);" /></td>
                    </tr>
                    <tr>
                        <td style="width:20%"><label for="text_align" class="hastip" title="<?php echo JText::_('CAPTION TEXT ALIGN DESC');?>"><?php echo JText::_('CAPTION ALIGN');?></label></td>
                        <td colspan="2">
                        <select id="text_align" onChange="CaptionDialog.updateCaption();" >
                            <option value=""><?php echo JText::_('NOT SET');?></option>
                            <option value="left"><?php echo JText::_('Left');?></option>
                            <option value="center"><?php echo JText::_('Center');?></option>
                            <option value="right"><?php echo JText::_('Right');?></option>
                            <option value="justified"><?php echo JText::_('Justified');?></option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;"><label for="text_color" class="hastip" title="<?php echo JText::_('CAPTION TEXT COLOR DESC');?>"><?php echo JText::_('COLOR');?></label></td>
                        <td style="width:50%;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td><input id="text_color" type="text" value="" size="9" onChange="TinyMCE_Utils.updateColor(this);CaptionDialog.updateCaption();" /></td>
                                    <td id="text_color_pickcontainer">&nbsp;</td>
                                </tr>
                           </table>	
                        <td>
                            <table>
                                <tr>
                                    <td><label for="text_bgcolor" class="hastip" title="<?php echo JText::_('CAPTION TEXT BGCOLOR DESC');?>"><?php echo JText::_('BACKGROUND');?></label></td>
                                    <td><input id="text_bgcolor" type="text" value="" size="9" onChange="TinyMCE_Utils.updateColor(this);CaptionDialog.updateCaption();" /></td>
                                    <td id="text_bgcolor_pickcontainer">&nbsp;</td>	
                                </tr>
                            </table> 
                        </td>
                        
                    </tr>
                    <tr>
                        <td colspan="3">
                        <fieldset>
                            <legend><label class="hastip" title="<?php echo JText::_('CAPTION TEXT PADDING DESC');?>"><?php echo JText::_('CAPTION PADDING');?></label></legend>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                    <label for="text_padding_top"><?php echo JText::_('Top');?></label><input type="text" id="text_padding_top" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_padding');" />
                                    <label for="text_padding_right"><?php echo JText::_('Right');?></label><input type="text" id="text_padding_right" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_padding');" />
                                    <label for="text_padding_bottom"><?php echo JText::_('Bottom');?></label><input type="text" id="text_padding_bottom" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_padding');" />
                                    <label for="text_padding_left"><?php echo JText::_('Left');?></label><input type="text" id="text_padding_left" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_padding');" />
                                    <input type="checkbox" class="checkbox" id="text_padding_check" onClick="CaptionDialog.setSpacing('text_padding');"><label><?php echo JText::_('EQUAL VALUES');?></label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        <fieldset>
                            <legend><label class="hastip" title="<?php echo JText::_('CAPTION TEXT MARGIN DESC');?>"><?php echo JText::_('MARGIN');?></label></legend>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                    <label for="text_margin_top"><?php echo JText::_('Top');?></label><input type="text" id="text_margin_top" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_margin');" />
                                    <label for="text_margin_right"><?php echo JText::_('Right');?></label><input type="text" id="text_margin_right" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_margin');" />
                                    <label for="text_margin_bottom"><?php echo JText::_('Bottom');?></label><input type="text" id="text_margin_bottom" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_margin');" />
                                    <label for="text_margin_left"><?php echo JText::_('Left');?></label><input type="text" id="text_margin_left" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('text_margin');" />
                                    <input type="checkbox" class="checkbox" id="text_margin_check" onClick="CaptionDialog.setSpacing('text_margin');"><label><?php echo JText::_('EQUAL VALUES');?></label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <fieldset>
                                <legend><label><?php echo JText::_('CAPTION CLASSES');?></label></legend>
                                <table style="width:100%;">
                                    <tr>
                                        <td style="width:20%;"><label for="classlist" class="hastip" title="<?php echo JText::_('TEMPLATE CLASSES DESC');?>"><?php echo JText::_('TEMPLATE CLASSES');?></label></td>
                                        <td colspan="3">
                                            <select id="text_classlist" onChange="CaptionDialog.setClasses('text_classes', this.value);">
                                                <option value=""><?php echo JText::_('NOT SET');?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                         <td style="width:20%;"><label for="title" class="hastip" title="<?php echo JText::_('OTHER CLASSES DESC');?>"><?php echo JText::_('OTHER CLASSES');?></label></td>
                                         <td colspan="3"><input id="text_classes" type="text" value="" /></td>
                                    </tr>
                                </table>
                            </fieldset>    
                        </td>    
                    </tr>
                </table>
        	</div>
            <div id="container_panel" class="panel">
                <div>
                     <table cellpadding="2" cellspacing="0" border="0">
					 	<tr>
                            <td style="width:20%"><label for="align" class="hastip" title="<?php echo JText::_('CAPTION ALIGN DESC');?>"><?php echo JText::_('CAPTION ALIGN');?></label></td>
                            <td style="width:50%;">
                            <select id="align" onChange="CaptionDialog.updateCaption();" >
                                <option value=""><?php echo JText::_('Align Default');?></option>
                                <option value="top"><?php echo JText::_('Align Top');?></option>
                                <option value="middle"><?php echo JText::_('Align Middle');?></option>
                                <option value="bottom"><?php echo JText::_('Align Bottom');?></option>
                                <option value="left"><?php echo JText::_('Align Left');?></option>
                                <option value="right"><?php echo JText::_('Align Right');?></option>
                            </select>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td><label for="bgcolor" class="hastip" title="<?php echo JText::_('CAPTION BGCOLOR DESC');?>"><?php echo JText::_('BACKGROUND');?></label></td>
                                        <td><input id="bgcolor" type="text" value="" size="9" onChange="TinyMCE_Utils.updateColor(this);CaptionDialog.updateCaption();" /></td>
                                        <td id="bgcolor_pickcontainer">&nbsp;</td>	
                                    </tr>
                                </table>
                            </td>
                        </tr>	
                        <tr>
                            <td colspan="4">
                            <fieldset>
                                <legend><label class="hastip" title="<?php echo JText::_('CAPTION PADDING DESC');?>"><?php echo JText::_('CAPTION PADDING');?></label></legend>
                                <table style="width:100%;">
                                    <tr>
                                        <td>
                                        <label for="padding_top"><?php echo JText::_('Top');?></label><input type="text" id="padding_top" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('padding');" />
                                        <label for="padding_right"><?php echo JText::_('Right');?></label><input type="text" id="padding_right" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('padding');" />
                                        <label for="padding_bottom"><?php echo JText::_('Bottom');?></label><input type="text" id="padding_bottom" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('padding');" />
                                        <label for="padding_left"><?php echo JText::_('Left');?></label><input type="text" id="padding_left" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('padding');" />
                                        <input type="checkbox" class="checkbox" id="padding_check" onClick="CaptionDialog.setSpacing('padding');"><label><?php echo JText::_('Equal Values');?></label>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>    
                            </td>
                        </tr>                       
                        <tr>
                            <td colspan="4">
                                <fieldset>
                                    <legend><label class="hastip" title="<?php echo JText::_('CAPTION MARGIN DESC');?>"><?php echo JText::_('MARGIN');?></label></legend>
                                    <table style="width:100%;">
                                        <tr>
                                            <td>
                                            <label for="margin_top"><?php echo JText::_('Top');?></label><input type="text" id="margin_top" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('margin');" />
                                            <label for="margin_right"><?php echo JText::_('Right');?></label><input type="text" id="margin_right" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('margin');" />
                                            <label for="margin_bottom"><?php echo JText::_('Bottom');?></label><input type="text" id="margin_bottom" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('margin');" />
                                            <label for="margin_left"><?php echo JText::_('Left');?></label><input type="text" id="margin_left" value="" size="3" maxlength="3" onChange="CaptionDialog.setSpacing('margin');" />
                                            <input type="checkbox" class="checkbox" id="margin_check" onClick="CaptionDialog.setSpacing('margin');"><label><?php echo JText::_('EQUAL VALUES');?></label>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>    
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            <fieldset>
                                <legend><input type="checkbox" class="radio" id="border" onClick="CaptionDialog.setBorder();"/><?php echo JText::_('BORDER');?></legend>
                                <table style="width:100%;">
                                    <tr>
                                        <td>
                                        <label for="border_width" class="hastip" title="<?php echo JText::_('CAPTION BORDER STYLE DESC');?>"><?php echo JText::_('Width');?></label>
                                        <select id="border_width" onChange="CaptionDialog.updateCaption();" class="mceEditableSelect">
                                            <option value=""><?php echo JText::_('NOT SET');?></option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="thin"><?php echo JText::_('BORDER THIN');?></option>
                                            <option value="medium"><?php echo JText::_('BORDER MEDIUM');?></option>
                                            <option value="thick"><?php echo JText::_('BORDER THICK');?></option>
                                        </select>
                                        </td>
                                        <td>
                                        <label for="border_style" class="hastip" title="<?php echo JText::_('CAPTION BORDER STYLE DESC');?>"><?php echo JText::_('Style');?></label>
                                        <select id="border_style" onChange="CaptionDialog.updateCaption();">
                                            <option value=""><?php echo JText::_('NOT SET');?></option>
                                            <option value="none"><?php echo JText::_('BORDER NONE');?></option>
                                            <option value="solid"><?php echo JText::_('BORDER SOLID');?></option>
                                            <option value="dashed"><?php echo JText::_('BORDER DASHED');?></option>
                                            <option value="dotted"><?php echo JText::_('BORDER DOTTED');?></option>
                                            <option value="double"><?php echo JText::_('BORDER DOUBLE');?></option>
                                            <option value="groove"><?php echo JText::_('BORDER GROOVE');?></option>
                                            <option value="inset"><?php echo JText::_('BORDER INSET');?></option>
                                            <option value="outset"><?php echo JText::_('BORDER OUTSET');?></option>
                                            <option value="ridge"><?php echo JText::_('BORDER RIDGE');?></option>
                                        </select>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td><label for="border_color" class="hastip" title="<?php echo JText::_('CAPTION BORDER COLOR DESC');?>"><?php echo JText::_('Color');?></label></td>
                                                    <td><input id="border_color" type="text" value="" size="9" onChange="TinyMCE_Utils.updateColor(this);CaptionDialog.updateCaption();" /></td>
                                                    <td id="border_color_pickcontainer">&nbsp;</td>	
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <fieldset>
                                    <legend><label><?php echo JText::_('CAPTION CLASSES');?></label></legend>
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="width:20%;"><label for="classlist" class="hastip" title="<?php echo JText::_('TEMPLATE CLASSES DESC');?>"><?php echo JText::_('TEMPLATE CLASSES');?></label></td>
                                            <td colspan="3">
                                                <select id="classlist" onChange="CaptionDialog.setClasses('classes', this.value);">
                                                    <option value=""><?php echo JText::_('NOT SET');?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                             <td style="width:20%;"><label for="title" class="hastip" title="<?php echo JText::_('OTHER CLASSES DESC');?>"><?php echo JText::_('OTHER CLASSES');?></label></td>
                                             <td colspan="3"><input id="classes" type="text" value="" /></td>
                                        </tr>
                                    </table>
                                </fieldset>    
                            </td>    
                        </tr>
                    </table>
                </div>   
            </div>
    	</div>
        <div id="preview">
            <fieldset>
                <legend><?php echo JText::_('PREVIEW');?></legend>                   
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="vertical-align:top;">
                            <div style="overflow:auto;height:140px;">
                                <div id="caption">
                                    <img id="caption_image" src="<?php echo $caption->image('sample.jpg', 'plugins');?>" width="150" height="112" border="0" alt="Preview" />
                                    <div id="caption_text"></div>
                                </div>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                            </div>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div class="mceActionPanel">
            <div style="float: left">
            	<input type="button" id="insert" value="<?php echo JText::_('Insert');?>" onClick="CaptionDialog.insert();" />
            </div>
            <div style="float: right">
            	<input type="button" class="button" id="help" value="<?php echo JText::_('Help');?>" onClick="CaptionDialog.openHelp();" />
            	<input type="button" id="cancel" value="<?php echo JText::_('Cancel');?>" onClick="tinyMCEPopup.close();" />
            </div>
        </div>
    </form>
</body>
</html>
