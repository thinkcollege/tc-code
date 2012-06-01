<div class="contentdiv clearfix"><h3>Choose (click on) the false statement:</h3>
<div class="tOrF tOrF1">A high school will pay for and arrange testing to determine whether a student has a disability.

</div>
<div class="tOrF tOrF2">In college, a student can request help from the Disability Services Office if <a href="#" class="dotUnderW documentation">documentation</a> is provided.

</div>
<div class="tOrF tOrF3">Colleges will pay for and arrange a <a href="#" class="dotUnderW PCA">personal care attendant</a> for a student.


</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
$('div.tOrF1').qtip({
   content: 'Try again. This statement is true. ',
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
   content: 'Good try! But this statement is true. ',
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
   content: '<strong>Correct!</strong> This is the false statement.<br />A personal care attendant is considered a personal service, which the college is neither obligated to pay for and typically does not help the student arrange for one.',
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



$('a.documentation').qtip({
   content: 'paperwork that proves a student has a diagnosed disability and that disability impacts their participation in school',
   show: 'mouseover',
   hide: 'mouseout',
style: { 
      width: 350,
      padding: 5,
      background: '#ffffcc',
      color: 'black',
      textAlign: 'center',
	fontSize: '88%',
      border: {
         width: 7,
         radius: 5,
         color: '#ffffcc'
      },
      
      name: 'blue' // Inherit the rest of the attributes from the preset dark style
   },

position: {
      corner: {
         target: 'bottomMiddle',
         tooltip: 'topMiddle'
      }
   }


})



$('a.PCA').qtip({
   content: 'PCA, a paid, employed person who helps a person with a disability with activities of daily living',
   show: 'mouseover',
   hide: 'mouseout',
style: { 
      width: 350,
      padding: 5,
      background: '#ffffcc',
      color: 'black',
      textAlign: 'center',
	fontSize: '88%',
      border: {
         width: 7,
         radius: 5,
         color: '#ffffcc'
      },
      
      name: 'blue' // Inherit the rest of the attributes from the preset dark style
   },

position: {
      corner: {
         target: 'bottomMiddle',
         tooltip: 'top:Left'
      }
   }


})
});
</script>