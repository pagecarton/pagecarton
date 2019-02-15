<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_General
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: General.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_General
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log_View_General extends Application_Log_View_Abstract
{
	
    /**
     * Table where log goes to
     * 
     * @var string
     */
	protected static $_logTable = 'Application_Log_View_General_Log';
		
		
    /**
     * Creates a log
     * 
     */
	public static function log( $logInfo )
	{
	//	$log = array( 'error_message' => $message );
	//	var_export( static::getLogTable() );
		$result = self::getLogTable()->insert( $logInfo );
    }
	// END OF CLASS
}
