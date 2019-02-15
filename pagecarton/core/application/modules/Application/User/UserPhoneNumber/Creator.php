<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserPhoneNumber_Creator
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
 * @package    Application_User_UserPhoneNumber_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserPhoneNumber_Creator extends Application_User_UserPhoneNumber_Abstract 
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
			$this->createForm( 'Continue', 'Add a phone number' );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( Ayoola_Application::getUserInfo( 'user_id' ) );
			//		var_export( (int) '08054449535' );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
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
					$values['table'] = 'Application_User_UserPhoneNumber';
					$values['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
		//			var_export( $values );
					$response = Ayoola_Api_Dbase::send( $values );
				//	var_export( $response );
				//	var_export( $values );
				//	if( is_array( $response['data'] ) )
					if( true !== @$response['data'] ) 
					{
						$this->getForm()->setBadnews( 'Database error occurred while adding a new phone number.' );
						$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
					
				break;
				case 'relational':
					if( ! $this->insertDb() )
					{ 
						$this->getForm()->setBadnews( 'Database error occurred while adding a new phone number.' );
						$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
				break;
			}
			$this->setViewContent( '<p>Phone number information saved.</p>', true );	
	
		}
		catch( Ayoola_Exception $e )
		{ 
		//	var_export( $e->getMessage() );	
			$this->getForm()->setBadnews( 'Could not add a new phone number. .' );
			$this->setViewContent( $this->getForm()->view(), true );
		}
	//	$this->setViewContent( '<p>What Next? <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/">Verify Credit/Debit Card</a>.</p>' );		
    }
	// END OF CLASS
}
