<?php
/**
 * PageCarton
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
			
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			
			@$values['from'] = strtolower( $values['from'] ? : Ayoola_Application::getUserInfo( 'profile_url' ) );
			@$values['to'] = strtolower( $values['to'] ? : Ayoola_Application::$GLOBAL['profile']['profile_url'] );
			
			//	There must be a valid sender
			if( ! $senderInfo = Application_Profile_Abstract::getProfileInfo( $values['from'] ) )
			{
				$this->setViewContent( self::__( '<p class="badnews">Invalid sender.</p>' ) );
				return false;
			}
			if( ! $receiverInfo = Application_Profile_Abstract::getProfileInfo( $values['to'] ) )
			{
				$this->setViewContent( self::__( '<p class="badnews">Invalid receiver information.</p>' ) );
				return false;
			}
			@$values['timestamp'] = $values['timestamp'] ? : time();
			@$values['reference'] = $values['reference'] ? ( (array) $values['reference'] ) : array();
			$values['reference']['from'] = $values['from'];
			$values['reference']['to'] = $values['to'];
			if( $values['from'] == $values['to'] )
			{
				return false;
			}
			if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
			
			//	Send a message to the receiver
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
								'domainName' => Ayoola_Page::getDefaultDomain(), 
							);
			
			$emailInfo = self::replacePlaceholders( $emailInfo, $values + $receiverInfo );
			$emailInfo['to'] = $receiverInfo['email'];
			$emailInfo['from'] = '' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . '<no-reply@' . Ayoola_Page::getDefaultDomain() . '>';
			@self::sendMail( $emailInfo );
			$this->_objectData['goodnews'] = 'Private message has been sent successfully';
			$this->setViewContent(  '' . self::__( '<p class="goodnews">Private message has been sent successfully.</p>' ) . '', true  );
		}
		catch( Application_Message_Exception $e ){ return false; }
   } 
	// END OF CLASS
}
