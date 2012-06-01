<div class="contentdiv clearfix"><h3>Choose (click on) the false statement:</h3>
<div class="tOrF tOrF1">Colleges are <strong><em>required</em></strong> to identify students with disabilities to provide them services.
</div>
<div class="tOrF tOrF2">In high school, students with disabilities are <strong><em>entitled</em></strong> to receive services.
</div>
<div class="tOrF tOrF3">In college, students with disabilities are supported in <strong><em>accessing</em></strong> college courses. 
</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
$('div.tOrF1').qtip({
   content: '<strong>Correct!</strong><br /> This is the false statement. Colleges are not required to identify students with disabilities. In a college setting, it is the responsibility of the student to identify themselves to the disability services office and to ask for help.',
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
         target: 'topRight',
         tooltip: 'topLeft'
      },
adjust: { x: 12, y: 0 } }


})
$('div.tOrF2').qtip({
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
$('div.tOrF3').qtip({
   content: 'Sorry! This statement is true.',
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
});
</script>