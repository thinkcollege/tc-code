<?php
// -----------------------
// buildgallery.php v1.9.1
// ----------------------
//
// by:
// Jack Hardie - www.jhardie.com
// Mario - mariohm@fibertel.com.ar
// Mike Peck - www.mikecpeck.com 
// Mike Johnson - mike@solanosystems.com
// Christian Machmeier - www.redsplash.de
// Airtight - www.airtightinteractive.com
//
// DESCRIPTION
// -----------------------
// This script automatically generates the XML document and thumbnails for SimpleViewer 
// www.airtightinteractive.com/simpleviewer/
//
// TO USE
// -----------------------
// Instructions are at: www.airtightinteractive.com/simpleviewer/auto_server_instruct.html
//
//
// SET GALLERY OPTIONS HERE
// -----------------------
// Set Gallery options by editing the values in "double quotes" below:

$svOptions['maxImageWidth'] = "640";
$svOptions['maxImageHeight'] = "640";
$svOptions['textColor'] = "0xffffff";
$svOptions['frameColor'] = "0xffffff";
$svOptions['frameWidth'] = "20";
$svOptions['stagePadding'] = "40";
$svOptions['thumbnailColumns'] = "3";
$svOptions['thumbnailRows'] = "4";
$svOptions['navPosition'] = "left";
$svOptions['title'] = "SimpleViewer Title";
$svOptions['enableRightClickOpen'] = "true";
$svOptions['backgroundImagePath'] = "";
$svOptions['imagePath'] = "nsip";
$svOptions['thumbPath'] = "nsip/thumbs";
$svOptions['slidePath'] = "nsip/slides";

// Set options for the buildgallery script by editing the values below

// Set $bgOptions['showDownloadLinks'] to true if you want to show a 'Download Image' link as the caption to each image.
$bgOptions['showDownloadLinks'] = false;

// Set $bgOptions['sortImagesByDate'] to false to sort by file name. Otherwise files are sorted by date modified.
$bgOptions['sortImagesByDate'] = true;

// Set $bgOptions['sortInReverseOrder'] to false to sort images in forward order.
$bgOptions['sortInReverseOrder'] = true;

// Set $bgOptions['overwriteThumbnails'] to true to re-create existing thumbnails each time the program runs. Useful if you are editing the images and keeping the same names.
$bgOptions['overwriteThumbnails'] = false;

// set $bgOptions['useCopyResized'] to true if thumbnails are not being created. 
// This can be due to the imagecopyresampled function being disabled on some servers
$bgOptions['useCopyResized'] = false;

// END OF OPTIONS
// -----------------------------------------------------------------------------------------------

// set-up page for valid html output
$pageTitle = 'simpleviewer build gallery script';
$page = new Page($pageTitle);
print $page->getHeader();
// wrap options in an object so they can be passed around easily
$options = new Options($svOptions, $bgOptions);
print '<p>( buildgallery.php version 1.9, running under php version '.phpversion().', GD library version '.$options->gdVersion.')</p>';
// collect data about image files in the $imageFiles object
$imageFiles = new ImageFiles($options);
$fileNames = $imageFiles->getFileNames();
// attempt to create xml file
$galleryXml = new GalleryXml($fileNames, $options);
// attempt to create thumbnails
$thumbnails = new Thumbnails($fileNames, $options);
$slides = new Slides($fileNames, $options);
// close html tags
print '<p>buildgallery script complete</p>';
print $page->getFooter();

// -----------------------------------------------------------------------------------------------
// Class Options
// contains options for simpleviewer and buildgallery
class Options
{
// array svOptions simpleviewer options
  var $svOptions;
// array bgOptions buildgallery options
  var $bgOptions;
// string gdVersion version number of GD library
  var $gdVersion;
// floating point number gdVersionNumber version number of GD library expressed as a number
// contents of $gdVersion after the second decimal point will be lost
// function Options constructs Options pre-processes path data and gets GD version

  function Options($svOptions, $bgOptions)
  {
    $this->svOptions = $svOptions;
    $this->bgOptions = $bgOptions;
    $this->bgOptions['imagePath'] = $this->getPath('imagePath', $this->svOptions['imagePath']);
    $this->bgOptions['thumbPath'] = $this->getPath('thumbPath', $this->svOptions['thumbPath']);
    $this->bgOptions['slidePath'] = $this->getPath('slidePath', $this->svOptions['slidePath']);
    $this->gdVersion = $this->getGDVersion();
    $this->gdVersionNumber = (float)$this->gdVersion;

  }
// function getPath extracts image or thumb path from simpleviewer options
  function getPath($pathName, $path)
  {
// handle default path
    if ($path == '' && $pathName == 'imagePath') {return 'images';}
    if ($path == '' && $pathName == 'thumbPath') {return 'thumbs';}
    if ($path == '' && $pathName == 'slidePath') {return 'slides';}
// handle correctly formatted path with trailing /
    $lastChar = substr($path, -1);
    if ($lastChar == '/')
    {
      return substr($path, 0, -1);
    }
    return $path;
  }
  // function getGDVersion
// returns string $gd_version_number
// Use output buffering to get results from phpinfo()
// without disturbing the page we're in.  Output
// buffering is "stackable" so we don't even have to
// worry about previous or encompassing buffering.
  function getGDversion()
  {
    static $gd_version_number = null;
    if ($gd_version_number === null)
    {
      ob_start();
      phpinfo(8);
      $module_info = ob_get_contents();
      ob_end_clean();
      if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches))
      {
        $gd_version_number = $matches[1];
      }
      else
      {
        $gd_version_number = 0;
      }
    }
    return $gd_version_number;
  }
}

// Class ImageFiles
// Extracts information about the files in the designated image directory
class ImageFiles
{
// array $fileNames names and dates of files in images directory
  var $fileNames;
// function ImageFiles constructs ImageFiles
// parameter object $options simpleviewer and buildgallery options
  function ImageFiles($options)
  {
    $this->fileNames = array();
    $imageDir = $options->bgOptions['imagePath'];
    if (!is_dir($imageDir))
    {
      die('<p class="error">Error: the image directory <em>'.$imageDir.'</em> cannot be found</p>');
    }
    $folder = @opendir($imageDir);
    if ($folder === false)
    {
      die('<p class="error">Cannot open the <em>'.$imageDir.'</em> directory &ndash; check the file permissions.</p>');
    }
    $jpgCount = 0;
    $sortMessage = '';
    while(false !== ($fileName = readdir($folder)))
    {	
    	if (!$this->isImage($fileName)) {continue;}
    	if ($options->bgOptions['sortImagesByDate'])
      {
    			$this->fileNames[$fileName] = filemtime($imageDir.'/'.$fileName);
          $sortMessage = 'Sorting images by date. ';
    	}
      else
      {
    			$this->fileNames[$fileName] = $fileName;
          $sortMessage = 'Sorting images by file name. ';
    	}
      $jpgCount ++;
    }
    if ($jpgCount == 0)
    {
      print '<p class="warning">Warning: no jpg images found in <em>'.$imageDir.'</em> folder.</p>';
      return;
    }
    else
    {
      print '<p>'.$jpgCount.' jpg images found in <em>'.$imageDir.'</em> folder.</p>';
    }   
    // now sort by date modified
    if ($options->bgOptions['sortInReverseOrder'])
    {
      $sortMessage .= 'Sort in reverse order.';
    	arsort($this->fileNames);
    }
    else
    {
      $sortMessage .= 'Sort in forward order.';
    	asort($this->fileNames);
    }
    closedir($folder);
    print '<p>'.$sortMessage.'</p>';
    print '<ol>';
    foreach ($this->fileNames as $fileName => $value)
    {
      print '<li>'.$fileName.'</li>';
    }
    print '</ol>';
  }
// function getFileNames discards date information and returns array of image file names
  function getFileNames()
  {
    return array_keys($this->fileNames);
  }
// function isImage returns true if $fileName has a suffix .jpg or .jpeg, otherwise returns false
// parameter string file name
  function isImage($fileName)
  {
    if ($fileName[0] == "." || $fileName[0] == ".." ) {return false;}
    $components = explode(".", $fileName);
    $length = count($components);
    if ($length == 1) {return false;}
    $suffix = strtolower($components[$length - 1]);
    if ($suffix == 'jpg' || $suffix == 'jpeg') {return true;}
    return false;
  }
}

// class GalleryXml creates gallery.xml file
class GalleryXml {
// var string $xml contents of gallery.xml file
  var $xml;
// function GalleryXml constructs XmlDoc
// parameter array $fileNames names of image files
// parameter object $options user options
  function GalleryXml($fileNames, $options)
  {
    $this->xml = '<?xml version="1.0" encoding="UTF-8"'." standalone=\"yes\" ?>\n";
    $this->xml .= '<images>'."\n";
    foreach ($fileNames as  $fileName)
    {
      $this->xml .= "<pic>\n";
	    $this->xml .= "\t<image>".$fileName."</image>\n";
//add auto captions
	   
      		
		    $this->xml .= "\t".'<caption></caption>'."\n";
	    $this->xml .= "\t<thumbnail>thumbs/".$fileName."</thumbnail>\n";
	   
    	$this->xml .= "</pic>\n";
    }
    $this->xml .= '</images>';
    $file = "images.xml";
// attempt to change permissions
    if (file_exists($file))
    {
      @chmod($file, 0777);
    }
    if (!$file_handle = @fopen($file,"w"))
    { 
	    print '<p class="error">Cannot open XML document: $file. Change permissions to 0777 for $file and parent directory.</p>'; 
    }
    elseif (!@fwrite($file_handle, $this->xml))
    { 
	    print '<p class="error">Cannot write to XML document: $file. Change permissions to 0777 for file and parent directory.<p>';   
    }
    else
    {
	    print '<p>Successfully created XML document: <em>'.$file.'</em></p>';   
    }
    @fclose($file_handle);
// attempt to change file permissions for later editing by user
    @chmod($file, 0777);   
  }
// function getXmlOptions formats simpleviewer options as an xml tag
  function getXmlOptions($options)
  {
    $xmlOptions = '<simpleviewerGallery';
    foreach ($options->svOptions as $optName => $optValue)
    {
      $xmlOptions = $xmlOptions .= ' '.$optName.' = "'.$optValue.'"';
    }
    $xmlOptions = $xmlOptions .= '>';
    return $xmlOptions;
  }
}

class Thumbnails
{
// function Thumbnails constructs Thumbnails class
// parameter array $fileNames names of source images
// parameter object $options options for simpleviewer and buildgallery
  function Thumbnails($fileNames, $options)
  {
    $imageDir = $options->bgOptions['imagePath'];
    $thumbDir = $options->bgOptions['thumbPath'];
    if (!is_dir($thumbDir))
    {
// Note: mkdir($thumbDir, 0777) will not work reliably because of php's umask (www.php.net/umask)
      mkdir($thumbDir);
      chmod($thumbDir, 0777);
    }  
    if ($options->gdVersionNumber < 1.8)
    {
      print '<p class="warning">Warning: the GD imaging library was not found on this server or it is an old version that does not support jpeg images. Thumbnails will not be created. Either upgrade to a later version of GD or create the thumbnails yourself in a graphics application such as Photoshop.</p>';
      return;
    }
    elseif ($options->gdVersionNumber < 2)
    {
      print '<p class="warning">Warning: the GD imaging library on this server is version '.$options->gdVersion.'. Thumbnails will be of lower quality. You might want to upgrade to a later version of GD or create the thumbnails yourself in a graphics application such as Photoshop.</p>';
    }
    elseif ($options->bgOptions['useCopyResized'])
    {
      print '<p class="warning">Warning: the <em>useCopyResized</em> setting has been set to <em>true</em>. Thumbnails will be of lower quality. You might want to create the thumbnails yourself in a graphics application such as Photoshop.</p>';
    }
    print '<p>Attempting to create thumbnails in <em>'.$thumbDir.'</em> folder:</p>';
    $thumbCount = 0;
    $thumbList = '<ol>';
    
    foreach ($fileNames as $fileName)
    {
      $filePath = $imageDir.'/'.$fileName;
      $thumbPath = $thumbDir.'/'.$fileName;
      $thumbCount ++;
      if (file_exists($thumbPath) && !$options->bgOptions['overwriteThumbnails'])
      {
        $thumbList .= '<li>'.$fileName.' already exists</li>';
        continue;
      }        
      if ($this->createThumb($filePath, $thumbPath, $options))
      {
        $thumbList .= '<li>'.$fileName.' created</li>';
      }
      else
      {
        $thumbList .= '<li class="warning">'.$fileName.' could not be created</li>';
      }
    }
    $thumbList .= '</ol>';
    if ($thumbCount > 0) {print $thumbList;}
  }
// function createThumb creates and saves thumbnail image.
// returns boolean $success
// uses older GD library functions if current ones are not available
// parameter string $filePath path to source image
// parameter string $thumbPath path to new thumbnail
// parameter object $options options

  function createThumb($filePath, $thumbPath, $options)
  {
    $thumbSize = 65;
    $quality = 85;	
// Get the image dimensions.
  	$dimensions = @getimagesize($filePath);
  	$width		= $dimensions[0];
  	$height		= $dimensions[1];
    $smallerSide = min($width, $height);
// Calculate offset of square portion of image
// offsets will both be zero if original image is square
    $deltaX = ($width - $smallerSide)/2;
    $deltaY = ($height - $smallerSide)/2;
// get image identifier for source image
    $imageSrc  = @imagecreatefromjpeg($filePath);
// Create an empty thumbnail image.
    if ($options->gdVersionNumber < 2 || $options->bgOptions['useCopyResized'])
    {
      $imageDest = @imagecreate($thumbSize, $thumbSize);
      $success = @imagecopyresized($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $thumbSize, $thumbSize, $smallerSide, $smallerSide);
    }
    else
    {
      $imageDest = @imagecreatetruecolor($thumbSize, $thumbSize);
      $success = @imagecopyresampled($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $thumbSize, $thumbSize, $smallerSide, $smallerSide);
    }
    if (!$success) {return false;}
    {
      // save the thumbnail image into a file.
  		$success = @imagejpeg($imageDest, $thumbPath, $quality);
  		// Delete both image resources.
  		@imagedestroy($imageSrc);
  		@imagedestroy($imageDest);						
  	}	
  	return $success;
  }
}







class Slides
{
// function Thumbnails constructs Thumbnails class
// parameter array $fileNames names of source images
// parameter object $options options for simpleviewer and buildgallery
  function Slides($fileNames, $options)
  {
    $imageDir = $options->bgOptions['imagePath'];
    $slideDir = $options->bgOptions['slidePath'];
    if (!is_dir($slideDir))
    {
// Note: mkdir($slideDir, 0777) will not work reliably because of php's umask (www.php.net/umask)
      mkdir($slideDir);
      chmod($slideDir, 0777);
    }  
    if ($options->gdVersionNumber < 1.8)
    {
      print '<p class="warning">Warning: the GD imaging library was not found on this server or it is an old version that does not support jpeg images. Thumbnails will not be created. Either upgrade to a later version of GD or create the thumbnails yourself in a graphics application such as Photoshop.</p>';
      return;
    }
    elseif ($options->gdVersionNumber < 2)
    {
      print '<p class="warning">Warning: the GD imaging library on this server is version '.$options->gdVersion.'. Thumbnails will be of lower quality. You might want to upgrade to a later version of GD or create the thumbnails yourself in a graphics application such as Photoshop.</p>';
    }
    elseif ($options->bgOptions['useCopyResized'])
    {
      print '<p class="warning">Warning: the <em>useCopyResized</em> setting has been set to <em>true</em>. Thumbnails will be of lower quality. You might want to create the thumbnails yourself in a graphics application such as Photoshop.</p>';
    }
    print '<p>Attempting to create slides in <em>'.$slideDir.'</em> folder:</p>';
    $slideCount = 0;
    $slideList = '<ol>';
    
    foreach ($fileNames as $fileName)
    {
      $filePath = $imageDir.'/'.$fileName;
      $slidePath = $slideDir.'/'.$fileName;
      $slideCount ++;
      if (file_exists($slidePath) && !$options->bgOptions['overwriteSlides'])
      {
        $slideList .= '<li>'.$fileName.' already exists</li>';
        continue;
      }        
      if ($this->createSlide($filePath, $slidePath, $options))
      {
        $slideList .= '<li>'.$fileName.' created</li>';
      }
      else
      {
        $slideList .= '<li class="warning">'.$fileName.' could not be created</li>';
      }
    }
    $slideList .= '</ol>';
    if ($slideCount > 0) {print $slideList;}
  }






  function createSlide($filePath, $slidePath, $options)
  {
    $slideSizeH = 266;
    $quality = 85;	
// Get the image dimensions.
  	$dimensions = @getimagesize($filePath);
  	$width		= $dimensions[0];
  	$height		= $dimensions[1];
    $smallerSide = min($width, $height);
// Calculate offset of square portion of image
// offsets will both be zero if original image is square
    $deltaX = 0;
    $deltaY = 0;
	$slideSizeW = $width / ($height / 266);
// get image identifier for source image
    $imageSrc  = @imagecreatefromjpeg($filePath);
// Create an empty thumbnail image.
    if ($options->gdVersionNumber < 2 || $options->bgOptions['useCopyResized'])
    {
      $imageDest = @imagecreate($slideSizeW, $slideSizeH);
      $success = @imagecopyresized($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $slideSizeW, $slideSizeH, $width, $height);
    }
    else
    {
      $imageDest = @imagecreatetruecolor($slideSizeW, $slideSizeH);
      $success = @imagecopyresampled($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $slideSizeW, $slideSizeH, $width, $height);
    }
    if (!$success) {return false;}
    {
      // save the thumbnail image into a file.
  		$success = @imagejpeg($imageDest, $slidePath, $quality);
  		// Delete both image resources.
  		@imagedestroy($imageSrc);
  		@imagedestroy($imageDest);						
  	}	
  	return $success;
  }
}
// Class Page produces html headers and footers
class Page
{
// string $title contents for html <title></title> tags
  var $title;
// function Page constructs Page class
  function Page($title)
  {
    $this->title = $title;
  }



// Function getHeader
// returns string containing html header, css styles and page heading
// rules for heredoc ( <<<EOD...EOD; ) are on www.php.net/heredoc
// in particular note the rules for white space in closing heredoc tags
  function getHeader()
  {
    $header = <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>$this->title</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

<style type="text/css">
  body {
    background-color: #ffffff;
    color: #333333;
    font-family: arial, helvetica, sans-serif;
    font-size: 62.5%;
  }
  h1 {
    font-size: 1.4em;
    font-weight: bold;
  }
  p, ol {
    font-size: 1.2em;
  }
  .error {
    color: #FF0000;
    background-color: #FFFFFF;
  }
  .warning {
    color: #0000FF;
    background-color: #FFFFFF;
  }
</style>
</head>
<body>
  <h1>Creating XML and thumbnails for SimpleViewer.</h1>
EOD;
  return $header;
  }
// function getFooter returns string containing closing html tags
// could also be used for a page footer, such as a copyright message
// rules for heredoc ( <<<EOD...EOD; ) are on www.php.net/heredoc
// in particular note the rules for white space in closing heredoc tags
  function getFooter()
  {
    $footer = <<<EOD
</body>
</html>
EOD;
  return $footer;
  }
}

?>