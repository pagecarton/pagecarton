<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_PrimaryId
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PrimaryId.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_PrimaryId
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_PrimaryId extends Ayoola_Api implements Ayoola_Api_Interface
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
	protected static $_accessLevel = 0;
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
		$table = $data['data']['table'];
		if( true !== $table::isApiConnectionAllowed() )
		{
			throw new Ayoola_Abstract_Exception( $table . ' CANNOT BE USED WITH AYOOLA API' );
		}
		do
		{
			$table = new $table();
			if( ! $primaryId = $table->selectOne( null, null, $data['data']['select'] ) )
			{
				$table->insert( $data['data']['insert'] );
				$primaryId = $table->getLastInsertId();
				break;
			}
			$primaryId = $primaryId[$table->getTableName() . '_id'];
		}
		while( false );
		$data['options']['server_response'] = intval( $primaryId );
		return $data;
    } 
	// END OF CLASS
}
