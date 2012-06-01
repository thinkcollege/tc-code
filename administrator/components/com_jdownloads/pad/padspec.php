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
// PADSPEC.PHP
//
// DESCRIPTION
//
// Representation of a pad_spec.xml file in the PADSpec class.
// Use this class to load a PAD Spec file. Use the FindFieldNode method to
// find the spec of a specific PAD field.
//
// SAMPLE CODE
//
// // Create PADSpec file object for the local pad_spec.xml file (could also be an URL)
// $PADSpec = new PADSpec("pad_spec.xml");
//
// // Load file (see Constants section for possible error codes)
// if ( !$PADSpec->Load() )
//   die "Cannot load PAD Spec. Error Code: " . $PADSpec->LastError;
//
// // Find spec for a specific PAD field
// echo $PADSpec->FindFieldNode("XML_DIZ_INFO/Program_Info/Program_Name");
//
// HISTORY
//
// 2006-06-20 PHP5 compatibility (Oliver Grahl, ASP PAD Support)
// 2004-08-16 Initial Release (Oliver Grahl, ASP PAD Support)
//
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Includes
//////////////////////////////////////////////////////////////////////////////

include_once("xmlfile.php");


//////////////////////////////////////////////////////////////////////////////
// Classes
//////////////////////////////////////////////////////////////////////////////

// PADSpec (derives from XMLFile)
// Represents the PAD specification based on a XML PAD spec file
class PADSpec extends XMLFile
{
  //////////////////////////////////////////////////////////////////////////////
  // Public Properties
  //////////////////////////////////////////////////////////////////////////////

  // Reference to the /PAD_Spec/Fields node
  var $FieldsNode;


  //////////////////////////////////////////////////////////////////////////////
  // Construction
  //////////////////////////////////////////////////////////////////////////////

  // Constructor
  // IN: $URL - the URL or local path of the PAD spec file (optional)
  function PADSpec($URL = "")
  {
    // Inherited
    parent::XMLFile($URL);
  }


  //////////////////////////////////////////////////////////////////////////////
  // Public Methods
  //////////////////////////////////////////////////////////////////////////////

  // Load
  // RETURNS: true  - Success (LastError is ERR_NO_ERROR)
  //          false - Failure (see LastError)
  function Load()
  {
    $ret = parent::Load();

    $this->FieldsNode =& $this->XML->FindNodeByPath("PAD_Spec/Fields");

    return $ret;
  }

  // Find spec node for field with Path
  // IN:      $Path - path of the PAD field, i.e. XML_DIZ_INFO/Company_Info/Company_Name
  // RETURNS: reference to the Field node in the spec, NULL if not found
  function &FindFieldNode($Path)
  {
    // Walk over all fields in the spec and compare Path
    foreach($this->FieldsNode->ChildNodes as $FieldNode)
      if ( $FieldNode->GetValue("Path") == $Path )
        return $FieldNode;

    // Not found
    // To make PHP5 happy, we will not return NULL, but a variable which
    // has a value of NULL.
    $NULL = NULL;
    return $NULL;
  }
}

?>