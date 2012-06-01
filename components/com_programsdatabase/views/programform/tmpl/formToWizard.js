/* Created by jankoatwarpspeed.com */
(function(jQuery) {
    jQuery.fn.formToWizard = function(options) {
        options = jQuery.extend({  
            submitButton: "" 
        }, options); 
        
        var element = this;

        var steps = jQuery(element).find("fieldset");
        var count = steps.size();
        var submmitButtonName = "#" + options.submitButton;
        jQuery(submmitButtonName).hide();

        // 2
        jQuery(element).before("<ul id='steps'></ul>");

        steps.each(function(i) {
            jQuery(this).wrap("<div id='step" + i + "'></div>");
            jQuery(this).prepend("<p id='step" + i + "commands' class='heightfix clearfix'></p>");

            // 2
            var name = jQuery(this).find("legend").html();
            jQuery("#steps").append("<li id='stepDesc" + i + "'><a href='http://www.thinkcollege.net/databases/programs-database?task=searchform#' id=\"iconSel"+ (i) + "\"><span class='officon iconclass'></span></a>Step " + (i + 1) + "<span>" + name + "</span></li>");
 	 var stepName = "step" + i;
 jQuery("#iconSel" + (i)).bind("click", function(e) {

                jQuery("#step0").hide();
                jQuery("#step1").hide();
                jQuery("#step2").hide();
                jQuery("#step3").hide();
                jQuery("#step" + (i)).show();
                if (i  == 3)
                    jQuery(submmitButtonName).show(); if (i  != 3)
                    jQuery(submmitButtonName).hide();
                selectStep(i);
            });

            if (i == 0) {
                createNextButton(i);
                createNext2Button(i);
                selectStep(i);
            }
            else if (i == count - 1) {
                jQuery("#step" + i).hide();
               
createPrev2Button(i);
            }
            else {
                jQuery("#step" + i).hide();
               
createPrev2Button(i);
               
                createNext2Button(i);
            }
        });

        function createPrevButton(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands").append("<a href='http://www.thinkcollege.net/databases/programs-database?task=searchform#' id='" + stepName + "Prev' class='prev'><img src='/components/com_programsdatabase/views/programform/tmpl/images/back_but.gif' alt='Go back' /></a>");

            jQuery("#" + stepName + "Prev").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i - 1)).show();
                jQuery(submmitButtonName).hide();
                selectStep(i - 1);
            });
        }


        function createPrev2Button(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands2").append("<a href='http://www.thinkcollege.net/databases/programs-database?task=searchform#' id='" + stepName + "Prev2' class='prev'><img src='/components/com_programsdatabase/views/programform/tmpl/images/back_but.gif' alt='Previous step' /></a>");

            jQuery("#" + stepName + "Prev2").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i - 1)).show();
                jQuery(submmitButtonName).hide();
                selectStep(i - 1);
            });
        }

        function createNextButton(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands").append("<a href='http://www.thinkcollege.net/databases/programs-database?task=searchform#' id='" + stepName + "Next' class='next'><img src='/components/com_programsdatabase/views/programform/tmpl/images/next_but.gif' alt='Next step' /></a>");

            jQuery("#" + stepName + "Next").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i + 1)).show();
                if (i + 2 == count)
                    jQuery(submmitButtonName).show();
                selectStep(i + 1);
            });
        }

        function createNext2Button(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands2").append("<a href='http://www.thinkcollege.net/databases/programs-database?task=searchform#' id='" + stepName + "Next2' class='next'><img src='/components/com_programsdatabase/views/programform/tmpl/images/next_but.gif' alt='Next step' /></a>");

            jQuery("#" + stepName + "Next2").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i + 1)).show();
                if (i + 2 == count)
                    jQuery(submmitButtonName).show();
                selectStep(i + 1);
            });
        }

        function selectStep(i) {
            jQuery("#steps li").removeClass("current");
            jQuery("#stepDesc" + i).addClass("current");
            jQuery("#steps li .iconclass").removeClass("currenticon");
            jQuery("#steps li .iconclass").addClass("officon");
           jQuery("#stepDesc" + i + " .iconclass").removeClass("officon");
            jQuery("#stepDesc" + i + " .iconclass").addClass("currenticon");
// PF  to add an icon if (i == 1) { jQuery("#stepDesc1").prepend("<span class='what'>2</span>"); }
// if (i != 1) { jQuery(".what").remove(); }
        }

    }
})(jQuery); 