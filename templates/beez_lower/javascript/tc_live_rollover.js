function toolTip(txt,top,left) {
  var el=document.getElementById('circleDisplay');
  
  var text=new Array();
  text[0]="<img src=\"templates/beez_lower/images/tc_live/icon_video_sm.gif\" alt=\"Videos\" /><br /><h2>VIDEOS</h2><span>Many Think College Live modules include videos. Some feature students explaining their experiences in college. Others contain interviews with experts on higher education and disability. You'll also find discussion questions and suggested uses for each video.</span>";
  text[1]="<img src=\"templates/beez_lower/images/tc_live/icon_pubs_sm.gif\" alt=\"Publications\" /><br /><h2>PUBLICATIONS</h2><span>Think College produces publications targeted to several audiences: students, professionals, and families. Here you will find selected newsletters, journal articles, or Insight Briefs.</span>";
  text[2]="<img src=\"templates/beez_lower/images/tc_live/icon_activity_sm.gif\" alt=\"Learning Activities\" /><br /><h2>LEARNING ACTIVITIES</h2><span>Many Think College Live modules contain learning activities. These self-paced activities include interactive features that will help you build and test your knowledge on a module topic. </span>";
  text[3]="<img src=\"templates/beez_lower/images/tc_live/icon_exchange_sm.gif\" alt=\"TC Exchange\" /><br /><h2>TC EXCHANGE</h2><span>Think College Exchange is where the modules come to life. This is a moderated forum where you can share ideas about the modules, discuss the resources, and ask questions. The content is driven by Think College Live users like you.</span>"; 
  text[4]="<img src=\"templates/beez_lower/images/tc_live/icon_podcast_sm.gif\" alt=\"Podcasts\" /><br /><h2>PODCASTS</h2><span>If your learning style is auditory, these customized podcasts are a great way to access Think College content. They contain answers to frequently asked questions, interviews with disability and education experts, and links to discussion questions.</span>"; 
  text[5]="<img src=\"templates/beez_lower/images/tc_live/icon_webinar_sm.gif\" alt=\"Webinar Archives\" /><br /><h2>WEBINAR ARCHIVES</h2><span>Think College staff and partners are often asked to present and participate in webinars. Archived webinars are recordings of live online meetings, training sessions, and presentations. </span>"; 
  text[6]="<img src=\"templates/beez_lower/images/tc_live/icon_resources_sm.gif\" alt=\"Resources\" /><br /><h2>RESOURCES</h2><span>The resource section contains documents and other materials that are useful for face-to-face trainings or program capacity building.  Some were created by Think College, while others were submitted by colleges and universities across the United States. </span>"; 
     
     el.innerHTML=text[txt];
     el.style.top=top+"px";
     el.style.left=left+"px";

     el.style.display="block";
  document.onmouseout=function() {
     el.innerHTML="<img src=\"templates/beez_lower/images/tc_live/tc_live_logo.gif\" alt=\"Think College Live\" />";
     el.style.top="146px";
     el.style.left="68px";
  }
 }