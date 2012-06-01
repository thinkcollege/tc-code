<?php
defined('_JEXEC') or die('Restricted access');
global $mainframe;

require_once( $mainframe->getPath( 'class', 'com_chronocontact' ) );

// the class name must be the same as the file name without the .php at the end
class cf_CURL
{
    //the next 3 fields must be defined for every plugin
    var $result_TITLE = "CURL";
    var $result_TOOLTIP = "Submit form data to another URL using the CURL method.
    	Use this plugin to submit data to Acajoom, Salesforce or any other
    	script/web service which accepts data through a specific URL";
    var $plugin_name = "cf_CURL"; // must be the same as the class name
    var $event = "ONSUBMIT"; // must be defined and in Uppercase, should be ONSUBMIT or ONLOAD

    // the next function must exist and will have the backend config code
    function show_conf($row, $id, $form_id, $option)
    {
        global $mainframe;

        if ( function_exists('curl_init') ) {
            echo "CURL OK : the CURL function was found on this server.";
        } else {
            echo "CURL problem : the CURL function was not found on this server.<br />
            Sorry, but the CURL plugin cannot be used on this site as it is currently set up.";
            return;
        }

        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_chronocontact'.DS.'helpers'.DS.'plugin.php');
        $helper = new ChronoContactHelperPlugin();

        $tables = $db->getTableList();

        $query = "
        	SELECT *
        		FROM `#__chrono_contact`
        		WHERE id = ".$db->Quote($form_id) ;
        $db->setQuery($query);
        $form = $db->loadObject();

        $htmlstring = $form->html;
        preg_match_all('/name=("|\').*?("|\')/i', $htmlstring, $matches);
        $names = array();
        foreach ( $matches[0] as $name ) {
            $name = preg_replace('/name=("|\')/i', '', $name);
            $name = preg_replace('/("|\')/', '', $name);
            $name = preg_replace('/name=("|\')/', '', $name);
            if ( strpos($name, '[]') ) {
                $name = str_replace('[]', '', $name);
            }
            $names[] = trim($name);
        }
        $names = array_unique($names);

        // identify and initialise the parameters used in this plugin
        $params_array = array(
        	'debugging' => '0',
        	'target_url' => 'http://',
            'header_in_response' => '0',
            'onsubmit' => 'before_email');
        $params = $helper->loadParams($row, $params_array);

        // Load extras data
        $extras2 = new JParameter($row->extra2);
?>
<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm" >
<?php
        echo $pane->startPane("cf_curl");
        echo $pane->startPanel( 'General', "general" );
?>
<table border="0" cellpadding="3" cellspacing="0" class='cf_table' >
<?php
        $input = $helper->createHeaderTD('Field names from your form', '', true, $attribs['header']);
        echo $helper->wrapTR($input);

        foreach ( $names as $name ) {
            $tooltip = "Enter the other site field name that matches '$name'";
            $input = $helper->createInputTD("'$name' field",
                "extras2[$name]", $extras2->get($name), '', $attribs['input'], $tooltip);
            echo $helper->wrapTR($input, array('class' => 'cf_config'));
        }

        $input = $helper->createHeaderTD('Extra field values to send', '',
            true, array('colspan' => '4', 'class' => 'cf_header'));
        echo $helper->wrapTR($input);

        $tooltip = "Extra Fields, enter data in this format : ship_to_name=field_name<br />Take care to add each entry to a new line";
        $input = $helper->createTextareaTD('Extra fields Data', 'extra1', $row->extra1,  $attribs['textarea'], $tooltip );
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
?>
</table>
<?php
        echo $pane->endPanel();
        echo $pane->startPanel( "CURL params", 'curl_params' );
?>
<table border="0" cellpadding="3" cellspacing="0" class='cf_table' >
<?php
        $input = $helper->createHeaderTD('Plugin parameters', '',
            true, array('colspan' => '4', 'class' => 'cf_header'));
        echo $helper->wrapTR($input);

        $tooltip = "The target URL to send the data to";
        $input = $helper->createInputTD("Target URL",
            "params[target_url]", $params->get('target_url'), '', $attribs['input'], $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $option_array = array('before_email' => 'Before Email', 'after_email' => 'After Email' );
        foreach ( $option_array as $k => $v ) {
            $option_array[$k] = JHTML::_('select.option', $k, JText::_($v));
        }
        $tooltip = "Run the plugin before or after the email.<br />
        	Running it before the email may be necessary to include some data into the email";
        $input = $helper->createSelectTD("Flow control", "params[onsubmit]",
            $option_array, $params->get('onsubmit'), $attribs['select'], $tooltip );
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "Show debug information on Submit?";
        $input = $helper->createYesNoTD("Debugging", "params[debugging]", '',
            $params->get('debugging'), '', $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "Include Header response from the gateway? default is No";
        $input = $helper->createYesNoTD("Header in Response", "params[header_in_response]", '',
            $params->get('header_in_response'), '', $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
?>
</table>
<?php
        echo $pane->endPanel();
        echo $pane->startPanel( "Extra code", 'extracode' );
?>
<table border="0" cellpadding="3" cellspacing="0" class='cf_table' >
<?php
        $input = $helper->createHeaderTD('Extra code', '',
            true, array('colspan' => '4', 'class' => 'cf_header'));
        echo $helper->wrapTR($input);

        $tooltip = "Execute some code just before the CURL transaction is executed";
        $input = $helper->createTextareaTD('Extra before CURL code', 'extra4',
            $row->extra4, $attribs['textarea'], $tooltip );
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "Execute some code just after the CURL transaction is executed";
        $input = $helper->createTextareaTD('Extra after CURL code', 'extra5',
            $row->extra5, $attribs['textarea'], $tooltip );
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
 ?>
</table>
<?php
         echo $pane->endPanel();
         echo $pane->startPanel( 'Help', 'help' );
?>
<table border="0" cellpadding="3" cellspacing="0" class='cf_table' >
<?php
        $input = $helper->createHeaderTD('How to configure the CURL plugin', '',
            true, array('colspan' => '4', 'class' => 'cf_header'));
        echo $helper->wrapTR($input);
?>
    <tr>
        <td colspan='4' style='border:1px solid silver; padding:6px;'>
        <div>The plugin allows you to use the PHP CURL function to send data from a ChronoForms Form to
        another site or to another application on your site. Use this when you need to keep flow control
        with ChronoForms so that a ReDirect URL will not work correctly.</div>
        <ul><li>First, open the CURL params tab and put the url that you want to submit the information to
        in the Target URL box e.g. http://crm.zoho.com/crm/WebToLead </li>
        <li>Next click the General Tab and you will see a list of the fields in your form.
        Put the corresponding names for the site you are sending to in the boxes along-side
        (often these will be the same as the field names in your form.)</li>
        <li>In the Extra Fields Data box you can enter values for fields that will be the same
        for each submission. These will often be client or transaction identifiers for the other site.
        (Note: Putting this information here means that it is never exposed in your form.)</li>
        <li>For testing, set debugging on the CURL params tab to Yes and you will see a debug report after your form is submitted.</li>
        <li>Lastly on the CURL params tab, you can set the CURL to run before or after Emails are sent.
        Use this if you need to fine-tune the process flow.</li>
        <li>The Extra Code tab allows you to add extra PHP to run before or after the CURL transaction.
        Normally you will leave these boxes empty; use them if you need to alter the submitted data in some way.</li>
        </ul>
        </td>
    </tr>
    </table>
<?php
        echo $pane->endPane();
        echo $pane->endPane();

        $hidden_array = array (
            'id' => $id,
            'form_id' => $form_id,
            'name' => $this->plugin_name,
            'event' => $this->event,
            'option' => $option,
            'task' => 'save_conf');
        $hidden_array['params[onsubmit]'] = 'before_email';
        echo $helper->createHiddenArray( $hidden_array );
?>

</form>
<?php
        if ( $style ) $doc->addStyleDeclaration($style);
        if ( $script ) $doc->addScriptDeclaration($script);

    }

    // this function must exist and may not be changed unless you need to customize something
    function save_conf( $option )
    {
        global $mainframe;

        //$db =& JFactory::getDBO();

        $row =& JTable::getInstance('chronocontactplugins', 'Table');
        if (!$row->bind( $_POST )) {
            JError::raiseWarning(100, $row->getError());
            $mainframe->redirect( "index2.php?option=$option" );
        }

        $extras2 = JRequest::getVar( 'extras2', '', 'post', 'array' );
        if ( is_array( $extras2 ) ) {
            $txt2 = array();
            foreach ( $extras2 as $k => $v) {
                $txt2[] = "$k=$v";
            }
            $row->extra2 = implode( "\n", $txt2 );
        }
        if ( !$row->store() ) {
            JError::raiseWarning(100, $row->getError());
            $mainframe->redirect( "index2.php?option=$option" );
        }
        // save params
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_chronocontact'
            .DS.'helpers'.DS.'plugin.php');
        $helper = new ChronoContactHelperPlugin();
        $helper->save_conf($option);
    }

    /**
     * The function that will be executed when the form is submitted
     *
     */
    function onsubmit( $option, $params, $row )
    {
        global $mainframe;

        if ( !function_exists('curl_init') ) {
            $mainframe->enqueuemessage("CURL problem : the CURL function was not found on this server.<br />
            Sorry, but the CURL plugin cannot be used on this site as it is currently set up.", 'error');
            return;
        }

        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_chronocontact'
            .DS.'helpers'.DS.'plugin.php');
        $helper = new ChronoContactHelperPlugin();

        $doc =& JFactory::getDocument();
        $doc->addStyleDeclaration("div.debug {border:1px solid red; padding:3px; margin-bottom:3px;}");

        $messages = array();

        /*********do the before onsubmit code**********/
        if ( !empty($row->extra4) ) {
            eval( "?>".$row->extra4 );
        }

        $curl_values = array();
        /// add main fields

        if ( trim($row->extra2) ) {
            $extras2 = explode("\n", $row->extra2);
            foreach ( $extras2 as $extra2 ) {
                $values = array();
                $values = explode( "=", $extra2 );
                if ( $values[1] ) {
                    $v = urlencode(trim($values[1]));
                    $curl_values[$v] = JRequest::getVar(trim($values[0]), '', 'post', 'string', '');
                }
            }
        }

        if ( trim($row->extra1) ) {
            $extras = explode("\n", $row->extra1);
            foreach ( $extras as $extra ) {
                // Note: accept only the first parameter pair on each line
                $values = explode("=", $extra, 2);
                $curl_values[$values[0]] = trim($values[1]);
            }
        }
        $query = JURI::buildQuery($curl_values);

        $messages[] = '<b>cf_CURL debug info</b>';
        $messages[] = '$curl_values: '.print_r($query, true);
        $messages[] = '$params->target_url: '.print_r($params->get('target_url'), true);
		$ch = curl_init($params->get('target_url'));
		$messages[] = '$ch: '.print_r($ch, true);
		curl_setopt($ch, CURLOPT_HEADER, $params->get('header_in_response')); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch); //execute post and get results
		curl_close ($ch);

	    $messages[] = 'CURL response: '.print_r($response, true);
    	$helper->showCFDebugMessage('CURL transaction executed');
    	if ( $params->get('debugging') ) {
    	    $helper->showPluginDebugMessages($messages);
    	}
		/*********do the after onsubmit code**********/
		if ( !empty($row->extra5) ) {
			eval( "?>".$row->extra5 );
		}
	}
}
?>