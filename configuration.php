<?php
class JConfig {
	var $offline = '0';
	var $editor = 'jce';
	var $list_limit = '20';
	var $helpurl = 'http://help.joomla.org';
	var $debug = '0';
	var $debug_lang = '0';
	var $sef = '1';
	var $sef_rewrite = '1';
	var $sef_suffix = '0';
	var $feed_limit = '10';
	var $feed_email = 'author';
	var $secret = '0mDzeNYqEThp6pi4';
	var $gzip = '0';
	var $error_reporting = '0';
	var $xmlrpc_server = '0';
	var $log_path = '/home/thinkcollege/thinkcollege.net/htdocs/logs';
	var $tmp_path = '/home/thinkcollege/thinkcollege.net/htdocs/tmp';
	var $live_site = 'http://www.thinkcollege.net';
	var $force_ssl = '0';
	var $offset = '-5';
	var $caching = '0';
	var $cachetime = '15';
	var $cache_handler = 'file';
	var $memcache_settings = array();
	var $ftp_enable = '0';
	var $ftp_host = '127.0.0.1';
	var $ftp_port = '21';
	var $ftp_user = '';
	var $ftp_pass = '';
	var $ftp_root = '';
	var $dbtype = 'mysqli';
	var $host = 'localhost';
	var $user = 'tcuser';
	var $db = 'thinkcollege2';
	var $dbprefix = 'jos_';
	var $mailer = 'smtp';
	var $mailfrom = 'thinkcollege@communityinclusion.org';
	var $fromname = 'Think College';
	var $sendmail = '/usr/sbin/sendmail';
	var $smtpauth = '1';
	var $smtpsecure = 'tls';
	var $smtpport = '465';
	var $smtpuser = 'thinkcollege';
	var $smtppass = 'ajo&+#ii5H';
	var $smtphost = 'communityinclusion.org';
	var $MetaAuthor = '0';
	var $MetaTitle = '1';
	var $lifetime = '120';
	var $session_handler = 'database';
	var $password = '1999tc1886';
	var $sitename = 'Think College: College Options for People with Intellectual Disabilities';
	var $MetaDesc = 'Think College';
	var $MetaKeys = 'Think College';
	var $offline_message = 'This site is down for maintenance. Please check back again soon.';
}
?>