<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserCreditCard_Creator
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
 * @package    Application_User_UserCreditCard_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserCreditCard_Creator extends Application_User_UserCreditCard_Abstract 
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_User_UserCreditCard';

    /**
     * Does the process
     *
     * @param 
     * 
     */
    protected function init()
    {
		//	Check if there is a logged in user and redirect
		$this->createForm( 'Continue', 'Add a Credit/Debit Card' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
		//	Validate Credit Card
		
	//	var_export( $values );
	//	if( ! $values['bank_id'] ){ return false; }
		if( ! $this->insertDb() )
		{ 
			$this->getForm()->setBadnews( 'AN ERROR OCCURED WHILE ADDING CREDIT/DEBIT CARD' );
			$this->setViewContent( $this->getForm()->view(), true );
		
			return false; 
		}
		$this->setViewContent( '<h4>Credit/Debit Card Information Added.</h4>', true );		
		$this->setViewContent( '<p>What Next? <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/verify/get/mode/CreditCard/">Verify Credit/Debit Card</a>.</p>' );		
    }
	// END OF CLASS
}
