<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_Editor extends Application_Blog_Abstract
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
			$filename = self::getFilePath( $data['blog_directory'], $data['blog_name'] );
			$data['blog_content'] = file_get_contents( $filename );
			$this->createForm( 'Edit', 'Edit ' . $data['blog_title'], $data );
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
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['blog_editor_user_id'] = $userInfo['user_id'];
			$content = $values['blog_content'];
			unset( $values['blog_content'] ); // Prevent content from going to the database
			if( ! $this->updateDb( $values ) ){ return false; }
			$this->setViewContent( 'Blog edited successfully', true );
			Ayoola_Doc::createDirectory( dirname( $filename ) );
			file_put_contents( $filename, $content );
		}
		catch( Application_Blog_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
