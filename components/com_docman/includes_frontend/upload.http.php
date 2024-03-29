<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: upload.http.php 638 2008-03-01 12:49:09Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_METHOD_HTTP')) {
    return true;
} else {
    define('_DOCMAN_METHOD_HTTP' , 1);
}

include_once dirname(__FILE__) . '/upload.http.html.php';

class DMUploadMethod
{
    function fetchMethodForm($uid, $step, $update)
    {
        global $task;

        switch ($step)
        {
            case 2: // Input the filename (Form)
            {
                $lists = array();
                $lists['action'] = _taskLink($task, $uid, array('step' => $step + 1), false);
                return HTML_DMUploadMethod::uploadFileForm($lists);
            } break;

            case 3: // Process the file and edit the document
            {
                //upload file
                $file = mosGetParam(DOCMAN_Utils::stripslashes($_FILES), 'upload');

                $err  = DMUploadMethod::uploadFileProcess($uid, $step, $file);
                if($err['_error']) {
                	_returnTo($task, $err['_errmsg'], '', array('step' => $step - 1, 'method' => 'http'));
                }

   				$catid = $update ? 0 : $uid;
                $docid = $update ? $uid : 0;

   				return fetchEditDocumentForm($docid , $file->name, $catid);

            } break;

            default: break;
        }
        return true;
    }

    function uploadFileProcess($uid, $step, &$file)
	{
        DOCMAN_token::check() or die('Invalid Token');
        
  		global $_DMUSER, $_DOCMAN;

  		if ($file['name'] == '') {
            return array(
				'_error' => 1,
				'_errmsg'=> _DML_FILENAME_REQUIRED
         	);
        }

  		/* ------------------------------ *
     	 *   MAMBOT - Setup All Mambots   *
     	 * ------------------------------ */
    	$logbot = new DOCMAN_mambot('onLog');
   		$prebot = new DOCMAN_mambot('onBeforeUpload');
    	$postbot = new DOCMAN_mambot('onAfterUpload');

   		$logbot->setParm('filename' , $file['name']);
    	$logbot->setParm('user' , $_DMUSER);
    	$logbot->copyParm('process' , 'upload');
    	$prebot->setParmArray ($logbot->getParm()); // Copy the parms over
    	$postbot->setParmArray($logbot->getParm());

   		/* ------------------------------ *
     	*   Pre-upload                    *
     	* ------------------------------ */
    	$prebot->trigger();
    	if ($prebot->getError()) {
      		$logbot->setParm('msg' , $prebot->getErrorMsg());
        	$logbot->copyParm('status' , 'LOG_ERROR');
        	$logbot->trigger();

        	return array(
				'_error' => 1,
				'_errmsg'=> $prebot->getErrorMsg()
         	);
    	}

    	/* ------------------------------ *
     	*   Upload                        *
     	* ------------------------------ */

    	$path = $_DOCMAN->getCfg('dmpath');

   		//get file validation settings
   		if ($_DMUSER->isSpecial) {
      		$validate = _DM_VALIDATE_ADMIN;
   		} else {
     		if ($_DOCMAN->getCfg('user_all', false)) {
        		$validate = _DM_VALIDATE_ALL ;
      		} else {
           		$validate = _DM_VALIDATE_USER;
       		}
  		}

  		//upload the file
  		$upload = new DOCMAN_FileUpload();
  		$file = $upload->uploadHTTP($file, $path, $validate);

   		/* -------------------------------- *
	 	 *    Post-upload                   *
	 	 * -------------------------------- */

   		if (!$file) {
     		$linkOpt = array('step' => $step - 1, 'method' => 'http');
       		$msg = _DML_ERROR_UPLOADING . " - " . $upload->_err;
       		$logbot->setParm('file', $file['name']);
       		$logbot->setParm('msg' , $msg);
       		$logbot->copyParm('status' , 'LOG_ERROR');
       		$logbot->trigger();

       		return array(
				'_error' => 1,
				'_errmsg'=> $msg
         	);

   		}

       	$msg = "&quot;" . $file->name . "&quot; " . _DML_UPLOADED;

       	$logbot->copyParm(array('msg' => $msg ,'status' => 'LOG_OK'));
       	$logbot->trigger();

       	$postbot->setParm('file', $file->name);
       	$postbot->trigger();

       	if ($postbot->getError()) {
           	$logbot->setParm('msg' , $postbot->getErrorMsg());
            $logbot->copyParm('status' , 'LOG_ERROR');
            $logbot->trigger();

            return array(
				'_error' => 1,
				'_errmsg'=> $postbot->getErrorMsg()
         	);
       	}
        $linkOpt['step'] = $step + 1;

        return array (
			'_error' => 0,
			'_errmsg'=> $msg
        );
	}
}

