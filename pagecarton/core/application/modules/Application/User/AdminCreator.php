<?php
/**
 * PageCarton
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
     *
     * 
     */
    public static function isNewInstall()
    {
		$table = new PageCarton_MultiSite_Table();
		if( $response = $table->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) ) )
		{
			//	Don't run this if we are a product of multi-site
			return false;
		}
		if( ! is_file( 'index.php' ) )
		{
			//	Don't run this if we didn't just install site
			return false;
		}


		if( time() - filemtime( 'index.php' ) > 3600  )
		{
			switch( $_SERVER['SERVER_NAME'] )
			{
				case '127.0.0.1':
				case 'localhost':

				break;
				default:
					//	We must activate a new install within an hour
					return false;
				break;
			}
		}
		//	set table to private so when parent have admin, we dont allow new admin on child
		//	if not like this, it becomes a security breach on .com
		$userTable = 'Ayoola_Access_LocalUser';
		$userTable = $userTable::getInstance( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setAccessibility( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setRelationship( $userTable::SCOPE_PROTECTED );
		$response = $userTable->select( null, array( 'access_level' => 99 ), array( 'disable_cache' => true ) );
		if( $response )
		{
			//	Don't run this if we have admin present.
			return false;
		}

		return true;

	}

    /**
     * Do the Sign up process
     *
     * @param 
     * 
     */
    protected function init()
    {

		if( ! self::isNewInstall() )
		{
			return false;
		}

		if( $this->getParameter( 'new_site_setup' ) )
		{
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/personalize' );
			exit();
		}

		$this->createForm( '' . self::__( 'Create admin account' ) . '' );
 		$this->setViewContent( $this->getForm()->view() );
		
		//	Try to use curent userInfo
		$hashedCredentials = array();			

		
		if( $values = $this->getForm()->getValues() )
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
			$database = 'private';
		}
		$saved = false;
		$message = null;
		switch( $database )
		{
			default:
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
 		$this->setViewContent(  '' . self::__( '<h2>Account Opening Confirmation:</h2>' ) . '', true  );
 		$this->setViewContent( self::__( '<p>Your account opening process is now complete. An email has been sent to you, containing how to activate and verify your new account.</p>' ) );
 		$this->setViewContent( self::__( '<h4>What Next?</h4>' ) );
 		$this->setViewContent( self::__( '<p>Go to </p>' ) );
 		$this->setViewContent( self::__( '<ul>' ) );
 		$this->setViewContent( self::__( '<li><a href="' . Ayoola_Page::getPreviousUrl() . '">Previous page,</a></li>' ) );
		
    }
	
    /**
     * Creates the form 
     *
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		$form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		$this->setForm( $form );
		
		if( ! self::isNewInstall() )
		{
			return false;
		}

		parent::createForm( $submitValue, $legend, $values );
		//	call_user_func_array( parent::createForm(), func_get_args() );
    } 
	// END OF CLASS
}
