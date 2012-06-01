<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::script('jquery-1.3.2.min.js','modules/mod_slider/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider/js/');
JHTML::script('coda-slider.js','modules/mod_slider/js/'); 
JHTML::stylesheet('styles.css', 'modules/mod_slider/css/'); ?>
<h1>Think College</h1>
	<p><em>Here's what some college students have to say about their experiences...</em></p>
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites"><?php echo $params->get('slider_title1', ''); ?></a></li>
                <li><a href="#files"><?php echo $params->get('slider_title2', ''); ?></a></li>
                <li><a href="#editor"><?php echo $params->get('slider_title3', ''); ?></a></li>
                <li><a href="#preview"><?php echo $params->get('slider_title4', ''); ?></a></li>
                <li><a href="#css"><?php echo $params->get('slider_title5', ''); ?></a></li>
                <li><a href="#terminal"><?php echo $params->get('slider_title6', ''); ?></a></li>
                <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li>
            </ul>

            <div class="scroll">
                <div class="scrollContainer">
                <div class="panel" id="sites"><p align="center"><?php echo $params->get('slider_body1', ''); ?></p></div>
                <div class="panel" id="files"><p align="center"><?php echo $params->get('slider_body2', ''); ?></p></div>
                <div class="panel" id="editor"><p align="center"><?php echo $params->get('slider_body3', ''); ?></p></div>
                <div class="panel" id="preview"><p align="center"><?php echo $params->get('slider_body4', ''); ?></p></div>
                <div class="panel" id="css"><p align="center"><?php echo $params->get('slider_body5', ''); ?></p></div>
                <div class="panel" id="terminal"><p align="center"><?php echo $params->get('slider_body6', ''); ?></p></div>
                <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div>
               
                </div>
            </div></div>
