<?php
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
	JHTML::stylesheet('style.css', 'components/com_ctp/views/results/tmpl/css/' , array('media'=>'all'));
		$document =& JFactory::getDocument();
		$viewType        = $document->getType();

$post   = JRequest::get( 'post' );
# print_r($post);
 ?>
<div class="smIcon"><img src="templates/beez_lower/images/tc_live/icon_activity_sm.gif" alt="Video" height="49" width="49" /></div>
<div id="printPdf"><form id="printForm">
<input type="button" value="Print this page" onClick="window.print()">
</form><form id="pdfForm" action="/think-college-live/financial-aid/become-a-ctp?task=results&format=pdf" method="post"><?php 	
foreach($post as $list=>$switch) echo '<input type="hidden" name="' . $list . '" value="' . $switch .'" />'; ?> <input id="getpdf" type="submit" value="Download as a PDF" /></form></div>
<?php echo $viewType == 'pdf'? '<h1>Checklist</h1>' : '<h1>FINANCIAL AID</h1>'; ?>
<h2 class="h2large">Learning Activity: How to Become a CTP</h2>
<?php echo $viewType == 'pdf'? '<p><strong>The list below shows items you need to complete for your CTP application.  Items you have already completed are greyed out.</strong></p>' : ''; ?>
<h2 class="h2blue">I. Satisfactory Academic Progress (SAP)</h2><p class="subhed"><em>One component of your application must include information about your institution's policy for determining whether a student enrolled in the program is making satisfactory academic progress.  Financial aid professionals are familiar with these requirements.</em></p><ul class="resultList">
<li id="q1"<?php echo $post['q1'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include an explanation of the institution's current SAP policy and how it is similar or dissimilar to the SAP Policy pertinent to the CTP Program?<?php echo $post['q1'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q2"<?php echo $post['q2'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include the length of time in which students are expected to complete program, including the number of hours or credits that are required to complete the program?<?php echo $post['q2'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
<li id="q3"<?php echo $post['q3'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include the minimum number of credits or hours that a student can take during one semester and a statement related to the percentage of time it would take students to complete the program based on this minimum number of hours?<?php echo $post['q3'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
<li id="q4"<?php echo $post['q4'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include the prerequisites that may be required for the program?<?php echo $post['q4'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q5"<?php echo $post['q5'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include the framework or system that programs use to measure student progress, such as a grading system or performance rubric?<?php echo $post['q5'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
<li id="q6"<?php echo $post['q6'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>
Did you explain the criteria that the program will use to assess whether a student meets a performance target?<?php echo $post['q6'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q7"<?php echo $post['q7'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include policies for addressing students who do not complete course content or who withdraw from a course?<?php echo $post['q7'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q8"<?php echo $post['q8'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include how the institutional financial aid office will monitor this policy for the CTP Program?<?php echo $post['q8'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q9"<?php echo $post['q9'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include procedures or steps that students can take to appeal decisions made under the program's SAP Policy?<?php echo $post['q9'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q10"<?php echo $post['q10'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you explain how course repeats are treated?<?php echo $post['q10'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q11"<?php echo $post['q11'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you explain how the program counts attempted credits a student might take?<?php echo $post['q11'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>
<h2 class="h2blue">II. CLOCK HOURS</h2><ul class="resultList"><li id="q12"<?php echo $post['q12'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?> 
Did you include a statement about the clock hour expectations and measurements for students in the CTP?<?php echo $post['q12'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

</ul><h2 class="h2blue">III. CREDENTIAL OFFERED</h2><ul class="resultList"><li id="q13"<?php echo $post['q13'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include a description of the educational credential offered?<?php echo $post['q13'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>
<h2 class="h2blue">IV. ACCREDITATION LETTER</h2><ul class="resultList"><li id="q14"<?php echo $post['q14'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>
Did you include a copy of the letter sent to the institution's accrediting agency informing the agency of its comprehensive transition and postsecondary program?<br />
The letter or notice must include a description of:
<ul><li>SAP Policy</li>
<li>Program length including clock/credit hours</li>
<li>Education credential offered</li></ul><?php echo $post['q14'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>
<h2 class="h2blue">V. CTP DEFINITION</h2>
<p class="subhed"><em>Is offered by an institution of higher education.</em></p><ul class="resultList">
<li id="q15"<?php echo $post['q15'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>
Does your institution already participate in Federal Student Aid programs?<?php echo $post['q15'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>

<p class="subhed"><em>Is offered to students physically attending the institution.</em></p>
<ul class="resultList"><li id="q16"<?php echo $post['q16'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include evidence to assert that the courses in your program are offered on campus?<?php echo $post['q16'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>


<li id="q17"<?php echo $post['q17'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>If students are able to take courses via distance education, did you provide a breakdown on the percentage of time that students spend on campus?<?php echo $post['q17'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q18"<?php echo $post['q18'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Can you provide information regarding the percentage of time that students spend on campus, in campus-related activities, and also in off-campus sites, such as work experiences and internships?<?php echo $post['q18'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
</ul>
<p class="subhed"><em>Is designed to support students with intellectual disabilities who are seeking to continue academic, career and technical and independent living instruction at an institution of higher education in order to prepare for gainful employment. </em></p>
<ul class="resultList"><li id="q19"<?php echo $post['q19'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include evidence, such as course materials, syllabi, and student career plans that identify the academic, employment, and independent living goals and objectives for students?<?php echo $post['q19'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q20"<?php echo $post['q20'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you provide data to demonstrate links between program content and employment or education outcomes?<?php echo $post['q20'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q21"<?php echo $post['q21'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include SOC Codes associated with the career objectives of the program?<?php echo $post['q21'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>

<p class="subhed"><em>Advising and curriculum structure</em></p>
<ul class="resultList"><li id="q22"<?php echo $post['q22'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you provide evidence such as advising descriptions, or examples of how students participate, that demonstrate that your program offers guidance to students regarding course selection, administrative processes, and planning for postsecondary outcomes in similar ways as it does to students without disabilities?<?php echo $post['q22'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q23"<?php echo $post['q23'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Have you allocated program resources and staff to ensure that students get guidance regarding course sequencing and selection?<?php echo $post['q23'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q24"<?php echo $post['q24'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Have you demonstrated that the sequence of courses students can select in aggregate, lead to a body of knowledge that will be marketable and result in realistic career or education outcomes?<?php echo $post['q24'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>

<p class="subhed"><em>Requires students with intellectual disabilities to participate on not less than a half-time basis as determined by the institution, with such participation focusing on academic components, and occurring through one or more of the following activities:</em></p><ol class="subhed"><li>Regular enrollment in credit-bearing courses with nondisabled students offered by the institution</li>
<li>Auditing or participating in courses with nondisabled students offered by the institution for which the student does not receive regular academic credit.</li>
<li>Enrollment in noncredit-bearing, non-degree courses with nondisabled students.</li>
<li> Participation in internships or work-based training in settings with nondisabled individuals.</li></ol>
<ul class="resultList"><li id="q25"<?php echo $post['q25'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>
Did you include a breakdown of the total program clock hours and the varying time that students will spend in inclusive activities and courses?<?php echo $post['q25'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q26"<?php echo $post['q26'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you provide evidence, such as past student transcripts, institutional and informational materials that demonstrate that students may participate in courses, internships, and work-based training with their peers without disabilities?<?php echo $post['q26'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q27"<?php echo $post['q27'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include information about campus awareness activities and trainings that would enhance the receptiveness of faculty to instruct students with intellectual disabilities in their classes?<?php echo $post['q27'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q28"<?php echo $post['q28'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include materials regarding peer-mentoring programs that may exist on your campus between students with and without disabilities?<?php echo $post['q28'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li></ul>
<p class="subhed"><em>Requires students with intellectual disabilities to be socially and academically integrated with non-disabled students to the maximum extent possible.</em></p>
<ul class="resultList"><li id="q29"<?php echo $post['q29'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you describe the process by which you would ensure that students are included in academic and non-academic campus activities to the maximum extent possible?<?php echo $post['q29'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q30"<?php echo $post['q30'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include any informational materials to support the process such as event announcements and training tools that demonstrate that the program is reaching out to the campus community to enhance awareness regarding students with intellectual disabilities?<?php echo $post['q30'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>

<li id="q31"<?php echo $post['q31'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>Did you include examples of your previous experience in moving students to more inclusive academic and non-academic activities on your campus?<?php echo $post['q31'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
</ul>
<h2 class="h2blue">VI. Additional Considerations Regarding Format/Organization of your Application</h2>
<ul class="resultList"><li id="q32"<?php echo $post['q32'] == '1' ? ($viewType == 'pdf' ? '><font color="#cccccc">' : ' class="off">') : '>'; ?>
Did you check to makes sure that your program description did not exceed thirty (30) pages, including appendices?<br />Note--Headings in supplemental materials correspond to regulatory requirements to make it easy for review.<?php echo $post['q32'] == '1' && $viewType == 'pdf' ? '</font>' : ''; ?></li>
</ul><p align="center"><a href="/think-college-live/financial-aid/become-a-ctp">Restart Activity</a> | <a href="/think-college-live/financial-aid/become-a-ctp/learning-activity-discussion">Discuss</a></p>