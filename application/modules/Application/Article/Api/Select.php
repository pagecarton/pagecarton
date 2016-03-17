<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Api_Select
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Select.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Api_Select
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Api_Select extends Application_Article_Api implements Ayoola_Api_Interface
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
		$otherTablesList = null;
		$whereClause = ' application_id = ' . $values['application_id'] ;
		if( ! empty( $values['category_id'] ) )
		{
			$otherTablesList .= ', articlecategory ';
			$whereClause .= ' AND article.article_id = articlecategory.article_id AND category_id = ' . $values['category_id'] ;
		}
		if( ! empty( $values['username'] ) )
		{
			$otherTablesList .= ', user ';
			$whereClause .= ' AND article.user_id = user.user_id AND username = "' . $values['username'] . '"' ;
		}
		$sql = 'SELECT DISTINCT * FROM article ' . $otherTablesList . ( $whereClause ? ' WHERE ' . $whereClause : null );
//		var_export( $sql );
		if( ! $data['options']['server_response'] = $table->fetchSQLQuery( $sql ) )
		{
		//	throw new Ayoola_Api_Exception( 'COULD NOT SAVE ARTICLE INFO TO THE SERVER' );
		}
//		$data['options']['server_response'] = true;
		return $data;
    } 
	// END OF CLASS
}
