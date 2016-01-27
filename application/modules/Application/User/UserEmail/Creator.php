<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserEmail_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 10.14.2011 8.06 ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserEmail_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserEmail_Creator extends Application_User_UserEmail_Abstract 
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * Does the process
     *
     * @param 
     * 
     */
    protected function init()
    {
		try
		{
			//	Check if there is a logged in user and redirect
			$this->createForm( 'Continue', $this->getParameter( 'legend' ) ? : 'Add an Email Address' ); 
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( Ayoola_Application::getUserInfo( 'user_id' ) );
			//		var_export( (int) '08054449535' );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$this->setViewContent( '<p>Thank you!</p>', true );	
			$goodnews = null;
			
			//	first add to mailing list if selected.
			if( @$values['add_email_to_mailing_list'] )
			{
				try
				{
					$table = new Application_User_UserEmail_MailingList;
					$table->insert( $values );
			//	$goodnews .= "Email was successfully added to our mailing list. ";
					$this->setViewContent( '<p>Email was successfully added to our mailing list.</p>' );	
				}
				catch( Ayoola_Exception $e )
				{ 
				//	var_export( $e->getMessage() );	
				//	$this->getForm()->setBadnews( 'Could not add e-mail address to mailing list.' );
			//		$this->setViewContent( $this->getForm()->view(), true );
					$this->setViewContent( '<p class="badnews">Email was not added to our mailing list. This is likely because you are already on our list.</p>' );	
				}
			}
			
			if( ! Ayoola_Application::getUserInfo( 'username' ) )
			{
				return false;
			}
			
		//	var_export( $values );
			//	Check where our user information is being saved.
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'cloud';
			}
			$list = array();
			switch( $database ) 
			{
				case 'cloud':
					$values['method'] = 'insert';
					$values['table'] = 'Application_User_UserEmail';
					$values['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
		//			var_export( $values );
					$response = Ayoola_Api_Dbase::send( $values );
			//		var_export( $response );
				//	var_export( $values );
				//	if( is_array( $response['data'] ) )
					if( true !== @$response['data'] )
					{
				//		$this->getForm()->setBadnews( 'Database error occurred while adding a new e-mail address to your account.' );
						$this->setViewContent( '<p class="badnews">Database error occurred while adding a new e-mail address to your account.</p>' );	
					//	$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
					
				break;
				case 'relational':
					if( ! $this->insertDb() )
					{ 
					//	$this->getForm()->setBadnews( 'Database error occurred while adding a new e-mail address to your account.' );
						$this->setViewContent( '<p class="badnews">Database error occurred while adding a new e-mail address to your account.</p>' );	
					//	$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
				break;
			}
			$this->setViewContent( '<p>E-mail address saved to your account.</p>' );	
	
		}
		catch( Ayoola_Exception $e )
		{ 
		//	var_export( $e->getMessage() );	
			$this->getForm()->setBadnews( 'Could not add a new e-mail address.' );
			$this->setViewContent( $this->getForm()->view(), true );
		}
	//	$this->setViewContent( '<p>What Next? <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/">Verify Credit/Debit Card</a>.</p>' );		
    }
	// END OF CLASS
}
