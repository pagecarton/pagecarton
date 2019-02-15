<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_SignIn_Log
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
 * @package    Application_Log_View_SignIn_Log
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
