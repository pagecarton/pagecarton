<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Doc_Abstract
 */
 
require_once 'Ayoola/Doc/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Delete extends Ayoola_Doc_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
		//	$data = $this->getIdentifierData();
 			if( empty( $_GET['uri'] ) && empty( $_GET['url'] ) )
			{ 
				return false; 
			}
			$identifier = $this->getIdentifier();
		//	var_export( $this->getIdentifier() );  
			$url = $identifier['url'] ? : ( $_GET['url'] ? : $_GET['uri'] );
			$myPath = self::getDocumentsDirectory() . $url;
		//	$myPath = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $filename;
		//	$realPath = Ayoola_Loader::checkFile( $filename );
	///		var_export( $myPath );
		//	var_export( $filename );
			if( is_file( $myPath ) )
			{
				$this->createConfirmationForm( 'Delete File', 'Delete "' . $url . '"' );
				$this->setViewContent( $this->getForm()->view(), true );
			}
/*			elseif( is_file( $realPath ) )
			{
				$this->setViewContent( '<p>You cannot delete a protected file.</p>', true ); 
				$this->setViewContent( '<p><a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=%KEY%">Replace file instead</a></p>' ); 
				return false;
			}
*/			else
			{
				$this->setViewContent( '<p>File not found.</p>', true ); 
				$this->setViewContent( '<p><a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/?image_url=' . $url . '">Upload new file instead</a></p>' ); 
				return false;
			}
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( unlink( $myPath ) )
			{ 
				$this->setViewContent( '<p class="goodnews">' . $url . ' deleted successfully</p>', true ); 
			}				
			return true;
		}
		catch( Ayoola_Doc_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
