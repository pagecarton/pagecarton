<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api_UserEditor
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UserEditor.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api_UserEditor
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_UserEditor extends Ayoola_Api implements Ayoola_Api_Interface
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
	public static function call( $data )
    {
		$values = $data['data'];
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		$where = array( 'user_id' => $values['user_id'], 'application_id' => $values['application_id'], );
		
		//	Create an application user.
		$table = new Ayoola_Application_ApplicationUserSettings; 
		if( empty( $values['applicationusersettings_id'] ) )
		{
		//	var_export( $values );
			if( ! self::getPrimaryId( $table, $values, $where ) )
			{
				throw new Ayoola_Api_Exception( 'COULD NOT SAVE APP USER INFO' );
			}
		}
		else
		{
			$table->update( $values, $where );
		}
		$data['options']['server_response'] = true;
		return $data;
	//	var_export( $values );
    } 
	// END OF CLASS
}
