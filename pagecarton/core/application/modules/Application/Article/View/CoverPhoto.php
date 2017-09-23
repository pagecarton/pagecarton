<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_View_CoverPhoto
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CoverPhoto.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_View_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_View_CoverPhoto
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_View_CoverPhoto extends Application_Article_View_Abstract
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
			$this->setViewContent( $data['document_url'] ? '<img style="max-width:100%;" title="Cover photo for: ' . $data['article_title'] . '." src="' . $data['document_url'] . '"/>' : null, true );
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
