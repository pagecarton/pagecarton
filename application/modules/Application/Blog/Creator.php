<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_Creator extends Application_Blog_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Create', 'Create a Blog' );
			$this->setViewContent( $this->getForm()->view(), true );
			Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor_basic.js' );
			$name = Ayoola_Form::hashElementName( 'blog_content' );
			Application_Javascript::addCode( 'ayoola.events.add( window, "load", function(){ CKEDITOR.replace( "' . $name . '" ); } );' );
			Application_Javascript::addCode
			( 
				'ayoola.xmlHttp.setAfterStateChangeCallback( function(){ if (CKEDITOR.instances["' . $name . '"]) { delete CKEDITOR.instances["' . $name . '"] };
				if (CKEDITOR.instances["' . $name . '"]) { CKEDITOR.instances["' . $name . '"].destroy(); } CKEDITOR.replace( "' . $name . '" ); } )' 
			);
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( is_array( static::$_forcedOptions ) )
			{
				$values = array_merge( $values, static::$_forcedOptions );
			}
		//	var_export( $values );
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['blog_name'] = $filter->filter( $values['blog_title'] );
			$values['blog_creator_user_id'] = $userInfo['user_id'];
			$blogPath = Ayoola_Doc::getRandomDirectory( self::getFolder() );
			$values['blog_directory'] = str_ireplace( self::getFolder(), '', $blogPath );
			$values['blog_directory'] = str_ireplace( DS, '/', $values['blog_directory'] );
			$content = $values['blog_content'];
			unset( $values['blog_content'] ); // Prevent content from going to the database
			if( ! $this->insertDb( $values ) ){ return false; }
			$filename = self::getFilePath( $values['blog_directory'], $values['blog_name'] );
			file_put_contents( $filename, $content );
			$this->setViewContent( 'Blog created successfully. <a href="' . Ayoola_Application::getUrlPrefix() . '/' . strtolower( $values['blog_name'] ) . '/">View article.</a>', true );
			
			//	Create Sitemap link for SEO
			try
			{
				$url = '/blog/view/get/blog_name/' . $values['blog_name'] . '/';
				$linkValues = array
				( 
					'link_name' => strtolower( $values['blog_name'] ), 'link_url' => $url, 'link_domain' => DOMAIN, 'link_priority' => 8 
				);
				$link = new Application_Link();
				$link->insert( $linkValues );
			}
			catch( Exception $e ){ return false; }
			
			//	Notify Admin
			$mailInfo['subject'] = 'New Article Created';
			$mailInfo['body'] = 'A new article titled "' . $values['blog_title'] . '", has been created with the Blog module. 
			
			You can view the new article by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '/' . strtolower( $values['blog_name'] ) . '/.
			
			To edit, delete or administer the whole module, visit the Blog administration page on http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/blog/.
			';
			try
			{
				Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
			
			
		}
		catch( Application_Blog_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
