<?php
   defined('_JEXEC') or die('Restricted Access');
   jimport( 'joomla.application.component.view');
jimport( 'joomla.document.pdf.pdf' );

   class CtpViewResults extends JView
   {
      function display($tpl = null)
      {
         	global $mainframe;
      
		
    
        
         $document = &JFactory::getDocument();
         $document->setName('think_college'. date('Y-m-d'));
         $document->setTitle('How to Become a CTP: Checklist--http://www.thinkcollege.net/think-college-live/');
         $document->setDescription('Financial Aid module of Think College Live: How to Become a CTP');
         $document->setMetaData('keywords', 'ID/DD College CTP');
         $document->setGenerator('Thinkcollege.net');

         
         parent::display($tpl);
         
      }
   }
?>