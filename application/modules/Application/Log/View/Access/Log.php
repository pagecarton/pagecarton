<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_View_Access_Log
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Log.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml/Private.php';


/**
 * @category   Ayoola
 * @package    Application_Log_View_Access_Log
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_View_Access_Log extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.04';

	protected $_dataTypes = array
	( 
		'uri' => 'INPUTTEXT',
		'http_referer' => 'INPUTTEXT',
		
		//	NUMBER OF PAGES VIEWED IN THIS SESSION
		'NPS' => 'INPUTTEXT',
		
		'ip' => 'INPUTTEXT',
//		'server_name' => 'INPUTTEXT',
		'http_host' => 'INPUTTEXT',
		'http_user_agent' => 'INPUTTEXT',
//		'http_request_type' => 'INPUTTEXT',
//		'http_application_mode' => 'INPUTTEXT',
//		'redirect_status' => 'INPUTTEXT',
//		'server_protocol' => 'INPUTTEXT',
		'request_method' => 'INPUTTEXT', 
	//	'request_uri' => 'INPUTTEXT',
//		'redirect_url' => 'INPUTTEXT',
		'request_time' => 'INPUTTEXT',
		'total_run_time' => 'INPUTTEXT',
		'user_id' => 'INPUTTEXT',
	//	'post' => 'JSON', 
	//	'get' => 'JSON',
	//	'request' => 'ARRAY', 
		'request' => 'JSON', 
	//	'log_time' => 'INPUTTEXT', 
//		'session_id' => 'INPUTTEXT'
	);
	// END OF CLASS
}
