<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Event_NewSession
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NewSession.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Event_NewSession
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Event_NewSession extends Ayoola_Event
{
	
    /**
     * Plays the class
     * 
     */
	public function init()
    {
		//	See if a user is still logged in through cookie
		//	Try to login with persistent cookie variables
		do
		{
		//	var_export( APPLICATION_DIR );
		//	var_export( $_COOKIE );
		
		//	break;
			if( isset( $_COOKIE[Ayoola_Session::getName()] ) )
			{
				//	new session
		//		break;
			}
			
			$auth = new Ayoola_Access();
			if( empty( $_COOKIE['accessLogin'] ) ){ break; }
			if( $userInfo = $auth->getUserInfo() ){ break; }
		//	var_export( $_COOKIE );
		//	self::v( $_COOKIE );

			$cookieValue = $_COOKIE['accessLogin'];
			list( $cookieUserid, $cookiePassword, $cookieCreationTime ) = explode( ':', base64_decode( $cookieValue ) );
			if( ! isset( $cookieUserid, $cookiePassword, $cookieCreationTime ) ){ break; }
			$cookieAge = time() - $cookieCreationTime;
			if( $cookieAge < 0 || $cookieAge > 1728000 ){ break; }
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'cloud';
			}
			$saved = false;
			switch( $database )
			{
				case 'cloud':
					$response = Ayoola_Api_UserList::send( array( 'user_id' => intval( $cookieUserid ) ) );
			//		self::v( $response );
					if( is_array( $response['data'] ) )
					{
						$realUserInfo = $response['data'];
		//		var_export( $realUserInfo );
					}
				break;
				case 'relational':
					$table = new Application_User();
					if( ! $realUserInfo = $table->selectOne( '', 'useremail, usersettings, userpassword, userpersonalinfo, useractivation', array( 'user_id' => intval( $cookieUserid ) )  ) ){ break; }
				break;
			
			}
			if( empty( $realUserInfo['password'] ) ){ break; }
			if( $realUserInfo['access_level'] > 98 )
			{ 
				
		//		self::v( $realUserInfo );
		//		self::v( Ayoola_Application::getUserInfo() );
		//		self::v( $cookiePassword );
		//		self::v( Ayoola_Access_Login::hashPassWord( $realUserInfo['password'], $cookieCreationTime ) );
				
		//		exit();
				break;
			}
			$correctCookiePassword = Ayoola_Access_Login::hashPassWord( $realUserInfo['password'], $cookieCreationTime );
		//	self::v( $cookieCreationTime ); 
		//	self::v( $cookieUserid );
		//	self::v( $cookiePassword );
		//	self::v( $correctCookiePassword );
		//	self::v( $realUserInfo['password'] );
		
	//		if( 
			if( $correctCookiePassword != $cookiePassword )
			{
				$auth->logout();
				break;
			}
	//	exit( $correctCookieValue );
			$auth->getStorage()->store( $realUserInfo );
		}
		while( false );
    } 
	// END OF CLASS
}
