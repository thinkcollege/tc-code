<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class Caption extends JContentEditorPlugin {
	/**
	* Constructor activating the default information of the class
	*
	* @access	protected
	*/
	function __construct(){
		parent::__construct();
						
		// Set javascript file array
		$this->script( array( 'tiny_mce_popup' ), 'tiny_mce' );
		$this->script( array( 
			'mootools',
			'tiny_mce_utils',
			'jce',
			'plugin',
			'window'
		) );
		$this->script( array( 'caption' ), 'plugins' );
		$this->css( array( 'plugin' ), 'libraries' );
		$this->css( array( 'caption' ), 'plugins' );
		$this->css( array( 
			'window',
			'dialog'
		), 'skins' );
		$this->loadLanguages();
	}
	/**
	 * Returns a reference to a plugin object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $advlink = &AdvLink::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JCE  The editor object.
	 * @since	1.5
	 */
	function &getInstance(){
		static $instance;

		if ( !is_object( $instance ) ){
			$instance = new Caption();
		}
		return $instance;
	}
}