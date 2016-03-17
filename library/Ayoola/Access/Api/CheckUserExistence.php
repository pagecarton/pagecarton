<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Api_CheckUserExistence
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CheckUserExistence.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Api_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Api_CheckUserExistence
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Api_CheckUserExistence extends Ayoola_Api
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
	protected static $_accessLevel = 99;
	
    /**
     * CALL THE required api
     * 
     */
    public function call( array $data )
    {
		$credentials = array( 'application_id' => $data['options']['authentication_info']['application_id'] );
	//	var_export( $credentials );
		$auth = new Ayoola_Access();
		if( ! empty( $data['data']['auth_mechanism'] ) )
		{
			$auth->setAuthMechanism( $data['data']['auth_mechanism'] );
		}
		$auth->setCredentials( $credentials );
		if( $auth->authenticate( $data['data'] ) )
		{
			$data['options']['server_response'] = $auth->getUserInfo();
		//	$response = array( 'data' => $auth->getUserInfo(), 'options' => $data['options']['return_info'] );
			return $data;
		}
		return false;
    } 
	// END OF CLASS
}
