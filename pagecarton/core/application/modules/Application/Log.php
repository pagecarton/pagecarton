<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Log.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Log
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log extends Application_Log_Abstract
{
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{ 
	//		var_export( __LINE__ );
			
			//	DON'T LOG
			Ayoola_Application::$accessLogging = false;

			$this->setViewContent( $this->getForm()->view() );
		//	var_export( __LINE__ );
			$this->setViewContent( $this->getXml()->saveHTML() ); 
		}
		catch( Ayoola_Exception $e ){ return false; }
		
	}
	
    /**
     * Returns the options available for the log
     * 
     */
	public function getXml()
    {
		//	var_export( 35 );
	//		var_export( $this->getIdentifier() );
		$log = $this->getIdentifierData();
		$xml = new Ayoola_Xml();
		$table = $xml->createElement( 'table' );
		$table  = $xml->appendChild( $table );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$table->setAttribute( 'class', 'pc-table' );
		
		//	Show the name of the Log
		$data = $xml->createElement( 'th', 'Log Information (' . $log['log_name'] . ')' );
		$data  = $row->appendChild( $data );
		$classPlayer = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name';  
	//	$identifier = http_build_query( $this->getIdentifier() );
		$identifier = null;
		foreach( $this->getIdentifier() as $key => $value )
		{
			$identifier .= $key .  '/' . $value . '/';
		}
	//	$identifier = implode( '/', $this->getIdentifier() ) . '/';

		//	Begin each option on the same row with the name
		$options = array( 'Application_Log_Creator' => 'New Log', 'Application_Log_Clear' => 'Clear Log', 'Application_Log_Editor' => 'Edit Log Viewer' );
		foreach( $options as $player => $viewLink )
		{
			$link = $xml->createElement( 'a', $viewLink );
			$link->setAttribute( 'href', $classPlayer . '/' . $player . '/' . $identifier );
			$link->setAttribute( 'rel', 'shadowbox;' );
			$data = $xml->createElement( 'td' );
			$link  = $data->appendChild( $link );
			$data  = $row->appendChild( $data );		
		}
		
		//	View Log
		$link = $xml->createElement( 'a', 'View Log' );
		$link->setAttribute( 'href', $classPlayer . '/Application_Log_View/' . $identifier );
		$link->setAttribute( 'rel', 'shadowbox' );
		$data = $xml->createElement( 'td' );
		$link  = $data->appendChild( $link );
		$data  = $row->appendChild( $data );

		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'td', $log['log_description'] );
		$data->setAttribute( 'colspan', 5 );
		$data  = $row->appendChild( $data );
		return $xml;
	}
	
    /**
     * Creates the form to select which Log to view
     * 
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'method' => 'GET' ) );
		$form->submitValue = 'View';
		$fieldset = new Ayoola_Form_Element();	
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'log_name', 'log_name' );
		$logs = $filter->filter( $this->getDbData() );
		$fieldset->hashElementName = false;
		$fieldset->addElement( array( 'name' => 'log_name','label' => 'Choose a log', 'description' => 'Select the log to view', 'type' => 'Select' ), $logs );
		$fieldset->addRequirement( 'log_name', array( 'InArray' => array_keys( $logs )  ) );
		unset( $logs );
	//	$fieldset->addElement( array( 'name' => 'View', 'type' => 'Submit', 'value' => 'View' ) );
		$fieldset->addLegend( 'Log View Options' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
