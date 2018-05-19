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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Create a post'; 

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
		//	var_export( Application_Profile_Abstract::getMyDefaultProfile() );


			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );  
	//		var_export( '1' );
//			$postType = @$_REQUEST['article_type'] ? : 'post'; 
			$postType = @$_REQUEST['article_type'] ? : @$_REQUEST['post_type']; 
			$realType = $postType; 
			$joinedType = $postType; 
			if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $postType ) )
			{
				$realType = $postTypeInfo['article_type'];
				$postType = $postTypeInfo['post_type'];
				$joinedType = $realType . ' ('. $postType . ')'; 
			}   
			$joinedType = $joinedType ? : 'Post';
			@$articleSettings['allowed_writers'] = $articleSettings['allowed_writers'] ? : array();
			$articleSettings['allowed_writers'][] = 98; //	subdomain owners can add posts
			if( ! $this->requireRegisteredAccount() )
			{
				return false;
			}
			if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
			{ 
				$this->setViewContent( '<span class="badnews">You do not have enough priviledge to add a new ' . $joinedType . ' on this website. </span>', true );
				return false;     
			}
			if( ! $this->requireProfile() )
			{
				return false;
			}
			
			$this->createForm( 'Save', $this->getParameter( 'form_legend' ) ? : 'Add a new ' . $joinedType );
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
			$table = Application_Category::getInstance();
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
		//	$postType = $values['article_type'];
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
			$values['category_name'][] = $values['article_type'];
			$values['category_name'][] = $values['true_post_type'];
			if( ! @in_array( $category['category_name'], $values['category_name'] ) )
			{
				@array_push( $values['category_name'], $category['category_name'] );
			}
			array_unique( $values['category_name'] );
			
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
			$values['article_url'] = substr( trim( $filter->filter( strtolower( $values['article_title'] ) ) , '-' ), 0, 70 ) ? : microtime();
			$values['user_id'] = $userInfo['user_id'];
			$values['username'] = $userInfo['username'];
			
		//	$values['profile_url'] = @$userInfo['profile_url'];
			$values['profile_url'] = strtolower( $values['profile_url'] );
			$values['article_creation_date'] = time();
			$values['article_modified_date'] = time();
			@$values['publish'] = ( ! isset( $values['publish'] ) && ! is_array( @$values['article_options'] ) ) ? '1' :  $values['publish'];
			@$values['auth_level'] = is_array( $values['auth_level'] ) ? $values['auth_level'] : array( 0 );
			
		//	$values['article_filename'] = self::getFolder();
		//	$date = $articleSettings['no-date-in-url'] ? '/' : date( '/Y/m/d/' );
			$articleSettings['extension'] = @$articleSettings['extension'] ? : 'html';
			
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
		//	Application_HashTag_Abstract::set( @$values['article_tags'], 'articles', $values['article_url'] );
			
//			return false;
		//	posts var_export( $values['article_filename'] );
			$this->_objectData['article_url'] = $values['article_url']; 
		//	$this->setViewContent(  );
		
			// Share
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . ''; 
			$this->setViewContent( '<div class="boxednews greynews">' . ucfirst( $joinedType ) . ' successfully saved.</div> <div class="boxednews greynews"><a href="' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . '">View ' . $joinedType . '</a></div>', true );
			$this->setViewContent( '<div class="boxednews greynews" title="Share this with your contacts...">' . self::getShareLinks( $fullUrl ) . '</div>' );  
						
			//	Notify Admin
			$mailInfo['subject'] = 'New ' . $joinedType . ' created';
			$mailInfo['body'] = 'A new ' . $joinedType . ' titled "' . $values['article_title'] . '", has been created on your ' . Ayoola_Page::getDefaultDomain() . '. 
			
			You can view the new ' . $joinedType . ' by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . strtolower( $values['article_url'] ) . '
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
	//	catch( Application_Article_Exception $e )
		catch( Exception $e )
		{ 
		//	print_r(debug_backtrace());
		//	exit();
	//		var_export( $e->getTraceAsString() );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
