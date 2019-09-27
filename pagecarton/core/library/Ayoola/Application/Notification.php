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
	//	$users = new Application_User_List( array( 'access_level' => array( 99, 98 ) ) );
		$userTable = 'Ayoola_Access_LocalUser';
		$userTable = $userTable::getInstance( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setAccessibility( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setRelationship( $userTable::SCOPE_PROTECTED );
		$users = $userTable->select( null, array( 'access_level' => array( 99, 98 ) ) );
		$emails = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' );
        foreach( $users as $each )
        {
            $emails .= ( ',' . $each['email'] );
        }
		$emails = trim( $emails, ', ' );
	//	var_export( $users );
	//	var_export( $emails );
	//	var_export( Ayoola_Access_LocalUser::getInstance()->select() );
	//	exit();
		return $emails;
    } 
	
    /**
     * What to prepend to all notification messages
     * 
     */
	public static function getHeader()
    {
		$message = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain(); 
		$message .= ', 
		Your website; ' . Ayoola_Page::getDefaultDomain() . ' has generated an automated response to a recent activity on ' . Ayoola_Page::getCanonicalUrl() . '.		
		';
		return $message;
    } 
	
    /**
     * What to append to all notification messages
     * 
     */
	public static function getFooter()
    {
		return '
        You are receiving this notification because your e-mail is listed as one to receive admin notifications on the website, All emails listed are ' . Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' ) . '. This was set in PageCarton admin panel. Here is the direct link to the admin panel: ' . Ayoola_Page::getHomePageUrl() . '/pc-admin. All users with admin privileges may also receive some notifications.
            
        Update Notification Emails Here: ' . Ayoola_Page::getHomePageUrl() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/CompanyInformation/
		
		For tutorials, help on developing with PageCarton, visit http://www.pagecarton.org. PageCarton is a tool that makes it easy to build responsive websites and apps. 
		';
    } 
	// END OF CLASS
}
