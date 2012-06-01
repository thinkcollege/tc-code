<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @copyright	Copyright (C) 2008 - 2009 Ryan Demmer. All rights reserved.
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * Based on and contains some methods in tinymce.php in plugins/editors
 */

// Do not allow direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * JCE WYSIWYG Editor Plugin
 *
 * @author Ryan Demmer <ryandemmer@gmail.com>
 * @package Editor - JCE
 * @since 1.5
 */
class plgEditorJCE extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgEditorJCE(& $subject, $config){
		parent::__construct($subject, $config);
	}

	/**
	 * Method to handle the onInit event.
	 *  - Initializes the JCE WYSIWYG Editor
	 *
	 * @access public
	 * @return string JavaScript Initialization string
	 * @since 1.5
	 */
	function onInit(){
		global $mainframe;
			
		// Editor gets loaded twice in Legacy mode???
		if( defined( '_JCE_ISLOADED' ) ){
			return false;
		}
		define( '_JCE_ISLOADED', 1 );
		
		if( !is_dir( JPATH_SITE .DS. 'components' .DS. 'com_jce' ) || !is_dir( JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce' ) ){
			JError::raiseWarning( '404', 'The JCE Administration Component is not installed! The Editor cannot function without it!' );
		}
		require_once( dirname( __FILE__ ) .DS. 'jce' .DS. 'libraries' .DS. 'classes' .DS. 'editor.php'  );
		
		$jce 		=& JContentEditor::getInstance();
        
        $document 	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		       	
		$params 	= $jce->getEditorParams();
		
		$gzip		= $jce->getParam( $params, 'editor_gzip', '0', '0' ) ? '_gzip' : '';
		
		// TinyMCE url must be absolute!
		$document->addScript( JURI::root() . 'plugins/editors/jce/tiny_mce/tiny_mce'. $gzip .'.js?version='. $jce->getVersion() );
		$document->addScript( JURI::root(true) . '/plugins/editors/jce/libraries/js/editor.js?version='. $jce->getVersion() );
		// Set parameter array
		$param 	= array();
		
		//Languages
		$param['language'] 			= $jce->getLanguage();
		$param['directionality'] 	= $jce->getLanguageDir();
		
		$settings = '';
		
		if( $jce->checkUser() ){	
			//Url
			$param['document_base_url'] 					= JURI::root();
			$param['site_url'] 								= JURI::base(true) . '/';
			
			//$param['convert_urls']							= $jce->getParam( $params, 'editor_convert_urls', '0', '1' );
			
			//Theme
			$param['theme_advanced_toolbar_location'] 		= $jce->getParam( $params, 'editor_theme_advanced_toolbar_location', 'top', 'bottom' );
			$param['theme_advanced_toolbar_align'] 			= $jce->getParam( $params, 'editor_theme_advanced_toolbar_align', 'left', 'center' );
			$param['theme_advanced_path']					= '1';
			$param['theme_advanced_statusbar_location'] 	= $jce->getParam( $params, 'editor_theme_advanced_statusbar_location', 'bottom', 'none' );
			
			$param['theme_advanced_resizing']	    		= $jce->getParam( $params, 'editor_theme_advanced_resizing', '1', '0' );
			$param['theme_advanced_resize_horizontal']	    = $jce->getParam( $params, 'editor_theme_advanced_resize_horizontal', '1', '0' );
			$param['theme_advanced_resizing_use_cookie']	= $jce->getParam( $params, 'editor_theme_advanced_resizing_use_cookie', '1', '0' );
			
			$param['theme_advanced_disable'] 				= $jce->getRemovePlugins();
			$param['theme_advanced_blockformats'] 			= $jce->getParam( $params, 'editor_theme_advanced_blockformats', 'p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp,pre', 'p,address,pre,h1,h2,h3,h4,h5,h6' );
			
			$param['theme_advanced_fonts']					= $jce->getEditorFonts( $jce->getParam( $params, 'editor_theme_advanced_fonts_add', '' ), $jce->getParam( $params, 'editor_theme_advanced_fonts_remove', '' ) );	
			
			//$param['font_size_classes'] 					= $jce->getParam( $params, 'editor_font_size_classes', '' );
			$param['font_size_style_values'] 				= $jce->getParam( $params, 'editor_font_size_style_values', '8pt,10pt,12pt,14pt,18pt,24pt,36pt' );
			
			// Defaults
			$param['theme_advanced_buttons1']				= '';
			$param['theme_advanced_buttons2']				= '';
			$param['theme_advanced_buttons3']				= '';
			
			$rows = $jce->getRows();
			for( $i=1; $i<=count( $rows ); $i++ ){
				$param['theme_advanced_buttons'. $i] = $rows[$i];
			}
			
			$param['verify_html']							= $jce->getParam( $params, 'editor_verify_html', '0', '1' );	
			$param['event_elements']	    				= $jce->getParam( $params, 'editor_event_elements', 'a,img', 'a,img' );
			
			$param['width']	    							= $jce->getParam( $params, 'editor_width', '' );
			$param['height']	    						= $jce->getParam( $params, 'editor_height', '' );
			
			$param['plugin_preview_width']	    			= $jce->getParam( $params, 'preview_width', '750', '550' );
			$param['plugin_preview_height']	    			= $jce->getParam( $params, 'preview_height', '550', '600' );
					
			
			$param['custom_colors']	    					= $jce->getParam( $params, 'custom_colors', '', '' );
					
			$param['table_inline_editing']	    			= '1';
			$param['fix_list_elements']	    				= '1';
			$param['fix_table_elements']	    			= '1';
			
			// Encoding
			$param['entity_encoding'] 						= $jce->getParam( $params, 'editor_entity_encoding', 'raw', 'named' );		
			
			//Default template url
			$template_path 	= JPATH_THEMES .DS. $jce->getSiteTemplate() .DS. 'css';
			$template_url 	= JURI::root(true) . "/templates/" . $jce->getSiteTemplate() . "/css/";
			// Joomla! 1.5 standard
			$template_file	= 'template.css';
			// check for legacy template
			if( file_exists( $template_path .DS. 'template_css.css' ) ){
				$template_file	= 'template_css.css';
			}
			$param['content_css'] = $template_url . $template_file;
			
			//Custom template url
			if( $params->get( 'editor_content_css', '1' ) == '0' ){
				$custom = $params->get( 'editor_content_css_custom', '' );
				$param['content_css'] = JURI::root(true) . '/' . str_replace( '$template', $jce->getSiteTemplate(), $custom );
			}				
			$elements = $jce->getElements(); 
			// Configuration list of invalid elements as array
			$invalid_elements = explode( ',', $jce->getParam( $params, 'editor_invalid_elements', '', '' ) );
			
			// Add elements to invalid list (removed by plugin)
			$jce->addKeys( $invalid_elements, array( 'applet', 'iframe', 'object', 'param', 'embed', 'script' ) );

			/* Plugin specific settings
			 * TODO : Move all out of this file to plugin functions called if plugin loaded
			 */
			 
			// Code
			$param['code_php'] 			= $params->get( 'editor_allow_php', '0' );
			$param['code_javascript'] 	= $params->get( 'editor_allow_javascript', '0' );
			$param['code_css'] 			= $params->get( 'editor_allow_css', '0' );
						
			//Paste
			if( $jce->isLoaded( 'paste' ) ){
				$paste_params = $jce->getPluginParams( 'paste' );
				
				$param['paste_create_paragraphs'] 		= $jce->getParam( $paste_params, 'paste_create_paragraphs', '1', '1' );
				$param['paste_create_linebreaks']		= $jce->getParam( $paste_params, 'paste_create_linebreaks', '1', '1' );
				$param['paste_use_dialog'] 				= $jce->getParam( $paste_params, 'paste_use_dialog', '0', '0' );
				$param['paste_auto_cleanup_on_paste'] 	= $jce->getParam( $paste_params, 'paste_auto_cleanup_on_paste', '0', '0' );
				$param['paste_strip_class_attributes'] 	= $jce->getParam( $paste_params, 'paste_strip_class_attributes', 'all', 'all' );
				$param['paste_remove_spans'] 			= $jce->getParam( $paste_params, 'paste_remove_spans', '1', '1' );
				$param['paste_remove_styles'] 			= $jce->getParam( $paste_params, 'paste_remove_styles', '1', '1' );
			}
			if( $jce->isLoaded( 'media' ) || $jce->isLoaded( 'mediamanager' ) ){
				$media_params = $jce->getPluginParams( 'media' );		
				// Media parameters
				if( $jce->getParam( $media_params, 'media_use_script', '0', '0' ) ){
					$param['media_use_script']	= '1';
					$param['code_javascript'] 	= '1';
				}else{
					$jce->removeKeys( $invalid_elements, array( 'object', 'param', 'embed' ) );
				}
				$param['media_version_flash'] 			= $jce->getParam( $media_params, 'media_version_flash', '9,0,124,0', '9,0,124,0' );
				$param['media_version_shockwave'] 		= $jce->getParam( $media_params, 'media_version_shockwave', '11,0,0,458', '11,0,0,458' );
				$param['media_version_windowsmedia'] 	= $jce->getParam( $media_params, 'media_version_windowsmedia', '5,1,52,701', '5,1,52,701' );
				$param['media_version_quicktime'] 		= $jce->getParam( $media_params, 'media_version_quicktime', '6,0,2,0', '6,0,2,0' );
				$param['media_version_reallpayer'] 		= $jce->getParam( $media_params, 'media_version_reallpayer', '7,0,0,0', '7,0,0,0' );
				
				if( !$jce->isLoaded( 'media' ) ){
					$plugins[] = 'media';
				}
			}
			
			// IFrame
			if( $jce->isLoaded( 'iframe' ) ){
				$jce->removeKeys( $invalid_elements, 'iframe' );
			}
			// Invalid Elements
			if( $param['code_javascript'] == 0 ){
				$jce->addKeys( $invalid_elements, 'script' );
			}else{
				$jce->removeKeys( $invalid_elements, 'script' );
			}
				
			//Template Manager
			if( $jce->isLoaded( 'templatemanager' ) ){
				$tpl_params = $jce->getPluginParams( 'templatemanager' );
				$rv = $jce->getParam( $tpl_params, 'template_replace_values', '', '' );
				if( strpos( $rv, ',' ) == strlen( $rv ) ){
					$rv = substr( $rv, 0, -1 );
				}
				$rv = str_replace( array( '$username', '$usertype', '$name', '$email' ), array( $user->username, $user->usertype, $user->name, $user->email ), $rv );
				$param['templatemanager_replace_values'] 			= !$rv ? '' : "{" . $rv . "}";
				$param['templatemanager_selected_content_classes']	= $jce->getParam( $tpl_params, 'templatemanager_selected_content_classes', '' );
				$param['templatemanager_cdate_classes']			= $jce->getParam( $tpl_params, 'templatemanager_cdate_classes', 'cdate creationdate', 'cdate creationdate' );
				$param['templatemanager_mdate_classes']			= $jce->getParam( $tpl_params, 'templatemanager_mdate_classes', 'mdate modifieddate', 'mdate modifieddate' );
				$param['templatemanager_cdate_format']				= $jce->getParam( $tpl_params, 'templatemanager_cdate_format', '%m/%d/%Y : %H:%M:%S', '%m/%d/%Y : %H:%M:%S' );
				$param['templatemanager_mdate_format']				= $jce->getParam( $tpl_params, 'templatemanager_mdate_format', '%m/%d/%Y : %H:%M:%S', '%m/%d/%Y : %H:%M:%S' );
			}
			
			//Spellchecker
			$spell_params = $jce->getPluginParams( 'spellchecker' );
			$param['spellchecker_languages'] = '+' . $jce->getParam( $spell_params, 'spellchecker_languages', 'English=en', '' );
			
			/* End Plugin functions */

			// Paragraph handling
			$param['forced_root_block']	= $jce->getParam( $params, 'editor_forced_root_block', '0', 'p' );
			
			if( $params->get( 'editor_newlines', '0' ) == '1' ){
				$param['force_br_newlines'] = '1';
				$param['force_p_newlines'] 	= '0';
			}else{
				$param['force_br_newlines'] = '0';
				$param['force_p_newlines'] 	= '1';
			}
			
			//Elements
			$param['invalid_elements'] 			= implode( ',', $invalid_elements );
			$param['extended_valid_elements'] 	= $elements;					
			$param['plugins'] 					= implode( ',', array_merge( $jce->getPlugins(),  array('code') ) );			
			
			// 'Look & Feel'
			$param['skin']						= $jce->getParam( $params, 'editor_skin', 'default', 'default' );
			$param['skin_variant']				= $jce->getParam( $params, 'editor_skin_variant', 'default', 'default' );
			$param['inlinepopups_skin'] 		= $jce->getParam( $params, 'editor_inlinepopups_skin', 'clearlooks2' );
			$param['body_class'] 				= $jce->getParam( $params, 'editor_body_class_type', 'custom' ) == 'contrast' ? 'mceForceColors' : $jce->getParam( $params, 'body_class_custom', '' ); 
			
			//Other - user specified
			$userParams 		= $params->get( 'editor_custom_config', '' );
			$baseParams 		= array(
				'mode', 
				'cleanup_callback', 
				'save_callback', 
				'file_browser_callback',
				'onpageload',
				'editor_selector'
			);
			if( $userParams ){
				$userParams = explode( ';', $userParams );
				foreach( $userParams as $userParam ){
					$keys = explode( ':', $userParam );
					if( !in_array( trim( $keys[0] ), $baseParams ) ){
						$param[trim( $keys[0] )] = trim( $keys[1] );
					}
				}
			}
			$callbackFile = $params->get( 'editor_callback_file', '' );
			
			// Relative urls?
			$param['relative_urls'] = $jce->getParam( $params, 'editor_relative_urls', '1', '1' );
			if( $param['relative_urls'] == '0' ){
				$param['remove_script_host'] = '0';
			}
			foreach( $param as $k => $v ){
				if( $v != ''){
					// objects or arrays or functions
					if( preg_match('/(\[[^\]*]\]|\{[^\}]*\}|function\([^\}]*\})/', $v ) ){
						$v = $v;
					// anything that is not solely an integer
					}else if( !is_numeric( $v ) ){
						$v = '"'. $v .'"';
					// 1 or 0 become true/false
					}else if( $v == '1' || $v == '0' ){
						$v = intval( $v ) ? 'true' : 'false';
					}
					$settings .= "\t\t\t". $k .": ". $v .",\n";
				}
				if( preg_match('/theme_advanced_buttons([1-3])/', $k ) && $v == '' ){
					$settings .= "\t\t\t". $k .": \"\",\n";
				}
			}
			$theme = 'advanced';
		}else{
			$params->set( 'editor_toggle', '0' );
			$param['invalid_elements'] 	= 'applet, iframe, object, param, embed, script, style';
			$param['plugins'] 			= '';
			$theme = 'none';
		}
		$gz = "
		tinyMCE_GZ.init({
			plugins : '". $param['plugins'] ."',
			themes : 'none,advanced',
			languages : '". $param['language'] ."',
			disk_cache : false
		});";
		$init = "
		tinyMCE.init({
			doctype: '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">',
			mode: 'textareas',
			theme: '". $theme ."',
			editor_selector: 'mceEditor',\n";
			$init .= $settings;
			$init .= "\t\t\tonpageload: 'jceOnLoad',
			cleanup_callback: 'jceCleanup',
			save_callback: 'jceSave',
			file_browser_callback: 'jceBrowser'
		});
		JContentEditor.set({
			pluginmode 	: ". $params->get( 'editor_pluginmode', '0' ) .",
			state 		: '". $params->get( 'editor_state', 'mceEditor' ) ."',
			allowToggle : ". $params->get( 'editor_toggle', '1' ) .",
			toggleText 	: '". $jce->xmlEncode( $params->get( 'editor_toggle_text', '[show/hide]' ) ) ."'
		});
		function jceSave(id, html, body){
			return JContentEditor.save(html);
		};
		function jceCleanup(type, value){
			return JContentEditor.cleanup(type, value);
		};
		function jceBrowser(name, url, type, win){
			return JContentEditor.browser(name, url, type, win);
		};";
		if( $gzip ){
			$document->addScriptDeclaration( $gz );
		}
		$document->addScriptDeclaration( $init );
		if( $params->get( 'editor_callback_file' ) ){
			$document->addScript( JURI::root( true ) .'/'. $callbackFile );
		}
	}

	/**
	 * JCE WYSIWYG Editor - get the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function onGetContent( $editor ) {
		return "JContentEditor.getContent('".$editor."');";
	}

	/**
	 * JCE WYSIWYG Editor - set the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function onSetContent( $editor, $html ) {
		return "tinyMCE.activeEditor.setContent(".$html.");";
	}

	/**
	 * JCE WYSIWYG Editor - copy editor content to form field
	 *
	 * @param string 	The name of the editor
	 */
	function onSave( $editor ) {
		return "tinyMCE.triggerSave();";
	}

	/**
	 * JCE WYSIWYG Editor - display the editor
	 *
	 * @param string The name of the editor area
	 * @param string The content of the field
	 * @param string The width of the editor area
	 * @param string The height of the editor area
	 * @param int The number of columns for the editor area
	 * @param int The number of rows for the editor area
	 * @param mixed Can be boolean or array.
	 */
	function onDisplay( $name, $content, $width, $height, $col, $row, $buttons = true)
	{
		// Only add "px" to width and height if they are not given as a percentage
		if (is_numeric( $width )) {
			$width .= 'px';
		}
		if (is_numeric( $height )) {
			$height .= 'px';
		}

		$buttons = $this->_displayButtons($name, $buttons);
		$editor = "<div id=\"jce_editor_".$name."_toggle\"></div>";
		$editor .= "<textarea id=\"$name\" name=\"$name\" cols=\"$col\" rows=\"$row\" style=\"width:{$width}; height:{$height};\" class=\"mceEditor\">$content</textarea>\n" . $buttons;
		$editor .= "<script type=\"text/javascript\">function jceOnLoad(){JContentEditor.initEditorMode('$name');}</script>";
		
		return $editor;
	}

	function onGetInsertMethod($name)
	{
		$doc = & JFactory::getDocument();

		$js= "function jInsertEditorText( text, editor ) {
			tinyMCE.execInstanceCommand(editor, 'mceInsertContent',false,text);
		}";
		$doc->addScriptDeclaration($js);

		return true;
	}

	function _displayButtons($name, $buttons)
	{
		// Load modal popup behavior
		JHTML::_('behavior.modal', 'a.modal-button');

		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->update($args);
		foreach ($results as $result) {
			if (is_string($result) && trim($result)) {
				$return .= $result;
			}
		}

		if(!empty($buttons))
		{
			$results = $this->_subject->getButtons($name, $buttons);

			/*
			 * This will allow plugins to attach buttons or change the behavior on the fly using AJAX
			 */
			$return .= "\n<div id=\"editor-xtd-buttons\">\n";
			foreach ($results as $button)
			{
				/*
				 * Results should be an object
				 */
				if ( $button->get('name') )
				{
					$modal		= ($button->get('modal')) ? 'class="modal-button"' : null;
					$href		= ($button->get('link')) ? 'href="'.JURI::base().$button->get('link').'"' : null;
					$onclick	= ($button->get('onclick')) ? 'onclick="'.$button->get('onclick').'"' : null;
					$return .= "<div class=\"button2-left\"><div class=\"".$button->get('name')."\"><a ".$modal." title=\"".$button->get('text')."\" ".$href." ".$onclick." rel=\"".$button->get('options')."\">".$button->get('text')."</a></div></div>\n";
				}
			}
			$return .= "</div>\n";
		}

		return $return;
	}
}
?>