<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Log_View_Error
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Error.php 10.3.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
//require_once 'Application/Log/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Log_View_Error
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
		//var_export( $message );

		echo '<style>h2, p, pre{ max-width: 900px; margin: 0 auto; padding: 1em; overflow:auto; }</style>';
		echo '<h2></h2>';
		echo '<h2>Critical Error</h2>';

		$log = array( 'error_message' => $message, 'error_time' => time() );
		$mailInfo["subject"] = "Application Error";
		$mailInfo["body"] = $message;
		try
		{
		}
        catch( Ayoola_Exception $e ){ null; }
        function_exists( 'http_response_code' ) ? http_response_code(500) : null;


		$pMessage = "There is error on this page please reload your browser to continue. If this persist, contact the administrator or hosting support. You can also go back to the <a href='/'>Home</a>. The error has been has also been logged into the site log.";
		echo "<p class='badnews'>$pMessage</p>";

		$result = self::getLogTable()->insert( $log );

		if( Application_User_AdminCreator::isNewInstall() || PageCarton_Widget::hasPriviledge( 99 ) )
		{
			echo "<pre>Error Details: $message</pre>";
		}
    }
	// END OF CLASS
}
