<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Verify
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Verify.php 12-30-2012 5.16 ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @user   Ayoola
 * @package    Application_User_Verify
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Verify extends Application_User_Creator
{

    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'mode' );
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_User';

    /**
     * verify
     *
     * @param 
     * 
     */
    protected function init()
    {
		//	Check if there is a logged in user and redirect
/* 		$this->createForm( 'Verify Account' );
		require_once 'Ayoola/Access.php'; 
		$auth = new Ayoola_Access();
		if( $auth->isLoggedIn() )
		{ 
			require_once 'Ayoola/Page.php'; 
			$urlToGo = urldecode( Ayoola_Page::getPreviousUrl() );
			//var_export( $urlToGo );
			header( 'Location: ' . $urlToGo );
			exit();
		}
 */		//	Detect mode
//			var_export( $className );
		$mode = $_GET['mode'];
		$className = __CLASS__ . '_' . ucfirst( strtolower( $mode ) );
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $className ) )
		{ 
		//	var_export( $className );
			throw new Application_User_Exception( 'INVALID VERIFICATION MODE' ); 
		}
/* 		$class = new $className();
		if( ! $class instanceof Application_User_Verify_Interface )
		{ 
			throw new Application_User_Exception( 'INVALID VERIFICATION MODE' ); 
		}
 */
		$this->setViewContent( $className::viewInLine() );
	//	var_export( $className::viewInLine() );
    }
	
    /**
     * Returns a random code
     * 
     */
 	public static function getVerificationCode()
	{
		return rand( 0, 99999 );
	}
 
	
    /**
     * Resets the verification code for security
     * 
     */
/* 	public static function resetVerificationCode( array $verificationInfo );
 */	// END OF CLASS
}
