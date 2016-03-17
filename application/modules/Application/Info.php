<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Info
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Info.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Info
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Info extends Ayoola_Abstract_Playable
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{ 
		//	if( ! $info = $this->getIdentifierData() ){ return false; }
			$this->setViewContent( $this->getXml()->saveHTML() ); 
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
    /**
     * Returns the options available for the Info
     * 
     */
	public function getXml()
    {
//		var_export( $info );

		$xml = new Ayoola_Xml();
		$table = $xml->createElement( 'table' );
		$table  = $xml->appendChild( $table );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		
		//	Show the name of the Info
		$data = $xml->createHTMLElement( 'td', 'Application Information' );
	//	$data->setAttribute( 'colspan', 2 );
		$data  = $row->appendChild( $data );
		
		//	Show version
		@$installationInfo = file_get_contents( APPLICATION_DIR . DS . 'backup_information' ) ? : file_get_contents( APPLICATION_PATH . DS . 'backup_information' );
		$installationInfo = unserialize( $installationInfo );
		$installationInfo = @$installationInfo['backup_name'] ? : filemtime( __FILE__ );
		
		$data = $xml->createHTMLElement( 'td', 'Application Framework ' . Ayoola_Application::$version . ' ( ' . $installationInfo . ' )' );
		$data  = $row->appendChild( $data );
		$backup = new Application_Backup_Backup();
		$backup = array_pop( $backup->select() );
		$filterTime = new Ayoola_Filter_Time();
		if( ! empty( $backup['backup_creation_date'] ) )
		{
			$backup = $backup['backup_creation_date'];
		//	$filterTime = new Ayoola_Filter_Time();
			$backup = $filterTime->filter( $backup );
		}
		else{ $backup = 'NEVER'; }
		$users = 0;
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'cloud';
		}
		switch( $database )
		{
			case 'cloud':
				$response = Ayoola_Api_UserList::send( array() );
		//		var_export( $response );
				if( is_array( $response['data'] ) )
				{
					$users = count( $response['data'] );
				}
			break;
			case 'relational':
				$users = new Application_Dbase_Table_User();
				$users = count( $users->select() );
			break;
		
		}
		
		$diskspace = 0;
	//	$this->getObjectStorage( 'time' )->store( time() );
		if( ! $diskspace = $this->getObjectStorage( array( 'id' => 'diskspace', 'device' => 'File', 'time_out' => 86400, ) )->retrieve() )
		{
			$files = Ayoola_Doc::getFilesRecursive( Ayoola_Application::getDomainSettings( APPLICATION_DIR ) );
			$this->getObjectStorage( array( 'id' => 'file_count', 'device' => 'File', ) )->store( count( $files ) ); 
			foreach( $files as $file )
			{ 
				$diskspace = $diskspace + filesize( $file ); 
			}
			$this->getObjectStorage( array( 'id' => 'diskspace', 'device' => 'File', ) )->store( $diskspace );
			$this->getObjectStorage( array( 'id' => 'time', 'device' => 'File', ) )->store( time() );
		}
		$filter = new Ayoola_Filter_FileSize();
		$diskspace = $filter->filter( $diskspace ); 
		$options = array(
							'Default Domain' => Ayoola_Page::getDefaultDomain(), 
							'Last Backup' => "<a href='" . Ayoola_Application::getUrlPrefix() . "/ayoola/backup/'>$backup</a>", 
							'Total Signed Up Accounts' => "<a href='" . Ayoola_Application::getUrlPrefix() . "/ayoola/accounts/'>$users</a>", 
							'Disk Space Used' => $diskspace . ' (' . $this->getObjectStorage( array( 'id' => 'file_count', 'device' => 'File', 'time_out' => 86400, ) )->retrieve() . ' files; ' . $filterTime->filter( $this->getObjectStorage( array( 'id' => 'time', 'device' => 'File', 'time_out' => 86400, ) )->retrieve() ) . ') ',
							'Upgrade' => '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Upgrade/" class="goodnews">Upgrade PageCarton</a>'
						);
		foreach( $options as $key => $value )
		{
			$row = $xml->createElement( 'tr' );
			$row  = $table->appendChild( $row );
			$data = $xml->createElement( 'th', $key );
		//	$data->setAttribute( 'colspan', 1 );
			$data  = $row->appendChild( $data );
			$data = $xml->createHTMLElement( 'td', $value );
		//	$data->setAttribute( 'colspan', 1 );
			$data  = $row->appendChild( $data );
		}
		return $xml;
	}
	  
    /**
     * Creates the form to select which Info to view
     * 
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'method' => 'get' ) );
		$fieldset = new Ayoola_Form_Element();	
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'Info_name', 'Info_name' );
		$infos = $filter->filter( $this->getDbData() );
		$fieldset->addElement( array( 'name' => 'Info_name', 'description' => 'Select the Info to view', 'type' => 'Select' ), $infos );
		$fieldset->addRequirement( 'Info_name', array( 'InArray' => array_keys( $infos )  ) );
		unset( $infos );
		$fieldset->addElement( array( 'name' => 'View', 'type' => 'Submit', 'value' => 'View' ) );
		$fieldset->addLegend( 'Info View Options' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
