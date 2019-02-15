<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_UserList
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UserList.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_UserList
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_UserList extends Ayoola_Api implements Ayoola_Api_Interface
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
	public static function getData( array $where, $method = 'select', array $data = array() )
    {
	//	var_export( $method );
	//	var_export( $method );

	//	Create an application user.
		$table = new Application_User; 
		if( $select = $table->$method( null, 'userpassword,useremail,userpersonalinfo,applicationusersettings,useractivation', $where ) )
		{
			if( @$select['password'] )
			{
				//	change to sha1 for public view
			//	$select['password'] =  sha1( $select['password'] );
			}
	//		throw new Ayoola_Api_Exception( 'COULD NOT SAVE APP USER INFO' );
		}
	//	var_export( $where );
		$data['options']['server_response'] = $select;
		return $data;
	//	var_export( $values );
    } 
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$values = $data['data'];
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		$where = array( 'application_id' => $values['application_id'], );
		$method = 'select';
		if( ! empty( $values['user_id'] ) )
		{
			$where['user_id'] = $values['user_id'];
			$method = @$values['method'] ? : 'selectOne';
		}
		if( ! empty( $values['username'] ) )
		{
			$where['username'] = $values['username'];
			$method = @$values['method'] ? : 'selectOne';
		}
		if( ! empty( $values['email'] ) )
		{
			$where['email'] = $values['email'];
			$method = @$values['method'] ? : 'selectOne';
		}
		if( ! empty( $values['access_level'] ) )
		{
			$where['access_level'] = $values['access_level'];
		}
	//	var_export( $method );
		return self::getData( $where, $method, $data );
	//	var_export( $values );
    } 
	// END OF CLASS
}
