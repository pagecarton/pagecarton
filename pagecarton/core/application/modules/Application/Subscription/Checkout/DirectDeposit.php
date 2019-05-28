<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_DirectDeposit
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DirectDeposit.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_DirectDeposit
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_DirectDeposit extends Application_Subscription_Checkout_Abstract
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => '', 'blacklist' => '' );

    /**
     * Plays the process
     * 
     */
	protected function init()
    {		
		$table = Application_Subscription_Checkout_DirectDeposit_Account::getInstance();
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		$accounts = $table->select( null, array( 'account_currency' => $values['settings']['currency_abbreviation'] ) );
	//	$accounts = $table->select();
		if( $accounts )
		{
			$this->setViewContent( '<h4>Make payments into any of the following bank account(s).</h4>' );
		}
		else
		{
			$this->setViewContent( '<h4>Please contact us to get our bank information.</h4>' );		
			$this->setViewContent( '<h4>NOTE:</h4>' );
		//	$this->setViewContent( '<p>' );
			$this->setViewContent( '<p>Payments must be made in ' . $values['settings']['currency_abbreviation'] . '.</p>' );
			$this->setViewContent( '<p>After payments is made, please ensure you send us the bank payment (or teller) information by contacting us.</p>' );
			$this->setViewContent( '<h2 class="badnews">Notice!</h2>' );
			$this->setViewContent( '<p class="badnews">Your online order is NOT yet completed until you confirm you would be making a bank deposit to us.</p>' );  
		}
		foreach( $accounts as $each )
		{
			$this->setViewContent( htmlspecialchars_decode( $each['account_info'] ) );
		
		}
		$this->setViewContent( '<h2><a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Subscription_Checkout_Confirmation/get/api/DirectDeposit/status/1/"><input name="' . __CLASS__ . '_confirm_order" onClick="ayoola.div.selectElement( this )" class="boxednews goodnews" value="Confirm order" type="button" /></a></h2>' );
	//	$this->setViewContent( '</p>' );
		
		
    } 
	// END OF CLASS
}
