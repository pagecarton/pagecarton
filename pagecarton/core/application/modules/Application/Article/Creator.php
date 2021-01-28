<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
	protected function isAuthorized()
    {
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );  
		@$articleSettings['allowed_writers'] = $articleSettings['allowed_writers'] ? : array();
		$articleSettings['allowed_writers'][] = 98; //	subdomain owners can add posts
		if( ! $this->requireRegisteredAccount() )
		{
			return false;
		}
		if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
		{ 
            if( ! self::hasPriviledge( @$articleSettings['restricted_writers'] ) )
            { 
                $this->setViewContent(  '<span class="badnews">' . self::__( 'You do not have enough priviledge to publish on this website.' ) . '</span>', true  );
                return false;     
            }
			if( $postTypeInfo = self::getPostTypeInfo() )
			if( empty( $postTypeInfo['auth_level'] ) || ! Ayoola_Abstract_Table::hasPriviledge( $postTypeInfo['auth_level'] ) )
			{ 
                $this->setViewContent(  '<span class="badnews">' . self::__( 'You do not have enough priviledge to publish this kind of post on this website.' ) . '</span>', true  );
				return false;
			}
                
		}
		if( ! $this->requireProfile() )
		{
			return false;
		}
		return true;
	}
	
    /**
     * Returns post type to be created
     * 
     */
	public static function getPostTypeInfo()
    {
        $options = array();
        $postType = @$_REQUEST['article_type'] ? : @$_REQUEST['post_type']; 
        $realType = $postType; 
        $joinedType = $postType; 
        if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $postType ) )
        {
            $realType = $postTypeInfo['article_type'];
            $postType = $postTypeInfo['post_type'];
            $joinedType = $realType . ' (' . $postType . ') item'; 
        } 
        $options['real_type'] = $realType;
        $options['joined_type'] = $joinedType;
        $options['post_type'] = $postType;
        $options += $postTypeInfo ? : array();
        return $options;
    }
	
    /**
     * The method does the whole Class Process
     * 
     */
	public function init()
    {
		try
		{ 

			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );  

			if( $postTypeInfo = self::getPostTypeInfo() )
			{
				$realType = $postTypeInfo['real_type'];
				$postType = $postTypeInfo['post_type'];
				$joinedType = $postTypeInfo['joined_type'];
			}   
			$joinedType = $joinedType ? : 'Post';
			if( ! $this->isAuthorized() )
			{
				return false;
			}
			$this->createForm( 'Save', $this->getParameter( 'form_legend' ) ? : 'New ' . $postType );
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			$this->setViewContent( self::getQuickLink() );
			$this->setViewContent( $this->getForm()->view() );
 			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			// authenticate the article_title here because it may not have been done in custom forms
			if( strlen( trim( $values['article_title'] ) ) < 3 )
			{
				// title is required
				return false;
			}			
		
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
			if( ! $category = $table->selectOne( null, array( 'category_name' => $values['article_type'] ) ) )
			{

            }
			//	Changing to category_name to correct error in grep
			$values['category_name'] = @$values['category_name'] ? : array();
			$values['category_name'][] = $values['article_type'];
			$values['category_name'][] = $values['true_post_type'];
			if( ! @in_array( $category['category_name'], $values['category_name'] ) )
			{
				@array_push( $values['category_name'], $category['category_name'] );
			}
			$values['category_name'] = array_unique( $values['category_name'] );
			
			if( is_array( static::$_forcedValues ) )
			{
				$values = array_merge( $values, static::$_forcedValues );
			}
			if( is_array( static::$_optionalValues ) )
			{
				$values = array_merge( static::$_optionalValues, $values );
			}
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$filter = new Ayoola_Filter_Transliterate();
			$values['article_url'] = $filter->filter( $values['article_title'] );

			$filter = new Ayoola_Filter_SimplyUrl();
			$values['article_url'] = $filter->filter( $values['article_url'] );
	
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			if( function_exists( 'mb_substr') )
			{
				$values['article_url'] = mb_substr( trim( $filter->filter( strtolower( $values['article_url'] ) ) , '-' ), 0, 70 ) ? : microtime();
			}
			else
			{
				$values['article_url'] = substr( trim( $filter->filter( strtolower( $values['article_url'] ) ) , '-' ), 0, 70 ) ? : microtime();
			}
			$values['user_id'] = $userInfo['user_id'];
			$values['username'] = $userInfo['username'];
			
			//	default to my default profile
			$defaultProfile = Application_Profile_Abstract::getMyDefaultProfile();
			$defaultProfile = $defaultProfile['profile_url'];
			$values['profile_url'] = $values['profile_url'] ? : $defaultProfile;
			$values['profile_url'] = strtolower( $values['profile_url'] ) ;
			$values['article_creation_date'] = time();
			$values['article_modified_date'] = time();
			@$values['publish'] = ( ! isset( $values['publish'] ) && ! is_array( @$values['article_options'] ) ) ? '1' :  $values['publish'];
			@$values['auth_level'] = is_array( $values['auth_level'] ) ? $values['auth_level'] : array( 0 );			
			$articleSettings['extension'] = @$articleSettings['extension'] ? : 'html';			
			
			//	Check availability of article url
			$time = null;
			do
			{
			
				$newUrl = date( '/Y/m/d/' ) . '' . $values['article_url'] . $time . '.' . $articleSettings['extension'];
				$path = Application_Article_Abstract::getFolder() . $newUrl;
				$time = '-' . $values['article_creation_date'] . '';
				if( is_file( $path ) )
				{
					if( $thatPost = self::loadPostData( $newUrl ) )
					{
						$keysToCheck = array( 'article_title', 'article_description', 'document_url', 'username', 'user_id', 'article_type', 'profile_url', );
						foreach( $keysToCheck as $eachKey )
						{
							if( @$thatPost[$eachKey] !== @$values[$eachKey] )
							{
								continue 2;
							}
						}
						$this->setViewContent(  '' . self::__( '<div class="badnews">' . ucfirst( $joinedType ) . ' With the same info exists. <a href="' . Ayoola_Application::getUrlPrefix() . '' . $newUrl . '">View ' . $joinedType . '</a> or <a href="' . Ayoola_Page::getPreviousUrl() . '">Go Back</a></div>' ) . '', true  );
						return false;

					}
				}
			}
			while( is_file( $path ) );
			$values['article_url'] =  $newUrl;
						
			//	write to file
            Ayoola_Doc::createDirectory( dirname( self::getFolder() . $values['article_url'] ) );
            
			self::saveArticle( $values );

            $this->_objectData['article_url'] = $values['article_url']; 
		
			// Share
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . ''; 
			$this->setViewContent(  '<div class="goodnews">' . sprintf( self::__( '%s successfully saved.' ), ucfirst( $joinedType ) ) . '</div>', true  );
            $this->setViewContent(  '<a class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . '">' . sprintf( self::__( 'View  %s' ), $joinedType ) . '</a>'  );
            
            $eachPostTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $values['article_type'] );
            if( $eachPostTypeInfo['article_type'] === 'post-list' || in_array( 'post-list', $eachPostTypeInfo['post_type_options'] ) )
            {
                $this->setViewContent(  '<a class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_PostList_Sort?article_url=' . $values['article_url'] . '">' . sprintf( self::__( 'Sort list' ) ) . '</a>'  );

            }
            else
            {
                $this->setViewContent(  '<a class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_PostList_Add?article_url=' . $values['article_url'] . '">' . sprintf( self::__( 'Add post to list' ) ) . '</a>'  );

            }

			$this->setViewContent(  '<a class="pc-btn" href="' . Ayoola_Page::getPreviousUrl() . '">' . sprintf( self::__( 'Go Back' ) ) . '</a>'  );
						
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
			if( $this->getParameter( 'class_to_play_when_completed' ) )
			{
				$this->setViewContent( Ayoola_Object_Embed::viewInLine( array( 'editable' => $this->getParameter( 'class_to_play_when_completed' ) ) + $this->getParameter() ? : array() ) );
			}
			
			
		}
		catch( Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
