<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_Email_Abstract
 */
 
require_once 'Application/User/Email/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_Creator extends Application_User_Email_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	$table = new Application_User_Email( new Ayoola_Dbase(  ) );
	//	$table->setTableName( 'users' );
	//	var_export( get_current_user() );
		$this->createForm( 'Create', 'Create a new email' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	var_export( $values );
		//	Save the user in the default user db table
		if( ! $provider = Application_Settings_Abstract::getSettings( 'E-mail', 'provider' ) )
		{
			$provider = 'ayoola';
		}
	//	var_export( $provider );
//		var_export( $values );
		switch( $provider )
		{
			case 'ayoola':
				$response = Application_User_Email_Api_Creator::send( $values );
		//		if( $_SERVER['HTTP_HOST'] == 'irceed.org' )
			//	{
			//		var_export( $response['data'] );
		//		}
			//	$response = $response['data'];
		//		var_export( $response );
				if( is_array( $response ) && $response['data'] )
				{
					$response = $response['data'];
					$values['email'] = strtolower( $values['username'] . '@' . $values['domain'] );
					$this->setViewContent( "Email '{$values['email']}' has been created successfully.", true );
					if( @$values['user_id'] )
					{
						//	lets do a welcome service.
						$table = Application_User_NotificationMessage::getInstance(); 
						$emailInfo = $table->selectOne( null, array( 'subject' => 'Your new e-mail Address' ) );
						$r = Ayoola_Api_UserList::send( array( 'user_id' => $values['user_id'] ) );
						if( is_array( @$r['data'] ) )
						{
							$userInfo = $r['data'];
						}
						$replacements = array( 
											'firstname' => $userInfo['firstname'], 
											'new_email' => $values['email'], 
											'domainName' => Ayoola_Page::getDefaultDomain(), 
										);
						$emailInfo = self::replacePlaceholders( $emailInfo, $replacements );
	 			//		var_export( $userInfo );
				//		var_export( $emailInfo );
						$emailInfo['to'] = $values['email'] . ', ' . @$userInfo['email'];
					//	var_export( $emailInfo['to'] );
						$emailInfo['from'] = 'no-reply@' . Ayoola_Page::getDefaultDomain();
						@self::sendMail( $emailInfo );
					}
					break;
			//		var_export( $response );
				}
				//	var_export( $response );
				$this->getForm()->setBadnews( $response );
				$this->setViewContent( $this->getForm()->view(), true );
			break;
			case 'self':
				if( $values['password'] != $values['password2'] )
				{ 
					$this->getForm()->setBadnews( 'Password does not match' );
					$this->setViewContent( $this->getForm()->view(), true );
					return false;
				}
				$values['email'] = strtolower( $values['username'] . '@' . $values['domain'] );
				$duplicate = $this->getDbTable()->select( null, null, array( 'email' => $values['email'] ) );
				if( $duplicate )
				{
					$this->getForm()->setBadnews( "Email [{$values['email']}] already exists" );
					$this->setViewContent( $this->getForm()->view(), true );
					return false;
				}
				$values['password'] = '{SHA}' . sha1( $values['password'] ); 
				$userAccountInfo =  self::getUserAccountInfo();
		//	var_export( $values );
				$userAccountInfo['application_id'] =  $values['application_id'];
				
				$userAccount = $this->setDbTable( new Application_User_Email_UserAccount );
				do
				{
					if( ! $userRecorded = $userAccount->selectOne( null, null, array( 'application_id' => $userAccountInfo['application_id'] ) ) )
					{
						$userAccount->insert( $userAccountInfo );
					}
			//	var_export( $userRecorded );
				}
				while( ! $userRecorded );

				$domain = $this->setDbTable( new Application_User_Email_Domain );
				do
				{
					if( ! $domainRecorded = $domain->selectOne( null, null, array( 'domain' => $values['domain'] ) ) )
					{	
						$domain->insert( array( 'domain' => $values['domain'], 'useraccount_id' => $userRecorded['useraccount_id'] ) ); 
					}
				}
				while( ! $domainRecorded );
				
				$values['domain_id'] = $domainRecorded['domain_id'];
			//	var_export( $values );
			//	var_export( $values );
				$this->setDbTable();
				if( $this->insertDb( $values ) ){ $this->setViewContent( "Email '{$values['email']}' has been created successfully.", true ); }
			break;
		
		}
	//	var_export( $values );
    } 
	// END OF CLASS
}
