<?php
/**
 * @copyright Copyright &copy; 2010, QIUHAO
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author QIUHAO
 * @link http://www.aijoomla.com
 */

 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.plugin.plugin');

 class PlgContentSAudioPlayer extends JPlugin {
 	/**
 	 * prepare content method
 	 * 
 	 * @access public
 	 * @param $article object	the article object
 	 * @param $params object	the article params
 	 */
	function onPrepareContent(&$article,&$params) {
		$this->_replace_player($article);
		return true;
	}

	/**
	 * replace flag
	 * 
	 * @access private
	 * @param $article object	the article object
	 * @return boolean
	 */
	function _replace_player($article) {
		$ereg = '/{saudioplayer(\s+autostart)?}(.+\.mp3){\/saudioplayer}/iU';
		if(preg_match_all($ereg,$article->text,$matches,PREG_SET_ORDER)) {
			//get plugin parameters
			$plugin = & JPluginHelper::getPlugin('content','saudioplayer');
			$params = new JParameter($plugin->params);
			$default_folder = $params->get('default_folder');
			$default_width = $params->get('default_width');
			$default_height = $params->get('default_height');
			$default_background_color = $params->get('default_background_color');
			
			//replace flag
			$loop_count = 1;
			foreach($matches as $match) {
				$mp3_file = JURI::base() . $default_folder . '/' . $match[2];
				$player_file = JURI::base() . 'plugins/content/saudioplayer/niftyplayer.swf';
				if($match[1]) {
					$auto_start = '&as=1';
				} else {
					$auto_start = '';
				}
				$play_code = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="' . $default_width . '" height="' . $default_height . '" id="niftyPlayer' . $loop_count . '">
								<param name="movie" value="' . $player_file . '?file=' . $mp3_file . $auto_start . '" />
								<param name="quality" value="high" />
								<param name="bgcolor" value="'. $default_background_color . '" />
								<embed src="' . $player_file . '?file=' . $mp3_file . $auto_start;
				$play_code .= '" quality="high" bgcolor="' . $default_background_color . '" width="' . $default_width .'" height="' . $default_height . '" name="niftyPlayer' . $loop_count . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
								</embed>
							</object>';
				$flag_pos = JString::strpos($article->text,$match[0]);
				$article->text = JString::substr_replace($article->text,$play_code,$flag_pos,JString::strlen($match[0]));
				$loop_count ++;
			}
			return true;
		} else {
			return false;
		}
	} //end function
 }
