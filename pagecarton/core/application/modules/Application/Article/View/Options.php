<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_View_Options
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Options.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_View_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_View_Options
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_View_Options extends Application_Article_View_Abstract
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
			$this->setViewContent( '<p style="display:flex;   " class="pc-btn-parent">' . self::getQuickPostLinks( $data ) . '</p>', true );
		}
		catch( Exception $e )
		{ 
			return $this->setViewContent( self::__( '<p class="badnews">Error with article package.</p>' ) ); 
		}
    } 
	
	// END OF CLASS
}
