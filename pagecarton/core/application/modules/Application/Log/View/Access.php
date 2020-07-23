<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_Access
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Access.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_Access
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log_View_Access extends Application_Log_View_Abstract
{
	
    /**
     * Table where log goes to
     * 
     * @var string
     */
	protected static $_logTable = 'Application_Log_View_Access_Log';
		
		
    /**
     * Creates a log
     * 
     */
	public static function log()
	{
		
	//	var_export( self::getLogTable() );
        $log = array();
	//	$log['total_run_time'] = microtime( true ) - $log['request_time'];
		$log['total_run_time'] = Ayoola_Application::getRuntimeSettings( 'total_runtime' );
	//	var_export( $log['total_run_time'] );  
	//	var_export( $log ); 
		$timestamp = date( "Y-m-d H:i:s" ); // this line is for demonstration

		$log['total_run_time'] = number_format( $log['total_run_time'], 2 );
	//	var_export( $log['total_run_time'] );
		$log['ip'] = Ayoola_Application::getRuntimeSettings( 'user_ip' );
	//	$log['session_id'] = sha1( session_id() );
	//	$log['post'] = $_POST;
	//	$log['get'] = $_GET; 
	//	$log['request'] = $_REQUEST; 
	//	$log['request'] = $_GET + $_POST;  
		$log['request_time'] = $timestamp;
		if( strlen( serialize( $_POST ) ) < 10000 )
		{
			$log['request'] = $_POST; 
		}
		
		//	NUMBER OF PAGES VIEWED IN THIS SESSION
        @$log['NPS'] = ++$_SESSION['NPS']; 
        
        
    //    $referer = parse_url( 'http://localhost:8888/sample/pc-admin' );
        $referer = parse_url( @$log['http_referer'] );
    //    var_export( $log['http_referer'] );
    //    var_export( $referer );
        $log['log_time'] = time();
          
		$log['referal_domain'] = @$referer['host'];  
		
		unset( $log['request']['password'], $log['request']['password2'], $log['request'][Ayoola_Form::hashElementName( 'password' )], $log['request'][Ayoola_Form::hashElementName( 'password2' )], $log['request']['local_password'], $log['request'][Ayoola_Form::hashElementName( 'local_password' )] );
		$log['uri'] = Ayoola_Application::getPresentUri(); 
		switch( $log['uri'] )
		{
			case '/tools/classplayer':
			case '/object':
			case '/widgets':
			case '/pc-admin':
				@$log['uri'] = @$_GET['object_name'] ? : $_GET['name'];
			break;
		}
		$access = new Ayoola_Access();
		$userInfo = $access->getUserInfo();
	//	var_export( $userInfo ); 
//		if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
		{
		//	var_export( $userInfo ); 
		}
		$log['user_id'] = $userInfo['user_id']; 
		$log['username'] = $userInfo['username']; 
		foreach( self::getLogTable()->getDataTypes() as $key => $value )
		{
            if( empty( $log[$key] ) && ! empty( $_SERVER[strtoupper( $key )] ) )
            {
                $log[$key] = $_SERVER[strtoupper( $key )];
            }
            if( empty( $log[$key] ) && ! empty( $_REQUEST[$key] ) )
            {
                $log[$key] = $_REQUEST[$key];
            }
		}
	//	var_export( $log ); 
		try
		{
			self::getLogTable()->insert( $log );
		}
		catch( Ayoola_Dbase_Adapter_Exception $e ){ null; } // Encountered error when trying to log the process of clearing access log
    }
	// END OF CLASS
}
