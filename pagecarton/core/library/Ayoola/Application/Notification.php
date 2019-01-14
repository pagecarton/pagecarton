<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
		$users = new Application_User_List( array( 'access_level' => array( 99, 98 ) ) );
		$emails = Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' );
		if( $users = $users->getDbData() )
		{
			//	$users = array_column( $users, 'email' );
			foreach( $users as $each )
			{
				$emails .= ( ',' . $each['email'] );
			}
		}
		$emails = trim( $emails, ', ' );
	//	var_export( $emails );
	//	var_export( $users );
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
		Your e-mail, ' . Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'email' ) . ' was set in PageCarton admin panel. Here is the direct link to the admin panel: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/pc-admin
		
		For tutorials, help on developing with PageCarton, visit http://pagecarton.org. PageCarton is a content management system that makes it easy to build responsive websites and apps. 
		';
    } 
	// END OF CLASS
}
