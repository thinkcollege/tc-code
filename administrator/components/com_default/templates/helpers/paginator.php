<?php
/**
 * @version     $Id: paginator.php 769 2010-12-20 23:55:44Z stian $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Paginator Helper
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses		KRequest
 * @uses		KConfig
 */
class ComDefaultTemplateHelperPaginator extends KTemplateHelperPaginator
{
	/**
	 * Render item pagination
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
	public function pagination($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'total'   => 0,
			'display' => 4,
			'offset'  => 0,
			'limit'	  => 0,
			'attribs' => array('attribs' => array('onchange' => 'this.form.submit();'))
		));
		
		// Paginator object
		$paginator = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $config->total,
					  'offset' => $config->offset,
					  'limit'  => $config->limit,
					  'display' => $config->display)
		);
				
		// Get the paginator data
		$list = $paginator->getList();
		
		$html  = '<del class="container">';
		$html  = '<div class="pagination">';
		$html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config->toArray()).'</div>';
		$html .=  $this->_pages($list);
		$html .= '<div class="limit"> '.JText::_('Page').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
		$html .= '</div>';
		$html .= '</del>';
		
		return $html;
	}
	
	/**
	 * Render a list of pages links
	 * 
	 * This function is overriddes the default behavior to render the links in the khepri template
	 * backend style.
	 *
	 * @param	araay 	An array of page data
	 * @return	string	Html
	 */
	protected function _pages($pages)
	{
		$class = $pages['first']->active ? '' : 'off';
		$html  = '<div class="button2-right '.$class.'"><div class="start">'.$this->_link($pages['first'], 'First').'</div></div>';
		
		$class = $pages['previous']->active ? '' : 'off';
		$html  .= '<div class="button2-right '.$class.'"><div class="prev">'.$this->_link($pages['previous'], 'Prev').'</div></div>';
		
		$html  .= '<div class="button2-left"><div class="page">';
		foreach($pages['pages'] as $page) {
			$html .= $this->_link($page, $page->page);
		}
		$html .= '</div></div>';
		
		$class = $pages['next']->active ? '' : 'off';
		$html  .= '<div class="button2-left '.$class.'"><div class="next">'.$this->_link($pages['next'], 'Next').'</div></div>';
		
		$class = $pages['last']->active ? '' : 'off';
		$html  .= '<div class="button2-left '.$class.'"><div class="end">'.$this->_link($pages['last'], 'Last').'</div></div>';

		return $html;
	}
	
	protected function _link($page, $title)
	{
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);

		//For compatibility with Joomla use limitstart instead of offset
		$query['limit']      = $page->limit;
		$query['limitstart'] = $page->offset;	
		
		$url->setQuery($query);

		$class = $page->current ? 'class="active"' : '';

		if($page->active && !$page->current) {
			$html = '<a href="'.JRoute::_('index.php?'.$url->getQuery()).'" '.$class.'>'.JText::_($title).'</a>';
		} else {
			$html = '<span '.$class.'>'.JText::_($title).'</span>';
		}

		return $html;
	}
}