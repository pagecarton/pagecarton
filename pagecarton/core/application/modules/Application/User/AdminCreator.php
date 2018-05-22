<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Creator
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
 * @package    Application_User_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_AdminCreator extends Application_User_Creator 
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );

    /**
     * Do the Sign up process
     *
     * @param 
     * 
     */
    protected function init()
    {
		$userTable = new Ayoola_Access_LocalUser();
		if( $response = $userTable->selectOne( null, array( 'access_level' => 99 ) ) )
		{
			//	Don't run this if we have admin present.
			return false;
		}
		$userTable = new PageCarton_MultiSite_Table();
		if( $response = $userTable->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
		{
			//	Don't run this if we are a product of multi-site
			return false;
		}
	//	$auth = new Ayoola_Access();
		$this->createForm( 'Create Admin Account' );
	//	$this->setViewContent( '<h2>Sign up for a free account.</h2>' ); 
 		$this->setViewContent( $this->getForm()->view() );
		
		//	Try to use curent userInfo
		$hashedCredentials = array();			
		if( $values = Ayoola_Application::getUserInfo() )
		{ 
			//	don't update password
			unset( $values['password'] );
		}
		elseif( $values = $this->getForm()->getValues() )
		{ 
			$access = new Ayoola_Access();
			$hashedCredentials = $access->hashCredentials( $values );			
		}
		else
		{
			//	no user info to work on.
			return false;
		}
		$values['access_level'] = 99;
	//	if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'file';
		}
		$saved = false;
		$message = null;
		switch( $database )
		{
			case 'file':
			//	var_export( $values );
				try
				{
					unset( $values['password2'] );
					//	Retrieve the password hash
				//	$values = $hashedCredentials + $values; 	//	We need raw passwords later for login
				
					Ayoola_Access_Localize::info( $hashedCredentials + $values );
					Ayoola_Access_Login::localLogin( $values ); 
				}
				catch( Exception $e )
				{
				//	var_export( $e->getMessage() );
				//	var_export( $e->getTraceAsString() );
				}
			//	var_export( $values );
				$saved = true;
 				
				//	Send Verification E-mail
				//	not yet working for flat files
			//	Application_User_Verify_Email::resetVerificationCode( $values );
			break;
		
		}
	//	var_export( $saved );
		if( ! $saved )
		{ 
		//	var_export( $saved );
			$this->setViewContent( $this->getForm()->view(), true );
			return false;
		}
 		$this->setViewContent( '<h2>Account Opening Confirmation:</h2>', true );
 		$this->setViewContent( '<p>Your account opening process is now complete. An email has been sent to you, containing how to activate and verify your new account.</p>' );
 		$this->setViewContent( '<h4>What Next?</h4>' );
 		$this->setViewContent( '<p>Go to </p>' );
 		$this->setViewContent( '<ul>' );
 		$this->setViewContent( '<li><a href="' . Ayoola_Page::getPreviousUrl() . '">Previous page,</a></li>' );
		
    }
	
    /**
     * Creates the form 
     *
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		$form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		$this->setForm( $form );
		$userTable = new Ayoola_Access_LocalUser();
		$response = $userTable->selectOne( null, array( 'access_level' => 99 ) );
		if( $response || Ayoola_Application::getUserInfo() )
		{
			//	Don't run this if we have admin present.
			// Also if we are a loggedin user, just perfom an upgrade
			return false;
		}
		$userTable = new PageCarton_MultiSite_Table();
		if( $response = $userTable->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
		{
			//	Don't run this if we are a product of multi-site
			return false;
		}
		parent::createForm( $submitValue, $legend, $values );
	//	call_user_func_array( parent::createForm(), func_get_args() );
    } 
	// END OF CLASS
}
