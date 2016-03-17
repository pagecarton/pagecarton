<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_Clear
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Clear.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
require_once 'Application/Log/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Log_Clear
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_Clear extends Application_Log_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( __LINE__ );
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Clear log' );
			
			//	DON'T LOG
			Ayoola_Application::$accessLogging = false;
			
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $this->getForm()->getValues() ){ return false; }
			$logViewer = $data['log_viewer'];
			if( $path = Ayoola_Loader::checkFile( $logViewer ) )
			{
				if( ! is_writable( $path ) )
				{ 
					$this->setViewContent( 'PROTECTED LOG CANNOT BE MODIFIED', true );
					return false;
				}
				file_put_contents( $path, null );
			}
			else
			{
				//	log viewer is a class
				if( ! $class = Ayoola_Loader::loadClass( $logViewer ) )
				{
					throw new Application_Log_Exception( 'INVALID LOG VIEWER' );
				}
		//		self::v( $logViewer ); 
				$log = $logViewer::clearLog();
			}
			$this->setViewContent( 'Log Cleared Successfully', true );
	//		return;
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<span class="badnews boxednews">' . $e->getMessage() . '</span>' );
			return false; 
		}
    } 
	// END OF CLASS
}
