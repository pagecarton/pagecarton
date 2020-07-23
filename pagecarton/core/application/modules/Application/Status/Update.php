<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Status_Update
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Update.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Status_Abstract
 */
 
require_once 'Application/Status/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Status_Update
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status_Update extends Application_Status_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
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
			$this->createForm( 'Post', 'Post a status update' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $values );
			
			
			@$values['subject'] = $values['subject'] ? : Ayoola_Application::getUserInfo( 'username' );
			
			//	There must be a valid subject
			
			if( ! $senderInfo = Ayoola_Access::getAccessInformation( $values['subject'] ) )
			{
				return false;
			//	throw new Application_Message_Exception( 'UNABLE TO POST AN UPDATE BECAUSE USER IS INVALID.' );
			}
		//	var_export( $senderInfo );
/* 			if( ! Ayoola_Access::getAccessInformation( $values['subject'] ) )
			{
				return false;
			//	throw new Application_Status_Exception( 'UNABLE TO POST AN UPDATE BECAUSE USER IS INVALID.' );
			}
 */			@$values['object'] = $values['object'] ? : Ayoola_Application::$GLOBAL['profile']['username'];
			@$values['timestamp'] = $values['timestamp'] ? : time();
			@$values['reference'] = $values['reference'] ? ( (array) $values['reference'] ) : array();
			$values['reference']['subject'] = $values['subject'];
			$values['reference']['object'] = $values['object'];
			
			if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
			
			//	Send a message to the receiver
		//	$table = new Application_User_NotificationMessage();
		//	$emailInfo = $table->selectOne( null, array( 'subject' => 'Private Message Received' ) ); 
			if( $receiverInfo = Ayoola_Access::getAccessInformation( $values['object'] ) )
			{
				$emailInfo = array
				(
									'subject' => 'Status Update', 
									'body' => '
Dear ' . $receiverInfo['firstname'] . ',
' . $senderInfo['display_name'] . ' just posted an update on your profile on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ) . '.

***LINK***
View your profile: http://' . Ayoola_Page::getDefaultDomain() . '/' . $receiverInfo['username'] . '
View ' . $senderInfo['display_name'] . '\'s profile: http://' . Ayoola_Page::getDefaultDomain() . '/' . $senderInfo['username'] . '
										
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
			}
			
			
			$this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">Status has been updated successfully.</p>' ) . '', true  );
		}
		catch( Application_Status_Exception $e ){ return false; }
    } 
	
	// END OF CLASS
}
