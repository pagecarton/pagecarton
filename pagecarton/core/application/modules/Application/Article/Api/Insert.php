<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Api_Insert
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Insert.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Api_Insert
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Api_Insert extends Application_Article_Api implements Ayoola_Api_Interface
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
	public static function insertCategory( $data )
    {
		$values = $data['data'];
		$values['application_id'] = $data['options']['authentication_info']['application_id'];
		//	Categories
		$table = new Application_Article_ArticleCategory; 
		//	Get rid of the previous categories
		if( ! $table->delete( array( 'article_id' => $values['article_id'], ) ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT DELETE PREVIOUS ARTICLE CATEGORIES INFO ON THE SERVER' );
		}
		$categories = array();
		
		//	Build query for a single insert
		if( ! empty( $values['category_id'] ) )
		{
			foreach( $values['category_id'] as $each )
			{
				$categories[] = array( 'article_id' => $values['article_id'], 'category_id' => $each ); 
			}
		}
		if( $categories )
		{
			if( ! $table->insert( $categories ) )
			{
				throw new Ayoola_Api_Exception( 'COULD NOT INSERT NEW ARTICLE CATEGORIES INFO INTO THE SERVER' );
			}
		}
		return true;
    } 
	
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
		$insert = array( 'user_id' => $values['user_id'], 'article_url' => $values['article_url'], 'application_id' => $values['application_id'], );
		if( ! $insert['user_id'] ){ unset( $insert['user_id'] ); } 
		if( ! $table->insert( $insert ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT SAVE ARTICLE INFO TO THE SERVER' );
		}
	//	$table->insertId();
		$data['data']['article_id'] = (int) $table->insertId();
		if( self::insertCategory( $data ) )
		{
			$data['options']['server_response'] = $data['data'];
			return $data;
		}
    } 
	// END OF CLASS
}
