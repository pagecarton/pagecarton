<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Email.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Send_Abstract
 */
 
require_once 'Application/User/NotificationMessage/Send/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage_Send_Email extends Application_User_NotificationMessage_Send_Abstract  
{
	
    /**
     * The method does the whole Class Process
     * 
     * @var array
     */
	protected $_requiredTables = array( 'email' => 'Application_User_UserEmail' );
    
	
    /**
     * The method does the whole Class Process
     * 
     */
	public function init()
    {
	//	try
		{ 
			
		//	if( $this->sendMessage( false ) ){ $this->setViewContent( 'Notification message sent', true ); }
		}
	//	catch( Exception $e ){ return false; }
	}
	
    /**
     * Returns the required dbtables for rerieving notification requirements
     * 
     * @return array $column => $tableClass
     */
	public function getRequiredTables()
	{
		return $this->_requiredTables;
	}
	
    /**
     * Sends the notification message
     * 
     * @param array The parameters peculiar to the notification mode
     */
	public function sendMessage( array $mailInfo )
	{
		if( empty( $mailInfo['body'] ) || empty( $mailInfo['to'] ) ){ return false; }
/* 		$headers = 'From: ' . $mailInfo['from'] . "\r\n";
		$headers .= 'cc: ' . $mailInfo['cc'] . "\r\n";
		$headers .= 'bcc: ' . $mailInfo['bcc'] . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
 */ 		
//	var_export( $recipients );
//	var_export( $mailInfo );
 	if( Application_User_Abstract::sendMail( $mailInfo ) ){ return true; }
	//	throw new Application_User_NotificationMessage_Exception( 'Email not sent' );
	}
	// END OF CLASS 
}
