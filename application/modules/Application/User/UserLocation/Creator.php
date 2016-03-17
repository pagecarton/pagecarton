<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserLocation_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 10.14.2011 8.06 ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserLocation_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserLocation_Creator extends Application_User_UserLocation_Abstract 
{

    /**
     * Does the process
     *
     * @param 
     * 
     */
    protected function init()
    {
		try
		{
			//	Check if there is a logged in user and redirect
		//	return false;
			
			$this->createForm( 'Continue', 'Add an address' );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( Ayoola_Application::getUserInfo( 'user_id' ) );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
	//		var_export( $values );
		
			//	If we have valid value, save it in the session
	//		if( $validValues = $this->getForm()->getValues() )
			{ 
				$storage = $this->getObjectStorage( 'addresses' ); 
				$addresses = $storage->retrieve() ? : array();
				$addresses[$values['street_address']] = $values;
				$storage->store( $addresses );
			}
			
			//	Check where our user information is being saved.
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'cloud';
			}
			$list = array();
			switch( $database )
			{
				case 'cloud':
					$values['method'] = 'insert';
					$values['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
					$response = Application_User_UserLocation_Api::send( $values );
				//	var_export( $response );
				//	if( is_array( $response['data'] ) )
					if( true !== $response['data'] )
					{
						$this->getForm()->setBadnews( 'AN ERROR OCCURED WHILE ADDING NEW ADDRESS IN THE CLOUD' );
						$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
					
				break;
				case 'relational':
					if( ! $this->insertDb() )
					{ 
						$this->getForm()->setBadnews( 'AN ERROR OCCURED WHILE ADDING NEW ADDRESS' );
						$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
				break;
			}
			$this->setViewContent( '<p>Address information saved.</p>', true );	
	
		}
		catch( Ayoola_Exception $e )
		{ 
		//	var_export( $e->getMessage() );	
			$this->getForm()->setBadnews( 'Could not add a new address. Possibly trying to add an address twice.' );
			$this->setViewContent( $this->getForm()->view(), true );
		}
	//	$this->setViewContent( '<p>What Next? <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/">Verify Credit/Debit Card</a>.</p>' );		
    }
	// END OF CLASS
}
