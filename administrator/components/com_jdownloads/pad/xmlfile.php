<?php

//////////////////////////////////////////////////////////////////////////////
// PAD SDK Version 1.5
//
// Copyright 2004-2006 by Association of Shareware Professionals, Inc.
// All Rights Reserved.
// PAD, PADGen, and PADKit are trademarks of the Association of Shareware
// Professionals in the United States and/or other countries.
//
// Use the PAD SDK on your own risk. Read the disclaimer in index.html
// Use, modify and distribute the SDK for free - but do not remove or modify
// this complete copyright and disclaimer section.
//
// http://www.asp-shareware.org/pad/
//
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// XMLFILE.PHP
//
// DESCRIPTION
//
// Representation of a XML file in the XMLFile base class.
// The XML can be loaded from an URL or from a local file. After loading the
// XML, a tree of XMLNode objects will be created based on the XML. Now the
// tree can be used to walk through, search for a node, etc.
//
// SAMPLE CODE
//
// // Create XML file object for an URL (this could also be the path to a local file)
// $XMLFile = new XMLFile("http://host.com/file.xml");
//
// // Load file (see Constants section for possible error codes)
// if ( !$XMLFile->Load() )
//   die "Cannot load XML. Error Code: " . $XMLFile->LastError;
//
// // Walk through the first level of the tree
// foreach($XMLFile->XML->ChildNodes as $Node)
//   echo $Node->Name . " = " . $Node->Value;
//
// // Find a specific node value
// echo $XMLFile->XML->GetValue("tag1/tag2/tag3");
//
// HISTORY
//
// 2006-08-31 PHP5 compatibility: avoid allow_call_time_pass_reference
//            warning (Oliver Grahl, ASP PAD Support)
// 2006-06-20 PHP5 compatibility (Oliver Grahl, ASP PAD Support)
// 2006-02-17 LastErrorMsg: additional error message when Load() failed
//            (Oliver Grahl, ASP PAD Support)
// 2004-11-11 Fixed bug with encoding conversion (Oliver Grahl, ASP PAD Support)
// 2004-10-29 Improved support for other encodings, like Windows-1252
//            (Oliver Grahl, ASP PAD Support)
// 2004-09-30 Improved UTF-8 support, added XMLFile->OutputEncoding, which
//            defaults to ISO-8859-1 (Oliver Grahl, ASP PAD Support)
// 2004-08-16 Initial Release (Oliver Grahl, ASP PAD Support)
//
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Constants
//////////////////////////////////////////////////////////////////////////////

// Error values
define("ERR_NO_ERROR",              0);
define("ERR_NO_URL_SPECIFIED",      1);
define("ERR_READ_FROM_URL_FAILED",  2);
define("ERR_PARSE_ERROR",           3);


//////////////////////////////////////////////////////////////////////////////
// Classes
//////////////////////////////////////////////////////////////////////////////

// XMLNode class
// Represents a simple XML Node with tag name, value and an array of child nodes
class XMLNode
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $Name;
  var $Value = "";
  var $ParentNode;
  var $ChildNodes;
  var $Level = 0;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  function XMLNode($Name)
  {
    // Initializations
    $this->Name = $Name;
    $this->ChildNodes = array();
  }


  //////////////////////////////////////////////////////////////////////////////
  // Public Methods
  //////////////////////////////////////////////////////////////////////////////

  // Set parent node
  // IN: &$ParentNode - reference to the new parent node
  function SetParent(&$ParentNode)
  {
    $this->ParentNode = &$ParentNode;
    $this->Level = $this->ParentNode->Level + 1;
  }


  // Clear contents
  function Clear()
  {
    $this->Name = "";
    $this->ChildNodes = array();
    unset($this->ParentNode);
    $this->Level = 0;
  }

  // Append a node
  // IN:      $Name - the tag name of the node to add
  // RETURNS: reference to the XMLNode object that has been created
  function &AppendNode($Name)
  {
    $node =& new XMLNode($Name);
    $node->SetParent($this);

    // Do not use array_push with pass-by-reference any more to avoid
    // allow_call_time_pass_reference warning (Oliver Grahl, 2006-08-31)
    //array_push($this->ChildNodes, &$node);
    $this->ChildNodes[] =& $node;

    return $node;
  }

  // Returns the node according to the path (/-separated)
  // IN:      $Path  - the path to the XML node, e.g. Root/Child/Name
  // RETURNS: reference to the XMLNode object, NULL if node is not found
  function &FindNodeByPath($Path)
  {
    // To make PHP5 happy, we will not return NULL, but a variable which
    // has a value of NULL.
    $NULL = NULL;

    $tags = explode("/", $Path);

    if ( count($tags) <= 0 )
      return $NULL;

    $node =& $this;
    foreach($tags as $tag)
      if ( ($tag != "") && !($node =& $node->FindNodeByName($tag)) )
        return $NULL;

    return $node;
  }

  // Returns a node value according to the path (/-separated)
  // IN:      $Path    - the path to the XML node, e.g. Root/Child/Name
  // IN:      $Default - value to use if node does not exists (optional)
  // RETURNS: value of the node as string, empty string or default value if
  //          node is not found
  function GetValue($Path, $Default = "")
  {
    $node =& $this->FindNodeByPath($Path);
    if ( $node )
      return $node->Value;
    else
      return $Default;
  }

  // Transforms XML tree to XML text
  // RETURNS: the XML string
  function ToString()
  {
    if ( $this->Level == 0 )
    {
      // This is the root node, only walk through children
      $out = "";
      foreach($this->ChildNodes as $node)
        $out .= $node->ToString();
    }
    else
    {
      // Print node depending of it's type
      $indent = str_repeat("\t", $this->Level - 1);

      if ( count($this->ChildNodes) <= 0 )
      {
        // A node without children
        if ( $this->Value == "" )
          $out = $indent . "<" . $this->Name . " />\n";
        else
          $out = $indent . "<" . $this->Name . ">" . $this->Value . "</" . $this->Name . ">\n";
      }
      else
      {
        // A node with children
        $out = $indent . "<" . $this->Name . ">" . $this->Value . "\n";
        foreach($this->ChildNodes as $node)
          $out .= $node->ToString();
        $out .= $indent . "</" . $this->Name . ">\n";
      }
    }

    return $out;
  }

  // Dumps the XML text to HTML
  function Dump()
  {
    echo "<pre>" . htmlspecialchars($this->ToString()) . "</pre>";
  }

  // Returns the node according to the name
  // IN:      $Name  - the name of the XML child node, e.g. Child
  // RETURNS: reference to the XMLNode object, NULL if node is not found
  function &FindNodeByName($Name)
  {
    foreach($this->ChildNodes as $node)
      if ( $node->Name == $Name )
        return $node;

    // To make PHP5 happy, we will not return NULL, but a variable which
    // has a value of NULL.
    $NULL = NULL;
    return $NULL;
  }
}


// XMLFile class
// Represents the XML file read from URL (URL or local file).
// Parses the XML into a tree of XMLNode objects (property XML).
class XMLFile
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $URL = "";
  var $XML;
  var $ParseError = "";
  var $LastError = ERR_NO_ERROR;
  var $LastErrorMsg = "";
  var $OutputEncoding = "ISO-8859-1";

  //////////////////////////////////////////////////////////////////////////////
  // Private Properties - DO NOT CALL FROM EXTERNAL
  //////////////////////////////////////////////////////////////////////////////

  var $_CurrentNode;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: $URL - the URL or local path of the XML file (optional)
  function XMLFile($URL = "")
  {
    // Initializations
    $this->URL = $URL;
    $this->XML = new XMLNode("[root]");
  }


  //////////////////////////////////////////////////////////////////////////////
  // Public Methods
  //////////////////////////////////////////////////////////////////////////////

  // Load file from URL
  // RETURNS: true  - Success (LastError is ERR_NO_ERROR)
  //          false - Failure (see LastError, LastErrorMsg)
  function Load()
  {
    $this->LastErrorMsg = "";

    // Check if URL exists
    if ( $this->URL == "" )
    {
      $this->LastError = ERR_NO_URL_SPECIFIED;
      return false;
    }

    // Set track_errors, so that $php_errormsg can be used
    // (possibly this will fail if PHP is running in SAFE MODE)
    ini_set('track_errors', true);

    // Read in file (line by line)
    $raw = @file($this->URL);
    if ( !$raw )
    {
      $this->LastError = ERR_READ_FROM_URL_FAILED;
      if (isset($php_errormsg))
      {
        $this->LastErrorMsg = trim($php_errormsg);
        if ($this->LastErrorMsg == "failed to open stream: No error")
          $this->LastErrorMsg = "Unknown host";
      }
      return false;
    }

    // Merge array of lines into one long string
    $raw = implode("", $raw);

    // Remove Byte-Order-Mark (BOM) if existing
    // (required to avoid problems on some PHP versions)
    // UNCOMMENT THIS LINE IF YOU HAVE PROBLEMS WITH PAD FILES WITH A BOM
    // $raw = substr($raw, strpos($raw, "<"));

    // Parse the raw XML into Nodes
    if ( !$this->Parse($raw) )
    {
      $this->LastError = ERR_PARSE_ERROR;
      return false;
    }

    // Succeeded
    $this->LastError = ERR_NO_ERROR;
    return true;
  }

  // Read from XML
  // IN:      $Raw  - the XML string
  // RETURNS: true  - Success
  //          false - Failure (see ParseError)
  function Parse($Raw)
  {
    // Inits
    $this->ParseError = "";
    $this->XML->Clear();
    $this->_CurrentNode =& $this->XML;

    // Look up the XML encoding
    if ( preg_match("/<\?xml [^>]*encoding=['\"](.*?)['\"][^>]*>.*/", $Raw, $m) )
      $encoding = strtoupper($m[1]);
    else
      $encoding = "UTF-8";

    // If the encoding is not natively supported by the PHP XML parser, try to
    // decode on our own (to UTF-8), if decoding is not possible, assume UTF-8,
    // which is the XML default.
    if ( ($encoding != "UTF-8") && ($encoding != "US-ASCII") && ($encoding != "ISO-8859-1") )
    {
      $encoding = "";
      if ( function_exists("mb_convert_encoding") )
      {
        $encoded_source = @mb_convert_encoding($Raw, "UTF-8", $encoding);
        if ( $encoded_source != NULL )
        {
          $Raw = str_replace($m[0], "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>", $encoded_source);
          $encoding = "UTF-8";
        }
      }
    }

    // Create and initialize parser
    $xml_parser = xml_parser_create($encoding);
    xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, $this->OutputEncoding);
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
    // Do not pass by reference (&$this) any more to avoid
    // allow_call_time_pass_reference warning (Oliver Grahl, 2006-08-31)
    xml_set_object($xml_parser, $this);
    xml_set_element_handler($xml_parser, "_StartElement", "_EndElement");
    xml_set_character_data_handler($xml_parser, "_CharacterData");

    // Parse
    if ( !xml_parse($xml_parser, $Raw, true) )
    {
      // Save parse error and free parser
      $this->ParseError = sprintf("%s at line %d",
        xml_error_string(xml_get_error_code($xml_parser)),
        xml_get_current_line_number($xml_parser));
      xml_parser_free($xml_parser);
      unset($this->_CurrentNode);
      return false;
    }

    // Free parser
    xml_parser_free($xml_parser);
    unset($this->_CurrentNode);

    return true;
  }


  //////////////////////////////////////////////////////////////////////////////
  // Private methods - DO NOT CALL FROM EXTERNAL
  //////////////////////////////////////////////////////////////////////////////

  // XML Parser: Node start
  function _StartElement($parser, $name, $attrs) {
    $this->_CurrentNode =& $this->_CurrentNode->AppendNode($name);
  }

  // XML Parser: Node end
  function _EndElement($parser, $name) {
    $this->_CurrentNode->Value = trim($this->_CurrentNode->Value);
    $this->_CurrentNode =& $this->_CurrentNode->ParentNode;
  }

  // XML Parser: Node value
  function _CharacterData($parser, $data) {
    $this->_CurrentNode->Value .= $data;
  }
}

?>