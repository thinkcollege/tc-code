<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
JHTML::stylesheet('styles.css', 'modules/mod_slider7/css/');

JHTML::script('jquery-1.3.2.min.js','modules/mod_slider7/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider7/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider7/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider7/js/');
JHTML::script('coda-slider.js','modules/mod_slider7/js/');?>

<h1>Working with Educational Coaches</h2>
	
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites">Tim</a></li>
                <li><a href="#files">Grace</a></li>
                <li><a href="#editor">Christine</a></li>
                <li><a href="#preview">Allie</a></li>
                <!-- <li><a href="#css"><?php echo $params->get('slider_title5', ''); ?></a></li>
                <li><a href="#terminal"><?php echo $params->get('slider_title6', ''); ?></a></li>
                <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li> -->
            </ul>

            <div class="scroll">
                <div class="scrollContainer">
                <div class="panel" id="sites"><p align="center"><img align="center" src="/modules/mod_slider7/images/tim.jpg" alt="Allie smiling" /><p class="picQuote"><em>Work with your coach to figure out what you <strong>really</strong> need help with at college</em></p></p></div>
                <div class="panel" id="files"><p align="center"><img align="center" src="/modules/mod_slider7/images/grace.jpg" alt="Michael at college" /><p class="picQuote"><em>I like to learn things on my own once I get used to the course.</em></p></p></div>
                <div class="panel" id="editor"><p align="center"><img align="center" src="/modules/mod_slider7/images/christine.jpg" alt="Michael at college" /><p class="picQuote"><em>Try doing work on your own before asking for help.</em></p></p></div>
                <div class="panel" id="preview"><p align="center"><img align="center" src="/modules/mod_slider7/images/allie_irene.jpg" alt="Michael at college" /><p class="picQuote"><em>I like my coach. She helped me learn how to get around the campus and I really like working with her.</em></p></p></div>
                <!-- <div class="panel" id="css"><p align="center"><?php echo $params->get('slider_body5', ''); ?></p></div>
                <div class="panel" id="terminal"><p align="center"><?php echo $params->get('slider_body6', ''); ?></p></div>
                <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div> -->
               
                </div>
            </div></div>
