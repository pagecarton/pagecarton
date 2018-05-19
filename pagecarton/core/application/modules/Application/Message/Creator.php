<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Message   Ayoola
 * @package    Application_Message_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Message_Abstract
 */
 
require_once 'Application/Message/Abstract.php';


/**
 * @Message   Ayoola
 * @package    Application_Message_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Message_Creator extends Application_Message_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Direct Message';      
	
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
			if( ! $this->requireRegisteredAccount() )
			{
				return false;
			}
			if( ! $this->requireProfile() )
			{
				return false;
			}
			$this->createForm( 'Send', 'Send a private message' );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( Ayoola_Application::$GLOBAL );
	//		var_export( Ayoola_Application::getUserInfo() );
			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
			
			
			@$values['from'] = strtolower( $values['from'] ? : Ayoola_Application::getUserInfo( 'profile_url' ) );
			@$values['to'] = strtolower( $values['to'] ? : Ayoola_Application::$GLOBAL['profile_url'] );
			
			//	There must be a valid sender
	//		Application_Profile_Abstract::getMyDefaultProfile()
			if( ! $senderInfo = Application_Profile_Abstract::getProfileInfo( $values['from'] ) )
			{
				$this->setViewContent( '<p class="badnews">Invalid sender.</p>' );
				return false;
			//	throw new Application_Message_Exception( 'UNABLE TO POST AN UPDATE BECAUSE USER IS INVALID.' );
			}
			if( ! $receiverInfo = Application_Profile_Abstract::getProfileInfo( $values['to'] ) )
			{
				$this->setViewContent( '<p class="badnews">Invalid receiver information.</p>' );
				return false;
			//	throw new Application_Message_Exception( 'UNABLE TO POST AN UPDATE BECAUSE USER IS INVALID.' );
			}
	//		var_export( $receiverInfo );
			@$values['timestamp'] = $values['timestamp'] ? : time();
			@$values['reference'] = $values['reference'] ? ( (array) $values['reference'] ) : array();
			$values['reference']['from'] = $values['from'];
			$values['reference']['to'] = $values['to'];
			if( $values['from'] == $values['to'] )
			{
				return false;
			//	throw new Application_Message_Exception( 'PRIVATE MESSAGE CANNOT BE SENT TO ONESELF.' );
			}
			if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
			
			//	Send a message to the receiver
		//	$table = new Application_User_NotificationMessage();
		//	$emailInfo = $table->selectOne( null, array( 'subject' => 'Private Message Received' ) ); 
			$emailInfo = array
			(
								'subject' => 'Private Message Received', 
								'body' => '
Dear ' . $receiverInfo['firstname'] . ',
You have just received a new private message from ' . $senderInfo['display_name'] . '. Click the following link to view the message: 

***LINK***
http://' . Ayoola_Page::getDefaultDomain() . '/' . $senderInfo['profile_url'] . '/message
									
								', 
			
			); 
			
			$values = array( 
							//	'firstname' => $receiverInfo['firstname'], 
								'domainName' => Ayoola_Page::getDefaultDomain(), 
							);
			
			$emailInfo = self::replacePlaceholders( $emailInfo, $values + $receiverInfo );
		//	var_export( $emailInfo );
			$emailInfo['to'] = $receiverInfo['email'];
			$emailInfo['from'] = '' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . '<no-reply@' . Ayoola_Page::getDefaultDomain() . '>';
			@self::sendMail( $emailInfo );
			
			$this->setViewContent( '<p class="goodnews">Private message has been sent successfully.</p>', true );
		}
		catch( Application_Message_Exception $e ){ return false; }
   } 
	// END OF CLASS
}
