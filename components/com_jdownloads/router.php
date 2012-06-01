<?php
/**
* @version 1.5
* @package JDownloads
* @copyright (C) 2009 www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* 
*
*/  

defined( '_JEXEC' ) or die( 'Restricted access' );
Error_Reporting(E_ERROR);

function jdownloadsBuildRoute(&$query) {
    $segments    = array();
    
    if (isset($query['view'])){
        $segments[] = $query['view'];
        unset($query['view']);
    } else {
        if (isset($query['task'])){
            $segments[] = $query['task'];
            unset($query['task']);
        }        
    }    
               
    if (isset($query['viewcategory'])){
        $segments[] = $query['viewcategory'];
        unset($query['viewcategory']);
    }
    if (isset($query['view.download'])){
        $segments[] = $query['view.download'];
        unset($query['view.download']);
    }
    if (isset($query['summary'])){
        $segments[] = $query['summary'];
        unset($query['summary']);
    }
    if (isset($query['finish'])){
        $segments[] = $query['finish'];
        unset($query['finish']);
    }    
    if (isset($query['search'])){
        $segments[] = $query['search'];
        unset($query['search']);
    }  
    if (isset($query['upload'])){
        $segments[] = $query['upload'];
        unset($query['upload']);
    }          
    
    if (isset($query['catid'])){
        $segments[] = $query['catid'];
        unset($query['catid']);
    }
    if (isset($query['cid'])){
        $segments[] = $query['cid'];
        unset($query['cid']);
    }
    if (isset($query['m'])){
        $segments[] = $query['m'];
        unset($query['m']);
    }
    if (isset($query['list'])){
        $segments[] = $query['list'];
        unset($query['list']);
    }
    if (isset($query['user'])){
        $segments[] = $query['user'];
        unset($query['user']);
    }    
    
        
    return $segments;
}

function jdownloadsParseRoute($segments) {

       $vars = array();
       switch($segments[0])
       {
               case 'viewcategory':
                       $vars['view'] = 'viewcategory';
                       $catid = explode( ':', $segments[1] );
                       $vars['catid'] = (int) $catid[0];
                       break;
               case 'view.download':
                       $vars['view'] = 'view.download';
                       if (isset($segments[2])){
                          $catid = explode( ':', $segments[1] );
                          $vars['catid'] = (int) $catid[0];
                          $id = explode( ':', $segments[2] );
                          $vars['cid'] = (int) $id[0];
                       } else {
                          $id = explode( ':', $segments[1] );
                          $vars['cid'] = (int) $id[0];
                       }   
                       if (isset($segments[3])){
                           $m = explode( ':', $segments[3] );
                           $vars['m'] = (int) $m[0];
                       }    
                       break;
               case 'summary':
                       $vars['view'] = 'summary';
                       $catid = explode( ':', $segments[1] );
                       $vars['catid'] = (int) $catid[0];
                       $cid = explode( ':', $segments[2] );
                       $vars['cid'] = (int) $cid[0];
                        if (isset($segments[3])){
                           $m = explode( ':', $segments[3] );
                           $vars['m'] = (int) $m[0];
                       }
                       break;
               case 'finish':
                       $vars['view'] = 'finish';
                       $vars['catid'] = (int) $segments[1];
                       $dummy = $segments[3];
                       if ($dummy < 5) {
                           $id = explode( ':', $segments[2] );
                           $vars['cid'] = (int) $id[0];
                           $m = explode( ':', $segments[3] );
                           $vars['m'] = (int) $m[0];     
                       } else {     
                            // only when multiple downloads with checkboxes
                            $list = explode( ',', $segments[2] );
                            $vars['list'] = implode(',',$list);
                            $vars['user'] = (int) $segments[3];
                       }
                       break;
               case 'search':
                       $vars['view'] = 'search';
                       break;
               case 'upload':
                       $vars['view'] = 'upload';
                       break; 
               case 'report':
                       $vars['view'] = 'report';
                       $cid = explode( ':', $segments[1] );
                       $vars['cid'] = (int) $cid[0];
                       break;                                             
                       
              default: $vars['view'] = 'viewcategory';
                       break;
      
       }
       return $vars;
} 
?>