<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::script('jquery-1.3.2.min.js','modules/mod_slider4/js/');
JHTML::script('jquery.scrollTo-1.4.2-min.js','modules/mod_slider4/js/');
JHTML::script('jquery.localscroll-1.2.7-min.js','modules/mod_slider4/js/');
JHTML::script('jquery.serialScroll-1.2.2-min.js','modules/mod_slider4/js/');
JHTML::script('coda-slider.js','modules/mod_slider4/js/');
JHTML::stylesheet('styles.css', 'modules/mod_slider4/css/'); ?>

<h1>Understanding College</h2>
	<p><em>Students' points of view...</em></p>
  <div id="slider">    
            <ul class="navigation">
                <li><a href="#sites">Allie</a></li>
                <li><a href="#files">Stephan</a></li>
                <li><a href="#editor">Adrian</a></li>
                <li><a href="#preview">Grace</a></li>
                <li><a href="#css">Christine</a></li>
                <li><a href="#terminal">Fabiola</a></li>
               <!-- <li><a href="#seven"><?php echo $params->get('slider_title7', ''); ?></a></li> -->
            </ul>

            <div class="scroll">
                <div class="scrollContainer">
                <div class="panel" id="sites"><p align="center"><img align="center" src="/modules/mod_slider4/images/allie.jpg" alt="Allie smiling" /><p class="picQuote"><em>Classes at college are different than high school because everything is different: the teachers, classmates and subjects are different. </em></p></p></div>
                <div class="panel" id="files"><p align="center"><img align="center" src="/modules/mod_slider4/images/stephan.jpg" alt="Stephan" /><p class="picQuote"><em>School work is different in college. Like they give you different homework. The class I'm taking now about men and women and psychology is not something I would have taken in high school.</em></p></p></div>
                <div class="panel" id="editor"><p align="center"><img align="center" src="/modules/mod_slider4/images/adrian.jpg" alt="Adrian with instructor" /><p class="picQuote"><em>The computer teacher was nice. Whenever I said I wasn’t sure I could do this, she told me to just keep coming back.</em></p></p></div>
                <div class="panel" id="preview"><p align="center"><img align="center" src="/modules/mod_slider4/images/grace.jpg" alt="Grace doing school work" /><p class="picQuote"><em>In college I'm having to learn to do harder work. In high school I didn't have homework a lot. In college the professors don't baby you like they do in high school. You're responsible for your own work.</em></p></p></div>
                <div class="panel" id="css"><p align="center"><img align="center" src="/modules/mod_slider4/images/christine.jpg" alt="Christine at computer" /><p class="picQuote"><em>I agree that the college class are hard but once you get used to it they get much easier. Take your time with homework. Don’t rush through it.</em></p></p></div>
                <div class="panel" id="terminal"><p align="center"><img align="center" src="/modules/mod_slider4/images/fab.jpg" alt="Fab holding books" /><p class="picQuote"><em>This is helping my future. Getting skills. I'll probably be back here next semester. I might try an introduction to nursing class. Or study about police work. Or maybe something different like photography.</em></p></p></div>
                 <!-- <div class="panel" id="seven"><p align="center"><?php echo $params->get('slider_body7', ''); ?></p></div> -->
               
                </div>
            </div></div>
