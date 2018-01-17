<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Backup.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup extends Application_Backup_Abstract
{
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		
		try
		{ 
			if( ! $backup = $this->getIdentifierData() ){ return false; }
			$this->setViewContent( $this->getXml()->saveHTML(), true ); 
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
    /**
     * Returns the options available for the Backup
     * 
     */
	public function getXml()
    {
		$filter = new Ayoola_Filter_Time();
		$backup = $this->getIdentifierData();
//		var_export( $backup );
		$backup['backup_creation_date'] = $filter->filter( $backup['backup_creation_date'] );
		$xml = new Ayoola_Xml();
		$table = $xml->createElement( 'table' );
		$table->setAttribute( 'class', 'pc-table' );
		$table  = $xml->appendChild( $table );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		
		//	Show the name of the Backup
		$data = $xml->createElement( 'td', '' . $backup['backup_name'] . '' );
		$data  = $row->appendChild( $data );
		$classPlayer = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/';
		$identifier = http_build_query( $this->getIdentifier() );

		//	Begin each option on the same row with the name
		$options = array( 'Application_Backup_Editor' => 'Export', 'Application_Backup_Download' => 'Download', 'Application_Backup_Delete' => 'Delete', 'Application_Backup_Restore' => 'Restore' );
		foreach( $options as $player => $viewLink ) 
		{
			$link = $xml->createElement( 'a', $viewLink );
			$link->setAttribute( 'href', $classPlayer . $player . '/?' . $identifier );
			$link->setAttribute( 'rel', 'shadowbox;' );
			$data = $xml->createElement( 'td' );
			$link  = $data->appendChild( $link );
			$data  = $row->appendChild( $data );		 
		}

		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'td', $backup['backup_description'] );
		$data->setAttribute( 'colspan', 4 );
		$data  = $row->appendChild( $data );

		//	Time
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'td', 'Created ' . $backup['backup_creation_date'] );
		$data->setAttribute( 'colspan', 3 );
		$data  = $row->appendChild( $data );

		//	Size
		$filter = new Ayoola_Filter_FileSize();
		$diskspace = $filter->filter( @filesize( $backup['backup_filename'] ) );
		$row  = $table->appendChild( $row );
		$data = $xml->createElement( 'td', ' ' . $diskspace );
		$data->setAttribute( 'colspan', 1 );
		$data  = $row->appendChild( $data );
		return $xml;
	}
	
    /**
     * Creates the form to select which Backup to view
     * 
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'method' => 'get' ) );
		$fieldset = new Ayoola_Form_Element();	
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'Backup_name', 'Backup_name' );
		$Backups = $filter->filter( $this->getDbData() );
		$fieldset->addElement( array( 'name' => 'Backup_name', 'description' => 'Select the Backup to view', 'type' => 'Select' ), $Backups );
		$fieldset->addRequirement( 'Backup_name', array( 'InArray' => array_keys( $Backups )  ) );
		unset( $Backups );
		$fieldset->addElement( array( 'name' => 'View', 'type' => 'Submit', 'value' => 'View' ) );
		$fieldset->addLegend( 'Backup View Options' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
