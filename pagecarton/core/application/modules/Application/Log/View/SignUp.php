<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_SignUp
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignUp.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_SignUp
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
