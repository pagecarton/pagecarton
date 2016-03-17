<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_View_Content
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Content.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_View_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_View_Content
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_View_Content extends Application_Article_View_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = self::getArticleInfo() ){ return; }
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			$this->setViewContent( $data['article_content'], true );
			$pageInfo = array(
				'description' => $data['article_description'],
				'keywords' => $data['article_tags'],
				'title' => trim( $data['article_title'] )
			);
	//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
		}
		catch( Exception $e )
		{ 
		//	$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
