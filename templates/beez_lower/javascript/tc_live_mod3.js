function toolTip(txt,top,left) {
  var el=document.getElementById('circleDisplay');
  
  var text=new Array();
  text[0]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>Video: </strong>This 14-minute video contains select questions and answers about the Financial Aid process by David Bergeron, US Department of Education and Ian Foss, Financial Services Administration from June 2011.</span>";
  text[1]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>Learning Activity: </strong>This is the place for professionals to learn more about the process of becoming a Comprehensive Transition Program (CTP). It includes a flow chart of the process and an interactive checklist for completing the application.</span>";
  text[2]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>TC Exchange: </strong>This is where people can participate in an in-depth discussion of the module topic.</span>";
  text[3]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>Podcast: </strong>This podcast addresses some of the key questions that students and families have about the process for applying for financial aid.  An FAQ to assist families and professionals with the process accompanies the podcast."; 
  text[4]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>Webinar: </strong>October 12th webinar with Ian Foss, US Department of Education Federal Student Aid Policy Liaison & Implementation</span>"; 
  text[5]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span><strong>Resources: </strong>Find more resources related to accessing financial aid for including a dictionary, FAQ for families and a link to the Think College TTA database. </span>"; 
  text[6]="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2><span></span>"; 
     
     el.innerHTML=text[txt];
     el.style.top=top+"px";
     el.style.left=left+"px";

     el.style.display="block";
  document.onmouseout=function() {
     el.innerHTML="<h1>MODULE 3:</h1><h2>FINANCIAL AID</h2>";
     el.style.top="146px";
     el.style.left="68px";
  }
 }