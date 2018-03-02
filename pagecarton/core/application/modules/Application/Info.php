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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Application Info'; 
	
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
	//	return null;    
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
		$table->setAttribute( 'class', 'pc-table' );
		$row = $xml->createElement( 'tr' );
		$row  = $table->appendChild( $row );
		
		//	Show the name of the Info
		$data = $xml->createHTMLElement( 'th', 'PageCarton Version' );
	//	$data->setAttribute( 'colspan', 2 );
		$data  = $row->appendChild( $data );
		
		//	Show version
		@$installationInfo = file_get_contents( APPLICATION_DIR . DS . 'backup_information' ) ? : file_get_contents( APPLICATION_PATH . DS . 'backup_information' );
		$installationInfo = unserialize( $installationInfo );
		$installationInfo = @$installationInfo['backup_name'] ? : filemtime( __FILE__ );
		
		$data = $xml->createHTMLElement( 'td', 'PageCarton ' . PageCarton::VERSION . ' ( ' . $installationInfo . ' )' );
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
		//	$database = 'cloud';
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
			default:
				$users = new Ayoola_Access_LocalUser();
				$users = count( $users->select() );
			break;
		
		}
/* 		
		$diskspace = 0;
		$this->getObjectStorage( 'time' )->store( time() );
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
 */		
			$options = array(
							'Default Domain' => Ayoola_Page::getDefaultDomain(), 
							'Last Backup' => "<a href='" . Ayoola_Application::getUrlPrefix() . "/ayoola/backup/'>$backup</a>", 
							'Total Signed Up Accounts' => "<a href='" . Ayoola_Application::getUrlPrefix() . "/ayoola/accounts/'>$users</a>", 
					//		'Disk Space Used' => $diskspace . ' (' . $this->getObjectStorage( array( 'id' => 'file_count', 'device' => 'File', 'time_out' => 86400, ) )->retrieve() . ' files; ' . $filterTime->filter( $this->getObjectStorage( array( 'id' => 'time', 'device' => 'File', 'time_out' => 86400, ) )->retrieve() ) . ') ',
							'Upgrade' => '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Upgrade/" class="pc-btn pc-bg-color pc-btn-small">Upgrade PageCarton</a>'
						);
						
		$dataX['domain'] = Ayoola_Page::getDefaultDomain();
		$dataX['homepage'] = $dataX['domain'] . Ayoola_Application::getUrlPrefix();
		$dataX['last_backup'] = $backup;
		$dataX['user_count'] = $users;
		$dataX['pagecarton_version'] = PageCarton::VERSION;
		$dataX['pagecarton_version_info'] = PageCarton::VERSION . ' (' . $installationInfo . ')';
		$dataX['pagecarton_version_info'] = PageCarton::VERSION . ' (' . $installationInfo . ')';
		$dataX['upgrade_link'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Upgrade';
		
		//	Pages
		$option = Ayoola_Page_Page::getInstance();
		$option->getDatabase()->setAccessibility( $option::SCOPE_PRIVATE );
		$dataX['page_count'] = count( $option->select() );
		
		//	Themes
		$table = Ayoola_Page_PageLayout::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
		$myThemes = $table->select( null, null, array( 'workww--x-acrrwwwosssuwdnd-1-333' => true ) );
		$dataX['theme_count'] = count( $myThemes );
		
		//	Forms
		$table = new Ayoola_Form_Table;
		$dataX['form_count'] = count( $table->select() );
		
		//	Posts
//		$parameters = array( 'username_to_show' => $username );  
		$class = new Application_Article_List();   
		$class->setDbData();
		$allPosts = $class->getDbData();
		$dataX['post_count'] = count( $allPosts );
		
		$this->_objectData = $dataX;
		$this->_objectTemplateValues = $dataX;
		
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
