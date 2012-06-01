<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
JHTML::script('jquery-1.3.2.min.js','modules/mod_slider2/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider2/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider2/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider2/js/');
JHTML::script('coda-slider.js','modules/mod_slider2/js/');
JHTML::stylesheet('styles.css', 'modules/mod_slider2/css/');?>
<h1>Getting Started</h2>
	<p><em>Here is some student advice on getting started...</em></p>
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites">Michael</a></li>
                <li><a href="#files">Beth</a></li>
               
                <li><a href="#preview">Cassidy</a></li>
                <li><a href="#css">Bob</a></li>
               <!--  <li><a href="#terminal"><?php echo $params->get('slider_title6', ''); ?></a></li>
                <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li> -->
            </ul>

            <div class="scroll">
                <div class="scrollContainer">
                <div class="panel" id="sites"><p align="center"><img align="center" src="/modules/mod_slider2/images/michael.jpg" alt="Michael at college" /><p class="picQuote"><em>I am looking into 3 colleges. They all have learning support services for students with disabilities. They all have the major that I want to pursue and one college has a dorm.</em></p></p></div>
                <div class="panel" id="files"><p align="center"><img align="center" src="/modules/mod_slider2/images/beth.jpg" alt="Beth at disability office" /><p class="picQuote"><em>If you have a certain disability and want to go to college you need to sign up in the disability office if they have one.  The student needs to have paperwork that documents their disability. After you do that, you can get help you might need.</em></p></p></div>
                
                <div class="panel" id="preview"><p align="center"><img align="center" src="/modules/mod_slider2/images/cassidy.jpg" alt="Cassidy at computer" /><p class="picQuote"><em>Read through the course catalog and ask other students about the classes.</em></p></p></div>
                <div class="panel" id="css"><p align="center"><img align="center" src="/modules/mod_slider2/images/bob.jpg" alt="Bob walking down hall" /><p class="picQuote"><em>Getting around the campus was so difficult at first. I got some help from Jane, my mobility instructor. She helped me learn the routes, and gave me some directions to help me. She told me to listen for cues like vehicles outside, students talking, and the humming from the vending machines. All those sound cues helped me learn to get around more on my own.</em></p></p></div>
                 <!--  <div class="panel" id="terminal"><p align="center"><?php echo $params->get('slider_body6', ''); ?></p></div>
                <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div> -->
               
                </div>
            </div></div>
