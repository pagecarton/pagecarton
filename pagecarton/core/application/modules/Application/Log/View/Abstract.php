<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Log_View_Abstract implements Application_Log_View_Interface
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
     * DB Table where log goes to
     * 
     */
	protected static $_logTable;
		
    /**
     * Gets the table of log
     * 
     */
	public static function getLogTable()
	{
		if( ! static::$_logTable ){ throw new Application_Log_View_Exception( 'No DB Table found for Log ' . get_called_class() ); }
		if( is_string( static::$_logTable ) )
		{ 
			$errorMessage = static::$_logTable . ' is not a valid log table';
			if( ! $class = Ayoola_Loader::loadClass( static::$_logTable ) ){ throw new Application_Log_View_Exception( $errorMessage ); }
            $table = static::$_logTable;
			static::$_logTable = $table::getInstance();
			if( ! static::$_logTable instanceof Ayoola_Dbase_Table_Interface ){ throw new Application_Log_View_Exception( $errorMessage ); }
		}
		return static::$_logTable;
    }
		
    /**
     * Gets the log
     * 
     */
	public static function viewLog()
	{
		return static::getLogTable()->view();
    }
		
    /**
     * clear the log
     * 
     */
	public static function clearLog()
	{
	//	self::v( static::getLogTable() );
		return static::getLogTable()->drop();
    }
	
	// END OF CLASS
}
