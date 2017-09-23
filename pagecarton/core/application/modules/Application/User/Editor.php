<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Editor extends Application_User_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 1;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifier() ){ return false; }
			if( $data['username'] !== Ayoola_Application::getUserInfo( 'username' )  && ! self::hasPriviledge( 98 ) )
			{
				//	We are not the owner of data and we are not admin
				return false;
			}
			if( ! $data = self::getIdentifierData() ){ return false; }
			
			if( $data['username'] !== Ayoola_Application::getUserInfo( 'username' )  && ! self::hasPriviledge( 98 ) )
			{
				//	We are not the owner of data and we are not admin
				return false;
			}
				
	//		var_export( $data );
			$this->createForm( 'Save...', $data['username'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
	//		var_export( $values );  
			
			//	Empty password means we are not trying to update it
			unset( $values['password2'] );
			if( empty( $values['password'] ) )
			{ 
				unset( $values['password'] );   
			}
	//		var_export( $values );  
			
			if( $values['access_level'] == 99 )
			{
				if( ! self::hasPriviledge() )
				{
					//	We must be an admin to even try to do this
					return false;
				}
				//	We have a new administrator
				//	lets do a welcome service.
				$table = new Application_User_NotificationMessage(); 
				$emailInfo = $table->selectOne( null, array( 'subject' => 'Welcome to the Admin Group' ) );
				$replacements = array( 
									'firstname' => $data['firstname'], 
									'domainName' => Ayoola_Page::getDefaultDomain(), 
								);
				$emailInfo = self::replacePlaceholders( $emailInfo, $replacements );
			//	var_export( $emailInfo );
				$emailInfo['to'] = $data['email'];
				$emailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
				@self::sendMail( $emailInfo );
			}
			$values = array_merge( $data, $values );
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'file';
			}
			
			switch( $database )
			{
				case 'cloud':
					$response = Ayoola_Api_UserEditor::send( $values );
			//		var_export( $response );
					if( true === $response['data'] )
					{
					//	$this->setViewContent( 'User account edited successfully', true );
						$this->setViewContent( '<div class="boxednews goodnews">User account edited successfully</div>', true );
						
						
						//	localize
						unset( $values['password'] ); 
						Ayoola_Access_Localize::info( $values );
					}
				break;
				case 'relational':
					if( $this->updateDb() )
					{
					//	$this->setViewContent( 'User account edited successfully', true );
						$this->setViewContent( '<div class="boxednews goodnews">User account edited successfully</div>', true );
					}
				break;
				case 'file':
					try
					{
						$values = $values + $data;
						
						
						//	Retrieve the password hash
						$access = new Ayoola_Access();
						$hashedCredentials = $access->hashCredentials( $values );
						$values = $hashedCredentials + $values;
						if( Ayoola_Access_Localize::info( $values ) )
						{
							$this->setViewContent( '<div class="boxednews goodnews">User account changes saved successfully.</div>', true );
						}
					}
					catch( Exception $e )
					{
					//	var_export( $e->getMessage() );
					//	var_export( $e->getTraceAsString() );
					}
				break;
			
			}
		}
		catch( Application_User_Exception $e ){ return false; }
    } 
	
    /**
     * This method does the database operation
     *
     * @param void
     * @return boolean
     */
    protected function updateDb()
    {
		
		if( ! $this->_validate() ){ return false; }
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }
		
	//	var_export( $values['password'] );
	//	return;
	//	var_export( $this->getIdentifier() );
/* 		if( ! $this->getDbTable()->update( $values, $this->getIdentifier() ) )
		{
			$this->getForm()->setBadnews( 'System error from - ' . $this->getObjectName() );
			return false;
		}
 */		$this->_otherTables = array_combine( array_values( $this->_otherTables ), $this->_otherTables ); 
		if( ! $values['password'] ){ unset( $this->_otherTables['UserPassword'] ); }
		else
		{
			require_once 'Ayoola/Filter/Hash.php';
			$filter = new Ayoola_Filter_Hash( 'sha512' );
			$values['password'] = $filter->filter( $values['password'] );
		
		}
		unset( $this->_otherTables['UserActivation'] );
	//	var_export( $values['password'] );
	//	var_export( $this->_otherTables );
		$namespace = 'Application_User_';
		foreach( $this->_otherTables as $each )
		{
			$table = $namespace . $each;
			$table = new $table();
			if( ! $table->update( $values, $this->getIdentifier() ) )
			{
				$this->getForm()->setBadnews( 'Error while updating ' . $each );
				return false;
			}
		}
		return true;
    } 
	// END OF CLASS
}
