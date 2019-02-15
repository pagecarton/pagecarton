<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_SimplePay
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SimplePay.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_SimplePay
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_SimplePay extends Application_Subscription_Checkout_Abstract_HtmlForm
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => '₦,NGN', 'blacklist' => 'ALL' ); 

    /**
     * Form Action
     * 
     * @var string
     */
	protected static $_formAction = 'https://simplepay4u.com/process.php';
//	protected static $_formAction = 'http://sandbox.simplepay4u.com/process.php'; 
	
    /**
     * The Mechant ID
     * 
     * @var string
     */
	protected static $_merchantId = 'ayoolaonline';
		
    /**
     * Creates the request
     * 
     * @param void
     * @return array
     */
	protected static function buildRequest()
    {
		if( ! $cart = self::getStorage()->retrieve() ){ return; }
		$values = $cart['cart'];
		
		if( count( $values ) > 1 )
		{
	//		throw new Application_Subscription_Checkout_Exception( 'SIMPLEPAY ALLOWS ONLY ONE PRODUCT IN SHOPPING CART. PLEASE EDIT YOUR CART' );
		}
		//	Initialize array for the POST parameters
		$parameters = static::getDefaultParameters();
		$parameters['unotify'] = $parameters['notify_url'];
		$parameters['ureturn'] = $parameters['success_url'];
		$parameters['ucancel'] = $parameters['fail_url'];
		$parameters['total'] = $parameters['total'];
		$parameters['member'] = Application_Settings_Abstract::getSettings( 'Payments', 'simplepay_username' );
		$parameters['customid'] = $parameters['order_number'];
		$parameters['site_logo'] = $parameters['logo'];
		$parameters['action'] = 'payment';
		$parameters['escrow'] = 'N';
		
		$counter = 1;
		foreach( $values as $name => $value )
		{
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}  
			@@$parameters['product'] .= ' ' . $value['subscription_name'];
		//	@$parameters['quantity'] = $value['multiple'];
			@$parameters['comments'] .= ' ' .  @$value['subscription_description'];
			@$parameters['price'] += $value['price'] * $value['multiple'];
			$counter++;
		}
	//	$form = new Ayoola_Form();
	//	var_export( self::getObjectName( __CLASS__ ) );
	//	var_export( $parameters );
		return $parameters;
    } 
	// END OF CLASS
}
