<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Generator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Generator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php'; 


/**
 * @category   PageCarton
 * @package    Application_Article_Generator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Generator extends Application_Article_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Whether to hash the form elements name as an antibot mechanism
     *
     * @var boolean
     */
	public $hashFormElementName = false;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			//	Only allowed users can write
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
			{ 
			//	$this->setViewContent( '<p class="badnews">You are not enabled to write articles on this website. Please check that you are properly logged inn. <a rel="spotlight;width=300px;height=300px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/accessLogin/">Check your login status.</a></p>', true );
				return false; 
			}
			  
			//	Check settings
	//		var_export( $articleSettings );
			switch( @$_POST['article_type'] )
			{
				case 'download':
				case 'poll':
				case 'subscription':
				case 'quiz':
				case 'video':
				case 'photos':
				case 'article':
					$this->createArticle();
				break;
				default:
				//	Default is to view a new article generator
				
					//	Retrieve useful form elements for the Js EDITOR
					$usefulElements = array( 'article_description', 'document_url', 'category_name', 'auth_level', 'article_requirements', 'publish', 'article_tags', );
					$elementsMarkUp = null;
					foreach( $this->getForm()->getFieldsets() as $fieldset )
					{
						$elements = $fieldset->getElements();
						foreach( $usefulElements as $each )
						{
							$elementsMarkUp .= str_ireplace( '{{{---@@@BADNEWS@@@---}}}', '', @$elements[$each] );
						}
					
					}
				//	var_export( $elementsMarkUp );
				//	Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
					Application_Javascript::addFile( '/ayoola/js/post.js' );
					Application_Javascript::addFile( '/ayoola/js/form.js' );
					Application_Javascript::addCode( 'ayoola.post.container = "xcontainer";ayoola.post.init();' );
				//	Application_Javascript::addCode( "ayoola.post.categories = '{$fieldset->view()}';" );
				//	$this->setViewContent( Application_Article_Creator::viewInLine(), true );
					$this->setViewContent( self::__( '<div id="xcontainer">Please wait...</div>' ) );
					$this->setViewContent( '<div style= "display:none;" id="ayoola_post_categories">' . $elementsMarkUp . '</div>' ) ;
				break;
			}
			
		//	$this->setViewContent( self::getQuickLink() );
		//	$this->setViewContent( self::__( '<script src="/js/objects/tinymce/tinymce.min.js"></script>' ) );
/* 			if( empty( $values ) )
			{
				return false;
			}
 */			
			
		}
		catch( Application_Article_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	
    /**
     * Creates the article for all the types
     * 
     */
	protected function createArticle()
    {
		$class = new Application_Article_Creator();
		$class->hashFormElementName = false;
		$class->fakeValues = $_POST;
		$class->init();
	//	$class->view();
	//	var_export( $_POST );
		if( ! $class->getForm()->getValues() || $class->getForm()->getBadnews() )
		{
			$this->setViewContent( self::__( '' . showBadnews( $class->getForm()->getBadnews() ) . '' ) );
			return false;
		}
		
	//	$class->getForm()->getValues();
		$this->setViewContent( $class->view() );

	}
	// END OF CLASS
}
