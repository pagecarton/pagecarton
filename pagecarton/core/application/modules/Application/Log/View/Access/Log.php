<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_Access_Log
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Log.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml/Private.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_Access_Log
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Log_View_Access_Log extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.05';

    /**
     * Time to hold the cache before refreshing
     *
     * @param int
     */
    public static $cacheTimeOut = 86400;

	protected $_dataTypes = array
	( 
		'uri' => 'INPUTTEXT',
		'http_referer' => 'INPUTTEXT',
		'referal_domain' => 'INPUTTEXT',
		
		//	NUMBER OF PAGES VIEWED IN THIS SESSION
		'NPS' => 'INPUTTEXT',
		
		'ip' => 'INPUTTEXT',
		'http_host' => 'INPUTTEXT',
		'http_user_agent' => 'INPUTTEXT',
		'request_method' => 'INPUTTEXT', 
		'request_time' => 'INPUTTEXT',
		'total_run_time' => 'INPUTTEXT',
		'user_id' => 'INPUTTEXT',
		'request' => 'JSON', 
	);
	// END OF CLASS
}
