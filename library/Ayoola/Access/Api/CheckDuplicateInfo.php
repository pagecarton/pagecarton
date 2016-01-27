<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Api_CheckDuplicateInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CheckDuplicateInfo.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Api_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Api_CheckDuplicateInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Api_CheckDuplicateInfo extends Ayoola_Api
{
	
    /**
     * CALL THE required api
     * 
     */
    public function call( array $data )
    {
	//	var_export( $credentials );
		$data['options']['server_response'] = false;
		if( ! empty( $data['data']['username'] ) )
		{
			$table = new Application_User();
			if( $table->selectOne( null, null, array( 'username' => $data['data']['username'] ) ) )
			{
				$data['options']['server_response'] = true;
			}
		}
		elseif( ! empty( $data['data']['email'] ) )
		{
			$table = new Application_User_UserEmail();
			if( $table->selectOne( null, null, array( 'email' => $data['data']['email'] ) ) )
			{
				$data['options']['server_response'] = true;
			}
		}
		return $data;
    } 
	// END OF CLASS
}
