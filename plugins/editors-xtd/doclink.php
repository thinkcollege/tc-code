<?php
/**
 * DOCLink 1.5.x
 * @version $Id: doclink.php 624 2008-02-22 20:38:00Z mjaz $
 * @package DOCLink_1.5
 * @copyright (C) 2003-2007 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_JEXEC') or die('Restricted access');

/**
 * plgButtonDoclink Class
 */
class plgButtonDoclink extends JPlugin {

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param   object $subject The object to observe
     * @param   array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgButtonDoclink(& $subject, $config) {
        parent::__construct($subject, $config);
    }

    /**
     * Display the button
     */
    function onDisplay($name){
        global $mainframe;

        $doc            =& JFactory::getDocument();

        $doclink_url    = JURI::root()."plugins/editors-xtd/doclink";
        $docman_url     = JURI::root()."components/com_docman/";

        $style = ".button2-left .doclink {
                background:transparent url($doclink_url/images/j_button2_doclink.png) no-repeat scroll 100% 0pt;
                }";
        $doc->addStyleDeclaration($style);

        $doc->addScript($docman_url.'assets/js/doclink.js');

        $button = new JObject();
        $button->set('modal', true);
        $button->set('text', JText::_('DOClink'));
        $button->set('name', 'doclink');
        $button->set('link', JRoute::_('index.php?option=com_docman&task=doclink&e_name='.$name));
        $button->set('options', "{handler: 'iframe', size: {x: 570, y: 510}}");

        return $button;
    }
}