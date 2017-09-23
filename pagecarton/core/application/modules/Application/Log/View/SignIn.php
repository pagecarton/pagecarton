<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Log_View_SignIn
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignIn.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Log_View_SignIn
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log_View_SignIn extends Application_Log_View_Abstract
{
	
    /**
     * Table where log goes to
     * 
     * @var string
     */
	protected static $_logTable = 'Application_Log_View_SignIn_Log';
		
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
//		$log['password'] = $info['password'];
		$log['ip'] = implode( ':', Ayoola_Application::getRuntimeSettings( 'user_ip' ) );
		$log['medium'] = $info['medium'];
		$log['result'] = $info['result'];
//		$log['message'] = $info['message'];
		$log['time'] = time();
		$result = self::getLogTable()->insert( $log );
 	//	var_export( $result );
   }
	// END OF CLASS
}
