<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Play.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Play
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Play extends Application_Article_Abstract
{

    /**
     * The Options Available as a Viewable Object
     * This property makes it possible to use this same class
     * To serve all menu available on the site
     * 
     * @var array
     */
	protected $_classOptions;

    /**
     * The xml string
     * 
     * @var string
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( 'ewe' );
		try
		{
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
	//		@$articleSettings['post_url'] = rtrim( $articleSettings['post_url'] ? : '/article/', '/' );
		//	var_export( $this->getParameter() );
			if( Ayoola_Application::$mode == 'document' )
			{
				//	Read post
				$data = self::getIdentifierData();
			//	$this->setViewContent( Application_Article_Generator::viewInLine() );
				$this->setViewContent( Application_Article_View::viewInLine() );
				$option = array( 'option_name' => 'Edit Post', 'url' => '' . self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'] . '', 'title' => 'View your recent posts.', 'rel' => '', 'link_options' => array( 0 => 'logged_in', ), 'sub_menu_name' => '', 'auth_level' => 0, );
				Ayoola_Menu::setRawMenuOption( 'Posts', $option );
			//	$this->setViewContent( Application_Facebook_Comment::viewInLine() );
			//	$this->setViewContent( Application_Disqus_Comment::viewInLine() );
			}
			else
			{
				switch( @$_GET['post'] )
				{
					case 'creator';
						$this->setViewContent( Application_Article_Creator::viewInLine(), true );
						$pageInfo = array(
							'description' => 'Create a new post on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
							'title' => trim( 'New Post ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
						);
						//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
						Ayoola_Page::setCurrentPageInfo( $pageInfo );
					break;
					case 'generator';
				//		$this->setViewContent( Application_Article_Generator::viewInLine(), true );
						$pageInfo = array(
							'description' => 'Create a new post on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
							'title' => trim( 'New Post ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
						);
						//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
						Ayoola_Page::setCurrentPageInfo( $pageInfo );
					break;
					case 'editor';
						$pageInfo = array(
							'description' => 'Edit post on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
							'title' => trim( 'Edit Post ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
						);
						//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
						Ayoola_Page::setCurrentPageInfo( $pageInfo );
						$this->setViewContent( Application_Article_Editor::viewInLine(), true );
					break;
					default:
					//	$this->setViewContent( Application_Article_Generator::viewInLine() );
						$this->setViewContent( Application_Article_ShowAll::viewInLine() );
				}
			}
			//	Dynamically add menu
			
			//	Only allowed users can write
			if( self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
			{ 
			//	var_export( $articleSettings );
				$option = array( 'option_name' => 'Create new post', 'url' => '' . self::getPostUrl() . '/post/creator/', 'title' => 'Click here to create a new post.', 'rel' => '', 'link_options' => array( 0 => 'logged_in', 1 => 'logged_out', ), 'sub_menu_name' => '', 'auth_level' => 0, );
				Ayoola_Menu::setRawMenuOption( 'Posts', $option );
			}
			$option = array( 'option_name' => 'Recent Posts', 'url' => '' . self::getPostUrl() . '/', 'title' => 'View the most recent posts.', 'rel' => '', 'link_options' => array( 0 => 'logged_in', 1 => 'logged_out', ), 'sub_menu_name' => '', 'auth_level' => 0, );
			Ayoola_Menu::setRawMenuOption( 'Posts', $option );
			$option = array( 'option_name' => 'Trending Posts', 'url' => '' . self::getPostUrl() . '/tag/trend/', 'title' => 'View the most popular posts.', 'rel' => '', 'link_options' => array( 0 => 'logged_in', 1 => 'logged_out', ), 'sub_menu_name' => '', 'auth_level' => 0, );
			Ayoola_Menu::setRawMenuOption( 'Posts', $option );
			$option = array( 'option_name' => 'My Posts', 'url' => '' . self::getPostUrl() . '/tag/mine/', 'title' => 'View your recent posts.', 'rel' => '', 'link_options' => array( 0 => 'logged_in', ), 'sub_menu_name' => '', 'auth_level' => 0, );
			Ayoola_Menu::setRawMenuOption( 'Posts', $option );
			$option = array( 'option_name' => 'Settings', 'url' => '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/', 'title' => 'Advanced posts settings.', 'logged_in' => 1, 'logged_out' => 0, 'append_previous_url' => 0, 'auth_level' => 99, 'rel' => 'spotlight', 'link_options' => NULL, 'sub_menu_name' => '', );
			Ayoola_Menu::setRawMenuOption( 'Posts', $option );
		//	$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( Application_Article_Generator::viewInLine, true );
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' );
			return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->getDbData() );
    } 
	
// END OF CLASS
}
