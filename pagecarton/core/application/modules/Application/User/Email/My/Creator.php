<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Email_My_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_Email_My_Abstract
 */
 
require_once 'Application/User/Email/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Email_My_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email_My_Creator extends Application_User_Email_My_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	$table = new Application_User_Email_My( new Ayoola_Dbase(  ) );
	//	$table->setTableName( 'users' );
	//	var_export( get_current_user() );
		$this->createForm( 'Continue', 'Create a new email' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	Save the user in the default user db table
		if( ! $provider = Application_Settings_Abstract::getSettings( 'E-mail', 'provider' ) )
		{
			$provider = 'ayoola';
		}
	//	var_export( $provider );
		switch( $provider )
		{
			case 'ayoola':
				$response = Application_User_Email_My_Api_Creator::send( $values );
				
				//	var_export( $response['data'] );
			//	$response = $response['data'];
				if( is_array( $response ) && $response['data'] )
				{
					$response = $response['data'];
					$values['email'] = strtolower( $values['username'] . '@' . $values['domain'] );
					$this->setViewContent( self::__( "Email '{$values['email']}' ) has been created successfully.", true ) );
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
				
				$userAccount = $this->setDbTable( new Application_User_Email_My_UserAccount );
				do
				{
					if( ! $userRecorded = $userAccount->selectOne( null, null, array( 'userid' => $userAccountInfo['userid'] ) ) )
					{
						$userAccount->insert( $userAccountInfo );
					}
			//	var_export( $userRecorded );
				}
				while( ! $userRecorded );

				$domain = $this->setDbTable( new Application_User_Email_My_Domain );
				do
				{
					if( ! $domainRecorded = $domain->selectOne( null, null, array( 'domain' => $values['domain'] ) ) )
					{	
						$domain->insert( array( 'domain' => $values['domain'], 'userid' => $userRecorded['userid'] ) ); 
					}
				}
				while( ! $domainRecorded );
				
				$values['domain_id'] = $domainRecorded['domain_id'];
			//	var_export( $values );
			//	var_export( $values );
				$this->setDbTable();
				if( $this->insertDb( $values ) ){ $this->setViewContent( self::__( "Email '{$values['email']}' ) has been created successfully.", true ) ); }
			break;
		
		}
	//	var_export( $values );
    } 
	// END OF CLASS
}
