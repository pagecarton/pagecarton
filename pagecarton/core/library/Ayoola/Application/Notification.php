<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Application_Notification
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Notification.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Application_Notification
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Application_Notification extends Ayoola_Abstract_Table
{
	
    /**
     * Sends an e-mail to the admin
     * 
     * param array The array containing the email
     */
	public static function mail( array $mailInfo )
    {
		$mail = array();
		
	//	$mail['to'] = $mailInfo['to'];
		$mail['to'] = self::getEmails();
		

		if( ! $mail['to'] )
		{
			return false;
		//	throw new Ayoola_Abstract_Exception( 'E-MAIL NOT SET IN COMPANY INFO' );;
		}
		if( ! $mailInfo['body'] )
		{
			return false;
		//	throw new Ayoola_Abstract_Exception( 'NO BODY WAS SPECIFIED IN NOTIFICATION MESSAGE' );;
		}
		$mail['subject'] = trim( $mailInfo['subject'] . ' [PageCarton Notification]' );
		$mail['body'] = self::getHeader() . $mailInfo['body'] . self::getFooter();
//		echo nl2br( $mail['subject'] );
//		echo nl2br( $mail['body'] );
	//	echo nl2br( $mail['body'] );
		self::sendMail( $mail );
		
    } 
	
    /**
     * What to prepend to all notification messages
     * 
     */
	public static function getEmails()
    {
        //	also sent the message to all admin accounts
        $emails = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' );
        $userEmail = null;
        if( 
            Ayoola_Application::getUserInfo( 'access_level' ) == 99  
            || Ayoola_Application::getUserInfo( 'access_level' ) == 98

            )
        {
            $userEmail = Ayoola_Application::getUserInfo( 'email' );
        }
        elseif( ! empty( Ayoola_Application::$GLOBALS['user']['email'] ) )
        {
            $userEmail = Ayoola_Application::$GLOBALS['user']['email'];
            var_export( $userEmail );
        }
        elseif( ! $userEmail = Ayoola_Application::getDomainSettings( 'email' ) )
        {
            $userTable = 'Ayoola_Access_LocalUser';
            $userTable = $userTable::getInstance( $userTable::SCOPE_PROTECTED );
            $userTable->getDatabase()->getAdapter()->setAccessibility( $userTable::SCOPE_PROTECTED );
            $userTable->getDatabase()->getAdapter()->setRelationship( $userTable::SCOPE_PROTECTED );
            $users = $userTable->select( null, array( 'access_level' => array( 99 ) ) );
            foreach( $users as $each )
            {
                $emails .= ( ',' . $each['email'] );
            }
        }
        $emails .= ( ',' . $userEmail );

        $emails = trim( $emails, ', ' );
		return $emails;
    } 
	
    /**
     * What to prepend to all notification messages
     * 
     */
	public static function getHeader()
    {
	//	$message = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain(); 
	//	$message .= '';
	//	return $message;
    } 
	
    /**
     * What to append to all notification messages
     * 
     */
	public static function getFooter()
    {
	//	return '';
    } 
	// END OF CLASS
}
