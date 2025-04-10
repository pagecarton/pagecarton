<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_DeleteX extends Application_Article_Abstract
{
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
		try
		{ 

            Application_Article_Table::getInstance()->delete( array( "article_title" => "..." ) );
            Application_Article_Table::getInstance()->delete( array( "article_title" => "Add new \"Blog Post\" here" ) );
            Application_Article_Table::getInstance()->delete( array( "article_title" => "Add new &quot;Blog Post&quot; here" ) );

            $this->setViewContent( '<div class="pc_give_space_top_bottom pc-btn-parent">Done</div>' );
        }
		catch( Application_Article_Exception $e )
		{ 
			$this->getForm()->oneFieldSetAtATime = false;
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
