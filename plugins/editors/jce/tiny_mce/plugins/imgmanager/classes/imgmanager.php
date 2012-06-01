<?php
/**
 * ImageManager Class.
 * @author $Author: Ryan Demmer $
 * @version $Id: manager.class.php 27 2005-09-14 17:51:00 Ryan Demmer $
 */
class ImageManager extends Manager{
        var $_ext = 'image=jpg,jpeg,gif,png';
		/**
		* @access	protected
		*/
		function __construct(){
			parent::__construct();			
			
			// Set the file type map from parameters
			$this->setFileTypes( $this->getPluginParam('imgmanager_extensions', $this->_ext ) );
			// Init plugin
			$this->init();
		}
		/**
		 * Returns a reference to a editor object
		 *
		 * This method must be invoked as:
		 * 		<pre>  $browser = &JCE::getInstance();</pre>
		 *
		 * @access	public
		 * @return	JCE  The editor object.
		 * @since	1.5
		 */
		function &getInstance(){
			static $instance;
	
			if ( !is_object( $instance ) ){
				$instance = new ImageManager();
			}
			return $instance;
		}
		function getFileDetails( $file ){
			jimport('joomla.filesystem.file');
			clearstatcache();
			
			$h = array(
				'dimensions'	=>	'',
				'size'			=>	'', 
				'modified'		=>	'',
				'preview'		=>	''
			);
			
			$path = Utils::makePath( $this->getBaseDir(), utf8_decode( rawurldecode( $file ) ) );
			if( file_exists( $path ) ){
				$url 	= Utils::makePath( $this->getBaseUrl(), rawurldecode( $file ) );
				$ext 	= JFile::getExt( $path );
				$dim 	= @getimagesize( $path );
				
				$date 	= Utils::formatDate( @filemtime( $path ) );
				$size 	= Utils::formatSize( @filesize( $path ) );
				
				$pw 	= ( $dim[0] >= 100 ) ? 100 : $dim[0];
				$ph 	= ( $pw / $dim[0] ) * $dim[1];
				
				if( $ph > 80 ){
					$ph = 80;
					$pw = ( $ph / $dim[1] ) * $dim[0];
				}
				$h = array(
					'dimensions'	=>	$dim[0]. ' x ' .$dim[1],
					'size'			=>	$size, 
					'modified'		=>	$date,
					'preview'		=>	array(
						'src'		=>	$url,
						'width'		=>	round( $pw ),
						'height'	=> 	round( $ph )
					)
				);
			}
			return $h;
		}
		function getDimensions( $file ){			
			$path = Utils::makePath( $this->getBaseDir(), utf8_decode( rawurldecode( $file ) ) );
			$h = array(
				'width'		=>	'', 
				'height'	=>	''
			);
			if( file_exists( $path ) ){
				$dim = @getimagesize( $path );
				$h = array(
					'width'		=>	$dim[0], 
					'height'	=>	$dim[1]
				);
			}
			return $h;
		}
		function getUploadFileTypes(){
			$list = $this->getPluginParam( 'imgmanager_extensions', 'image=jpg,jpeg,gif,png' );
			return $this->mapUploadFileTypes( $list );
		}
}
?>