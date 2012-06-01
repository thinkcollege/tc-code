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
<div id="descDiv">

<p><strong>Getting Started</strong><br />
Work cooperatively with your financial aid administrator and get to know the FSA representatives who you will likely have contact with.  Do you know:</p>

<ul><li><strong>School Participation Team (SPT):</strong> this is the FSA regional team that typically handles all changes to your Federal student aid agreement; and</li>
<li><strong>Eligibility Analyst:</strong> this is the regional school participation team member who will be assigned to your CTP application.  You and your financial aid administrator may participate in discussions with the eligibility analyst as your application is being reviewed.</li></ul>
<p>Check off the items that you have completed, below, and on the next page.  When you reach the end of the list press the "Show my checklist" button.  You will get a list of items you still need to complete, which you can print or download.</p></div>
<form class="search" action="" method="post" enctype="application/x-www-form-urlencoded" id="CtpForm"><fieldset>
            <legend>Checklist, Page 1</legend><h2 class="h2blue">I. Satisfactory Academic Progress (SAP)</h2><span class="example"><strong>An Example:</strong> <a href="/think-college-live/financial-aid/become-a-ctp/sap-example" target="_blank">Open in new window</a> | <a href="/components/com_ctp/views/ctp/tmpl/docs/CTP_how_to_example_1.doc" target="_blank">Download as Word document</a></span><p class="subhed"><em>One component of your application must include information about your institution's policy for determining whether a student enrolled in the program is making satisfactory academic progress.  Financial aid professionals are familiar with these requirements.</em></p><ul class="quesList">
<li><input type="checkbox" id="q1" name="q1" value="1" /> <label for="q1">Did you include an explanation of the institution's current SAP policy and how it is similar or dissimilar to the SAP Policy pertinent to the CTP Program?</label></li> 


<li><input type="checkbox" id="q2" name="q2" value="1" /> <label for="q2">Did you include the length of time in which students are expected to complete program, including the number of hours or credits that are required to complete the program?</label></li>
<li><input type="checkbox" id="q3" name="q3" value="1" /> <label for="q3">Did you include the minimum number of credits or hours that a student can take during one semester and a statement related to the percentage of time it would take students to complete the program based on this minimum number of hours?</label><div id="moreInformation" class="more1"></div></li>
<li><input type="checkbox" id="q4" name="q4" value="1" /> <label for="q4">
Did you include the prerequisites that may be required for the program?</label></li> 


<li><input type="checkbox" id="q5" name="q5" value="1" /> <label for="q5">Did you include the framework or system that programs use to measure student progress, such as a grading system or performance rubric?</label><div id="moreInformation" class="more2"></div></li>
<li><input type="checkbox" id="q6" name="q6" value="1" /> <label for="q6"> 
Did you explain the criteria that the program will use to assess whether a student meets a performance target?</label><div id="moreInformation" class="more3"></div></li> 


<li><input type="checkbox" id="q7" name="q7" value="1" /> <label for="q7">Did you include policies for addressing students who do not complete course content or who withdraw from a course?</label></li> 


<li><input type="checkbox" id="q8" name="q8" value="1" /> <label for="q8">Did you include how the institutional financial aid office will monitor this policy for the CTP Program?</label></li> 


<li><input type="checkbox" id="q9" name="q9" value="1" /> <label for="q9">Did you include procedures or steps that students can take to appeal decisions made under the program's SAP Policy?</label></li> 


<li><input type="checkbox" id="q10" name="q10" value="1" /> <label for="q10">Did you explain how course repeats are treated? </label><div id="moreInformation" class="more4"></div></li> 


<li><input type="checkbox" id="q11" name="q11" value="1" /> <label for="q11">Did you explain how the program counts attempted credits a student might take?</label><div id="moreInformation" class="more5"></div></li></ul>
<h2 class="h2blue">II. CLOCK HOURS</h2><span class="example"><strong>An Example:</strong> <a href="/think-college-live/financial-aid/become-a-ctp/clock-hours" target="_blank">Open in new window</a> | <a href="/components/com_ctp/views/ctp/tmpl/docs/CTP_how_to_example_2.doc" target="_blank">Download as Word document</a></span><ul class="quesList"><li><input type="checkbox" id="q12" name="q12" value="1" /> <label for="q12">
Did you include a statement about the clock hour expectations and measurements for students in the CTP?</label><div id="moreInformation" class="more6"></div></li>

</ul><h2 class="h2blue">III. CREDENTIAL OFFERED</h2><span class="example"><strong>An Example:</strong> <a href="/think-college-live/financial-aid/become-a-ctp/credential-offered" target="_blank">Open in new window</a> | <a href="/components/com_ctp/views/ctp/tmpl/docs/CTP_how_to_example_3.doc" target="_blank">Download as Word document</a></span><ul class="quesList"><li><input type="checkbox" id="q13" name="q13" value="1" /> <label for="q13">Did you include a description of the educational credential offered? </label><div id="moreInformation" class="more7"></div></li></ul>
<h2 class="h2blue">IV. ACCREDITATION LETTER</h2><ul class="quesList"><li><input type="checkbox" id="q14" name="q14" value="1" /> <label for="q14">
Did you include a copy of the letter sent to the institution's accrediting agency informing the agency of its comprehensive transition and postsecondary program?<br />
The letter or notice must include a description of:
<ul><li>SAP Policy</li>
<li>Program length including clock/credit hours</li>
<li>Education credential offered</li></ul></label></li> </ul><p id="step0commands2" class="heightfix"></p></fieldset><fieldset  id="page2"><legend>Checklist, Page 2</legend>
<h2 class="h2blue">V. CTP DEFINITION</h2>
<span class="example"><strong>An Example:</strong> <a href="/think-college-live/financial-aid/become-a-ctp/ctp-definition" target="_blank">Open in new window</a> | <a href="/components/com_ctp/views/ctp/tmpl/docs/CTP_how_to_example_4.doc" target="_blank">Download as Word document</a></span>
<p class="subhed"><em>Is offered by an institution of higher education.</em></p><ul class="quesList">
<li><input type="checkbox" id="q15" name="q15" value="1" /> <label for="q15">
Does your institution already participate in Federal Student Aid programs?</label></li> </ul>

<p class="subhed"><em>Is offered to students physically attending the institution.</em></p>
<ul class="quesList"><li><input type="checkbox" id="q16" name="q16" value="1" /> <label for="q16">Did you include evidence to assert that the courses in your program are offered on campus?</label></li> 


<li><input type="checkbox" id="q17" name="q17" value="1" /> <label for="q17">If students are able to take courses via distance education, did you provide a breakdown on the percentage of time that students spend on campus?</label></li> 


<li><input type="checkbox" id="q18" name="q18" value="1" /> <label for="q18">Can you provide information regarding the percentage of time that students spend on campus, in campus-related activities, and also in off-campus sites, such as work experiences and internships?</label></li>
</ul>
<p class="subhed"><em>Is designed to support students with intellectual disabilities who are seeking to continue academic, career and technical and independent living instruction at an institution of higher education in order to prepare for gainful employment. </em></p>
<ul class="quesList"><li><input type="checkbox" id="q19" name="q19" value="1" /> <label for="q19">Did you include evidence, such as course materials, syllabi, and student career plans that identify the academic, employment, and independent living goals and objectives for students?</label></li> 


<li><input type="checkbox" id="q20" name="q20" value="1" /> <label for="q20">Did you provide data to demonstrate links between program content and employment or education outcomes?</label></li> 


<li><input type="checkbox" id="q21" name="q21" value="1" /> <label for="q21">Did you include SOC Codes associated with the career objectives of the program?</label></li></ul>

<p class="subhed"><em>Advising and curriculum structure</em></p>
<ul class="quesList"><li><input type="checkbox" id="q22" name="q22" value="1" /> <label for="q22">Did you provide evidence such as advising descriptions, or examples of how students participate, that demonstrate that your program offers guidance to students regarding course selection, administrative processes, and planning for postsecondary outcomes in similar ways as it does to students without disabilities?</label></li> 


<li><input type="checkbox" id="q23" name="q23" value="1" /> <label for="q23">Have you allocated program resources and staff to ensure that students get guidance regarding course sequencing and selection?</label></li> 


<li><input type="checkbox" id="q24" name="q24" value="1" /> <label for="q24">Have you demonstrated that the sequence of courses students can select in aggregate, lead to a body of knowledge that will be marketable and result in realistic career or education outcomes?</label></li> </ul>

<p class="subhed"><em>Requires students with intellectual disabilities to participate on not less than a half-time basis as determined by the institution, with such participation focusing on academic components, and occurring through one or more of the following activities:</em></p><ol class="subhed"><li>Regular enrollment in credit-bearing courses with nondisabled students offered by the institution</li>
<li>Auditing or participating in courses with nondisabled students offered by the institution for which the student does not receive regular academic credit.</li>
<li>Enrollment in noncredit-bearing, non-degree courses with nondisabled students.</li>
<li> Participation in internships or work-based training in settings with nondisabled individuals.</li></ol>
<ul class="quesList"><li><input type="checkbox" id="q25" name="q25" value="1" /> <label for="q25">
Did you include a breakdown of the total program clock hours and the varying time that students will spend in inclusive activities and courses?</label></li> 


<li><input type="checkbox" id="q26" name="q26" value="1" /> <label for="q26">Did you provide evidence, such as past student transcripts, institutional and informational materials that demonstrate that students may participate in courses, internships, and work-based training with their peers without disabilities?</label></li> 


<li><input type="checkbox" id="q27" name="q27" value="1" /> <label for="q27">Did you include information about campus awareness activities and trainings that would enhance the receptiveness of faculty to instruct students with intellectual disabilities in their classes?</label></li> 


<li><input type="checkbox" id="q28" name="q28" value="1" /> <label for="q28">Did you include materials regarding peer-mentoring programs that may exist on your campus between students with and without disabilities?</label></li></ul>
<p class="subhed"><em>Requires students with intellectual disabilities to be socially and academically integrated with non-disabled students to the maximum extent possible.</em></p>
<ul class="quesList"><li><input type="checkbox" id="q29" name="q29" value="1" /> <label for="q29">Did you describe the process by which you would ensure that students are included in academic and non-academic campus activities to the maximum extent possible? </label></li> 


<li><input type="checkbox" id="q30" name="q30" value="1" /> <label for="q30">Did you include any informational materials to support the process such as event announcements and training tools that demonstrate that the program is reaching out to the campus community to enhance awareness regarding students with intellectual disabilities?</label></li> 


<li><input type="checkbox" id="q31" name="q31" value="1" /> <label for="q31">Did you include examples of your previous experience in moving students to more inclusive academic and non-academic activities on your campus? </label></li> 
</ul>
<h2 class="h2blue">VI. Additional Considerations Regarding Format/Organization of your Application</h2>
<ul class="quesList"><li><input type="checkbox" id="q32" name="q32" value="1" /> <label for="q32">
Did you check to makes sure that your program description did not exceed thirty (30) pages, including appendices?<br />Note--Headings in supplemental materials correspond to regulatory requirements to make it easy for review.</label></li> 
</ul><p id="step1commands2" class="heightfix"></p></fieldset><input type="hidden" name="task" value="results" />
            <input id="ShowSummary" type="submit" value="Show my checklist" /></form>