<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_Delete extends Application_Blog_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete ' . $data['blog_name'],  'Delete Blog Information and Files' );
			$this->setViewContent( $this->getForm()->view(), true );
			$filename = self::getFilePath( $data['blog_directory'], $data['blog_name'] );
			file_put_contents( $filename, '' ); // empty content and then delete
			
			//	Only remove from DB if file deleted.
			if( $this->deleteDb( false ) )
			{ 
				unlink( $filename );
				@Ayoola_Doc::removeDirectory( dirname( $filename ) );
				$this->setViewContent( 'Blog deleted successfully', true ); 
			}
			
			try
			{
				$url = '/blog/view/get/blog_name/' . $data['blog_name'] . '/';
				$where = array( 'link_url' => $url );
				$link = new Application_Link();
				$link->delete( $where );
			}
			catch( Exception $e ){ return false; }
		}
		catch( Application_Blog_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
