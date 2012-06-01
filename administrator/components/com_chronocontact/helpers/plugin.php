<?php
/**
 * @version		$Id: form.php
 *
 * Helper file for forms
 *
 * @package		ghRecruit
 * @module      Admin
 * @author      Bob Janes aka GreyHead info@greyhead.net greyhead.net
 * @copyright	Copyright (C) 2008 Bob Janes. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// initialise plugin setup
$attribs = array();
$attribs['input']    = array('maxlength' => '150', 'size' => '80', 'class' => 'text_area' );
$attribs['textarea'] = array('cols' => '50', 'rows' => '8' );
$attribs['header']   = array('colspan' => '4', 'class' => 'cf_header');
$attribs['select']   = array('class' => 'cf_select');

$db =& JFactory::getDBO();
$doc =& JFactory::getDocument();
$script = $style = "";
$doc->addStyleSheet(JURI::Base().'components/com_chronocontact/css/plugin.css');

jimport('joomla.html.pane');
$pane =& JPane::getInstance('tabs');

/**
 * Content Component Query Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class ChronoContactHelperPlugin
{
    /**
     * Saves the plugin parameter array
     * NB packs array parameters as v1|v2|v3|. . .
     *
     * Redirects on completion
     */
    function save_conf( $option )
    {
        global $mainframe;
        $database =& JFactory::getDBO();
        $post = JRequest::get( 'post' , JREQUEST_ALLOWRAW );

        $row =& JTable::getInstance('chronocontactplugins', 'Table');
        if ( !$row->bind( $post ) ) {
            JError::raiseWarning(100, $row->getError());
            $mainframe->redirect( "index2.php?option=$option" );
        }

        $params = JRequest::getVar( 'params', '', 'post', 'array', array(0) );
        if ( is_array( $params ) ) {
            $txt = array();
            foreach ( $params as $k => $v ) {
                if ( is_array($v) ) {
                    $v = implode('|', $v);
                }
                $txt[] = "$k=$v";
            }
            $row->params = implode( "\n", $txt );
        }
        if ( !$row->store() ) {
            JError::raiseWarning(100, $row->getError());
            $mainframe->redirect( "index2.php?option=$option" );
        }
        $mainframe->redirect( "index2.php?option=".$option, "Config Saved" );
                $row =& JTable::getInstance('chronocontactplugins', 'Table');
    }

    /**
     * Convert the PlugIn parameters to an object
     * and initialise and add any missing parameters
     *
     */
    function loadParams($row, $params_array)
    {
        $txt = array();
        foreach ( $params_array as $k => $v ) {
            $txt[] = "$k=$v";
        }
        $ini_string = implode( "\n", $txt );

        $params = new JParameter($ini_string);
        //echo '<div>$params->get("days"): '.print_r($params->get("days"), true).'</div>';
        $params->bind($row->params);
        //echo '<div>$params->get("days"): '.print_r($params->get("days"), true).'</div>';

        return $params;
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & input field
     *
     * @param $title string title
     * @param $name string field name
     * @param $value string field value
     * @param $maxlength integer max input length
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     *
     */
    function createInputTD($title, $name, $value='', $null='', $attributes=array(), $tooltip='', $id=false)
    {
        $return  = ChronoContactHelperPlugin::createTitleTD($title, $tooltip);
        $return .= ChronoContactHelperPlugin::createInput($name, $value, $attributes, $id);
        return $return;
    }
    /**
     * Create a 4x<td> row with tooltip, empty td, title & input field
     *
     * @param $name string field name
     * @param $value string field value
     * @param $maxlength integer max input length
     * @param $attributes array additional input atributes
     * @param $id string
     * @param $addTD boolean if true add <td>. . .</td> tags
     *
     */
    function createInput($name, $value='', $attributes=array(), $id=false, $addTD=true, $attribTD=array() )
    {
        // create an id from $name if not supplied
        $id = ChronoContactHelperPlugin::createId($name, $id);

        $return = "<input type='text' name='$name'id='$id' ".JArrayHelper::toString($attributes)."
        	value='".JText::_(htmlspecialchars($value, ENT_QUOTES))."' />
			";
        if ( $addTD ) {
            $return = ChronoContactHelperPlugin::wrapTD($return, $attribTD);
        }
        return $return;
    }
    /**
     * Creates a title block of 4x<td> with tooltip and text
     *
     * @param $title string title
     * @param $tooltip string tooltip
     */
    function createTitleTD($title, $tooltip='')
    {
        $return = "";
        if ( $tooltip ) {
            $return .= ChronoContactHelperPlugin::createTooltip($tooltip);
        } else {
            $return .= "<td>&nbsp;</td>";
        }
        $return .= ChronoContactHelperPlugin::createTitle($title);
        $return .= "<td class='cf_spacer' >&nbsp;</td>";
        return $return;
    }
    /**
     * Creates a title block of 4x<td> with tooltip and text
     *
     * @param $title string title
     * @param $addTD boolean wrap the result in <td> tags if true
     * @param $styleTD string valid css style string e.g. 'font-weight:bold;' to td
     */
    function createTitle($title, $addTD=true, $attribTD=array('class' => 'key') )
    {
        $return = JText::_($title);
        if ( $addTD ) {
            $return = ChronoContactHelperPlugin::wrapTD($return, $attribTD);
        }
        return $return;
    }
    /**
     * Creates a tooltip - optionally with a styled td
     *
     * @param $tooltip string tooltip
     * @param $style string a valid style declaration
     * @addTD boolean wraps the tooltip in a TD
     */
    function createTooltip($tooltip, $addTD=true, $attribTD=array('class' => 'cf_tooltip'))
    {
        $return = JHTML::_('tooltip', JText::_($tooltip) );
        if ( $addTD ) {
            $return = ChronoContactHelperPlugin::wrapTD($return, $attribTD);
        }
        return $return;
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & select drop-down
     *
     * @param $title string title
     * @param $name string field name
     * @param $selected string/array field value(s)
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     * @param $translate boolean apply JText?
     *
     */
    function createSelectTD($title, $name, $options, $selected='',
        $attributes='', $tooltip='', $id=false )
    {
        $return  = ChronoContactHelperPlugin::createTitleTD($title, $tooltip);
		$translate=false;
        $return .= ChronoContactHelperPlugin::createSelect($name, $options, $selected,
            $attributes, $id, $translate);
        return $return;
    }
    /**
     * create a select dropdown
     *
     * @param $name string field name
     * @param $selected string/array field value(s)
     * @param $attributes array additional input attributes
     * @param $id string
     * @param $translate boolean apply JText?
     * @param $addTD boolean wrap the result in <td> tags if true
     * @param $styleTD string valid css style string e.g. 'font-weight:bold;' to td
     */
    function createSelect($name, $options, $selected='',
        $attributes=array(), $id=false, $translate=false, $addTD=true, $attribTD=array())
    {
        $id = ChronoContactHelperPlugin::createId($name, $id);
        if ( !is_array($selected) ) {
            $selected = explode(', ', $selected);
        }
        // if the selection is empty add a null option to the beginnig of the options list
        if ( !empty($selected) ) {
            $null_option = array('null' => JHTML::_('select.option', JText::_('--?--')));
            $options = array_merge($null_option, $options);
        }
        $return = JHTML::_('select.genericlist', $options, $name, JArrayHelper::toString($attributes),
        	'value', 'text', $selected, $id, $translate );
        if ( $addTD ) {
            $return = ChronoContactHelperPlugin::wrapTD($return, $attribTD);
        }
        return $return;
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & Yes?No radio buttons
     *
     * @param $title string title
     * @param $name string field name
     * @param $options - will be ignored
     * @param $selected string field value(s)
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     * @param $translate boolean apply JText?
     *
     */
    function createYesNoTD($title, $name, $options='', $selected='',
        $attributes=array(), $tooltip='', $id=false )
    {
        $options = array('1' => JText::_('Yes'), '0' => JText::_('No'));
		$translate=false;
        return $this->createRadioTD($title, $name, $options,
            $selected, $attributes, $tooltip, $id, $translate);
    }
    /**
     * Create a Yes/No radio button pair optionally wrapped in a td
     *
     */
    function createYesNo( $name, $options='', $selected='',
        $attributes=array(), $id=false, $addTD=true, $attribTD=array())
    {
        $options = array('1' => JText::_('Yes'), '0' => JText::_('No'));
        return $this->createRadio( $name, $options, $selected,
            $attributes, $id, $addTD, $attribTD);
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & radio button set
     *
     * @param $title string title
     * @param $name string field name
     * @param $options array buttons
     * @param $selected string field value(s)
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     * @param $translate boolean apply JText?
     *
     */
    function createRadioTD($title, $name, $options, $selected='',
        $attributes=array(), $tooltip='', $id=false )
    {
        $return  = $this->createTitleTD($title, $tooltip);
        $return .= $this->createRadio($name, $options, $selected, $attributes );
        return $return;
    }
    /**
     * Create a radio button array optionally wrapped in a td
     *
     * @param $name string field name
     * @param $options array buttons
     * @param $selected string field value(s)
     * @param $attributes string additional input attributes
     * @param $id string
     * @param $addTD boolean wrap the result in a TD
     * @param $attribTD array attributes for the TD
     *
     */
    function createRadio($name, $options, $selected='',
        $attributes=array(), $id=false, $addTD=true, $attribTD=array())
    {
        $id = $this->createId($name, $id);
        $options_array = array();
        foreach ( $options as $k => $v ) {
            $options_array[] = JHTML::_('select.option', $k, $v);
        }
        $return = JHTML::_('select.radiolist', $options_array, $name,
            JArrayHelper::toString($attributes), 'value', 'text', $selected );
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }

        return $return;
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & TextArea
     *
     * @param $title string title
     * @param $name string field name
     * @param $rows integer no of rows
     * @param $cols integer no of columns
     * @param $value string field value
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     *
     */
    function createTextareaTD($title, $name, $value='', $attributes=array(), $tooltip='', $id=false )
    {
        $return  = $this->createTitleTD($title, $tooltip);
        $return .= $this->createTextarea($name, $value, $attributes, $id );
        return $return;
    }
    /**
     * Create a 4x<td> row with tooltip, empty td, title & TextArea
     *
     * @param $title string title
     * @param $name string field name
     * @param $rows integer no of rows
     * @param $cols integer no of columns
     * @param $value string field value
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     *
     */
    function createTextarea($name, $value='', $attributes=array(), $id=false,
        $addTD=true, $attribTD=array() )
    {
        $id = $this->createId($name, $id);
        // create an attribute string
        $return = "<textarea name='$name' id='$id' ".JArrayHelper::toString($attributes)." >$value</textarea>";
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }
        return $return;
    }
    /**
     * Create a 4x<td> row with tooltip, empty td, title & Date picker
     *
     * @param $title string title
     * @param $name string field name
     * @param $selected string/array field value(s)
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     * @param $translate boolean apply JText?
     * @param $config string additional javascript options
     *
     */
    function createDateTD($title, $name, $selected='',
        $attributes='', $tooltip='', $id=false, $config=null )
    {
        $id = $this->createId($name, $id);
        $return  = $this->createTitleTD($title, $tooltip);
        $return .= "<td>".$this->createCalendar($selected, $name,
            $name, '%d-%m-%Y %H:%M', 'style="width:120px;"', $config )."</td>";
        return $return;
    }

    function createDate($name, $selected='', $attributes='', $id=false,
        $addTD=true,  $attribTD=array(), $config=null )
    {
        $id = $this->createId($name, $id);
        // create an attribute string
        $attribute_string = $this->createAttributeString($attributes);
        $return = $this->createCalendar($selected, $name,
            $name, '%d-%m-%Y %H:%M', 'style="width:120px;"', $config );
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }
        return $return;
    }

    /**
     * Create a 4x<td> row with tooltip, empty td, title & Time picker
     *
     * @param $title string title
     * @param $name string field name
     * @param $selected string field value as hh:mm
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     *
     */
    function createTimeTD($title, $name, $selected='',
        $attributes=array(), $tooltip='', $id=false, $config=array() )
    {
        $return  = $this->createTitleTD($title, $tooltip);
        $return .= $this->createTime($name, $selected, $attributes, $id, true, array(), $config);

        return $return;
    }
    /**
     * Create a Time picker, optionally wrapped in a styled TD
     *
     * @param $title string title
     * @param $name string field name
     * @param $selected string field value as hh:mm
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     *
     */
    function createTime($name, $selected='', $attributes=array(), $id='', $addTD=true, $attribTD=array(), $config=array() )
    {
        $id = $this->createId($name, $id);
        // create an attribute string
        $return = "<input type='text' name='$name' id='$id' value='' ".JArrayHelper::toString($attributes)." />";
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }
        $config = array_merge(array('increment' => '5', 'delay' => '150'), $config);
        $config = JArrayHelper::toString($config, ': ', ', ');
        // initialise timer
        $start = 0;
        if ( $selected ) {
            $time_array = explode(':', $selected);
            $start = $time_array[0] * 60 + $time_array[1];
        }
        $script = "
            window.addEvent('domready', function() {
                var spinner1 = new TimeSpinner($('$id'), {
                    $config
                },
                $start );
            });
            ";
        $doc =& JFactory::getDocument();
        $doc->addScriptDeclaration($script);
        return $return;
    }

    /**
     * Create a <td> row with tooltip, empty td, title & plain text
     *
     * @param $title string title
     * @param $text string value
     * @param $attributes string additional input attributes
     * @param $tooltip string tooltip
     * @param $id string
     * @param $translate boolean apply JText?
     *
     */
    function createTextTD($title, $text, $attributes=array(), $tooltip='', $id=false )
    {
        $return  = $this->createTitleTD($title.":", $tooltip);
        $return .= $this->createText($text, $attributes, $id);
        return $return;
    }

    /**
     * Create a plain text input optionally wrapped in a TD
     *
     * @param $text string value
     * @param $attributes string additional input attributes
     * @param $id string
     * @param $translate boolean apply JText?
     * @addTD boolean wrap result in TD
     * @attribTD array attributes for TD
     *
     */
    function createText($text, $attributes=array(), $id=false, $addTD=true, $attribTD=array())
    {
        // create an attribute string
        if ( $id ) {
            $attributes['id'] = $id;
        }
        $return = "<span ".JArrayHelper::toString($attributes)." >".JText::_($text)."</span>";
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }
        return $return;
    }

    function createHeaderTD($text, $attributes=array(), $addTD=true, $attribTD=array() )
    {
        $attributes_default = array();
        $attribute_string = JArrayHelper::toString( $attributes );
        $return = $return = "<span $attribute_string >".JText::_($text)."</span>";
        if ( $addTD ) {
            $return = $this->wrapTD($return, $attribTD);
        }
        return $return;
    }
	
	function openTableLegend($title)
	{
		$return = '<fieldset class="adminform">
        <legend>'.JText::_($title).'</legend>
        <table class="admintable">';
		return $return;
	}
	function closeTableLegend()
	{
		$return = '</table>
        </fieldset>';
		return $return;
	}

    /**
     * Creates a series of hidden inputs
     * @param $hidden_array array name/value pairs
     *
     */
    function createHiddenArray($hidden_array){
        $return = array();
        foreach ($hidden_array as $name => $value ) {
            $return[] = $this->createHidden($name, $value);
        }
        return implode('', $return);
    }

    /**
     * Creates a single hidden input
     *
     */
    function createHidden($name, $value )
    {
        return "<input type='hidden' name='$name' id='$name' value='$value' />";
    }
    /**
     * Creates an input id from a name & removes [ & ]
     *
     */
    function createId( $name, $id='' )
    {
        if ( !$id ) {
            $id = $name;
        }
        $id = str_replace('[', '', $id);
        $id = str_replace(']', '', $id);

        return $id;
    }
    /**
     * The function trims text to $length,
     * removes any punctuation in the stops array from the end
     * and appends $tail
     *
     * @param $text string
     * @param $length integer
     * @param $tail string
     */
    function trimText($text, $length='150', $tail=" . . .")
    {
        $text = trim($text);
        $txtl = strlen($text);
        if ( $txtl > $length ) {
            for ( $i = 1; $text[$length-$i] != " "; $i++ ) {
                if ( $i == $length ) {
                    return substr($text, 0, $length).$tail;
                }
            }
            $stops = array(',', '.', ' ', ';');
            for ( ; in_array($text[$length-$i], $stops); $i++ ) {;}
            $text = substr($text, 0, $length-$i+1 ) . $tail;
        }
        return $text;
    }
    /**
     * Displays a calendar control field
     * Modified version of Joomla calendar to allow config inputs
     *
     * @param    string    The date value
     * @param    string    The name of the text field
     * @param    string    The id of the text field
     * @param    string    The date format
     * @param    array    Additional html attributes
     */
    function createCalendar($value, $name, $id='',
        $format='%Y-%m-%d', $attributes=null, $config=null )
    {
        $id = $this->createId($name, $id);
        $img_id = $id."_img";
        JHTML::_('behavior.calendar'); //load the calendar behavior
        if ( is_array($attributes) ) {
            $attributes = JArrayHelper::toString( $attributes );
        }
        $config_array = array(
        	'inputField' => "'$id'",
            'ifFormat' => "'$format'",
            'button' => "'$img_id'",
        	'align' => "'Tl'",
        	'singleClick' => "true",

        );
        if ( is_array($config) ) {
            $config_array = array_merge($config_array, $config);
        }
        $script = "
        	window.addEvent('domready', function() {Calendar.setup({";
        foreach ( $config_array as $k => $v ) {
            $script .= $k." : ".$v.", ";
        }
        $script .= "});});";
        $document =& JFactory::getDocument();
        $document->addScriptDeclaration($script);

        return "<input type='text' name='$name' id='$id'
        	value='".htmlspecialchars($value, ENT_COMPAT, 'UTF-8')."'
            $attributes.' />".
            "<img class='calendar'
           	src='".JURI::root(true)."/templates/system/images/calendar.png'
            alt='calendar' id='$img_id' />";
    }

    /**
     * Creates a style statement for a TD
     *
     * @param $styleTD string - the supplied style string
     * @param $styleTD_default string - the default style string
     *
     */
    function wrapTD($return, $attribTD=null)
    {
        $return = "<td ".JArrayHelper::toString($attribTD)." >$return</td>";
        return $return;
    }

    /* Creates a style statement for a TR
     *
     * @param $return string the string to be wrapped
     * @param $styleTR string - the supplied style string
     */
    function wrapTR($return, $attribTR=null )
    {
        $return = "<tr ".JArrayHelper::toString($attribTR)." >$return</tr>";
        return $return;
    }
    /**
     *
	 * @author Bob
	 *
     */
    function showCFDebugMessage($message)
    {
        $MyForm =& CFChronoForm::getInstance();
        $MyForm->addDebugMsg($message);
    }
    /**
     *
	 * @author Bob
	 *
     */
    function showPluginDebugMessages($messages)
    {
        if ( !is_array($messages) || !empty($messages) ) {
            $doc =& JFactory::getDocument();
            $doc->addStyleDeclaration("div.debug {border:1px solid red; padding:3px; margin-bottom:3px;}");
            foreach ( $messages as $message ) {
                echo "<div class='debug' >$message</div>";
            }
        }
    }
}
?>