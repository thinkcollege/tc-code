<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Set as an extension parent
define( '_JCE_EXT', 1 );
/**
 * fileManager Class.
 * @author $Author: Ryan Demmer
 */
class FileManager extends Manager{
	/* 
	* @var string
	*/
	var $_ext = 'xml=xml;html=htm,html;word=doc,docx;powerpoint=ppt;excel=xls;text=txt,rtf;image=gif,jpeg,jpg,png;acrobat=pdf;archive=zip,tar,gz;flash=swf;winrar=rar;quicktime=mov,mp4,qt;windowsmedia=wmv,asx,asf,avi;audio=wav,mp3,aiff;openoffice=odt,odg,odp,ods,odf';	
	/**
	* @access	protected
	*/
	function __construct(){	
		parent::__construct();
		
		// Set the file type map from parameters
		$this->setFileTypes( $this->getPluginParam( 'filemanager_extensions', $this->_ext ) );
		// Init plugin
		$this->init();
	}
	/**
	 * Returns a reference to a manager object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $manager = &FileManager::getInstance();</pre>
	 *
	 * @access	public
	 * @return	FileManager  The manager object.
	 * @since	1.5
	 */
	function &getInstance(){
		static $instance;

		if ( !is_object( $instance ) ){
			$instance = new FileManager();
		}
		return $instance;
	}
	function getFileDetails( $file ){
		jimport( 'joomla.filesystem.file' );
		clearstatcache();
		
		$path 	= Utils::makePath( $this->getBaseDir(), utf8_decode( rawurldecode( $file ) ) );
		$url 	= Utils::makePath( $this->getBaseUrl(), rawurldecode( $file ) );
		
		$date 	= Utils::formatDate( @filemtime( $path ) );
		$size 	= Utils::formatSize( @filesize( $path ) );
		
		$h = array(
			'size'		=>	$size, 
			'modified'	=>	$date
		);
		
		if( preg_match( '/\.(jpeg|jpg|gif|png)/i', $file ) ){	
			$dim 	= @getimagesize( $path );
			$pw 	= ( $dim[0] >= 100 ) ? 100 : $dim[0];
			$ph 	= ( $pw / $dim[0] ) * $dim[1];
			
			if( $ph > 80 ){
				$ph = 80;
				$pw = ( $ph / $dim[1] ) * $dim[0];
			}
			$width		= $dim[0];
			$height		= $dim[1]; 
			
			$h = array(
				'dimensions'=>	$width. ' x ' .$height,
				'size'		=>	$size, 
				'modified'	=>	$date,
				'preview'		=>	array(
					'src'		=>	$url,
					'width'		=>	round( $pw ),
					'height'	=>	round( $ph )
				)
			);
		}
		return $h;
	}
	function getProperties( $file ){
		clearstatcache();
		
		$path 	= Utils::makePath( $this->getBaseDir(), utf8_decode( rawurldecode( $file ) ) );
		
		$date 	= Utils::formatDate( @filemtime( $path ), $this->getPluginParam('filemanager_date_format', '%d/%m/%Y, %H:%M') );
		$size 	= Utils::formatSize( @filesize( $path ) );
		
		$h = array( 
			'size'		=>	$size, 
			'date'		=>	$date
		);
		return $h;
	}
	function getIconMap( $map=true ){
		$extensions = $this->getPluginParam( 'filemanager_extensions', $this->_ext );
		if( $map ){
			return $extensions;
		}else{
			$this->listFileTypes( $extensions );
		}
	}
	function getIconPath(){
		return $this->getPluginParam( 'filemanager_extensions_path', 'plugins/editors/jce/tiny_mce/plugins/filemanager/img/ext' );
	}
	function getIconPrefix(){
		return $this->getPluginParam( 'filemanager_extensions_prefix', '_small' );
	}
	function getViewable(){
		return $this->getPluginParam( 'filemanager_extensions_viewable', 'html,htm,doc,docx,ppt,rtf,xls,txt,gif,jpeg,jpg,png,pdf,swf,mov,mpeg,mpg,avi,asf,asx,dcr,flv,wmv,wav,mp3' );
	}
}
?>
