<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */
# Hungarian translation for Glossary Component: Adjusted: ZoltĂˇn Dombai 13.08.2008 http://www.forexclub.hu

// Header language definitions
if (!defined("_DEFINITION_TITLE")) DEFINE("_DEFINITION_TITLE","Meghatározás");
if (!defined("_DEFINITION_SELECT")) DEFINE("_DEFINITION_SELECT","Megjeleníthetsz tételeket, ha kiválasztod a kezdőbetűt.");
if (!defined("_DEFINITION_SELECT_SEARCH")) DEFINE("_DEFINITION_SELECT_SEARCH","Mindig kereshetsz tételeket(regexp engedélyezett).");
if (!defined("_DEFINITION_BEFOREENTRIES")) DEFINE("_DEFINITION_BEFOREENTRIES","Szerepel");
if (!defined("_DEFINITION_AFTERENTRIES")) DEFINE("_DEFINITION_AFTERENTRIES","Tétel a meghatározásban.");
if (!defined("_DEFINITION_PAGES")) DEFINE("_DEFINITION_PAGES","Oldalak:");
if (!defined("_DEFINITION_ONLYREGISTERED")) DEFINE("_DEFINITION_ONLYREGISTERED","Csak regisztrált felhasználók javasolhatnak feltételeket.<br />Kérlek jelentkezz be vagy regisztrálj.");

// Default category description
if (!defined("_DEFINITION_DEFAULT_CATEGORY")) DEFINE("_DEFINITION_DEFAULT_CATEGORY","Az oldal használati feltételei.");

// DEFINITION language definitions
if (!defined("_DEFINITION_TERM")) DEFINE("_DEFINITION_TERM","Feltétel");
if (!defined("_DEFINITION_TERMS")) DEFINE("_DEFINITION_TERMS","Feltételek");
if (!defined("_DEFINITION_AUTHOR")) DEFINE("_DEFINITION_AUTHOR","Szerző");
if (!defined("_DEFINITION_DEFINITION")) DEFINE("_DEFINITION_DEFINITION","Meghatározás");
if (!defined("_DEFINITION_FROM")) DEFINE("_DEFINITION_FROM","Kitől");
if (!defined("_DEFINITION_DEFINITION")) DEFINE("_DEFINITION_DEFINITION","Meghatározás");
if (!defined("_DEFINITION_SEARCH")) DEFINE("_DEFINITION_SEARCH","Keresés");
if (!defined("_DEFINITION_ALL")) DEFINE("_DEFINITION_ALL","Minden");
if (!defined("_DEFINITION_OTHER")) DEFINE("_DEFINITION_OTHER","Más");
if (!defined("_DEFINITION_NEW")) DEFINE("_DEFINITION_NEW","Új");
if (!defined("_DEFINITION_SIGNEDON")) DEFINE("_DEFINITION_SIGNEDON","Létrehozva");
if (!defined("_DEFINITION_ADMINSCOMMENT")) DEFINE("_DEFINITION_ADMINSCOMMENT","Megjegyzések");
if (!defined("_DEFINITION_VIEW")) DEFINE("_DEFINITION_VIEW","Meghatározás megjelenítése");
if (!defined("_DEFINITION_ENTRY")) DEFINE("_DEFINITION_ENTRY","Meghatározás");
if (!defined("_DEFINITION_NAME")) DEFINE("_DEFINITION_NAME","Feltétel");
if (!defined("_DEFINITION_SUBMIT")) DEFINE("_DEFINITION_SUBMIT","Feltétel létrehozása");
if (!defined("_E_REGISTERED")) DEFINE("_E_REGISTERED","Csak regisztrált felhasználók");

// Form language definitions
if (!defined("_SEL_CATEGORY")) DEFINE("_SEL_CATEGORY", "Válasz kategóriát");
if (!defined("_DEFINITION_VALIDATE")) DEFINE("_DEFINITION_VALIDATE","Kérlek ird be a legalább a neved, feltételed, meghatározásodat vagy a kategóriádat.");
if (!defined("_DEFINITION_ENTERNAME")) DEFINE("_DEFINITION_ENTERNAME","Neved:");
if (!defined("_DEFINITION_ENTERMAIL")) DEFINE("_DEFINITION_ENTERMAIL","E-Mail Címed:");
if (!defined("_DEFINITION_ENTERPAGE")) DEFINE("_DEFINITION_ENTERPAGE","Web oldalad:");
if (!defined("_DEFINITION_ENTERCOMMENT")) DEFINE("_DEFINITION_ENTERCOMMENT","Megjegyzésed:");
if (!defined("_DEFINITION_ENTERLOCA")) DEFINE("_DEFINITION_ENTERLOCA","Helység:");
if (!defined("_DEFINITION_ENTERTERM")) DEFINE("_DEFINITION_ENTERTERM","A feltétel:");
if (!defined("_DEFINITION_ENTERDEFINITION")) DEFINE("_DEFINITION_ENTERDEFINITION","Meghatározás:");
if (!defined("_DEFINITION_SUBMITFORM")) DEFINE("_DEFINITION_SUBMITFORM","Jóváhagy");
if (!defined("_DEFINITION_SENDFORM")) DEFINE("_DEFINITION_SENDFORM","Jóváhagy");
if (!defined("_DEFINITION_CLEARFORM")) DEFINE("_DEFINITION_CLEARFORM","Kiürít");

// Save language definitions
if (!defined("_DEFINITION_SAVED")) DEFINE("_DEFINITION_SAVED","Entry saved to definition.");

// Search options
if (!defined("_DEFINITION_SEARCH_BEGINS")) DEFINE ("_DEFINITION_SEARCH_BEGINS", "Ezzel kezdődik");
if (!defined("_DEFINITION_SEARCH_CONTAINS")) DEFINE ("_DEFINITION_SEARCH_CONTAINS", "Tartalmazza");
if (!defined("_DEFINITION_SEARCH_EXACT")) DEFINE ("_DEFINITION_SEARCH_EXACT", "Pontos megegyezés");

// Admin language definitions
if (!defined("_DEFINITION_DELENTRY")) DEFINE("_DEFINITION_DELENTRY","Törli a feltételeket");
if (!defined("_DEFINITION_DELMESSAGE")) DEFINE("_DEFINITION_DELMESSAGE","A feltételeket eltávolítottam.");
if (!defined("_DEFINITION_DEFVALIDATE")) DEFINE("_DEFINITION_DEFVALIDATE","Írd be a meghatározást.");
if (!defined("_DEFINITION_COMMENTSAVED")) DEFINE("_DEFINITION_COMMENTSAVED","A hozzászólásodat elmentettük.");
if (!defined("_DEFINITION_COMMENTDELETED")) DEFINE("_DEFINITION_COMMENTDELETED","A hozzászólásodat töröltük.");
if (!defined("_DEFINITION_ADMIN")) DEFINE("_DEFINITION_ADMIN","Ügyintéző");
if (!defined("_DEFINITION_AEDIT")) DEFINE("_DEFINITION_AEDIT","Szerkeszt");
if (!defined("_DEFINITION_ACOMMENT")) DEFINE("_DEFINITION_ACOMMENT","Hozzászólás");
if (!defined("_DEFINITION_ACOMMENTDEL")) DEFINE("_DEFINITION_ACOMMENTDEL"," Törli a hozzászólást");
if (!defined("_DEFINITION_ADELETE")) DEFINE("_DEFINITION_ADELETE","Törlés");

// Email language definitions
if (!defined("_DEFINITION_ADMINMAILHEADER")) DEFINE("_DEFINITION_ADMINMAILHEADER","Új meghatározás beírása");
if (!defined("_DEFINITION_ADMINMAIL")) DEFINE("_DEFINITION_ADMINMAIL","Hello Ügyintéző,\n\nA felhasználó javasolt egy új feltételt a Te meghatározásodhoz itt ".JURI::base().":\n");
if (!defined("_DEFINITION_USERMAILHEADER")) DEFINE("_DEFINITION_USERMAILHEADER","Köszönjük a javaslatát a Meghatározáshoz.");
if (!defined("_DEFINITION_USERMAIL")) DEFINE("_DEFINITION_USERMAIL","Üdv Felhasználó ,\n\n Köszönjük a javaslatodat a Meghatározáshoz".JURI::base().":\n It will be reviewed before being added to the site.\n");
if (!defined("_DEFINITION_MAILFOOTER")) DEFINE("_DEFINITION_MAILFOOTER","Kérjük, hogy ne válaszoljon erre az üzenetre, mert automatikusan készült, és csak tájékoztatásul küldtük.\n");

// update 1.9.0
if (!defined("_DEFINITION_SEARCHSTRING")) DEFINE("_DEFINITION_SEARCHSTRING","Keres...");
if (!defined("_DEFINITION_SEARCHBUTTON")) DEFINE("_DEFINITION_SEARCHBUTTON","Mehet");

?>