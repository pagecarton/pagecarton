<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Audio
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Audio.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Audio
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Audio extends Application_Article_Type_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Play Audio'; 

    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
			if( ! $data = $this->getParameter( 'data' ) )
			{
				$data = $this->getIdentifierData();
			}
		if( ! self::isDownloadable( $data ) )
		{
			$this->setViewContent( '<div class="badnews">Audio file has not been set</div>' );
			return false;
		}
		if( $data['true_post_type'] != 'audio' )
		{
			$this->setViewContent( '<div class="badnews">This is not a true audio post</div>' );
			return false;
		}
		$attributes = 'controls ';
		if( ! empty( $_REQUEST['autoplay'] ) || $this->getParameter( 'autoplay' ) )
		{
			$attributes .= 'autoplay ';
		}
		if( ! empty( $_REQUEST['autoplay_next'] ) || $this->getParameter( 'autoplay_next' ) )
		{
			
			$attributes .= 'onended="var xx = document.getElementsByClassName( \'pc_paginator_next_page_button\' )[0].href; location.href= xx + location.search + \'&autoplay_next_done=1&autoplay=1\'"';
		}
		$audio = '	<audio preload="none" style="width:100%;" ' . $attributes . ' src="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Type_Audio_Play/?article_url=' . $data['article_url'] . '&auto_download=1">
						
					</audio>';
		
		$this->setViewContent( $audio );

    } 
	
	
	
	// END OF CLASS
}
