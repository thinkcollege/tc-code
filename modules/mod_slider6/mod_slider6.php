<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::stylesheet('styles.css', 'modules/mod_slider6/css/');

JHTML::script('jquery-1.3.2.min.js','modules/mod_slider6/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider6/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider6/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider6/js/');
JHTML::script('coda-slider.js','modules/mod_slider6/js/'); ?>
<h1>Promoting Postsecondary Education</h2>
	
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites">Debra Hart</a></li>
                <li><a href="#files">Tom Sannicandro</a></li>
                <li><a href="#editor">Julia Landau</a></li>
                <li><a href="#preview">Robin Foley</a></li>
                <!-- <li><a href="#css"><?php echo $params->get('slider_title5', ''); ?></a></li>
                <li><a href="#terminal"><?php echo $params->get('slider_title6', ''); ?></a></li>
                <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li> -->
            </ul>

            <div class="scroll">
                <div class="scrollContainer">
                <div class="panel" id="sites"><p align="center"><object width="490" height="300"><param name="movie" value="http://www.youtube.com/v/5Rmsk4bay9o&hl=en_US&fs=1&&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/5Rmsk4bay9o&hl=en_US&fs=1&&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="490" height="300"></embed></object><p class="picQuote">Debra Hart, Director, Think College</p></p></div>
                <div class="panel" id="files"><p align="center"><object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/A0k4VhO6tSw&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/A0k4VhO6tSw&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object><p class="picQuote"><em>Massachusetts State Representative Tom Sannicandro on education and inclusion. </em><br /><br /></p></p></div>
                <div class="panel" id="editor"><p align="center"><object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/Uwq2V_oFOAM&hl=en_US&fs=1&&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/Uwq2V_oFOAM&hl=en_US&fs=1&&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object><p class="picQuote">Julia Landau, Esq.
Massachusetts Advocates for Children</p></p></div>
                <div class="panel" id="preview"><p align="center"><object width="500" height="300"><param name="movie" value="http://www.youtube.com/v/W2Ym-919JL4&hl=en_US&fs=1&&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/W2Ym-919JL4&hl=en_US&fs=1&&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="500" height="300"></embed></object><p class="picQuote">Robin Foley, Director, Special Education Projects. Federation for Children with Special Needs</p></p></div>
                <!-- <div class="panel" id="css"><p align="center"><?php echo $params->get('slider_body5', ''); ?></p></div>
                <div class="panel" id="terminal"><p align="center"><?php echo $params->get('slider_body6', ''); ?></p></div>
                <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div> -->
               
                </div>
            </div></div>
