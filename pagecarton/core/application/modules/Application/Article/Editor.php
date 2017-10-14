<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
 * @package    Application_Article_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Editor extends Application_Article_Abstract
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
		//	var_export( Application_HashTag_Abstract::get( 'articles' ) );
		
		//		self::v( $data );
			
			//	Only the owner can edit or priviledged user can edit
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ) && Ayoola_Application::getUserInfo( 'username' ) !== $data['username'] ){ return false; }  
		//	self::v( $data );
			//			var_export( $data['quiz_correct_option'] );
			$this->createForm( 'Continue...', 'Editing "' . $data['article_title'] . '"', $data );
//			$this->setViewContent( '<script src="/js/objects/tinymce/tinymce.min.js"></script>' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
			
			if( empty( $values['document_url_base64'] ) )
			{
				//	we are not interested in changing cover photo
				unset( $values['document_url_base64'] );
			}
			if( empty( $values['document_url'] ) )
			{
				//	we are not interested in changing cover photo
				unset( $values['document_url'] );
			}
			if( empty( $values['download_url'] ) )
			{
				//	we are not interested in changing download doc
				unset( $values['download_url'] );
			}
			if( empty( $values['download_base64'] ) )
			{
				//	we are not interested in changing download doc
				unset( $values['download_base64'] );
			}
		//	self::v( $values );
			$access = new Ayoola_Access();
			if( $userInfo = $access->getUserInfo() )
			{
				$data['article_editor_user_id'] = $userInfo['user_id'];
				@$data['article_editor_username'] = is_array( @$data['article_editor_username'] ) ? $data['article_editor_username'] : array();
				array_push( $data['article_editor_username'], $userInfo['username'] );
			}
			//	Old owner is still the new owner
			$values['user_id'] = $data['user_id'];
			$values['username'] = $data['username'];
			$values['article_modified_date'] = time();
			
			//	Save new data in the cloud
		//	if( ! $this->updateDb( $values ) ){ return false; }
		
/* 			//	Set a category to specify the type of Post this is 
			$table = new Application_Category();
		//	@$values['article_type'] = $values['article_type'] ? : 'article';
			if( ! $category = $table->selectOne( null, array( 'category_name' => $values['article_type'] ) ) )
			{
		//		$this->getForm()->setBadnews( 'Could not find a category ID in the database to save ' . $values['article_type'] );
		//		$this->setViewContent( '' . showBadnews( $this->getForm()->getBadnews() ) . '' );
		//		return false;
			}
			//	Changing to category_name to correct error in grep
		//	$values['category_name'] = @$values['category_name'] ? : array();
			if( ! @in_array( $category['category_name'], $values['category_name'] ) )
			{
				@array_push( $values['category_name'], $category['category_name'] );
			}
			is_array( $values['category_name'] ) ? array_unique( $values['category_name'] ) : null;
 */			
			
			//	compatibility
/* 			$values['category_id'] = $values['category_id'] ? : array();
			if( ! @in_array( $category['category_id'], $values['category_id'] ) ) 
			{
				@array_push( $values['category_id'], $category['category_id'] );
			}
			array_unique( $values['category_id'] );
 */			
			//	making options that have been disabled to still be active.
			$values = array_merge( $data, $values );  
			
			// Save to server
		//	if( ! $response = Application_Article_Api_Update::send( $values ) ){ return false; }
		//	if( true !== $response['data'] ){ throw new Application_Article_Exception( $response ); }
			
			self::saveArticle( $values );
			
			//	Set Hash Tags
	//		Application_HashTag_Abstract::set( @$values['article_tags'], 'articles', $values['article_url'] );
	//		Application_HashTag_Abstract::get();
	
			// Share
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . '';
			$this->setViewContent( '<div class="boxednews greynews">Post successfully saved.</div> <div class="boxednews greynews"><a href="' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . '">View Post.</a></div>', true );
			$this->setViewContent( '<div class="boxednews greynews" title="Share this with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
	//		$this->setViewContent( 'Post edited successfully. <a href="' . strtolower( $values['article_url'] ) . '">View post.</a>', true );
			$this->_objectData['article_url'] = $values['article_url'];  
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
