<?php
defined('_JEXEC') or die('Restricted access');
global $mainframe;
require_once( $mainframe->getPath( 'class', 'com_chronocontact' ) );
// the class name must be the same as the file name without the .php at the end
class cf_profile
{
    //the next four fields must be defined for every plugin
    var $result_TITLE = "Profile page";
    var $result_TOOLTIP = "Load data from any table to be shown on the form page using a very simple method! All you need to do is to put the field name between { and } , then it will be replaced by the same field value from the table";
    var $plugin_name = "cf_profile"; // must be the same as the class name
    var $event = "ONLOAD"; // must be defined and in Uppercase, should be ONSUBMIT or ONLOAD

    // the next function must exist and will have the backend config code
    function show_conf($row, $id, $form_id, $option)
    {
        global $mainframe;

        require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugin.php');
        $helper = new ChronoContactHelperPlugin();

        // identify and initialise the parameters used in this plugin
        $params_array = array(
        	'table_name' => '',
        	'field_name' => '',
        	'parameter' => '',
            'editable' => '0',
            'evaluate' => '0' );
        $params = $helper->loadParams($row, $params_array);

        $tables = $db->getTableList();

        $script .= "
        function loadfields2()
        {
        	var table = $('table_name').value;
        	var test = $('field_name').getParent();
        	if (table == '' || table == '--?--' ) {
        		test.setHTML('<div id=\'field_name\'>Please select table name first</div>')
        		return false;
        	}
        	var url = 'index3.php?option=com_chronocontact&task=ajax&plugin=cf_profile&method=ajaxFields&format=raw';
        	myAjax = new Ajax(url, {
        		method: 'post',
        		postBody: 'table='+table,
        		onRequest: function() {
        			test.setHTML('<div id=\'field_name\'>Loading . . .</div>')
    			},
        		onSuccess: function(req) {
        			test = $('field_name').getParent();
        			test.setHTML('<div id=\'field_name\'></div>');
					test = $('field_name').getParent();
					test.setHTML(req);
    			}
    		}).request();
        }
        ";
?>
<form action="index2.php" method="post" name="adminForm" id="adminForm"
    class="adminForm" >
<?php
        echo $pane->startPane("cf_profile");
        echo $pane->startPanel( 'Configure', 'Configure' );
?>

<?php
        //$input = $helper->createHeaderTD('Configure Profile Table plugin', '', true, $attribs['header']);
        echo $helper->openTableLegend('Configure Profile Table plugin');

        $tooltip = "Choose the table to get the data from.";
        foreach ( $tables as $k => $table ) {
            $tables_array[$table] = JHTML::_('select.option', JText::_($table));
        }
        $input = $helper->createSelectTD('Table name',
            'params[table_name]', $tables_array, $params->get('table_name'),
            array('onChange' => 'loadfields2()', 'class' => 'cf_select'), $tooltip, 'table_name' );
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "The table column name which to be matched by the parameter;
        	for best results, this field must be UNIQUE";
        if ( $id == 0 ) {
            $input = $helper->createTextTD('Target field name',
                '<div id="field_name">Select Table name first</div>', '', $tooltip);
        } else {
			$input = "<td></td>";
            $fields_array = array();
            $table_fields = $db->getTableFields( $params->get('table_name') );
            foreach ( $table_fields[$params->get('table_name')] as $k => $v) {
                $fields_array[$k] = JHTML::_('select.option', JText::_($k));
            }
            $input = $helper->createSelectTD('Target field name',
                'params[field_name]', $fields_array,
                $params->get('field_name'), array('class' => 'cf_select'), $tooltip, 'field_name' );
        }
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "The name of the parameter provided in the page url e.g. userid=128.";
        $input = $helper->createInputTD("'Request' parameter name", 'params[parameter]',
            $params->get('parameter'), '', $attribs['input'], $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
		
		$tooltip = "This will be the default value of the requested parameter in case nothing was in the request!";
        $input = $helper->createInputTD("Default Request Parameter value", 'params[default_param_value]',
            $params->get('default_param_value'), '', $attribs['input'], $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "Will this profile page be editable by users?";
        $input = $helper->createYesNoTD("Editable", "params[editable]", '',
            $params->get('editable', '0'), '', $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
		
		$tooltip = "Skip populating these fields";
        $input = $helper->createInputTD("Skipped fields list", 'params[skippedarray]',
            $params->get('skippedarray'), '', $attribs['input'], $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));

        $tooltip = "Will the profile page evaluate code before doing its routine?
        	This may need to be enabled if you are generating some fields using PHP and want to load their data!?";
        $input = $helper->createYesNoTD("Evaluate code", "params[evaluate]", '',
            $params->get('evaluate', '0'), '', $tooltip);
        echo $helper->wrapTR($input, array('class' => 'cf_config'));
		echo $helper->closeTableLegend();
?>

<?php
        $hidden_array = array (
            'id' => $id,
            'form_id' => $form_id,
            'name' => $this->plugin_name,
            'event' => $this->event,
            'option' => $option,
            'task' => 'save_conf');
        echo $helper->createHiddenArray( $hidden_array );

        echo $pane->endPanel();
        echo $pane->startPanel( 'Help', "help" );
?>
<table border="0" cellpadding="3" cellspacing="0" class='cf_table' >
<?php
        $input = $helper->createHeaderTD('Help for Profile Table plugin', '', true, $attribs['header']);
        echo $helper->wrapTR($input);
?>
    <tr>
        <td colspan='4' style='border:1px solid silver; padding:6px;'>
        <div>The plugin allows you to read values from any table in the database
        and include them in your form.</div>
        <div>It was originally designed to allow access to the jos_users table to create member
        profiles but it is capable of much more.</div>
        <div>To use the plugin effectively you will need to call the form from a link on your site.
        This could be from - for example a list of users, or topics, or events where you have
        some related information in a database table.</div>
        <ul><li>Choose the table you want to use in the first drop-down
        e.g. jos_users to get a user's name and email.</li>
        <li>Select a field or column name from the table in the second drop down.
        This should be a field that will uniquely identify the record you want to use
        e.g. 'id' or 'username' for the jos_user table. NB This drop-down will not appear
        until you select a table in the first drop-down.</li>
        <li>In the Target field name box put the name of the field you will use to identify the
        record e.g. user_id. You will need to add this field to a url calling the form
        e.g. . . . &chronoformname=my_form&user_id=99 </li>
        <li>You can then use information from this record in your form by putting {column_name} where
        you want it to appear e.g. {name} for a users name from the jos_users table.</li>
        <li>Once this plugin is configured you must enable it in the Form 'Plugins'' tab.</li></ul>
        </td>
    </tr>
</table>

<?php
        echo $pane->endPanel();
        echo $pane->endPane();
?>
</form>
<?php
        if ( $style ) $doc->addStyleDeclaration($style);
        if ( $script ) $doc->addScriptDeclaration($script);
    }
    /**
     * The function executed when the form is loaded
     * Returns an amended $html_string
     *
     */
    function onload( $option, $pluginrow, $params, $html_string )
    {
        global $mainframe;
        $my =& JFactory::getUser();
        $database =& JFactory::getDBO();

        //$parid 	= JRequest::getVar($params->parameter, '', 'request', 'int', 0 );
		if($params->get('evaluate') == 'Yes'){
			ob_start();
			eval( "?>".$html_string );
			$html_string = ob_get_clean();
		}
		
		$parid 	= JRequest::getVar($params->get('parameter'));
        if ( $parid ) {
            $record_id = $parid;
		}else if($params->get('default_param_value')){
			$record_id = $params->get('default_param_value');
        } else {
            $record_id = $my->id;
            if ( $record_id == 0 ) {
                //$record_id = '##guest##';
            }
        }
        if ( !$record_id ) {
            $result = $database->getTableFields( '#__users' );
            $table_fields = array_keys($result['#__users']);
            foreach ( $table_fields as $table_field ) {
                $html_string = str_replace("{".$table_field."}", '', $html_string);
            }
        } elseif ( $record_id ) {
            $database->setQuery( "SELECT * FROM ".$params->get('table_name')." WHERE ".$params->get('field_name')." = '".$record_id."'" );
            $rows = $database->loadObjectList();
            $row = $rows[0];
            $tables = array( $params->get('table_name') );
            $result = $database->getTableFields( $tables );
            $table_fields = array_keys($result[$params->get('table_name')]);
            foreach ( $table_fields as $table_field ) {
                $html_string = str_replace("{".$table_field."}", $row->$table_field, $html_string);
            }
        }
		
		if($params->get('editable') == 'Yes'){
			$database->setQuery( "SELECT * FROM ".$params->get('table_name')." WHERE ".$params->get('field_name')." = '".$record_id."'" );
            $datarow = $database->loadAssoc();
			$formname = JRequest::getVar( 'chronoformname');
			if ( !$formname ) {
				$params =& $mainframe->getPageParameters('com_chronocontact');
				$formname = $params->get('formname');
			}
			$MyForm =& CFChronoForm::getInstance($formname);
			$MyForm->posted = $datarow;
			$skippedarray = explode(",", $params->get('skippedarray'));
			if (count($datarow)) {
				//text fields
				$pattern_input = '/<input([^>]*?)type=("|\')(text|password)("|\')([^>]*?)>/is';
				$matches = array();
				preg_match_all($pattern_input, $html_string, $matches);
				foreach ($matches[0] as $match) {
					$pattern_value = '/value=("|\')(.*?)("|\')/i';
					$pattern_name = '/name=("|\')(.*?)("|\')/i';
					preg_match($pattern_name, $match, $matches_name);
					if(!in_array($matches_name[2], $skippedarray)){
						$valuematch = preg_replace($pattern_value, '', $match); 
						$namematch = preg_replace($pattern_name, 'name="${2}" value="<?php echo $MyForm->posted[\'${2}\']; ?>"', $valuematch);										
						$html_string = str_replace($match, $namematch, $html_string);
					}
				}
				//hidden fields
				$pattern_input = '/<input([^>]*?)type=("|\')hidden("|\')([^>]*?)>/is';
				$matches = array();
				preg_match_all($pattern_input, $html_string, $matches);
				foreach ($matches[0] as $match) {
					$pattern_value = '/value=("|\')(.*?)("|\')/i';
					$pattern_name = '/name=("|\')(.*?)("|\')/i';
					preg_match($pattern_name, $match, $matches_name);
					if(!in_array($matches_name[2], $skippedarray)){
						$valuematch = preg_replace($pattern_value, '', $match); 
						$namematch = preg_replace($pattern_name, 'name="${2}" value="<?php echo $MyForm->posted[\'${2}\']; ?>"', $valuematch);										
						$html_string = str_replace($match, $namematch, $html_string);  
					} 
				}
				//checkboxes or radios fields
				$pattern_input = '/<input([^>]*?)type=("|\')(checkbox|radio)("|\')([^>]*?)>/is';
				$matches = array();
				preg_match_all($pattern_input, $html_string, $matches);
				foreach ($matches[0] as $match) {
					$pattern_value = '/value=("|\')(.*?)("|\')/i';
					$pattern_name = '/name=("|\')(.*?)("|\')/i';
					preg_match($pattern_name, $match, $matches_name);
					preg_match($pattern_value, $match, $matches_value);
					if(!in_array(str_replace('[]', '', $matches_name[2]), $skippedarray)){
						//multi values
						if(strpos($matches_name[2], '[]')){
							$namematch = preg_replace($this->skipregex($pattern_name), 'name="${2}" <?php if(in_array("'.$matches_value[2].'", explode(", ", $MyForm->posted["'.str_replace('[]', '', $matches_name[2]).'"])))echo \'checked="checked"\'; ?>', $match);						
						//single values
						}else{
							$namematch = preg_replace($pattern_name, 'name="${2}" <?php if($MyForm->posted["'.$matches_name[2].'"] == "'.$matches_value[2].'")echo \'checked="checked"\'; ?>', $match);
						}								
						$html_string = str_replace($match, $namematch, $html_string);
					}
				}
				//textarea fields
				$pattern_textarea = '/<textarea([^>]*?)><\/textarea>/is';
				$matches = array();
				preg_match_all($pattern_textarea, $html_string, $matches);
				$namematch = '';
				foreach ($matches[0] as $match) {
					$pattern_value = '/value=("|\')(.*?)("|\')/i';
					$pattern_name = '/name=("|\')(.*?)("|\')/i';
					preg_match($pattern_name, $match, $matches_name);
					if(!in_array($matches_name[2], $skippedarray)){
						$pattern_textarea2 = '/(<textarea(.*?)>)(.*?)(<\/textarea>)/is';
						$newtextarea_match = preg_replace($pattern_textarea2, '${1}<?php echo $MyForm->posted[\''.$matches_name[2].'\']; ?>${4}', $match);															
						$html_string = str_replace($match, $newtextarea_match, $html_string);
					} 
				}
				//select boxes
				$pattern_select = '/<select(.*?)select>/is';
				$matches = array();
				preg_match_all($pattern_select, $html_string, $matches); 
				 
				foreach ($matches[0] as $match) {
					$selectmatch = $match;
					$pattern_select2 = '/<select([^>]*?)>/is';
					preg_match_all($pattern_select2, $match, $matches2); 
					$options = preg_replace(array('/'.$this->skipregex($matches2[0][0]).'/is', '/<\/select>/i'), array('', ''), $match);
					
					$pattern_name = '/name=("|\')(.*?)("|\')/i';
					preg_match($pattern_name, $matches2[0][0], $matches_name);
					if(!in_array(str_replace('[]', '', $matches_name[2]), $skippedarray)){
						//multi select
						if(strpos($matches_name[2], '[]')){
							$pattern_options = '/<option(.*?)<\/option>/is';
							preg_match_all($pattern_options, $options, $matches_options);
							foreach($matches_options[0] as $matches_option){
								$pattern_value = '/value=("|\')(.*?)("|\')/i';
								preg_match($pattern_value, $matches_option, $matches_value); 
								$optionmatch = preg_replace('/<option/i', '<option <?php if(in_array("'.$matches_value[2].'", explode(", ", $MyForm->posted["'.str_replace('[]', '', $matches_name[2]).'"])))echo \'selected="selected"\'; ?>', $matches_option);
								$selectmatch = str_replace($matches_option, $optionmatch, $selectmatch);						 
							}
						//single select
						}else{
							$pattern_options = '/<option(.*?)<\/option>/is';
							preg_match_all($pattern_options, $options, $matches_options);
							foreach($matches_options[0] as $matches_option){
								$pattern_value = '/value=("|\')(.*?)("|\')/i';
								preg_match($pattern_value, $matches_option, $matches_value); 
								$optionmatch = preg_replace('/<option/i', '<option <?php if($MyForm->posted["'.$matches_name[2].'"] == "'.$matches_value[2].'")echo \'selected="selected"\'; ?>', $matches_option);
								$selectmatch = str_replace($matches_option, $optionmatch, $selectmatch);						 
							}
						}
						$html_string = str_replace($match, $selectmatch, $html_string);
					}
				}
			}

		}
		
        return $html_string ;
    }
    /////

    /**
     * Returns a select list of the fields in a table specified in a request param
     *
     *
     */
    function ajaxFields()
    {
      	$db =& JFactory::getDBO();
    	$tablename = JRequest::getVar('table');

        $fields_array = array();
        $table_fields = $db->getTableFields( $tablename );
        foreach ( $table_fields[$tablename] as $k => $v) {
            $fields_array[$k] = JHTML::_('select.option', $k);
        }
    	echo JHTML::_('select.genericlist', $fields_array,
                'params[field_name]', 'class="cf_select"', 'value', 'text',
                '', 'field_name' );
    }

    // this function must exist and may not be changed unless you need to customize something
    function save_conf( $option )
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugin.php');
        $helper = new ChronoContactHelperPlugin();
        $helper->save_conf($option);
    }

	function skipregex($regex){
		$reserved = array('[', ']');
		$replace = array('\[', '\]');
		return str_replace($reserved, $replace, $regex);
	}
}
?>