<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_View_SignUp
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: SignUp.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Log_View_SignUp
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_View_SignUp extends Application_Log_View_Abstract
{
	
    /**
     * Table where log goes to
     * 
     * @var string
     */
	protected static $_logTable = 'Application_Log_View_SignUp_Log';
		
    /**
     * Creates a log
     * 
     */
	public static function log( array $info )
	{
		foreach( self::getLogTable()->getDataTypes() as $key => $value )
		{
			$log[$key] = @$_SERVER[strtoupper( $key )];
		}
		$log['user_id'] = $info['user_id'];
		$log['ip'] = implode( ':', Ayoola_Application::getRuntimeSettings( 'user_ip' ) );
		$log['time'] = time();
		$result = self::getLogTable()->insert( $log );
   }
	// END OF CLASS
}
