<?php
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
	JHTML::stylesheet('style.css', 'components/com_ctp/views/ctp/tmpl/css/', array('media'=>'all'));
		JHTML::script('jquery-1.5.2.min.js','components/com_ctp/views/ctp/tmpl/js/');
		JHTML::script('initialize.js','components/com_ctp/views/ctp/tmpl/js/');
		JHTML::script('formToWizard.js','components/com_ctp/views/ctp/tmpl/js/');
		JHTML::script('jquery.qtip-1.0.0-rc3.min.js','components/com_ctp/views/ctp/tmpl/js/');
		JHTML::script('jquerytips.js','components/com_ctp/views/ctp/tmpl/js/');
		$document =& JFactory::getDocument();
		$viewType        = $document->getType();
if($viewType == 'html') $document->addCustomTag( '<script type="text/javascript">jQuery(document).ready(function(){
            jQuery("#CtpForm").formToWizard({ submitButton: \'ShowSummary\' })
        });
       
       
</script>
	<!--[if IE]><style type="text/css">
.quesList li label { display: block; padding-left: 30px; position: relative; margin-top: -15px; }
</style><![endif]-->' );
$post = '';		?>
<div class="smIcon"><img src="templates/beez_lower/images/tc_live/icon_activity_sm.gif" alt="Video" height="49" width="49" /></div>
<h1>FINANCIAL AID</h1>
<h2 class="h2large">Learning Activity: How to Become a CTP<br />Checklist for Comprehensive Transition and Postsecondary (CTP) Programs for Students with Intellectual Disabilities to Participate in Federal Student Aid Programs</h2>
<div id="descDiv"><p>This checklist is intended help individuals at institutes of higher education who are serving students with intellectual disabilities compile the information required to become a Comprehensive Transition Program (CTP) allowing students to access certain federal student aid programs. Note: The information contained on this checklist is only intended to serve as descriptions of program features that are aligned with regulatory requirements and in no way guarantees that a particular program application will be reviewed favorably by Federal Student Aid (FSA).</p>

<p>The Checklist will cover the following topics:</p>
<ul><li>Satisfactory Academic Progress</li><li>
Clock Hours</li><li>
Credential Offered</li><li>
Accreditation Letter</li><li>
CTP Definition</li><li>
Additional Considerations</li></ul>

<p><a href="/components/com_ctp/views/ctp/tmpl/docs/ctp_decision_tree.pdf">Download a process diagram of how to become a CTP</a> (PDF)</p>
<p><a href="/think-college-live/financial-aid/become-a-ctp/blank-checklist">Click here to print a blank checklist for the application</a></p>

<p><a href="/think-college-live/financial-aid/become-a-ctp/checklist"><img src="/components/com_ctp/views/ctp/tmpl/images/to_checklist.gif" alt="Go to interactive Checklist" /></a></p>
<p><strong>For Discussion:</strong><br /><em><a href="/think-college-live/financial-aid/become-a-ctp/learning-activity-discussion">Use the Think College Exchange discussion space to discuss any concerns you have about your application.</a>  Peers and Think College staff will do their best to answer!<br /></em></p></div>