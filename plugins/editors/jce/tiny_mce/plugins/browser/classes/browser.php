<?php
/**
 * Browser Class.
 * @author Ryan Demmer $
 * @version browser.php 2009-02-15 $
 */

class Browser extends Manager{
	/* 
	* @var string
	*/
	var $_ext = 'xml=xml;html=htm,html;word=doc,docx;powerpoint=ppt;excel=xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz;flash=swf;winrar=rar;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf';	
	
	/**
	* @access	protected
	*/
	function __construct(){		
		// Call parent
		parent::__construct();
		if( JRequest::getVar( 'type', 'file' ) == 'file'){
			$this->setFileTypes( $this->getPluginParam( 'browser_extensions', $this->_ext ) );
		}else{
			$this->setFileTypes( 'image=jpg,jpeg,png,gif' );
		}		
		$this->init();
	}
	/**
	 * Returns a reference to a editor object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $browser = &Browser::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JCE  The editor object.
	 * @since	1.5
	 */
	function &getInstance(){
		static $instance;

		if ( !is_object( $instance ) ){
			$instance = new Browser();
		}
		return $instance;
	}
	function reInit( $manager ){
		$ext = 'xml=xml;html=htm,html;word=doc,docx;powerpoint=ppt;excel=xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz;flash=swf;winrar=rar;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf';	
		
		if( JRequest::getVar( 'type', 'file' ) == 'file'){
			$manager->setFileTypes( $manager->getPluginParam( 'browser_extensions', $ext ) );
		}else{
			$manager->setFileTypes( 'image=jpg,jpeg,png,gif' );
		}
	}
	function getViewable(){
		return $this->getPluginParam( 'browser_extensions_viewable', 'html,htm,doc,docx,ppt,rtf,xls,txt,gif,jpeg,jpg,png,pdf,swf,mov,mpeg,mpg,avi,asf,asx,dcr,flv,wmv,wav,mp3' );
	}
}
?>