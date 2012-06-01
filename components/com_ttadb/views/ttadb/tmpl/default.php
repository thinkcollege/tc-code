<?php defined('_JEXEC') or die('Restricted access');
  ?>
<div class="programsdatabase">
<h1>Searchable Training and Technical Assistance Database</h1>
<p class="introText">Please enter your email address and name to register as a user of the Think College Training and Technical Assistance Materials Database.  You will only need to register once.  We will add you to our email distribution list to receive the Think College monthly newsletter and occasional announcements. If you don't wish to receive these materials, check the box below.
Thank you for registering.  This information helps us to track the usage of our database for our funders.</p>
<form class="search_result search" action="<?php echo JRoute::_('&task=login'); ?>" method="post">
 <jdoc:include type="message" />
 <?php
	JRequest::setVar('cid', 0);
	$this->addItemForm($this->getModel('Type')->getIdByName('registrant'), $this->get('Data', 'Item'));
 ?>
 <p><button type="submit" class="Ttabutton">Register</button></p>
 <!-- Hack ** <input type="hidden" name="typeId" value="<?php echo $typeId; ?>" /> -->
 <input type="hidden" name="typeId" value="112" />
</form>

<p>For more information please contact us at <a href="mailto:thinkcollege@umb.edu">thinkcollege@umb.edu</a>.</p>
</div>