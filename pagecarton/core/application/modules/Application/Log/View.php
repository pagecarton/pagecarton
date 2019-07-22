<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log_View extends Application_Log_Abstract
{
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
		//	exit( $this->getLog() );
			//	DONT LOGG logViewer
			Ayoola_Application::$accessLogging = false;
			
			if( ! $this->getLog() )
			{ 
				$this->setViewContent(  '' . self::__( 'LOG IS EMPTY' ) . '', true  );
				return false;
			}
			if( is_string( $this->getLog() ) )
			{
				$this->setViewContent( $this->getLog() );
			}
			else
			{
				//	This is for file logs - Line by Line
				$this->setViewContent( $this->getXml()->saveHTML() );
			}
		}
		catch( Ayoola_Exception $e ){ $this->setViewContent(  '' . self::__( 'ERROR OCCURED WHILE TRYING TO VIEW LOG' ) . '', true  ); }
	}
	
    /**
     * Builds the log into a table for view
     * 
     */
	public function getXml()
    {
		//var_export( $this->getLog() ); exit();	
		$xml = new Ayoola_Xml();
		$table = $xml->createElement( 'table' );
		$table->setAttribute( 'width', '100%' );
		$table  = $xml->appendChild( $table );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'th', 'information' );
		$data  = $row->appendChild( $data );
		$data = $xml->createElement( 'th', 'line' );
		$data  = $row->appendChild( $data );
		foreach( $this->getLog() as $line => $log )
		{
			$row = $xml->createElement( 'tr' );
			$row  = $table->appendChild( $row );
			$data = $xml->createElement( 'td', $log );
	//		$data->setAttribute( 'align', 'justify' );
			$data  = $row->appendChild( $data );
			$data = $xml->createElement( 'td', $line );
			$data  = $row->appendChild( $data );
		}
//		var_export( $this->getLog() );
		return $xml;
    } 
	// END OF CLASS
}
