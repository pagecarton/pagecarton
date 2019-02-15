<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Api
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
 * @package    Application_Article_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Api extends Ayoola_Api implements Ayoola_Api_Interface
{
	
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
		
	//	var_export( $values );
		//	Create an application user.
		$table = new Application_Article; 
		if( ! $data['options']['server_response'] = $table->insert( $values ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT SAVE ARTICLE INFO TO THE SERVER' );
		}
	//	$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
