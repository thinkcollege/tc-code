function toolTip(txt,top,left) {
  var el=document.getElementById('circleDisplay');
  
  var text=new Array();
  text[0]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Video: </strong>This interview with Amy Dwyre, an experienced and successful job developer, describes the discovery and assessment process, the development of a Positive Personal Profile (PPP) and explains how to use the PPP in the job match and marketing process.</span>";
  text[1]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Publications: </strong>In this section, you will find a publication that helps job developers create a positive approach with employers and an article with an overview on the importance of college to job success.</span>";
  text[2]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Coming Soon<br />Learning Activity: </strong>Use this online tool to learn how to gather the best information about a student by creating a Positive Personal Profile (PPP) and developing a marketing script for potential employers. You can also practice your skills with a case study example.</span>";
 text[3]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>TC Exchange: </strong>This is where people can participate in an in-depth discussion of the module topic.</span>";
  text[4]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Podcast: </strong>This audio podcast with Amy Dwyre gives an overview of the steps necessary to complete a Positive Personal Profile (PPP) and describes how to use it to develop an individualized strategy for competitive employment."; 
  text[5]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Webinar: </strong>A 90 minute webinar with Amy Dwyre, Job Development Specialist at TransCen, Inc. that teaches us how to help students get jobs by focusing on strengths and talking to employers with a \"Features to Benefits\" approach that highlights what the student will bring to the workplace.</span>"; 
  text[6]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Resources: </strong>Here you will find resources that will help you to get to know your students and prepare materials for employers. </span>"; 
  text[7]="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2><span><strong>Resources: </strong>Here you will find resources that will help you to get to know your students and prepare materials for employers.  </span>"; 
     
     el.innerHTML=text[txt];
     el.style.top="126px";
     el.style.left="68px";
	el.style.fontSize="89%";

     el.style.display="block";
  document.onmouseout=function() {
     el.innerHTML="<h1>MODULE 4</h1><h2>JOB DEVELOPMENT: STRENGTH-BASED MARKETING</h2>";
     el.style.top="126px";
     el.style.left="68px";
	el.style.fontSize="89%";
  }
 }