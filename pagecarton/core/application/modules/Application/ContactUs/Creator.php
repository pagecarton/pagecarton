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
			$access = new Ayoola_Access();
			$userInfo = $access->getUserInfo();
			$values['contactus_creator_user_id'] = $userInfo['user_id'];
			$values['contactus_subject'] = $values['contactus_subject'] ? : substr( $values['contactus_message'], 0, 100 );
			$values['contactus_subject'] = $values['contactus_subject'] ? : $values['contactus_message'];
			if( ! $this->insertDb( $values ) ){ return false; }
			//	self::v( $values );
			$this->setViewContent(  '' . self::__( 'Thank you! Your message has reached us, we will get back to you as soon as possible.' ) . '', true  );
			
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
            
                unset( $values['contactus_creator_user_id'] );
                unset( $values['contactus_creation_date'] );
	
				//	make it nonusable for spam
				//	By not making 
				$emailInfo = array(
									'subject' => 'Contact Confirmation',
									'body' => 'Someone used this email address to submit a contact message on ' . Ayoola_Page::getHomePageUrl() . '. The message was successfully submited and has been relayed to the administrators. 
									
									If you have any questions, please leave a follow-up message on ' . Ayoola_Page::getHomePageUrl() . '/widgets/' . __CLASS__,
				);

				$emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );

				@self::sendMail( $emailInfo );

				$emailInfo = array(
					'subject' => 'Re: ' . $values['contactus_subject'],
					'body' => 'We have received the message with the following information from the contact form:
					' . self::arrayToString( $values ) . '
					',
				);

				$emailInfo['to'] = Ayoola_Application_Notification::getEmails();;
				@self::sendMail( $emailInfo );
				
			}
		}
		catch( Application_ContactUs_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
