function toolTip(txt,top,left) {
  var el=document.getElementById('circleDisplay');
  
  var text=new Array();
  text[0]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span><strong>Video: </strong>This 12-minute video contains an interview with a disability service professional. </span>";
  text[1]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span><strong>Publications: </strong>This section contains a few select publications related to disability services and a link to the Think College Literature Database.  </span>";
  text[2]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span><strong>Learning Activity: </strong>Test your knowledge as you learn more about the key differences between disability services in high school vs. college.</span>";
  text[3]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span><strong>TC Exchange: </strong>This is where people can participate in an in-depth discussion of the module topic.</span>"; 
  text[4]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span><strong>Resources: </strong>Find more resources related to accessing disability services here, including useful tools to address college barriers and a link to the Think College TTA database. </span>"; 
  text[5]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span></span>"; 
  text[6]="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2><span></span>"; 
     
     el.innerHTML=text[txt];
     el.style.top=top+"px";
     el.style.left=left+"px";

     el.style.display="block";
  document.onmouseout=function() {
     el.innerHTML="<h1>MODULE 2:</h1><h2>ACCESSING DISABILITY SERVICES</h2>";
     el.style.top="146px";
     el.style.left="68px";
  }
 }