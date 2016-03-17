<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Dbase
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Dbase.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Dbase
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_Dbase extends Ayoola_Api implements Ayoola_Api_Interface
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
/* 	public static function call( $data )
    {
		$options = array_shift( $data );
		$data = array_shift( $data );
		
		//	prevent infinite loop
		if( strstr( $options['class_name'], '_CloudCopy' ) )
		{ 
		//	return false;
		}
		elseif( $options['domain_name'] == Ayoola_Page::getDefaultDomain() )
		{ 
			return false;
		}
		$realClass = str_ireplace( '_CloudCopy', '', $options['class_name'] );
		if( ! Ayoola_Loader::loadClass( $options['class_name'] ) || ! Ayoola_Loader::loadClass( $realClass ) )
		{ 
			throw new Ayoola_Api_Exception( 'INVALID DATABASE TABLE: ' . $options['class_name'] );
		}
		$realClass = new $realClass;
		//		var_export( $options );
		//		var_export( $data );
		//		var_export( $realClass );
		if( ! $realClass instanceof Ayoola_Dbase_Table_Interface  )
		{ 
			throw new Ayoola_Api_Exception( 'INVALID METHOD FOR DATABASE TABLE: ' . $options['function_name'] );
		}
		$method = $options['function_name'];
		return call_user_func_array( array( $realClass, $method ), $data );
    } 
 */	// END OF CLASS

    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$applicationId = $data['options']['authentication_info']['application_id'];
		
		//	Check if table is allowed in the cloude
	//	var_export( $data['data'] );
		$table = $data['data']['table'];
		
		//	So $data may be useful in the where clause
		unset( $data['data']['table'] );
		if( ! Ayoola_Loader::loadClass( $table ) )
		{
			throw new Ayoola_Abstract_Exception( 'Table "' . $table . '" DOES NOT EXIST' );
		}
		if( true !== $table::isApiConnectionAllowed() )
		{
			throw new Ayoola_Abstract_Exception( $table . ' CANNOT BE USED WITH AYOOLA API' );
		}
	
		if( ! empty( $data['data']['user_id'] ) )
		{
			//	Authenticate the APPLICATION USER
			$userTable = new Ayoola_Application_ApplicationUserSettings();
			if( ! $userTable->select( null, null, array( 'user_id' => $data['data']['user_id'], 'application_id' => $applicationId ) ) )
			{
				throw new Ayoola_Api_Exception( 'AUTHENTICATION FAILED FOR USER' );
			}
		}
		if( ! empty( $data['data']['application_id'] ) )
		{
			$data['data']['application_id'] = $applicationId ;
		}
		$table = new $table();		
		$method = $data['data']['method'];
		
		//	So $data may be useful in the where clause		
		unset( $data['data']['method'] );
		switch( $method )
		{
			case 'insert':
				if( ! $response = $table->insert( $data['data'] ) )
				{
					throw new Ayoola_Api_Exception( 'UNABLE TO ADD INFORMATION' );
				}
			break;
			case 'selectOne':
				$response = $table->selectOne( null, null, $data['data'] );
			break;
			default:
				$response = $table->select( null, null, $data['data'] );
			break;
		}
		$data['options']['server_response'] = $response;
		return $data;
		
    } 
 
}
