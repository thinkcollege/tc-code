<?php
   defined('_JEXEC') or die('Restricted Access');
   jimport( 'joomla.application.component.view');
jimport( 'joomla.document.pdf.pdf' );

   class ProgramsDatabaseViewProgram extends JView
   {
      function display($tpl = null)
      {
         	global $mainframe;
       	JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
    	$program	=& $this->get('Data');
		$questions	=& $this->get('Data', 'questions');
		
		$this->assignRef('program', $program);
		$this->assignRef('questions', $questions);
         
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