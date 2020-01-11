<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_Clear
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Clear.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_Clear
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
					$this->setViewContent(  '' . self::__( 'PROTECTED LOG CANNOT BE MODIFIED' ) . '', true  );
					return false;
				}
				Ayoola_File::putContents( $path, null );
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
			$this->setViewContent(  '' . self::__( 'Log Cleared Successfully' ) . '', true  );
	//		return;
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( self::__( '<span class="badnews boxednews">' . $e->getMessage() . '</span>' ) );
			return false; 
		}
    } 
	// END OF CLASS
}
