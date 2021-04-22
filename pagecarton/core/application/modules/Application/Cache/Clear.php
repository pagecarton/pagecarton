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
	protected static function removeBrokenLink( $dir )
    {
        foreach(scandir( $dir ) as $entry) {
            $path = $dir . DIRECTORY_SEPARATOR . $entry;
            if (is_link($path) && !file_exists($path)) {
                unlink($path);
            }
            elseif( is_dir( $path ) && $entry !== '.' && $entry !== '..' )
            {
                self::removeBrokenLink( $path );
            }
        }
        @unlink( $dir );
    }

    
	/**
     * The method does the whole Class Process
     * 
     */
	protected static function do( $dir = null )
    {
        switch( $dir )
        {
            case PC_TEMP_DIR:
            case CACHE_DIR:
            break;
            default:
                $dir = CACHE_DIR;
            break;

        }
        Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
        set_time_limit( 0 );
        
        //	Reset domain
        Ayoola_Application::setDomainSettings( true );
        
        //	remove "cache dir" this is causing issues.
        //	Clear cache
        $ourDir = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) );
        $stupidCache = $ourDir . '/cache';
        
        //	var_export( $stupidCache );
        if( is_dir( $stupidCache ) )
        {
            $tempName = $stupidCache . '.' . time();
            rename( $stupidCache, $tempName );
            Ayoola_Doc::deleteDirectoryPlusContent( $tempName );
        }
        
        //	Clear cache
        if( is_dir( $dir ) )
        {
            $tempName = $dir . '.' . time();
            rename( $dir, $tempName );
            Ayoola_Doc::deleteDirectoryPlusContent( $tempName );
        }
        Ayoola_Application::$appNamespace .= rand( 0, 99999 ) . microtime();
        self::removeBrokenLink( $ourDir );
    }

    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {

		if( self::hasPriviledge() || $this->getParameter( 'strict_clear_all' ) )
		{
            self::do( PC_TEMP_DIR );
		}
		elseif( self::hasPriviledge( array( 98 ) ) || $this->getParameter( 'clear_all' ) )
		{
			self::do( CACHE_DIR );
		}
        if( function_exists( 'apcu_clear_cache' ) )
        {
        //    apcu_clear_cache();
        }
		
		$this->setViewContent(  '' . self::__( '<h1 class="badnews">Cache Cleared!</h1>' ) . '', true  ); 
    } 
	// END OF CLASS
}
