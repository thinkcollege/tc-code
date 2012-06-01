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
        jQuery(element).before("");

        steps.each(function(i) {
            jQuery(this).wrap("<div id='step" + i + "'></div>");
            jQuery(this).prepend("<p id='step" + i + "commands' class='heightfix'></p>");

            // 2
            var name = jQuery(this).find("legend").html();
            jQuery("#steps").append("");
 	 var stepName = "step" + i;
 jQuery("#iconSel" + (i)).bind("click", function(e) {

                jQuery("#step0").hide();
                jQuery("#step1").hide();
                jQuery("#step" + (i)).show();
                if (i  == 1) { jQuery('#descDiv').css('display', 'none');
                    jQuery(submmitButtonName).show(); } 
if (i  != 1) {
	jQuery("#descDiv").css('display', 'block');
                    jQuery(submmitButtonName).hide(); }
                selectStep(i);
            });

            if (i == 0) {
	
                createNextButton(i);
                createNext2Button(i);
                selectStep(i);
            }
            else if (i == count - 1) {
                jQuery("#step" + i).hide();
                createPrevButton(i);
createPrev2Button(i);
            }
            else {
                jQuery("#step" + i).hide();
                createPrevButton(i);
createPrev2Button(i);
                createNextButton(i);
            }
        });

        function createPrevButton(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands").append("<a href='/think-college-live/financial-aid/become-a-ctp/checklist#CtpForm' id='" + stepName + "Prev' class='prev'><img src=\"/components/com_ctp/views/ctp/tmpl/images/page_1.gif\" alt=\"go to page one\" /></a>");

            jQuery("#" + stepName + "Prev").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i - 1)).show();
                jQuery(submmitButtonName).hide();
jQuery("#descDiv").css('display', 'block');
                selectStep(i - 1);
            });
        }

        function createPrev2Button(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands2").append("<a href='/think-college-live/financial-aid/become-a-ctp/checklist#CtpForm' id='" + stepName + "Prev2' class='prev'><img src=\"/components/com_ctp/views/ctp/tmpl/images/page_1.gif\" alt=\"go to page one\" /></a>");

            jQuery("#" + stepName + "Prev2").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i - 1)).show();
                jQuery(submmitButtonName).hide();
jQuery("#descDiv").css('display', 'block');
                selectStep(i - 1);
            });
        }

        function createNextButton(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands").append("<a href='/think-college-live/financial-aid/become-a-ctp/checklist#page2' id='" + stepName + "Next' class='next'><img src=\"/components/com_ctp/views/ctp/tmpl/images/page_2.gif\" alt=\"go to page two\" /></a>");

            jQuery("#" + stepName + "Next").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i + 1)).show();
                if (i + 2 == count)
                    jQuery(submmitButtonName).show();
jQuery("#descDiv").css('display', 'none');
                selectStep(i + 1);
            });
        }

        function createNext2Button(i) {
            var stepName = "step" + i;
            jQuery("#" + stepName + "commands2").append("<a href='/think-college-live/financial-aid/become-a-ctp/checklist#page2' id='" + stepName + "Next2' class='next'><img src=\"/components/com_ctp/views/ctp/tmpl/images/page_2.gif\" alt=\"go to page two\" /></a>");

            jQuery("#" + stepName + "Next2").bind("click", function(e) {
                jQuery("#" + stepName).hide();
                jQuery("#step" + (i + 1)).show();
                if (i + 2 == count)
                    jQuery(submmitButtonName).show();
jQuery("#descDiv").css('display', 'none');
                selectStep(i + 1);
            });
        }

        function selectStep(i) {
          
// PF  to add an icon if (i == 1) { jQuery("#stepDesc1").prepend("<span class='what'>2</span>"); }
// if (i != 1) { jQuery(".what").remove(); }
        }

    }
})(jQuery); 