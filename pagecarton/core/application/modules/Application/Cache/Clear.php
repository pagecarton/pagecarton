<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   Application
 * @package    Application_Cache_Clear
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: City.php date time ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Cache_Clear
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Cache_Clear extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {

		if( self::hasPriviledge() )
		{
			//	Reset domain
			Ayoola_Application::setDomainSettings( true );
			
			//	remove "cache dir" this is causing issues.
			//	Clear cache
			$stupidCache = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . '/cache';
			//	var_export( $stupidCache );
			if( is_dir( $stupidCache ) )
			{
			//	var_export( $stupidCache );
				Ayoola_Doc::deleteDirectoryPlusContent( $stupidCache );
			}
			
			//	Clear cache
			if( is_dir( CACHE_DIR ) )
			{
				Ayoola_Doc::deleteDirectoryPlusContent( CACHE_DIR );
			}
		}
		elseif( self::hasPriviledge( array( 98 ) ) )
		{
			//	Reset domain
			Ayoola_Application::setDomainSettings( true );
					//	var_export( Ayoola_Application::getDomainSettings() );
			//	Clear cache
			$cache = CACHE_DIR . DS . 'STORAGE' . DS . Ayoola_Application::getDomainSettings( 'username' );
			if( is_dir( $cache ) )
			{
			//	var_export( $cache );
				Ayoola_Doc::deleteDirectoryPlusContent( $cache );
			}
		}
		
		//	Destroy the session. User information is lost.
	//	Ayoola_Session::destroy();
		$this->setViewContent( '<h1 class="badnews">Cache Cleared!</h1>', true ); 
	//	$this->setViewContent( '<p class="">Your session might have been lost. Which means you may need to sign in again.</p>' ); 
    } 
	// END OF CLASS
}
