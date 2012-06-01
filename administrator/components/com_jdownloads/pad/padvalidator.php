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
// PADVALIDATOR.PHP
//
// DESCRIPTION
//
// Representation of a PAD validator in the PADValidator class.
// Use this class to validate a PAD file against the PAD specification.
//
// SAMPLE CODE
//
// // Create PAD file object for an URL (could also be a local file)
// $PAD = new PADFile("http://myproduct.com/pad_file.xml");
//
// // Load PAD file (see Constants section for possible error codes)
// if ( !$PAD->Load() )
//   die "Cannot load PAD file. Error Code: " . $PAD->LastError;
//
// // Create PAD validator file object for for the local pad_spec.xml file
// $PADValidator = new PADValidator("pad_spec.xml");
//
// // Load PAD file (see Constants section for possible error codes)
// if ( !$PADValidator->Load() )
//   die "Cannot load PAD Validator. Error Code: " . $PADValidator->LastError;
//
// // Validate
// $nErrors = $PADValidator->Validate($PAD);
//
// // Print validation errors
// echo $nErrors . " Errors";
// if ( $nErrors > 0 )
//   foreach($PADValidator->ValidationErrors as $err)
//     $err->Dump();
//
// HISTORY
//
// 2004-08-16 Initial Release (Oliver Grahl, ASP PAD Support)
//
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Includes
//////////////////////////////////////////////////////////////////////////////

include_once("padfile.php");
include_once("padspec.php");


//////////////////////////////////////////////////////////////////////////////
// Classes
//////////////////////////////////////////////////////////////////////////////

// PADValidationError class
// Represents an abstract PAD validation error
class PADValidationError
{
  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: &$PADValidator - reference to the PADValidator object holding this error
  function PADValidationError(&$PADValidator)
  {
    // Append this error to the array
    array_push($PADValidator->ValidationErrors, &$this);
  }


  //////////////////////////////////////////////////////////////////////////////
  // Methods
  //////////////////////////////////////////////////////////////////////////////

  // Dump error to HTML
  function Dump()
  {
    echo "Unknown Error.";
  }

  // Dump a name/value pair
  // IN: $Name  - the name
  // IN: $Value - the corresponding value
  function DumpValue($Name, $Value)
  {
    echo "<pre><b>" . htmlspecialchars($Name . ":") . "</b> ";
    if ( $Value == "" )
      echo "<i>(empty)</i>";
    else
      echo htmlspecialchars($Value);
    echo "</pre>";
  }

  // Dump an error string
  // IN: $Title - the title
  // IN: $Text  - the error text
  // IN: $Descr - the error description
  function DumpError($Title, $Text, $Descr)
  {
    echo "<b>" . htmlspecialchars($Title) . "</b> " .
         htmlspecialchars($Text) . " " .
         "<i>" . htmlspecialchars($Descr) . "</i>";
  }
}

// PADValidationSimpleError class (derives from PADValidationError)
// Represents a PAD validation error
class PADValidationSimpleError extends PADValidationError
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $SpecFieldNode;
  var $Value;
  var $ErrorText;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: &$PADValidator - reference to the PADValidator object holding this error
  // IN: &$SpecFieldNode - reference to the XMLNode object holding the field spec
  // IN: $Value          - current value of the field
  function PADValidationSimpleError(&$PADValidator, &$SpecFieldNode, $Value, $ErrorText)
  {
    // Inherited
    parent::PADValidationError(&$PADValidator);

    $this->SpecFieldNode = $SpecFieldNode;
    $this->Value = $Value;

    $this->ErrorText = $ErrorText;
  }


  //////////////////////////////////////////////////////////////////////////////
  // Methods
  //////////////////////////////////////////////////////////////////////////////

  // Dump error to HTML
  function Dump()
  {
    $this->DumpValue($this->SpecFieldNode->GetValue("Name"), $this->Value);
    $this->DumpError($this->SpecFieldNode->GetValue("Title"), $this->ErrorText, "");
  }
}

// PADValidationRegExError class (derives from PADValidationError)
// Represents a PAD validation error regarding field regular expressions
class PADValidationRegExError extends PADValidationError
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $SpecFieldNode;
  var $Value;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: &$PADValidator - reference to the PADValidator object holding this error
  // IN: &$SpecFieldNode - reference to the XMLNode object holding the field spec
  // IN: $Value          - current value of the field
  function PADValidationRegExError(&$PADValidator, &$SpecFieldNode, $Value)
  {
    // Inherited
    parent::PADValidationError(&$PADValidator);

    $this->SpecFieldNode = $SpecFieldNode;
    $this->Value = $Value;
  }


  //////////////////////////////////////////////////////////////////////////////
  // Methods
  //////////////////////////////////////////////////////////////////////////////

  // Dump error to HTML
  function Dump()
  {
    $this->DumpValue($this->SpecFieldNode->GetValue("Name"), $this->Value);
    $this->DumpError($this->SpecFieldNode->GetValue("Title"),
                     "does not match specification:",
                     $this->SpecFieldNode->GetValue("RegExDocumentation"));
  }
}

// PADValidationDependencyError class (derives from PADValidationError)
// Represents a PAD validation error regarding field dependencies
// DRAFT - CURRENTLY NOT IMPLEMENTED
class PADValidationDependencyError extends PADValidationError
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $SpecFieldNode1;
  var $SpecFieldNode2;
  var $Value1;
  var $Value2;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: &$PADValidator - reference to the PADValidator object holding this error
  // IN: &$SpecFieldNode - reference to the XMLNode object holding the field spec
  // IN: $Value          - current value of the field
  function PADValidationDependencyError(&$PADValidator, &$SpecFieldNode1, $Value1, &$SpecFieldNode2, $Value2)
  {
    // Inherited
    parent::PADValidationError(&$PADValidator);

    $this->SpecFieldNode1 = $SpecFieldNode1;
    $this->SpecFieldNode2 = $SpecFieldNode2;
    $this->Value1 = $Value1;
    $this->Value2 = $Value2;
  }


  //////////////////////////////////////////////////////////////////////////////
  // Methods
  //////////////////////////////////////////////////////////////////////////////

  // Dump error to HTML
  function Dump()
  {
    $this->DumpValue($this->SpecFieldNode1->GetValue("Name"), $this->Value1);
    $this->DumpValue($this->SpecFieldNode2->GetValue("Name"), $this->Value2);
    $this->DumpError($this->SpecFieldNode1->GetValue("Title") . ", " .
                     $this->SpecFieldNode2->GetValue("Title"),
                     "do not match dependency:",
                     $this->SpecFieldNode1->GetValue("Dependencies"));
  }
}


// PADValidator class (derives from PADSpec)
// Represents a PAD Validator
class PADValidator extends PADSpec
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  var $ValidationErrors;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: $URL - the URL or local path of the PAD spec file (optional)
  function PADValidator($URL = "")
  {
    // Inherited
    parent::PADSpec($URL);

    // Inits
    $this->ValidationErrors = array();
  }


  //////////////////////////////////////////////////////////////////////////////
  // Public Methods
  //////////////////////////////////////////////////////////////////////////////

  // Validate
  // IN:     $PADFile - reference to the PADFile object to validate
  // RETURNS: number of errors found (see ValidationErrors)
  function Validate(&$PADFile)
  {
    // Clear
    $this->ValidationErrors = array();


    // Verify RegEx: Walk over all fields in the spec
    foreach($this->FieldsNode->ChildNodes as $SpecFieldNode)
    {
      // Get Path and RegEx for this field
      $Path = $SpecFieldNode->GetValue("Path");
      $RegEx = $SpecFieldNode->GetValue("RegEx");

      // Find the field content in the PAD
      $PADFieldValue = $PADFile->XML->GetValue($Path);

      // Match against the RegEx
      if ( !preg_match("/" . $RegEx . "/", $PADFieldValue) )
        new PADValidationRegExError($this, $SpecFieldNode, $PADFieldValue);
    }


    // Verify descriptions in languages other than English
    $DescrFieldNames = array("Keywords", "Char_Desc_45", "Char_Desc_80",
                             "Char_Desc_250", "Char_Desc_450", "Char_Desc_2000");
    $NodeDescriptions =& $PADFile->XML->FindNodeByPath("XML_DIZ_INFO/Program_Descriptions");
    if ($NodeDescriptions)
      foreach($NodeDescriptions->ChildNodes as $DescrNode)
        if ( $DescrNode->Name != "English" )
          foreach($DescrFieldNames as $DescrFieldName)
          {
            // Find the spec field (with English instead of this language)
            $Path = "XML_DIZ_INFO/Program_Descriptions/" . $DescrNode->Name . "/" . $DescrFieldName;
            $SpecFieldNode = $this->FindFieldNode("XML_DIZ_INFO/Program_Descriptions/English/" . $DescrFieldName);

            // Overriding the Path child node does not work yet, so it will always show 'English'
            //$NodePath = $SpecFieldNode->FindNodeByName("Path");
            //$NodePath->Value = $Path;

            // Get RegEx for this field
            $RegEx = $SpecFieldNode->GetValue("RegEx");

            // Find the field content in the PAD
            $PADFieldValue = $PADFile->XML->GetValue($Path);

            // Match against the RegEx
            if ( !preg_match("/" . $RegEx . "/", $PADFieldValue) )
              new PADValidationRegExError($this, $SpecFieldNode, $PADFieldValue);
          }


/*
    // Verify dependencies
    // DRAFT - CURRENTLY NOT IMPLEMENTED

    // Program_Cost_Dollars should not be empty for non-free software types
    $PADFieldValue = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Program_Cost_Dollars");
    $SpecFieldNode = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Program_Cost_Dollars");
    $PADFieldValue2 = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Program_Type");
    $SpecFieldNode2 = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Program_Type");
    if ( preg_match("/(Shareware|Commercial|Data Only)/", $PADFieldValue2) &&
         ($PADFieldValue == "") )
      new PADValidationDependencyError($this, $SpecFieldNode, $PADFieldValue, $SpecFieldNode2, $PADFieldValue2);

    // Program_Cost_Other_Code must not be empty if Program_Cost_Other is not empty
    $PADFieldValue = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Program_Cost_Other_Code");
    $SpecFieldNode = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Program_Cost_Other_Code");
    $PADFieldValue2 = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Program_Cost_Other");
    $SpecFieldNode2 = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Program_Cost_Other");
    if ( ($PADFieldValue2 != "") && ($PADFieldValue == "") )
      new PADValidationDependencyError($this, $SpecFieldNode, $PADFieldValue, $SpecFieldNode2, $PADFieldValue2);

    // Expire_Based_On must not be empty if Expire_Count is not empty
    $PADFieldValue = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Expire_Info/Expire_Based_On");
    $SpecFieldNode = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Expire_Info/Expire_Based_On");
    $PADFieldValue2 = $PADFile->XML->GetValue("XML_DIZ_INFO/Program_Info/Expire_Info/Expire_Count");
    $SpecFieldNode2 = $this->FindFieldNode("XML_DIZ_INFO/Program_Info/Expire_Info/Expire_Count");
    if ( ($PADFieldValue == "") && ($PADFieldValue2 == "") )
      new PADValidationDependencyError($this, $SpecFieldNode, $PADFieldValue, $SpecFieldNode2, $PADFieldValue2);
*/


    // Verify URL against Application_XML_File_URL
    $PADFieldValue = $PADFile->XML->GetValue("XML_DIZ_INFO/Web_Info/Application_URLs/Application_XML_File_URL");
    $SpecFieldNode = $this->FindFieldNode("XML_DIZ_INFO/Web_Info/Application_URLs/Application_XML_File_URL");
    if ( strcasecmp($PADFile->URL, $PADFieldValue) != 0 )
      new PADValidationSimpleError($this, $SpecFieldNode, $PADFieldValue, "does not match the URL you entered.");


    // Return number of errors
    return count($this->ValidationErrors);
  }
}

?>