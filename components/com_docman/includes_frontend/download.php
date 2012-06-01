<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: download.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

include_once dirname(__FILE__) . '/download.html.php';

require_once($_DOCMAN->getPath('classes', 'mambots'));
require_once($_DOCMAN->getPath('classes', 'model'));
require_once($_DOCMAN->getPath('classes', 'theme'));

function fetchDocumentLicenseForm($uid, $inline=0)
{
    $doc = new DOCMAN_Document($uid);

   	return HTML_DMDownload::licenseDocumentForm(
        $doc->getLinkObject(),
        $doc->getPathObject(),
        $doc->getDataObject(),
        $inline);
}

function licenseDocumentProcess($uid)
{
    // this needs to use REQUEST , so onBeforeDownload plugins can use redirect
	$accepted = mosGetParam($_REQUEST, 'agree', 0);
    $inline   = mosGetParam($_REQUEST, 'inline', 0);
	$doc = new DOCMAN_Document($uid);

   	if ($accepted) {
        download($doc, $inline);
    } else {
        _returnTo('view_cat', _DML_YOU_MUST, $doc->getData('catid'));
    }
}
function download(&$doc, $inline = false)
{
    global $database, $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_offset;
    global $_DOCMAN, $_DMUSER;
    // global $HTTP_USER_AGENT;

    require_once($_DOCMAN->getPath('classes', 'file'));

    $data = &$doc->getDataObject();

    /* ------------------------------ *
	 *   CORE AUTHORIZATIONS          *
	 * ------------------------------ */

    // if the user is not authorized to download this document, redirect
    if (!$_DMUSER->canDownload($doc->getDBObject())) {
        _returnTo('cat_view' , _DML_NOLOG_DOWNLOAD, $data->catid);
    }

    // If the document is not approved, redirect
    if (!$data->approved AND !$_DMUSER->canApprove()) {
        _returnTo('cat_view' , _DML_NOAPPROVED_DOWNLOAD , $data->catid);
    }

    // If the document is not published, redirect
    if (!$data->published AND !$_DMUSER->canPublish()) {
        _returnTo('cat_view' , _DML_NOPUBLISHED_DOWNLOAD , $data->catid);
    }

     // if the document is checked out, redirect
    if ($data->checked_out && $_DMUSER->userid <> $data->checked_out) {
       	_returnTo('cat_view' , _DML_NOTDOWN, $data->catid);
    }

    // If the remote host is not allowed, show anti-leech message and die.
	if (!DOCMAN_Utils::checkDomainAuthorization())
	{
		$from_url = parse_url($_SERVER['HTTP_REFERER']);
        $from_host = trim($from_url['host']);

		_returnTo('cat_view' , _DML_ANTILEECH_ACTIVE . " (".$from_host.")", $data->catid);
		exit();
	}

	/* ------------------------------ *
	 *   GET FILE 					  *
	 * ------------------------------ */

    $file = new DOCMAN_File($data->dmfilename, $_DOCMAN->getCfg('dmpath'));

    // If the file doesn't exist, redirect
    if ( !$file->exists() ) {
        _returnTo('cat_view' , _DML_FILE_UNAVAILABLE , $data->catid);
    }


    /* ------------------------------ *
	 *   MAMBOT - Setup All Mambots   *
	 * ------------------------------ */

	$doc_dbo = $doc->getDBObject(); //Fix for PHP 5

    $logbot  = new DOCMAN_mambot('onLog');
    $prebot  = new DOCMAN_mambot('onBeforeDownload');
    $postbot = new DOCMAN_mambot('onAfterDownload');
    $logbot->setParm('document' , $doc_dbo);
    $logbot->setParm('file' , $file);
    $logbot->setParm('user' , $_DMUSER);
    $logbot->copyParm('process' , 'download');
    $prebot->setParmArray($logbot->getParm()); // Copy the parms over
    $postbot->setParmArray($logbot->getParm());

    /* ------------------------------ *
	 *   MAMBOT - PREDOWNLOAD         *
	 * ------------------------------ */
    $prebot->trigger();
    if ($prebot->getError()) {
        $logbot->copyParm('msg' , $prebot->getErrorMsg());
        $logbot->copyParm('status' , 'LOG_ERROR');
        $logbot->trigger();
        _returnTo('cat_view', $prebot->getErrorMsg());
    }

    // let's increment the counter
    $dbobject = $doc->getDBObject();
    $dbobject->incrementCounter();

    // place an entry in the log
    if ($_DOCMAN->getCfg('log')) {
        require_once( $_DOCMAN->getPath( 'classes', 'jbrowser' ) );
        $browser = & JBrowser::getInstance($_SERVER['HTTP_USER_AGENT']);

        $now = date("Y-m-d H:i:s", time("Y-m-d g:i:s") + $mosConfig_offset * 60 * 60);
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $row_log = new mosDMLog($database);
        $row_log->log_docid = $data->id;
        $row_log->log_ip = $remote_ip;
        $row_log->log_datetime = $now;
        $row_log->log_user = $_DMUSER->userid;
        $row_log->log_browser = $browser->getBrowser();
        $row_log->log_os = $browser->getPlatform();
        if (!$row_log->store()) {
            exit();
        }
    }
    $logbot->copyParm(array('msg' => 'Download Complete' , 'status' => 'LOG_OK'));
    $logbot->trigger();
    $file->download($inline);

    /* ------------------------------ *
	 *   MAMBOT - PostDownload        *
	 * Currently - we die and no out  *
	 * ------------------------------ */
    $postbot->trigger();
    /* if( $postbot->getError() ){
	*		$logbot->copyParm( array(	'msg'	=> $postbot->getErrorMsg() ,
	*			 			  			'status'=> 'LOG_ERROR'
	*								)
	*						);
	*		$logbot->trigger();
	*		_returnTo('cat_view',$postbot->getErrorMsg() );
	*}
	*/

    die; // REQUIRED
}

