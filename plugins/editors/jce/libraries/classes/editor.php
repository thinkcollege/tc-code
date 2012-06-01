<?php
/**
 * @version		editor.php 2009-02-16 
 * @package		JCE
 * @copyright	Copyright (C) 2005 - 2007 Ryan Demmer. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * JCE class
 *
 * @static
 * @package		JCE
 * @since	1.5
 */

class JContentEditor extends JObject{
	/*
	*  @var varchar
	*/
	var $_version = '152';
	/*
	*  @var varchar
	*/
	var $_site_url = null;
	/*
	*  @var varchar
	*/
	var $_group = null;
	/*
	 *  @var object
	 */
	var $_params = null;
	/*
	*  @var varchar
	*/
	var $_url = array();
	/*
	*  @var varchar
	*/
	var $_request = null;
	/*
	*  @var array
	*/
	var $_scripts = array();
	/*
	*  @var array
	*/
	var $_css = array();
	/*
	*  @var boolean
	*/
	var $_debug = false;
	/**
	* Constructor activating the default information of the class
	*
	* @access	protected
	*/
	function __construct(){
		global $mainframe;
		
		//$db		=& JFactory::getDBO();
		//$user 	=& JFactory::getUser();	              
		
		/*$this->_usertype 	= strtolower( $user->usertype );
        $this->_username 	= $user->username;
		
		$this->_gid 	= $user->gid;		
		$this->_id 		= $user->id;*/
		
		// Get user group
		$this->_group 	= $this->getUserGroup();
		// Get editor and group params
		$this->_params	= $this->getEditorParams();
	}
	/**
	 * Returns a reference to a editor object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $browser = &JContentEditor::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JCE  The editor object.
	 * @since	1.5
	 */
	function &getInstance(){
		static $instance;

		if ( !is_object( $instance ) ){
			$instance = new JContentEditor();
		}
		return $instance;
	}
	function getVersion(){
		return $this->_version;
	}
	/**
	 * Get the current users group if any
	 *
	 * @access public
	 * @return group or false
	*/
	function getUserGroup(){
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$option = JRequest::getCmd( 'option' );
		
		if( $this->_group ){
			return $this->_group;
		}
		$query = 'SELECT *'
		. ' FROM #__jce_groups'
		. ' WHERE published = 1'
		. ' ORDER BY ordering ASC'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		
		foreach( $groups as $group ){
			$components = ( $option == 'com_jce' ) ? true : in_array( $option, explode(',', $group->components ) );

			// Check user			
			if( in_array( $user->id, explode(',', $group->users ) ) ){
				// Check components
				if( $group->components ){
					if( $components ){
						return $group;
					}
				}else{
					return $group;
				}
			}
			// Check usertype
			if( in_array( $user->gid, explode(',', $group->types ) ) ){
				// Check components
				if( $group->components ){
					if( $components ){
						return $group;
					}
				}else{
					return $group;
				}
			}
			// Check components only
			if( $group->components && $components ){
				return $group;
			}
		}
		return null;
	}
	/**
	 * Get the Super Administrator status
	 *
	 * Determine whether the user is a Super Administrator
	 *
	 * @access public
	 * @return boolean
	*/
	function isSuperAdmin(){
		$user =& JFactory::getUser();
		return ( strtolower( $user->usertype ) == 'superadministrator' || strtolower( $user->usertype ) == 'super administrator' || $user->gid == 25 ) ? true : false;	
    }
	function filterParams( $params, $key ){
		$params = explode( "\n", $params );					
		$return = array();
		
		foreach( $params as $param ){
			if( eregi( $key, $param ) ){
				$return[] = $param;
			}
		}
		return implode( "\n", $return );
	}
	/**
	 * Return the JCE Editor's parameters
	 *
	 * @access public
	 * @return object
	*/
	function getEditorParams(){		
		$db	=& JFactory::getDBO();
		
		if( isset( $this->_params ) ){
			return $this->_params;
		}
		
		$e_params = '';
		$g_params = '';
		
		$query = 'SELECT params FROM #__plugins'
		. ' WHERE element = '. $db->Quote('jce')
		. ' AND folder = '. $db->Quote('editors')
		. ' AND published = 1' 
		. ' LIMIT 1'
		;
		$db->setQuery( $query );
		
		$e_params = $db->loadResult();
		// check if group params available
		if( $this->_group ){
			$g_params = $this->filterParams( $this->_group->params, 'editor' );
		}

		return new JParameter( $e_params . $g_params );
	}
	/**
	 * Return an Editor parameter
	 *
	 * @access public
	 * @return object
	*/
	function getEditorParam( $key, $default='' ){		
		$params = $this->getEditorParams();
		return $this->cleanParam( $params->get( $key, $default ) );
	}
	/**
	 * Return the plugin parameter object
	 *
	 * @access 			public
	 * @param string	The plugin
	 * @return 			The parameter object
	*/
	function getPluginParams( $plugin ){						
		$params = '';
		if( $this->_group ){
			$params = $this->filterParams( $this->_group->params, $plugin );
		}				
		return new JParameter( $params );
	}
	/**
	 * Return a list of published JCE plugins
	 *
	 * @access public
	 * @return string list
	*/
	function getPlugins( $extra=array() ){		
		$db	=& JFactory::getDBO();
		
		if( $this->_group ){			
			// Load other plugins not included in Groups
			/*$query = "SELECT name"
			. " FROM #__jce_plugins"
			. " WHERE published = 1"
			. " AND type = 'plugin'"
			. " AND row = 0"
			. " AND editable = 0"
			;
			if( !$pseudo = $this->_query( $query, 'loadResultArray' ) ){
				$pseudo = array();
			}*/
			
			$query = "SELECT name"
			. " FROM #__jce_plugins"
			. " WHERE published = 1"
			. " AND type = 'plugin'"
			. " AND id IN (". $this->_group->plugins. ")"
			;
			
			$db->setQuery( $query );
			
			if( !$plugins = $db->loadResultArray() ){
				$plugins = array();
			}
			
			//$plugins = array_merge( $group, $pseudo );
		}else{
			$query = "SELECT name"
			. " FROM #__jce_plugins"
			. " WHERE published = 1"
			. " AND type = 'plugin'"
			;
			
			$db->setQuery( $query );
			
			if( !$plugins = $db->loadResultArray() ){
				$plugins = array();
			}
		}
		//$plugins = array_merge( $plugins, $extra );
       // return implode( ',', $plugins );
	   return $plugins = array_merge( $plugins, $extra );
	}
	/**
	 * Return a list of font familys
	 *
	 * @access public
	 * @return string list
	*/
	function getEditorFonts( $add, $remove ){
		
		$add 	= explode( ';', $this->getEditorParam( 'editor_theme_advanced_fonts_add', '' ) );
		$remove = preg_split( '/[;,]+/', $this->getEditorParam( 'editor_theme_advanced_fonts_remove', '' ) );
		
		// Default font list
		$fonts = array(
		'Andale Mono=andale mono,times',
		'Arial=arial,helvetica,sans-serif',
		'Arial Black=arial black,avant garde',
		'Book Antiqua=book antiqua,palatino',
		'Comic Sans MS=comic sans ms,sans-serif',
		'Courier New=courier new,courier',
		'Georgia=georgia,palatino',
		'Helvetica=helvetica',
		'Impact=impact,chicago',
		'Symbol=symbol',
		'Tahoma=tahoma,arial,helvetica,sans-serif',
		'Terminal=terminal,monaco',
		'Times New Roman=times new roman,times',
		'Trebuchet MS=trebuchet ms,geneva',
		'Verdana=verdana,geneva',
		'Webdings=webdings',
		'Wingdings=wingdings,zapf dingbats'
		);
		
		if( count( $remove ) ){
			foreach( $fonts as $key => $value ){
				foreach( $remove as $gone ){
					if( $gone ){
						if( preg_match( '/^'. $gone .'=/i', $value ) ){
							// Remove family
							unset( $fonts[$key] );
						}
					}
				}
			}
		}
		foreach( $add as $new ){
		// Add new font family
			if( preg_match( '/([^\=]+)(\=)([^\=]+)/', trim( $new ) ) && !in_array( $new, $fonts ) ){
				$fonts[] = $new;
			}
		}
		natcasesort( $fonts );
		return implode( ';', $fonts );
	}
	/**
	 * Return the curernt language code
	 *
	 * @access public
	 * @return language code
	*/
	function getLanguageDir(){
		/* $language =& JFactory::getLanguage();
		return $language->isRTL() ? 'rtl' : 'ltr';
		We can only support ltr at them moment...!
		*/
		return 'ltr';
	}
	/**
	 * Return the curernt language code
	 *
	 * @access public
	 * @return language code
	*/
	function getLanguageTag(){
		$language =& JFactory::getLanguage();
		if( $language->isRTL() ){
			return 'en-GB';
		}
		return $language->getTag();
	}
	/**
	 * Return the curernt language code
	 *
	 * @access public
	 * @return language code
	*/
	function getLanguage(){
		$tag = $this->getLanguageTag();
		if( file_exists( JPATH_SITE .DS. 'language' .DS. $tag .DS. $tag .'.com_jce.xml' ) ){
			return substr( $tag, 0, strpos( $tag, '-' ) );
		}
		return 'en';
	}
	/**
	 * Load a language file
	 *
	 * @access public
	*/
	function loadLanguage( $prefix, $path = JPATH_SITE ){
		$language =& JFactory::getLanguage();		
		$language->load( $prefix, $path );
	}
	/**
	 * Return the current site template name
	 *
	 * @access public
	*/
	function getSiteTemplate(){
		$db =& JFactory::getDBO();
		
		$query = 'SELECT template'
		. ' FROM #__templates_menu'
		. ' WHERE client_id = 0'
		. ' AND menuid = 0'
		;
		
		$db->setQuery( $query );
		
		return $db->loadResult();
	}
	function getSkin(){
		return $this->_params->get('editor_inlinepopups_skin', 'clearlooks2');
	}
	/**
	 * Remove keys from an array
	 *
	 * @param array	    The array
	 * @param string	An array of keys to remove
	 * @access public
	*/
	function removeKeys( &$array, $keys ){
		if( !is_array( $keys ) ){
			$keys = array( $keys );
		}
		$array = array_diff( $array, $keys );
	}
	/**
	 * Add keys to an array
	 *
	 * @param string	The array
	 * @param string	The keys to add
	 * @access public
	 * @return The string list with added key or the key
	*/
	function addKeys( &$array, $keys ){
		if( !is_array( $keys ) ){
			$keys = array( $keys );
		}
		$array = array_unique( array_merge( $array, $keys ) );
	}
	/**
	 * Remove linebreaks and carriage returns from a parameter value
	 *
	 * @param string	The parameter value
	 * @access public
	 * @return The modified value
	*/
	function cleanParam( $param ){
		return trim( preg_replace( '/\n|\r|\t(\r\n)[\s]+/', '', $param ) );
	}
	/**
	 * Get a JCE editor or plugin parameter
	 *
	 * @param object	The parameter object
	 * @param string	The parameter object key
	 * @param string	The parameter default value
	 * @param string	The parameter default value
	 * @access public
	 * @return The parameter
	*/
	function getParam( $params, $key, $p, $t='' ){		
		$v = $this->cleanParam( $params->get( $key, $p ) );
		return ( $v == $t ) ? '' : $v;
	}
	/**
	 * Return a string of JCE Commands to be removed
	 *
	 * @access public
	 * @return The string list
	*/
	function getRemovePlugins(){
		$db =& JFactory::getDBO();
		
		$query = "SELECT name"
        . "\n FROM #__jce_plugins"
        . "\n WHERE type = 'command'"
		. "\n AND published = 0"
        ;
		
		$db->setQuery( $query );
		
		$remove = $db->loadResultArray();
		if( $remove ){
			return implode( ',', $remove );
		}else{
			return '';
		}
	}
	/**
	 * Return a list of icons for each JCE editor row
	 *
	 * @access public
	 * @param string	The number of rows
	 * @return The row array
	*/
	function getRows(){
		$db =& JFactory::getDBO();
		
		$rows 	= array();
		if( $this->_group ){
			// Get all plugins that are in the group rows list
			$query = "SELECT id, icon"
			. " FROM #__jce_plugins"
			. " WHERE published = 1"
			. " AND id IN (". str_replace( ';', ',', $this->_group->rows ) .")"
			;
			
			$db->setQuery( $query );
			
			$icons 	= $db->loadObjectList();			
			$lists 	= explode( ';', $this->_group->rows );
			
			if( $icons ){
				for( $i=1; $i<=count( $lists ); $i++ ){
					$x = $i - 1;
					$items = explode( ',', $lists[$x] );
					$result = array();
					// I'm sure you can use array_walk for this but I just can't figure out how!	
					foreach( $items as $item ){
						foreach( $icons as $icon ){
							if( $icon->id == $item ){
								$result[] = $icon->icon;
							}
						}		
					}
					$rows[$i] = implode(',', $result); 
				}
			}
		}else{	
			$num = intval( $this->_params->get( 'editor_layout_rows', '5' ) );
			for( $i=1; $i<=$num; $i++ ){
				$query = "SELECT icon"
				. " FROM #__jce_plugins"
				. " WHERE published = 1"
				. " AND row = ".$i
				. " ORDER BY ordering ASC"
				;
				
				$db->setQuery( $query );
				
				$result = $db->loadResultArray();
				if( $result ){
					$rows[$i] 	= implode( ',', $result );
				}
			}
		}
        return $rows;
	}
	/**
	 * Return a string of extended elements for a plugin
	 *
	 * @access public
	 * @return The string list
	*/
	function getElements(){			
		$db =& JFactory::getDBO();
		
		$jce_elements = explode( ',', $this->cleanParam( $this->_params->get( 'editor_extended_elements', '' ) ) );
		
		$query = "SELECT elements"
    	. "\n FROM #__jce_plugins"
    	. "\n WHERE elements != ''"
    	. "\n AND published = 1"
    	;
		
		$db->setQuery( $query );
		
		$plugin_elements = $db->loadResultArray();
		
		$elements = array_merge( $jce_elements, $plugin_elements );
		return implode( ',', $elements );		
	}
	/**
	 * Determine whether a plugin is loaded
	 *
	 * @access 			public
	 * @param string	The plugin
	 * @return 			boolean
	*/
	function isLoaded( $plugin ){		
		$db =& JFactory::getDBO();       
	   
	    $query = "SELECT id"
        . "\n FROM #__jce_plugins"
        . "\n WHERE name = '" . $plugin . "'"
		. "\n AND published = 1 LIMIT 1"
        ;
		
		$db->setQuery( $query );
		
		return $db->loadResult() ? true : false;
	}
	/**
	 * Named wrapper to check access to a feature
	 *
	 * @access 			public
	 * @param string	The feature to check, eg: upload
	 * @param string	The defalt value
	 * @return 			string
	*/
	function checkUser(){
		if( $this->_group ){
			return true;
		}
		return false;
	}
	/**
	 * XML encode a string.
	 *
	 * @access	public
	 * @param 	string	String to encode
	 * @return 	string	Encoded string
	*/
	function xmlEncode( $string ){
		return preg_replace( array( '/&/', '/</', '/>/', '/\'/', '/"/' ), array( '&amp;', '&lt;', '&gt;', '&apos;', '&quot;' ), $string );
	}
	/**
	 * XML decode a string.
	 *
	 * @access	public
	 * @param 	string	String to decode
	 * @return 	string	Decoded string
	*/
	function xmlDecode( $string ){
		return preg_replace( array( '&amp;', '&lt;', '&gt;', '&apos;', '&quot;' ), array( '/&/', '/</', '/>/', '/\'/', '/"/' ), $string );
	}
}
?>