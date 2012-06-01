<div class="contentdiv clearfix"><h3>Choose (click on) the false statement:</h3>
<div class="tOrF tOrF1">In college, instructors expect students to refer to a course syllabus for assignment due dates.
</div>
<div class="tOrF tOrF2">In high school, teachers will check in with a student more often to determine whether he/she understands the material begin taught.  
</div>
<div class="tOrF tOrF3">In college, an instructor will make changes to what is being taught in order to accommodate a student with a disability. 
</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
$('div.tOrF1').qtip({
   content: 'Better luck next time! This statement is true. ',
   show: 'click',
   hide: 'unfocus',
style: { 
      width: 200,
      padding: 5,
      background: '#ff9999',
      color: 'black',
      textAlign: 'center',
	fontSize: '88%',
cursor:'pointer',
      border: {
         width: 7,
         radius: 5,
         color: '#ff9999'
      },
      
      name: 'blue' // Inherit the rest of the attributes from the preset dark style
   },

position: { 

corner: {
         target: 'topRight',
         tooltip: 'topLeft'
      },
adjust: { x: 12, y: 0 } }


})
$('div.tOrF2').qtip({
   content: 'Try again! this statement is true.',
   show: 'click',
   hide: 'unfocus',
style: { 
      width: 200,
      padding: 5,
      background: '#ff9999',
      color: 'black',
      textAlign: 'center',
	fontSize: '88%',
cursor:'pointer',
      border: {
         width: 7,
         radius: 5,
         color: '#ff9999'
      },
      
      name: 'blue' // Inherit the rest of the attributes from the preset dark style
   },

position: { 

corner: {
         
         target: 'rightMiddle',
         tooltip: 'leftMiddle'
      },
adjust: { x: 12, y: 0 } }


})
$('div.tOrF3').qtip({
   content: '<strong>Great job!</strong> This statement is false.<br />Unlike high school, faculty do not make modifications to the course content for students with disabilities. But they can provide opportunity for other ways for the student to access the course content. ',
   show: 'click',
   hide: 'unfocus',
style: { 
      width: 200,
      padding: 5,
      background: '#A2D959',
      color: 'black',
      textAlign: 'center',
	fontSize: '88%',
cursor:'pointer',
      border: {
         width: 7,
         radius: 5,
         color: '#A2D959'
      },
      
      name: 'blue' // Inherit the rest of the attributes from the preset dark style
   },

position: { 

corner: {
         target: 'bottomRight',
         tooltip: 'bottomLeft'
      },
adjust: { x: 12, y: 0 } }


})
});
</script>