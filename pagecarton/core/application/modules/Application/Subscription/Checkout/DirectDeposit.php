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
		if( $accounts )
		{
			$this->setViewContent( self::__( '<h4>Kindly follow the following instructions to complete your order</h4>' ) );
		}
		else
		{
			$this->setViewContent( self::__( '<h4>Please contact us to get our bank information.</h4>' ) );		
			$this->setViewContent( self::__( '<p>After payments is made, please ensure you send us a proof of payment so we can process your order.</p>' ) );
		}
		foreach( $accounts as $each )
		{
			$this->setViewContent( htmlspecialchars_decode( $each['account_info'] ) );
		
		}
		$this->setViewContent( '<a class="pc-btn pc-bg-color" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Subscription_Checkout_Confirmation/get/api/DirectDeposit/status/1/">' . self::__( 'Confirm Order' ) . ' <i class="fa fa-check pc_give_space"></i></a>' );
	//	$this->setViewContent( self::__( '</p>' ) );
		
		
    } 
	// END OF CLASS
}
