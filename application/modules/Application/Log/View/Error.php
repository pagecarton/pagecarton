<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_View_Error
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Error.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Log_View_Error
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_View_Error extends Application_Log_View_Abstract
{
	
    /**
     * Table where log goes to
     * 
     * @var string
     */
	protected static $_logTable = 'Application_Log_View_Error_Log';
		
    /**
     * Creates a log
     * 
     */
	public static function log( $message )
	{
		$log = array( 'error_message' => $message, 'error_time' => time() );
		$mailInfo["subject"] = "Application Error";
		$mailInfo["body"] = $message;
		try
		{
			Ayoola_Application_Notification::mail( $mailInfo );
		}
		catch( Ayoola_Exception $e ){ null; }
		echo "There is error on this page please reload your browser to continue. If this persist, contact the administrator. You can also go back to the <a href=\'/\'>homepage</a>";
	//	var_export( static::getLogTable() );
		$result = self::getLogTable()->insert( $log );
 	//	var_export( $result );
   }
	// END OF CLASS
}
