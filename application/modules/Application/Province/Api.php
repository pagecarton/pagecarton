<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Province_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Api.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   Ayoola
 * @package    Application_Province_Api
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Province_Api extends Ayoola_Api implements Ayoola_Api_Interface
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
		$list = new Application_Province();
		@$country_id = $data['data']['country_id'];
		$list = $list->select( null, 'countryprovince', array( 'country_id' => $country_id ) );
		$data['options']['server_response'] = $list;
	//	var_export( $values );
		return $data;
	//	var_export( $values );
    } 
	// END OF CLASS
}
