<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Api_Delete
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Api_Delete
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Api_Delete extends Application_Article_Api implements Ayoola_Api_Interface
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
		if( ! $table->delete( array( 'article_id' => $values['article_id'], 'article_url' => $values['article_url'], 'application_id' => $values['application_id'], ) ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT DELETE ARTICLE INFO ON THE SERVER' );
		}
		$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
