<?php 
/**
 * Xmap by Guillermo Vargas
 * A sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Turkish translation by http://cumla.blogspot.com  
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

if( !defined( 'JOOMAP_LANG' )) {
    define('JOOMAP_LANG', 1 );
    // -- General ------------------------------------------------------------------
    define('_XMAP_CFG_OPTIONS',            'Goruntuleme Ayarlari');
    define('_XMAP_CFG_CSS_CLASSNAME',        'CSS Class Adi');
    define('_XMAP_CFG_EXPAND_CATEGORIES',    'İçerik Kategorilerini Genişlet');
    define('_XMAP_CFG_EXPAND_SECTIONS',        'İçerik Bölümlerini Genişlet');
    define('_XMAP_CFG_SHOW_MENU_TITLES',    'Menu Basliklarini Goster');
    define('_XMAP_CFG_NUMBER_COLUMNS',    'Kolon Sayisi');
    define('_XMAP_EX_LINK',            'Dis Bağlantilari isaretle');
    define('_XMAP_CFG_CLICK_HERE',         'Buraya Tıklayın');
    define('_XMAP_CFG_GOOGLE_MAP',        'Google Site Haritası');
    define('_XMAP_EXCLUDE_MENU',        'Dışlanacak Menü ID leri');
    define('_XMAP_TAB_DISPLAY',            'Görüntüleme');
    define('_XMAP_TAB_MENUS',                'Menüler');
    define('_XMAP_CFG_WRITEABLE',            'Yazılabilir');
    define('_XMAP_CFG_UNWRITEABLE',        'Yazılamaz');
    define('_XMAP_MSG_MAKE_UNWRITEABLE',    'Kaydettikten sonra yazılamaz yap');
    define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION', 'Kaydederken yazılabilme iznini değiştir');
    define('_XMAP_GOOGLE_LINK',            'Google Bağlantısı');
    define('_XMAP_CFG_INCLUDE_LINK',        'Yazara görünmez bağlantı');

    // -- Tips ---------------------------------------------------------------------
    define('_XMAP_EXCLUDE_MENU_TIP',        'Site haritasına eklemek istemediğiniz menü ID lerini belirtiniz.<br /><strong>NOT</strong><br />ID leri virgul ile ayırabilirsiniz!');

    // -- Menus --------------------------------------------------------------------
    define('_XMAP_CFG_SET_ORDER',        'Menü Görüntüleme Sırasını Ayarla');
    define('_XMAP_CFG_MENU_SHOW',        'Göster');
    define('_XMAP_CFG_MENU_REORDER',        'Yeniden Sırala');
    define('_XMAP_CFG_MENU_ORDER',        'Sırala');
    define('_XMAP_CFG_MENU_NAME',        'Menü İsmi');
    define('_XMAP_CFG_DISABLE',            'Kapatmamak için tıklayınız.');
    define('_XMAP_CFG_ENABLE',            'Açmak için tıklayınız');
    define('_XMAP_SHOW',                'Göster');
    define('_XMAP_NO_SHOW',                'Gösterme');

    // -- Toolbar ------------------------------------------------------------------
    define('_XMAP_TOOLBAR_SAVE',             'Kaydet');
    define('_XMAP_TOOLBAR_CANCEL',         'İptal');

    // -- Errors -------------------------------------------------------------------
    define('_XMAP_ERR_NO_LANG',            '[ %s ] dil dosyası bulunamadı, varsayılan dil: ingilizce<br />');
    define('_XMAP_ERR_CONF_SAVE',         'HATA: Yapılandırma kaydedilemedi.');
    define('_XMAP_ERR_NO_CREATE',         'HATA: Ayarlar tablosu oluşturulamadı');
    define('_XMAP_ERR_NO_DEFAULT_SET',    'HATA: Varsayılan ayarlar yüklenemedi');
    define('_XMAP_ERR_NO_PREV_BU',        'UYARI: Önceki yedekleme silinemedi');
    define('_XMAP_ERR_NO_BACKUP',         'HATA: Yedekleme oluşturulamadı');
    define('_XMAP_ERR_NO_DROP_DB',        'HATA: Ayarlar tablosu boşaltılamadı');
    define('_XMAP_ERR_NO_SETTINGS',        'HATA: Veritabanındakı ayarlar yüklenemedi: <a href="%s">Ayarlar tablosu oluştur</a>');

    // -- Config -------------------------------------------------------------------
    define('_XMAP_MSG_SET_RESTORED',      'Settings restored');
    define('_XMAP_MSG_SET_BACKEDUP',      'Ayarlar kaydedildi');
    define('_XMAP_MSG_SET_DB_CREATED',    'Ayarlar tablosu oluşturuldu');
    define('_XMAP_MSG_SET_DEF_INSERT',    'Varsayılan Ayarlar yüklendi');
    define('_XMAP_MSG_SET_DB_DROPPED',    'Xmap\'s tabloları kaydedildi!');
    
    // -- CSS ----------------------------------------------------------------------
    define('_XMAP_CSS',                    'Xmap CSS');
    define('_XMAP_CSS_EDIT',                'Tema düzenle'); // Edit template
    
    // -- Sitemap (Frontend) -------------------------------------------------------
    define('_XMAP_SHOW_AS_EXTERN_ALT',    'Bağlantıyı yeni pencerede aç');
    
    // -- Added for Xmap 
    define('_XMAP_CFG_MENU_SHOW_HTML',        'Sitede göster');
    define('_XMAP_CFG_MENU_SHOW_XML',        'XML Site Haritasinda göster');
    define('_XMAP_CFG_MENU_PRIORITY',        'Önem');
    define('_XMAP_CFG_MENU_CHANGEFREQ',        'Değişme Sıklığı');
    define('_XMAP_CFG_CHANGEFREQ_ALWAYS',        'Herzaman');
    define('_XMAP_CFG_CHANGEFREQ_HOURLY',        'Saatlik');
    define('_XMAP_CFG_CHANGEFREQ_DAILY',        'Günlük');
    define('_XMAP_CFG_CHANGEFREQ_WEEKLY',        'Haftalık');
    define('_XMAP_CFG_CHANGEFREQ_MONTHLY',        'Aylık');
    define('_XMAP_CFG_CHANGEFREQ_YEARLY',        'Yıllık');
    define('_XMAP_CFG_CHANGEFREQ_NEVER',        'Hiçbir Zaman');

    define('_XMAP_TIT_SETTINGS_OF',            '%s için seçimler');
    define('_XMAP_TAB_SITEMAPS',            'Site Haritaları');
    define('_XMAP_MSG_NO_SITEMAPS',            'Henüz site haritası oluşturulmadı');
    define('_XMAP_MSG_NO_SITEMAP',            'Bu Site Haritası hazır değil');
    define('_XMAP_MSG_LOADING_SETTINGS',        'Seçenekler Yükleniyor...');
    define('_XMAP_MSG_ERROR_LOADING_SITEMAP',        'Hata. Site Haritasını yükleyemiyor.');
    define('_XMAP_MSG_ERROR_SAVE_PROPERTY',        'Hata. Site Haritasi kaydedilemiyor.');
    define('_XMAP_MSG_ERROR_CLEAN_CACHE',        'Hata. Site Haritası önbelleği temizlenemiyor');
    define('_XMAP_ERROR_DELETE_DEFAULT',        'Varsayılan Site Haritası silinemiyor!');
    define('_XMAP_MSG_CACHE_CLEANED',            'Önbellek temizlendi!');
    define('_XMAP_CHARSET',                'ISO-8859-9');
    define('_XMAP_SITEMAP_ID',                'Site Haritası ID');
    define('_XMAP_ADD_SITEMAP',                'Site Haritası Ekle');
    define('_XMAP_NAME_NEW_SITEMAP',            'Yeni Site Haritasi');
    define('_XMAP_DELETE_SITEMAP',            'Sil');
    define('_XMAP_SETTINGS_SITEMAP',            'Ayarlar');
    define('_XMAP_COPY_SITEMAP',            'Kopyala');
    define('_XMAP_SITEMAP_SET_DEFAULT',            'Varsayılan Olarak Ata');
    define('_XMAP_EDIT_MENU',                'Seçenekler');
    define('_XMAP_DELETE_MENU',                'Sil');
    define('_XMAP_CLEAR_CACHE',                'Önbellek temizle');
    define('_XMAP_MOVEUP_MENU',        'Yukarı');
    define('_XMAP_MOVEDOWN_MENU',    'Aşağı');
    define('_XMAP_ADD_MENU',        'Menu ekle');
    define('_XMAP_COPY_OF',        '%s\nin kopyası');
    define('_XMAP_INFO_LAST_VISIT',    'En son ziyaret');
    define('_XMAP_INFO_COUNT_VIEWS',    'Ziyaret sayısı');
    define('_XMAP_INFO_TOTAL_LINKS',    'Bağlantı sayısı');
    define('_XMAP_CFG_URLS',        'Site Haritasının URL\'si');
    define('_XMAP_XML_LINK_TIP',    'Bağlantıyı kopyala ve Google ve Yahoo\'ya ekle');
    define('_XMAP_HTML_LINK_TIP',    'Bu Site Haritasının URL\'si. Menü eklemek için kullanabilirsiniz.');
    define('_XMAP_CFG_XML_MAP',        'XML Site Haritası');
    define('_XMAP_CFG_HTML_MAP',    'HTML Site Haritası');
    define('_XMAP_XML_LINK',        'Google bağlantısı');
    define('_XMAP_CFG_XML_MAP_TIP',    'Arama motorları için oluşturulan XML dosyası');
    define('_XMAP_ADD', 'Kaydet');
    define('_XMAP_CANCEL', 'iptal');
    define('_XMAP_LOADING', 'Yükleniyor...');
    define('_XMAP_CACHE', 'Önbellek');
    define('_XMAP_USE_CACHE', 'Önbellek Kullan');
    define('_XMAP_CACHE_LIFE_TIME', 'Önbellek Ömrü');
    define('_XMAP_NEVER_VISITED', 'Hiçbir Zaman');


    // New on Xmap 1.1
    define('_XMAP_PLUGINS','Plugins');    
    define( '_XMAP_INSTALL_3PD_WARN', 'Uyarı: Kurduğunuz 3.parti eklentiler sunucunun güvenliğini tehlikeye atabilir.' );
    define('_XMAP_INSTALL_NEW_PLUGIN', 'Yeni eklenti Kur');
    define('_XMAP_UNKNOWN_AUTHOR','Bilinmeyen yazar');
    define('_XMAP_PLUGIN_VERSION','Sürüm %s');
    define('_XMAP_TAB_INSTALL_PLUGIN','Kur');
    define('_XMAP_TAB_EXTENSIONS','Eklentiler');
    define('_XMAP_TAB_INSTALLED_EXTENSIONS','Kurulu Eklentiler');
    define('_XMAP_NO_PLUGINS_INSTALLED','Özel eklenti kurulu değil');
    define('_XMAP_AUTHOR','Yazar');
    define('_XMAP_CONFIRM_DELETE_SITEMAP','Bu site haritasını silmek istediğinize eminmisiniz?');
    define('_XMAP_CONFIRM_UNINSTALL_PLUGIN','Bu eklentiyi kaldırmak istediğinize eminmisiniz?');
    define('_XMAP_UNINSTALL','Kaldır');
    define('_XMAP_EXT_PUBLISHED','Yayınlanmış');
    define('_XMAP_EXT_UNPUBLISHED','Yayınlanmamış');
    define('_XMAP_PLUGIN_OPTIONS','Seçenekler');
    define('_XMAP_EXT_INSTALLED_MSG','Eklenti başarıyla kuruldu, lütfen seçeneklerine göz atın ve eklentiyi yayınlayın.');
    define('_XMAP_CONTINUE','Devam');
    define('_XMAP_MSG_EXCLUDE_CSS_SITEMAP','Do not include the CSS within the Sitemap');
    define('_XMAP_MSG_EXCLUDE_XSL_SITEMAP','Klasik XML site haritası görünümünü kullan');

    // New on Xmap 1.1
    define('_XMAP_MSG_SELECT_FOLDER','Lütfen bir dizin seçin');
    define('_XMAP_UPLOAD_PKG_FILE','Paket Dosyası Yükle');
    define('_XMAP_UPLOAD_AND_INSTALL','Dosya Yükle &amp; Kur');
    define('_XMAP_INSTALL_F_DIRECTORY','Dizinden kur');
    define('_XMAP_INSTALL_DIRECTORY','Kurulum Dizini');
    define('_XMAP_INSTALL','Kur');
    define('_XMAP_WRITEABLE','Yazılabilir');
    define('_XMAP_UNWRITEABLE','Yazılamaz');
    
    -    // New on Xmap 1.2
    define('_XMAP_COMPRESSION','Compression');
    define('_XMAP_USE_COMPRESSION','Compress the XML sitemap to save bandwidth');

    // New on Xmap 1.2.1
    define('_XMAP_CFG_NEWS_MAP',            'News Sitemap');
    define('_XMAP_NEWS_LINK_TIP',   'This is the news sitemap\'s URL.');

    // New on Xmap 1.2.2
    define('_XMAP_CFG_MENU_MODULE',            'Module');
    define('_XMAP_CFG_MENU_MODULE_TIP',            'Specify the module you use to show this menu in your site (Default: mod_mainmenu).');

        // New on Xmap 1.2.3
    define('_XMAP_TEXT',            'Link Text');
    define('_XMAP_TITLE',            'Link Title');
    define('_XMAP_LINK',            'Link URL');
    define('_XMAP_CSS_STYLE',            'CSS style');
    define('_XMAP_CSS_CLASS',            'CSS class');
    define('_XMAP_INVALID_SITEMAP',            'Invalid Sitemap');
    define('_XMAP_OK', 'Ok');

    // New on Xmap 1.2.10
    define('_XMAP_CFG_IMAGES_MAP','Images Sitemap');

    // New on Xmap 1.2.13
    define('_XMAP_CACHE_TIP','The maximun number of time in minutes for a cache file to be stored before it is refreshed');
    define('_XMAP_MINUTES','Minutes');
}
