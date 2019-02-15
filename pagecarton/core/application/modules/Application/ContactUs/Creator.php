<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_ContactUs_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_ContactUs_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_Creator extends Application_ContactUs_Abstract
{
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;

    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Send message', $this->getParameter( 'form_legend' ) ? : 'Contact Form' );
			$this->setViewContent( $this->getForm()->view(), true );

			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//
		//	var_export( $values );
		//	return;
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['contactus_creator_user_id'] = $userInfo['user_id'];
			$values['contactus_subject'] = $values['contactus_subject'] ? : substr( $values['contactus_message'], 0, 100 );
			$values['contactus_subject'] = $values['contactus_subject'] ? : $values['contactus_message'];
			if( ! $this->insertDb( $values ) ){ return false; }
			//	self::v( $values );
			$this->setViewContent( 'Thank you! Your message has reached us, we will get back to you as soon as possible.', true );
			
			$emailAddress = array();
			if( Ayoola_Application::getUserInfo( 'email' ) )
			{
				$emailAddress[] = Ayoola_Application::getUserInfo( 'email' );
			}
			if( @$values['contactus_email'] )
			{
				$emailAddress[] = $values['contactus_email'];
			}  
			//	self::v( $emailAddress ); 
			
			if( $emailAddress )
			{
			

				$emailInfo = array(
									'subject' => 'Contact Message Received!',
									'body' => 'We have received the message with the following information:
									' . var_export( $values, true ) . '
									',
				
				);
				$emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );
				$emailInfo['bcc'] = Ayoola_Application_Notification::getEmails();
			//	$emailInfo['html'] = true; 
				@self::sendMail( $emailInfo );
			//	self::v( $emailInfo );
				
			}
			
/* 			//	Notify Admin
			$mailInfo['subject'] = 'New Contact Message';
			$mailInfo['body'] = 'Someone left a message titled "' . $values['contactus_subject'] . '", using the contact form. 
			
			You can view the new message by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '/ayoola/.
			';
			try
			{
				Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
 */		}
		catch( Application_ContactUs_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
