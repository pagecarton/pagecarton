<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_User_UserLocation_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Api.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_User_UserLocation_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserLocation_Api extends Ayoola_Api implements Ayoola_Api_Interface
{
		
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$applicationId = $data['options']['authentication_info']['application_id'];
	//	var_export( $data );
	//	var_export( array( 'user_id' => $data['data']['user_id'], 'application_id' => $applicationId ) );
		//	Authenticate the APPLICATION USER
 		$table = new Ayoola_Application_ApplicationUserSettings();
		
		if( ! $table->select( null, null, array( 'user_id' => $data['data']['user_id'], 'application_id' => $applicationId ) ) )
		{
			throw new Ayoola_Api_Exception( 'AUTHENTICATION FAILED FOR USER' );
		}
 		$table = new Application_User_UserLocation();
		switch( $data['data']['method'] )
		{
			case 'insert':
				if( ! $response = $table->insert( $data['data'] ) )
				{
					throw new Ayoola_Api_Exception( 'UNABLE TO ADD A NEW ADDRESS FOR USER' );
				}
			break;
			case 'selectOne':
				$response = $table->selectOne( null, null, array( 'user_id' => $data['data']['user_id'] ) );
			break;
			default:
				$response = $table->select( null, null, array( 'user_id' => $data['data']['user_id'] ) );
			break;
		}
		$data['options']['server_response'] = $response;
		return $data;
		
    } 
	// END OF CLASS
}
