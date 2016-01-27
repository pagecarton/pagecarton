<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserBankAccount_Creator
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
 * @package    Application_User_UserBankAccount_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserBankAccount_Creator extends Application_User_UserBankAccount_Abstract 
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_User_UserBankAccount';

    /**
     * Does the process
     *
     * @param 
     * 
     */
    protected function init()
    {
		//	Using this class to play the CreditCard Module
		if( @$_GET['mode'] )
		{
			$class = 'Application_User_UserBankAccount_' . ucfirst( strtolower( $_GET['mode'] ) ) . '_' . ucfirst( strtolower( @$_GET['file'] ) );
			if( Ayoola_Loader::loadClass( $class ) )
			{ 
				$this->setViewContent( $class::viewInLine() );
			}
			return;
		}
		//	Check if there is a logged in user and redirect
		$this->createForm( 'Continue', 'Add a Bank Account' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	if( ! $values['bank_id'] ){ return false; }
		if( ! $this->insertDb() )
		{ 
			$this->getForm()->setBadnews( 'AN ERROR OCCURED WHILE ADDING BANK ACCOUNT' );
			$this->setViewContent( $this->getForm()->view(), true );
		
			return false; 
		}
		$this->setViewContent( '<h4>Bank Account Information Added.</h4>', true );		
		$this->setViewContent( '<p>What Next? <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/BankAccount/">Verify Bank Accounts</a>.</p>' );		
    }
	// END OF CLASS
}
