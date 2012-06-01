function toolTip(txt,top,left) {
  var el=document.getElementById('circleDisplay');
  
  var text=new Array();
  text[0]="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2><span><strong>Video: </strong>Coaches and Mentors: Helping College Students Thrive</span>";
  text[1]="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2><span><strong>Publications: </strong>In this section, you will find literature and publications that relate to coaching or mentoring</span>";
  text[2]="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2><span><strong>TC Exchange: </strong>This is where people can participate in an in-depth discussion of the module topic.</span>";
  text[3]="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2><span><strong>Podcast: </strong>Molly Boyle, a Think College staff member, discusses the role and purpose of the educational coach or mentor in helping students with intellectual disabilities to be included in a postsecondary educational setting.</span>"; 
  text[4]="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2><span><strong>Resources: </strong>This section contains documents that will be helpful when hiring or training coaches or mentors; also, a link to the Think College TTA database.</span>"; 
  text[5]="<img src=\"templates/beez_lower/images/tc_live/icon_webinar_sm.gif\" alt=\"Webinar Archives\" /><br /><h2>WEBINAR ARCHIVES</h2><span>Think College staff and partners are often asked to present and participate in webinars. Archived webinars are recordings of live online meetings, training sessions, and presentations. </span>"; 
  text[6]="<img src=\"templates/beez_lower/images/tc_live/icon_resources_sm.gif\" alt=\"Resources\" /><br /><h2>RESOURCES</h2><span>The resource section contains documents and other materials that are useful for face-to-face trainings or program capacity building.  Some were created by Think College, while others were submitted by colleges and universities across the United States. </span>"; 
     
     el.innerHTML=text[txt];
     el.style.top=top+"px";
     el.style.left=left+"px";

     el.style.display="block";
  document.onmouseout=function() {
     el.innerHTML="<h1>MODULE 1:</h1><h2>COACHING AND MENTORING</h2>";
     el.style.top="146px";
     el.style.left="68px";
  }
 }