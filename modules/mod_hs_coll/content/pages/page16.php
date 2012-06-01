<div class="contentdiv clearfix"><h3>Choose (click on) the false statement:</h3>
<div class="tOrF tOrF1">In college, <strong>parent input</strong> is not always actively sought and may be discouraged at times.


</div>
<div class="tOrF tOrF2">Students should <strong>practice advocating for themselves</strong> in high school in order to be prepared to take on this role in college. 


</div>
<div class="tOrF tOrF3">College faculty or Disability Services staff will communicate directly with a <strong>student's parents</strong>.



</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
$('div.tOrF1').qtip({
   content: 'Please try again, this statement is true. ',
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
   content: 'Oops! Try again, this statement is true.  ',
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
   content: '<strong>Right!</strong> This is the false statement.  <br />College faculty or Disability Services staff will NOT communicate directly with a student\'s parents because under FERPA in college, a student\'s rights transfer to him/her.',
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