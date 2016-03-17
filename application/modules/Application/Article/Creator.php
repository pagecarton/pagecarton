<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Creator extends Application_Article_Abstract
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
			

			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );  
		//	var_export( $_POST );
	//		var_export( '1' );
			
			//	Only allowed users can write
			if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
			{ 
				$this->setViewContent( '<span class="badnews">You do not have enough priviledge to compose a new post on this website. </span>', true );
				if( ! Ayoola_Application::getUserInfo() )
				{ 
					$url = Ayoola_Page::setPreviousUrl( '/accounts/signin/' );
					$this->setViewContent( '<span class="badnews"> Please <a rel="" href="' . $url . '">login</a> to write a new post. </a></span>' );
				}
				return false;     
			}
			
			$this->createForm( 'Save', $this->getParameter( 'form_legend' ) ? : 'Create a new post' );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			$this->setViewContent( self::getQuickLink() );
	//		$this->setViewContent( '<script src="/js/objects/tinymce/tinymce.min.js"></script>' );
	
		//	if( ! @$_REQUEST['article_type'] )
		//	{
				
		//	}
			$this->setViewContent( $this->getForm()->view() );
			
		//	self::v( $this->getForm()->getValues() );
		//	self::v( $this->getForm()->getBadnews() );
 			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			// authenticate the article_title here because it may not have been done in custom forms
			if( strlen( trim( $values['article_title'] ) ) < 3 )
			{
				// title is required.
				return false;
			}
			
		//	var_export( $values );  
		//	var_export( '3' );  
			
		
			//	Set a category to specify the type of Post this is 
			$table = new Application_Category();
			@$values['article_type'] = $values['article_type'] ? : 'article';
			switch( $values['article_type'] )
			{
				case 'article':
				case 'post':
					$values['article_type'] = 'article';
				break;
				case 'profile':
				case 'organization':
				case 'personality':
					$values['article_type'] = 'profile';
				break;
			}
	//		var_export( $values['article_type'] );
			if( ! $category = $table->selectOne( null, array( 'category_name' => $values['article_type'] ) ) )
			{
			//	var_export();
		//		$this->getForm()->setBadnews( 'POST TYPE MUST BE A VALID CATEGORY: ' . $values['article_type'] );
		//		$this->setViewContent( '' . showBadnews( $this->getForm()->getBadnews() ) . '' );  
			//	return false;
			}
			//	Changing to category_name to correct error in grep
			$values['category_name'] = @$values['category_name'] ? : array();
			if( ! @in_array( $category['category_name'], $values['category_name'] ) )
			{
				@array_push( $values['category_name'], $category['category_name'] );
			}
			array_unique( $values['category_name'] );
			
			
			//	compatibility
/* 			$values['category_id'] = $values['category_id'] ? : array();
			if( ! @in_array( $category['category_id'], $values['category_id'] ) )
			{
				@array_push( $values['category_id'], $category['category_id'] );
			}
			array_unique( $values['category_id'] );
 */			
			
			
			if( is_array( static::$_forcedValues ) )
			{
				$values = array_merge( $values, static::$_forcedValues );
			}
			if( is_array( static::$_optionalValues ) )
			{
				$values = array_merge( static::$_optionalValues, $values );
			}
		//	var_export( $values );
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['article_url'] = substr( trim( $filter->filter( strtolower( $values['article_title'] ) ) , '-' ), 0, 70 );
			$values['user_id'] = $userInfo['user_id'];
			$values['username'] = $userInfo['username'];
			
			$values['profile_url'] = @$userInfo['profile_url'];
			$values['article_creation_date'] = time();
			$values['article_modified_date'] = time();
			@$values['publish'] = ( ! isset( $values['publish'] ) && ! @in_array( 'publish', @$values['article_options'] ) ) ? '1' :  $values['publish'];
			@$values['auth_level'] = is_array( $values['auth_level'] ) ? $values['auth_level'] : array( 0 );
			
		//	$values['article_filename'] = self::getFolder();
		//	$date = $articleSettings['no-date-in-url'] ? '/' : date( '/Y/m/d/' );
			$articleSettings['extension'] = $articleSettings['extension'] ? : 'html';
			
			//	Put the extension
	//		$values['article_url'] .=  '.article.' . $articleSettings['extension'];
		//	$values['article_url'] .=  '.' . $articleSettings['extension'];
			
			//	Check availability of article url
			$time = null;
			do
			{
			
			//	$date = ;
				$newUrl = date( '/Y/m/d/' ) . '' . $values['article_url'] . $time . '.' . $articleSettings['extension'];
				$path = Application_Article_Abstract::getFolder() . $newUrl;
				$time = '-' . $values['article_creation_date'] . '';
			}
			while( is_file( $path ) );
		//	self::v( $newUrl );
			$values['article_url'] =  $newUrl;
			
		//	$values['article_filename'] .=  $values['article_url'];
		//	$content = $values['article_content'];
	//		unset( $values['article_content'] ); // Prevent content from going to the database
	
			//	SAVE ARTICLES DETAILS IN THE CLOUD
		//	if( ! $this->insertDb( $values ) ){ return false; }
			
			
			// Save to server
		//	if( ! $response = Application_Article_Api_Insert::send( $values ) ){ return false; }
		//	if( ! is_array( $response ) || ! is_array( $response['data'] ) ){ throw new Application_Article_Exception( $response ); }
		//	$values = $response['data'];
		//	var_export( $values );
			
			//	write to file
			Ayoola_Doc::createDirectory( dirname( self::getFolder() . $values['article_url'] ) );
			self::saveArticle( $values );
			
			//	Set Hash Tags
			Application_HashTag_Abstract::set( @$values['article_tags'], 'articles', $values['article_url'] );
			
//			return false;
		//	var_export( $values['article_filename'] );
	//		$this->setViewContent( '<div style="goodnews boxednews">Post created successfully.</div> <div style="greynews boxednews"><a href="' . strtolower( $values['article_url'] ) . '">View Post.</a></div>', true );
			$this->_objectData['article_url'] = $values['article_url']; 
		//	$this->setViewContent(  );
		
			// Share
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '' . $values['article_url'] . ''; 
			$this->setViewContent( '<div class="boxednews greynews">Post successfully saved.</div> <div class="boxednews greynews"><a href="' . $fullUrl . '">View Post</a></div>', true );
			$this->setViewContent( '<div class="boxednews greynews" title="Share this with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
						
			//	Notify Admin
			$mailInfo['subject'] = 'New Post Created';
			$mailInfo['body'] = 'A new Post titled "' . $values['article_title'] . '", has been created with the Post module. 
			
			You can view the new Post by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . strtolower( $values['article_url'] ) . '.
			
			To edit, delete or administer the whole module, visit the Post administration page on http://' . Ayoola_Page::getDefaultDomain() . '/article/.
			';
			Application_Log_View_General::log( array( 'type' => 'New Post', 'info' => array( $mailInfo ) ) );
			try 
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
			
			//	Do something after creating an article
		//	self::v( $this->getParameter( 'class_to_play_when_completed' )  );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			
			
		}
		catch( Application_Article_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
