<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$mainframe->registerEvent( 'onPrepareContent', 'plgContentChronocontact' );

//load chronoforms classes
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'libraries'.DS.'chronoform.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'libraries'.DS.'mails.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'libraries'.DS.'customcode.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'libraries'.DS.'chronoformuploads.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'libraries'.DS.'plugins.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_chronocontact'.DS.'chronocontact.html.php');

function plgContentChronocontact(  &$row, &$params, $page=0 ) {
	
	$plugin =& JPluginHelper::getPlugin('content', 'chronocontact');
	// define the regular expression for the bot
	$regex = "#{chronocontact}(.*?){/chronocontact}#s";
	if(isset($row->text)){
		$row->text = preg_replace_callback( $regex, 'showform2', $row->text );
	}else{
		$row->text = '';
	}

	return true;
}

function showform2( &$matches ) {
	global $mainframe;
	$posted = JRequest::get( 'post' , JREQUEST_ALLOWRAW );
	$database =& JFactory::getDBO();
	$matches[0] = preg_replace('/{chronocontact}/i', '', $matches[0]);
	$matches[0] = preg_replace('/{\/chronocontact}/i', '', $matches[0]);
	$formname = $matches[0];
	$plugin =& JPluginHelper::getPlugin('content', 'chronocontact'); 
	$botParams = new JParameter($plugin->params);
		
	$type	= $botParams->def( 'type', 1 );	
	
	$MyForm =& CFChronoForm::getInstance($formname);
	
	$MyForm->pagetype = 'plugin';
	$session =& JFactory::getSession();
	$MyForm->formerrors = $session->get('chrono_form_errors', '', md5('chrono'));
	if($session->get('chrono_form_data', array(), md5('chrono'))){
		$posted = $session->get('chrono_form_data', array(), md5('chrono'));
		//print_r($posted);
	}
	ob_start();
	$MyForm->showForm($formname, $posted);	
	return $result = ob_get_clean();
	ob_end_clean();
}


?>