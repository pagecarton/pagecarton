<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Delete extends Application_User_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['username'],  'Delete User' );
			$this->setViewContent( $this->getForm()->view(), true );
			$namespace = 'Application_User_';
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			
//		case 'cloud':
			$response = Ayoola_Api_UserDelete::send( $data );
	//		var_export( $response );
			if( true === $response['data'] )
			{
				$this->setViewContent( '<p class="goodnews">User deleted successfully in the API</p>', true );
			}
//		break;
//		case 'file':
			// Find user in the LocalUser table
			$table = new Ayoola_Access_LocalUser();
		//	var_export( $data );

			if( $info = $table->delete( array( 'username' => strtolower( $data['username'] ) ) ) )
			{
				$this->setViewContent( '<p class="goodnews">User deleted successfully on the local table</p>', true );
			}
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
			//	$database = 'cloud';
			}
			switch( $database )
			{
				case 'relational':
				//	self::v( $this->deleteDb( false ) ); 					
/* 					foreach( $this->_otherTables as $each )
					{
						$table = $namespace . $each;
						$table = new $table();
						if( ! $table->delete( $this->getIdentifier() ) )
						{
							self::v( $this->getIdentifier() ); 
							$this->getForm()->setBadnews( 'Error while deleting ' . $each );
						//	return false;
						}
					}
 */					if( $this->deleteDb( false ) ){ $this->setViewContent( '<p class="goodnews">User deleted successfully</p>', true ); }
				break;
			
			}
		}
		catch( Application_User_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
