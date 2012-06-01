<?php
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'controller.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'controllers'.DS.'file.php');

define('COM_MEDIA_BASE', JPATH_ADMINISTRATOR . DS . 'components' . DS . strtolower(JRequest::getWord('option')));

class TtaDbMediaHelper extends MediaControllerFile {
	
}