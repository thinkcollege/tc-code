<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="./" method="post" name="adminForm" enctype="multipart/form-data">
 <p>This form will allow you to upload a Common Seperated Value (CSV) file to import large amounts
 	of data into the system.  Currently the rows in the file must be ordered in the same way the
 	attributes of the type to be imported are ordered.  If an error occurs on a row after row 1 all
 	the rows between the first row and the row before the error occurred on have been imporeted.
 	To prevent duplicate entries you should remove these rows from the file once the error has been
 	corrected.  If an error occurs and when re-submitting the form and error is reported that the
 	file already exists, please change the name of the file, e.g. add 1 to the end of the file name,
 	befor uploading.</p>
 <label for="file">CSV file to upload:</label>
 <input type="file" name="Filedata" id="file" value="" />

 <input type="hidden" name="option" value="<?php echo strtolower(JRequest::getVar('option', '')); ?>" />
 <input type="hidden" name="task" value="import" />
 <input type="hidden" name="typeId" value="<?php echo JRequest::getInt('typeId'); ?>" /> 
 <input type="hidden" name="returnTo" value="<?php echo htmlentities($_SERVER['HTTP_REFERER'], ENT_COMPAT, 'UTF-8'); ?>" />
 <?php echo JHTML::_('form.token'); ?>
</form>