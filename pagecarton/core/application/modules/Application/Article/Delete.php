<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Delete extends Application_Article_Abstract
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
			if( ! $data = self::getIdentifierData() ){ return false; }
		//	var_export( __LINE__ );
			//	Only the owner or priviledged users can delete
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::isAllowedToEdit( $data ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ? : 98 ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $data['username'] ) ){ return false; }  
			
			$this->createConfirmationForm( 'Delete ' . $data['article_title'],  'Delete information and files of this post' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Only remove from DB if file deleted.
			if( is_readable( self::getFolder() . $data['article_url'] ) )
			{
				// Save to server
			//	if( ! $response = Application_Article_Api_Delete::send( $data ) ){ return false; }
			//	var_export( $response );
			//	if( true !== $response['data'] ){ throw new Application_Article_Exception( $response ); }
			}
			unlink( self::getFolder() . $data['article_url'] );
			
			@Ayoola_Doc::removeDirectory( dirname( self::getFolder() . $data['article_url'] ) );

			// and we want to use tables for sorting categories and all
			$table = Application_Article_Table::getInstance();
			$table->delete( array( 'article_url' => $data['article_url'] ) );

			$this->setViewContent(  '' . self::__( '<p class="goodnews">Post deleted successfully</p>' ) . '', true  ); 
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getMessage() );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
