<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_View_SignIn_Log
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
 * @package    Application_Log_View_SignIn_Log
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_View_SignIn_Log extends Ayoola_Dbase_Table_Abstract_Xml
{
	protected $_dataTypes = array
	( 
		'user_id' => 'INPUTTEXT',
//		'password' => 'INPUTTEXT',
		'ip' => 'INPUTTEXT',
		'time' => 'INPUTTEXT',
		'medium' => 'INPUTTEXT',
		'result' => 'INPUTTEXT',
//		'message' => 'INPUTTEXT',
		'http_user_agent' => 'INPUTTEXT',
		'request_uri' => 'INPUTTEXT',
	);
	// END OF CLASS
}
