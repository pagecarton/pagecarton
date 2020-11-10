<?php
/**
 * PageCarton
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
 * @category   PageCarton
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

		if( self::hasPriviledge() || $this->getParameter( 'strict_clear_all' ) )
		{

            Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
			set_time_limit( 0 );
			
			//	Reset domain
			Ayoola_Application::setDomainSettings( true );
			
			//	remove "cache dir" this is causing issues.
			//	Clear cache
			$stupidCache = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . '/cache';
			
			//	var_export( $stupidCache );
			if( is_dir( $stupidCache ) )
			{
                $tempName = $stupidCache . '.' . time();
                rename( $stupidCache, $tempName );
				Ayoola_Doc::deleteDirectoryPlusContent( $tempName );
			}
			
			//	Clear cache
			if( is_dir( PC_TEMP_DIR ) )
			{
                $tempName = PC_TEMP_DIR . '.' . time();
                rename( PC_TEMP_DIR, $tempName );
				Ayoola_Doc::deleteDirectoryPlusContent( $tempName );
			}
            Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
		}
		elseif( self::hasPriviledge( array( 98 ) ) || $this->getParameter( 'clear_all' ) )
		{
			//	Reset domain
            Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
			Ayoola_Application::setDomainSettings( true );

            //	Clear cache
			if( is_dir( CACHE_DIR ) )
			{
                $tempName = CACHE_DIR . '.' . time();
                rename( CACHE_DIR, $tempName );
				Ayoola_Doc::deleteDirectoryPlusContent( $tempName );
			}
            Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
		}
		
		$this->setViewContent(  '' . self::__( '<h1 class="badnews">Cache Cleared!</h1>' ) . '', true  ); 
    } 
	// END OF CLASS
}
