<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_Localize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Localize.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_Localize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Localize extends Ayoola_Access_Abstract   
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
	protected static $_accessLevel = 0;
	
    public function init()
    {
		try
		{ 
			
			require_once 'Ayoola/Access.php'; 
			$auth = new Ayoola_Access();
			require_once 'Ayoola/Page.php'; 
			$userInfo = $auth->getUserInfo();   
			$defaultUserInfo = array();
			
			//	Reset user info
			Ayoola_Application::getUserInfo( false );
			
			if( ! self::hasPriviledge() )
			{
				$response = Ayoola_Api_UserList::send( array( 'access_level' => 99 ) );
				if( ! is_array( $response['data'] ) )
				{
					//	The Ayoola_Api is not working
					// Lets check if this is installer.
					if( is_file( Ayoola_Application::$installer ) &&  is_writable( Ayoola_Application::$installer ) )
					{ 
						//	SELF DESTRUCT THE INSTALLER
						$defaultUserInfo = array( 'user_id' => '0', 'creation_date' => time(), 'creation_ip' => $_SERVER['HTTP_HOST'], 'applicationusersettings_id' => '0', 'application_id' => '1007', 'access_level' => '99', 'enabled' => '1', 'approved' => '1', 'verified' => '1', 'modified_date' => time(), 'modified_ip' => '0', 'userpassword_id' => '0', 'password' => NULL, 'userpersonalinfo_id' => '0', 'firstname' => 'Administrator', 'lastname' => 'Webmaster', 'middlename' => '', 'sex' => 'M', 'birth_date' => '0000-00-00', 'useremail_id' => '0', 'email' => $this->getGlobalValue( 'email' ), 'emailtype_id' => '1', 'email_verification_status' => '0', 'email_verification_code' => '0', 'socialmediauser_id' => NULL, 'socialmedia_id' => NULL, 'socialmediauser_info' => NULL, 'socialmediauser_foreign_id' => NULL, );
						
					}
				}
				else
				{
					return false;
					throw new Ayoola_Access_Exception( 'ATTEMPT TO LOCALIZE A NON-ADMINISTRATIVE USER "' . $userInfo['username'] . '" ' );
				}
			} 
			$userInfo = array_merge( $defaultUserInfo, $userInfo ? : array() ); 
			if( ! $this->getGlobalValue( 'password' ) )
			{
				$this->setViewContent( $this->getForm()->view() );
				if( ! $this->getForm()->getValues() ){ return false; }
			}
			if( ! $values = $this->getForm()->getValues() ){ null; }
			
			$userInfo['password'] = $this->getGlobalValue( 'password' ) ? : $values['local_password'];  
			$userInfo['username'] = @$userInfo['username'] ? : $this->getGlobalValue( 'username' ); 
			if( ! $userInfo['password'] )
			{

            }
			
			//	Retrieve the password hash
			$access = new Ayoola_Access();
			$hashedCredentials = $access->hashCredentials( $userInfo );
		
			//	some scripts still need my password set but i must clear for security reasons
			$userInfo['password'] = $hashedCredentials['password'];
			
			// Insert the new user information into the LocalUser table
			try
			{
				self::info( $userInfo );
			}
			catch( Exception $e )
			{ 
				$this->getForm()->setBadnews( $e->getMessage() );
				$this->setViewContent( $this->getForm()->view(), true );
				return false; 
			}
			
			
		}
		catch( Application_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	
    /**
     * This method performs the class' essense.
     *
     * @param array User information to localize
     * @return boolean
     */
    public static function info( array $userInfo )
	{
		
		//	Localize information 
        $table = Ayoola_Access_LocalUser::getInstance( "xyz" );
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'private';
		}
		switch( $database )
		{
			case 'private':
				// Find user in the LocalUser table
				$table = "Ayoola_Access_LocalUser";
				$table = $table::getInstance( $table::SCOPE_PRIVATE . "xyz" );
				$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
				$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
			break;
			case 'file':
				// Find user in the LocalUser table
				$table = "Ayoola_Access_LocalUser";
				$table = $table::getInstance( $table::SCOPE_PUBLIC . "xyz" );
				$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PUBLIC );
				$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PUBLIC );
			break;
		}
        

		if( $oldInfo = $table->selectOne( null, array( 'username' => array( $userInfo['username'], strtolower( $userInfo['username'] ) ) ) ) )
		{
		
			try
			{
				if( ! $table->delete( array( 'username' => array( '', $userInfo['username'], strtolower( $userInfo['username'] ) ) ) ) )
				{
					//	If we cant delete, it means the info is protected on parent site. Just return "safely";
					return false;
				}
			}
			catch( Exception $e )
			{
			}
			@$userInfo['password'] = $userInfo['password'] ? : $oldInfo['password'];
			
			
			//	Retain old info incase of edit
			@$userInfo = ( is_array( $userInfo ) ? $userInfo : array() ) + ( is_array( $oldInfo['user_information'] ) ? $oldInfo['user_information'] : array() );
			
		}
		
		$newInfo = $userInfo;
		unset( $newInfo['password'] );
		$defaultUserInfo = array( 'user_id' => strval( microtime( true ) ), );
		$newInfo = array_merge( $defaultUserInfo, $newInfo ? : array() );      
		//	
 		try
		{
			@$table->insert( array( 'username' => strtolower( $userInfo['username'] ), 'email' => strtolower( $userInfo['email'] ), 'access_level' => intval( $userInfo['access_level'] ), 'password' => $userInfo['password'], 'user_information' => $newInfo, ) );	
		}
		catch( Exception $e )
		{

        }
        if( strtolower( $userInfo['username'] ) === strtolower( Ayoola_Application::getUserInfo( 'username' ) ) )
        {
            //  login again with new info
            Ayoola_Access_Login::login( $userInfo );
        }
		return true;
	}
	
    /**
     * Creates the form 
     *
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php'; 
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		if( ! $this->getGlobalValue( 'password' ) )
		{
			$form->submitValue = 'Save' ;
			$fieldset = new Ayoola_Form_Element();
			$fieldset->id = __CLASS__;
		//	$fieldset->placeholderInPlaceOfLabel = true;
			$fieldset->addElement( array( 'name' => 'local_password', 'label' => 'Secondary Password', 'placeholder' => 'Secret Password', 'type' => 'InputPassword', 'value' => @$values['local_password'] ) );
			
			$fieldset->addRequirement( 'local_password', 'WordCount=>8;;30' );
			$fieldset->addFilters( 'Trim::Escape' );
		//	$fieldset->addFilter( 'username','Username' );
		//	$fieldset->addLegend( '' );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
