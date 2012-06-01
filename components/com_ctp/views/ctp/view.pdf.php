<?php
   defined('_JEXEC') or die('Restricted Access');
   jimport( 'joomla.application.component.view');
jimport( 'joomla.document.pdf.pdf' );

   class CtpViewCtp extends JView
   {
      function display($tpl = null)
      {
         	global $mainframe;
      
		
    
         
         $document = &JFactory::getDocument();
         $document->setName('think_college'. date('Y-m-d'));
         $document->setTitle('Some Title');
         $document->setDescription('Some Description');
         $document->setMetaData('keywords', 'Some Keywords');
         $document->setGenerator('Some Generator');

         
         parent::display($tpl);
         
      }
   }
?>