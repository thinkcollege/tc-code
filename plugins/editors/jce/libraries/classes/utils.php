<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class Utils {
	/**
    * Append a / to the path if required.
    * @param string $path the path
    * @return string path with trailing /
    */
    function fixPath( $path ){
        //append a slash to the path if it doesn't exists.
        if( !( substr( $path, -1 ) == '/' ) )
            $path .= '/';
        return $path;
    }
   	/**
    * Concat two paths together. Basically $pathA+$pathB
    * @param string $pathA path one
    * @param string $pathB path two
    * @return string a trailing slash combinded path.
    */
    function makePath( $a, $b ){
        $a = Utils::fixPath( $a );
        if( substr( $b, 0, 1 )  ==  '/' ){
            $b = substr( $b, 1 );
		}
        return $a.$b;
    }
	/**
	 * Makes file name safe to use
	 * @param string The name of the file (not full path)
	 * @return string The sanitised string
	 */
	function makeSafe( $file ){
		jimport('joomla.filesystem.file');
		//return strtolower( JFile::makeSafe( preg_replace( '#\s#', '_', $file ) ) );
		return JFile::makeSafe( preg_replace( '#\s#', '_', $file ) );
	}
	/**
    * Format the file size, limits to Mb.
    * @param int $size the raw filesize
    * @return string formated file size.
    */
    function formatSize( $size ){
        if( $size < 1024 )
            return $size.' bytes';
        else if( $size >= 1024 && $size < 1024*1024 )
            return sprintf('%01.2f',$size/1024.0).' Kb';
        else
            return sprintf( '%01.2f', $size/(1024.0*1024) ).' Mb';
    }
   	/**
  	* Format the date.
    * @param int $date the unix datestamp
    * @return string formated date.
    */
   	function formatDate( $date, $format="%d/%m/%Y, %H:%M" ){
		return strftime( $format, $date );
   	}
	function getDate( $file ) {
		return Utils::formatDate( @filemtime( $file ) );
	}
	function getSize( $file ) {
		return Utils::formatSize( @filesize( $file ) );
	}
	/**
    * Count the number of folders in a given folder
    */
    function countDirs( $path ){
		jimport('joomla.filesystem.folder');
		$total = 0;
        if( JFolder::exists( $path ) ){
            $folders = JFolder::folders( $path );
            $total = count( $folders );
        }
        return $total;
    }
	function countFiles( $path ){
		jimport('joomla.filesystem.file');
		$total = 0;
        if( JFolder::exists( $path ) ){
            $files = JFolder::files( $path );
            $total = count( $files );
            foreach( $files as $file ){
                if( strtolower( $file ) == 'index.html' || strtolower( $file ) == 'thumbs.db'){
                    $total = $total -1;
                }
            }
        }
        return $total;
    }
}
?>
