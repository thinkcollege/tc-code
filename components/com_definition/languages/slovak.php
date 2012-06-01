<?php
/**
 * Language file
 * @author Vlado
 * @link http://www.outdoor.sk
 */


// Header language definitions
if (!defined("_DEFINITION_TITLE")) DEFINE("_DEFINITION_TITLE","Definícia");
if (!defined("_DEFINITION_SELECT")) DEFINE("_DEFINITION_SELECT","Záznamy môžete prehliadať stlačením začiatočného písmena");
if (!defined("_DEFINITION_SELECT_SEARCH")) DEFINE("_DEFINITION_SELECT_SEARCH","Môžete vyhľadávať záznamy.(okrem chránených).");
if (!defined("_DEFINITION_BEFOREENTRIES")) DEFINE("_DEFINITION_BEFOREENTRIES","Toto je");
if (!defined("_DEFINITION_AFTERENTRIES")) DEFINE("_DEFINITION_AFTERENTRIES","záznam v definíciách.");
if (!defined("_DEFINITION_PAGES")) DEFINE("_DEFINITION_PAGES","Strany:");
if (!defined("_DEFINITION_ONLYREGISTERED")) DEFINE("_DEFINITION_ONLYREGISTERED","Iba registrovaný užívatelia môžu vkladať výrazy.<br />Prihláste sa alebo registrujte.");

// Default category description
if (!defined("_DEFINITION_DEFAULT_CATEGORY")) DEFINE("_DEFINITION_DEFAULT_CATEGORY","Výrazy používané na stránke.");

// DEFINITION language definitions
if (!defined("_DEFINITION_TERM")) DEFINE("_DEFINITION_TERM","Výraz");
if (!defined("_DEFINITION_TERMS")) DEFINE("_DEFINITION_TERMS","Výrazy");
if (!defined("_DEFINITION_AUTHOR")) DEFINE("_DEFINITION_AUTHOR","Autor");
if (!defined("_DEFINITION_DEFINITION")) DEFINE("_DEFINITION_DEFINITION","Definícia");
if (!defined("_DEFINITION_FROM")) DEFINE("_DEFINITION_FROM","Od");
if (!defined("_DEFINITION_DEFINITION")) DEFINE("_DEFINITION_DEFINITION","Definícia");
if (!defined("_DEFINITION_SEARCH")) DEFINE("_DEFINITION_SEARCH","Hľadať");
if (!defined("_DEFINITION_ALL")) DEFINE("_DEFINITION_ALL","Všetko");
if (!defined("_DEFINITION_OTHER")) DEFINE("_DEFINITION_OTHER","Iné");
if (!defined("_DEFINITION_NEW")) DEFINE("_DEFINITION_NEW","Nové");
if (!defined("_DEFINITION_SIGNEDON")) DEFINE("_DEFINITION_SIGNEDON","Vytvorené");
if (!defined("_DEFINITION_ADMINSCOMMENT")) DEFINE("_DEFINITION_ADMINSCOMMENT","Komentáre");
if (!defined("_DEFINITION_VIEW")) DEFINE("_DEFINITION_VIEW","Zobraziť definíciu");
if (!defined("_DEFINITION_ENTRY")) DEFINE("_DEFINITION_ENTRY","Definícia");
if (!defined("_DEFINITION_NAME")) DEFINE("_DEFINITION_NAME","Pravidlo");
if (!defined("_DEFINITION_SUBMIT")) DEFINE("_DEFINITION_SUBMIT","Odoslať ");
if (!defined("_E_REGISTERED")) DEFINE("_E_REGISTERED","Iba registrovaný užívatelia");

// Form language definitions
if (!defined("_SEL_CATEGORY")) DEFINE("_SEL_CATEGORY", "Voľba kategórie");
if (!defined("_DEFINITION_VALIDATE")) DEFINE("_DEFINITION_VALIDATE","Zadajte prosím minimálne Vaše meno, výraz, definíciu a kategóriu.");
if (!defined("_DEFINITION_ENTERNAME")) DEFINE("_DEFINITION_ENTERNAME","Vaše meno:");
if (!defined("_DEFINITION_ENTERMAIL")) DEFINE("_DEFINITION_ENTERMAIL","Váš E-Mail:");
if (!defined("_DEFINITION_ENTERPAGE")) DEFINE("_DEFINITION_ENTERPAGE","Vaša www stránka:");
if (!defined("_DEFINITION_ENTERCOMMENT")) DEFINE("_DEFINITION_ENTERCOMMENT","Váš komentár:");
if (!defined("_DEFINITION_ENTERLOCA")) DEFINE("_DEFINITION_ENTERLOCA","Vaša lokalizácia:");
if (!defined("_DEFINITION_ENTERTERM")) DEFINE("_DEFINITION_ENTERTERM","Výraz:");
if (!defined("_DEFINITION_ENTERDEFINITION")) DEFINE("_DEFINITION_ENTERDEFINITION","Definícia:");
if (!defined("_DEFINITION_SUBMITFORM")) DEFINE("_DEFINITION_SUBMITFORM","Odoslať");
if (!defined("_DEFINITION_SENDFORM")) DEFINE("_DEFINITION_SENDFORM","Odoslať");
if (!defined("_DEFINITION_CLEARFORM")) DEFINE("_DEFINITION_CLEARFORM","Zmazať");

// Save language definitions
if (!defined("_DEFINITION_SAVED")) DEFINE("_DEFINITION_SAVED","Záznam bol uložený.");

// Search options
if (!defined("_DEFINITION_SEARCH_BEGINS")) DEFINE ("_DEFINITION_SEARCH_BEGINS", "Začína na");
if (!defined("_DEFINITION_SEARCH_CONTAINS")) DEFINE ("_DEFINITION_SEARCH_CONTAINS", "Obsahuje");
if (!defined("_DEFINITION_SEARCH_EXACT")) DEFINE ("_DEFINITION_SEARCH_EXACT", "Presná fráza");

// Admin language definitions
if (!defined("_DEFINITION_DELENTRY")) DEFINE("_DEFINITION_DELENTRY","Zmazať výraz");
if (!defined("_DEFINITION_DELMESSAGE")) DEFINE("_DEFINITION_DELMESSAGE","Výraz bol zmazaný.");
if (!defined("_DEFINITION_DEFVALIDATE")) DEFINE("_DEFINITION_DEFVALIDATE","Vložte prosím výraz.");
if (!defined("_DEFINITION_COMMENTSAVED")) DEFINE("_DEFINITION_COMMENTSAVED","Váš komentár bol uložený.");
if (!defined("_DEFINITION_COMMENTDELETED")) DEFINE("_DEFINITION_COMMENTDELETED","Váš komentár bol zmazaný.");
if (!defined("_DEFINITION_ADMIN")) DEFINE("_DEFINITION_ADMIN","Admin");
if (!defined("_DEFINITION_AEDIT")) DEFINE("_DEFINITION_AEDIT","Editácia");
if (!defined("_DEFINITION_ACOMMENT")) DEFINE("_DEFINITION_ACOMMENT","Komentár");
if (!defined("_DEFINITION_ACOMMENTDEL")) DEFINE("_DEFINITION_ACOMMENTDEL","Zmazať komentár");
if (!defined("_DEFINITION_ADELETE")) DEFINE("_DEFINITION_ADELETE","Zmazanie");

// Email language definitions
if (!defined("_DEFINITION_ADMINMAILHEADER")) DEFINE("_DEFINITION_ADMINMAILHEADER","Nový záznam definície");
if (!defined("_DEFINITION_ADMINMAIL")) DEFINE("_DEFINITION_ADMINMAIL","Ahoj Admin,\n\nA užívateľ vložil nový záznam do definícií ".JURI::base().":\n");
if (!defined("_DEFINITION_USERMAILHEADER")) DEFINE("_DEFINITION_USERMAILHEADER","Ďakujeme Vám za nový záznam do definícií.");
if (!defined("_DEFINITION_USERMAIL")) DEFINE("_DEFINITION_USERMAIL","Dobrý deň užívateľ,\n\nĎakujeme Vám za záznam do zoznamu definícií ".JURI::base().":\n Pred publikovaním bude skontrolovaný administrátorom.\n");
if (!defined("_DEFINITION_MAILFOOTER")) DEFINE("_DEFINITION_MAILFOOTER","Neodpovedajte prosím na tento e-mail, bol zaslaný automatom pre informáciu.\n");

// update 1.9.0
if (!defined("_DEFINITION_SEARCHSTRING")) DEFINE("_DEFINITION_SEARCHSTRING","hľadanie...");
if (!defined("_DEFINITION_SEARCHBUTTON")) DEFINE("_DEFINITION_SEARCHBUTTON","Hľadať");

?>