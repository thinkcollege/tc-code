<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).'/admin.literaturedatabase.html.php' ); 

litdbscreens::_ajax_check();
litdbscreens::_css();
litdbscreens::_menu();

if( empty( $task ) ){
	litdbscreens::Literature();
}elseif( method_exists( 'litdbscreens', $task ) ){
	call_user_func( array( 'litdbscreens', $task ) );
}else{
	litdbscreens::_default();
}

