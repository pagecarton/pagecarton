<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Php
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Php.php 1.21.2012 5.13pm ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Php
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Adapter_Abstract_Php extends Ayoola_Doc_Adapter_Abstract
{
		
    /**
     * This method outputs the document inline with HTML
     *
     * @param void
     * @return mixed
     */
    public function viewInline()
    {
		$content = null;
			
		//	ALWAYS LOG PHP FILES FOR SECURITY REASONS
		Ayoola_Application::$accessLogging = true;
		
		foreach( $this->getPaths() as $path )
		{
			// Default method is to include the document
	//		var_export( $path ); 
			$content .= $_REQUEST['do_not_highlight_file'] ? file_get_contents( $path ) : highlight_file( $path, true ); 
		}
		return $content; 
    } 
		
    /**
     * This method outputs the document
     *
     * @param void
     * @return mixed
     */
    public function view()
    {
/* 		foreach( $this->getPaths() as $path )
		{
			// Default method is to include the document
			include_once $path;
		}
 */ 
			
		//	ALWAYS LOG PHP FILES FOR SECURITY REASONS
		Ayoola_Application::$accessLogging = true;
		
		$content = null;
		$paths = array_unique( $this->getPaths() );
		foreach( $paths as $path )
		{
			// Default method is to include the document
		//	var_export( $path );
		//	var_export( Ayoola_Application::getPresentUri() );
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check = 0, pre-check=0');
			header('Pragma: public');
		
			//	Allowing PHP documents to load here because of third party scripts needed to run
			if( stripos( Ayoola_Application::getPresentUri(), '/ayoola/thirdparty/' ) === 0 )
			{
				//	Client-side
				Application_Javascript::addFile( '/js/js.js' );
				Application_Javascript::addFile( '/js/objects/files.js' );
				Application_Javascript::addFile( '/js/objects/events.js' );
				Application_Javascript::addFile( '/js/objects/spotLight.js' );
				Application_Javascript::addFile( '/js/objects/style.js' );
				Application_Javascript::addFile( '/js/objects/xmlHttp.js' );
				Application_Javascript::addFile( '/js/objects/div.js' );
				Application_Javascript::addFile( '/js/objects/js.js' );
				Application_Javascript::addFile( '/ayoola/js/form.js' );
				require_once $path;
			}
			else
			{
				$content .= $_REQUEST['do_not_highlight_file'] ? file_get_contents( $path ) : highlight_file( $path, true ); 
			}
		}
	//	var_export( $content );
		echo $content; 
	} 
	// END OF CLASS
}
