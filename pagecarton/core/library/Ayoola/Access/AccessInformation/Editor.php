<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_AccessInformation_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_AccessInformation_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AccessInformation_Editor extends Ayoola_Access_AccessInformation_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /** 
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! $data =  Ayoola_Access::getAccessInformation() )
		{
			return false;
		}
		$this->createForm( 'Save', 'Update profile information', $data );
		$this->setViewContent( $this->getForm()->view(), true );				
		$values = $this->fakeValues;
		if( ! $values )
		{
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		}
	//	if( ! $values  ){ return false; }
//		var_export( $values );
		if( empty( $values['display_picture_base64'] ) )
		{
			//	we are not interested in changing dp
			unset( $values['display_picture_base64'] );
		}
		
		// update access
		Ayoola_Access::setAccessInformation( $values );      
		
		//	Send E-mail
		$table = Application_User_NotificationMessage::getInstance();
		$emailInfo = $table->selectOne( null, array( 'subject' => 'Profile Update' ) ); 
		$options = $values + array( 
							'firstname' => Ayoola_Application::getUserInfo( 'firstname' ), 
							'profile_link' => 'http://' . Ayoola_Page::getDefaultDomain() . '/' . Ayoola_Application::getUserInfo( 'username' ), 
							'domainName' => Ayoola_Page::getDefaultDomain(), 
							'organization_name' => ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ), 
						);
						
		$emailInfo = self::replacePlaceholders( $emailInfo, $options );
		$emailInfo['to'] = Ayoola_Application::getUserInfo( 'email' );
		$emailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
		@self::sendMail( $emailInfo );
				
		//	Status update
		$class = new Application_Status_Update();
		$status = array( 
							'status' => ( 'My profile has just been updated.' ), 
							'reference' => array
							(
							//	'article_url' => $data['article_url'],
						//		'score' => $dataToSend['quiz_percentage'],
							), 
						); 
		$parameters = array( 'fake_values' => $status );
		$class->setParameter( $parameters );  
		$class->fakeValues = $status; 
		$class->init();
		
	//	if( ! $this->updateDb( $values ) ){ return false; }
		
	//	var_export( $data );
		$this->setViewContent(  '' . self::__( '<p class="boxednews normalnews">Profile information has been updated successfully.</p>' ) . '', true  ); 
		$this->setViewContent( self::__( '<a href="' . Ayoola_Application::getUrlPrefix() . '/' . $data['username'] . '" class="boxednews goodnews">View Profile.</a>' ) ); 
    } 
	// END OF CLASS
}
