<div class="contentdiv clearfix"><h3>Choose (click on) the false statement:</h3>
<div class="tOrF tOrF1">In high school, <strong>teachers and parents</strong> make sure a student gets the help he/she needs to be successful even if the student hasn't asked for help. 
</div>
<div class="tOrF tOrF2">In college, <strong>faculty</strong> will structure a student's time for him/her in order to meet assignment due dates.
</div>
<div class="tOrF tOrF3">In  high school, parents attend IEP meetings and <strong>advocate for the student</strong>.  
</strong>.
</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
$('div.tOrF1').qtip({
   content: 'Sorry! This statement is true.  ',
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
   content: '<strong>Nice job!</strong> This is the false statement.<br />In college, faculty will NOT structure a student\'s time for him/her. It is the responsibility of the student to be aware of due dates and to plan accordingly in order to complete the work on time.',
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
         
         target: 'rightMiddle',
         tooltip: 'leftMiddle'
      },
adjust: { x: 12, y: 0 } }


})
$('div.tOrF3').qtip({
   content: 'Try again! This statement is true. ',
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
         target: 'bottomRight',
         tooltip: 'bottomLeft'
      },
adjust: { x: 12, y: 0 } }


})
});
</script>