<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
JHTML::stylesheet('styles.css', 'modules/mod_slider5/css/');

JHTML::script('jquery-1.3.2.min.js','modules/mod_slider5/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider5/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider5/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider5/js/');
JHTML::script('coda-slider.js','modules/mod_slider5/js/');?>

<h1>Other Postsecondary Options</h2>
	<p><em>Students pursue a variety of work, training and education options...</em></p>
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites">Beth</a></li>
                <li><a href="#files">Fabiola</a></li>
                <li><a href="#editor">Jenemy</a></li>
                <li><a href="#preview">Ruan</a></li>
                <!-- <li><a href="#css"><?php echo $params->get('slider_title5', ''); ?></a></li>
                <li><a href="#terminal"><?php echo $params->get('slider_title6', ''); ?></a></li>
                <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li> -->
            </ul>

            <div class="scroll">
                <div class="scrollContainer5">
                <div class="panel" id="sites"><p align="center"><div align="center"><strong>Work-based learning</strong></div><div align="center"><img align="center" src="/modules/mod_slider5/images/beth.jpg" alt="Beth working with pets" /></div></p></div>
                <div class="panel" id="files"><p align="center"><div align="center"><strong>Career mentors</strong></div><div align="center"><img align="center" src="/modules/mod_slider5/images/fab.jpg" alt="Fabiola working in a kitchen" /></div></p></div>
                <div class="panel" id="editor"><p align="center"><div align="center"><strong>On-the-job training</strong></div><div align="center"><img align="center" src="/modules/mod_slider5/images/jenemy.jpg" alt="Jenemy doing auto body work" /></div></p></div>
                <div class="panel" id="preview"><p align="center"><div align="center"><strong>Professional lessons</strong></div><div align="center"><img align="center" src="/modules/mod_slider5/images/ruan.jpg" alt="Ruan singing" /></div></p></div>
                <!-- <div class="panel" id="css"><p align="center"><?php echo $params->get('slider_body5', ''); ?></p></div>
                <div class="panel" id="terminal"><p align="center"><?php echo $params->get('slider_body6', ''); ?></p></div>
                <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div> -->
               
                </div>
            </div></div>
