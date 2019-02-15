<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Validator_AccountAccessLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AccountAccessLevel.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Validator_AccountAccessLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_AccountAccessLevel extends Ayoola_Validator_Abstract
{
	
    /**
     * User Info to verify against
     *
     * @var array
     */
	public $userInfo;
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		//	By default, authenticate for a standard user
	//	if( ! $value ){ $value = 1; }
		if( ! $this->userInfo )
		{ 
			return Ayoola_Access_Localize::hasPriviledge( array( 1 ) ); 
		}
		//	Check if user sent a username or an email
		$validator = new Ayoola_Validator_EmailAddress();
		$validUserInfo = array();
		
		if( $validator->validate( $this->userInfo['username'] ) )
		{
			$this->userInfo['email'] = $this->userInfo['username'];
			$validUserInfo['email'] = $this->userInfo['email'];
			$validUserInfo['password'] = $this->userInfo['password'];
			$validUserInfo['auth_mechanism'] = 'EmailPassword';
			unset( $this->userInfo['username'] );
		}
		else
		{
			$validator = new Ayoola_Validator_Username();
			if( ! $validator->validate( $this->userInfo['username'] ) )
			{
				return false;
			}
			$validUserInfo['username'] = $this->userInfo['username'];
			$validUserInfo['password'] = $this->userInfo['password'];
		}
		Ayoola_Access_Login::$loginOnAuthentication = false;
	//	var_export( $validUserInfo );
		if( ! $response = Ayoola_Access_Login::localLogin( $validUserInfo ) )
		{ 
 			if( ! $response = Ayoola_Access_Login::apiLogin( $validUserInfo ) ) 
			{
			//	var_export( false );
				null;
			}
 		}
	//	var_export( false );
	//	var_export( $response );
	//	var_export( $value );
	//	var_export( Ayoola_Access_Localize::hasPriviledge( array( 1 ) ) );
		Ayoola_Access_Login::$loginOnAuthentication = true;
	//	var_export( $response );
		return $response ? true : false;
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews() 
    {
        return "Login failed. Please try again with a different username or password.";
    }
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( array $parameters )
    {
		$this->userInfo = $parameters;
    }
	// END OF CLASS
}
