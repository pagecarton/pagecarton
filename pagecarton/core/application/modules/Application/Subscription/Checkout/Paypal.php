<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Paypal
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Paypal.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Paypal
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Paypal extends Application_Subscription_Checkout_Abstract_HtmlForm
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
/* 	protected static $_currency= array( 'whitelist' => '', 'blacklist' => 'NGN,USD' );
 */
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => 'USD,$', 'blacklist' => 'ALL' );

    /**
     * Form Action
     * 
     * @var string
     */
	protected static $_formAction = 'https://www.paypal.com/cgi-bin/webscr';
	
    /**
     * The Mechant ID
     * 
     * @var string
     */
	protected static $_merchantId = '';
		
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
		
		//	Initialize array for the POST parameters
		$parameters = static::getDefaultParameters();
		$parameters['notify_url'] = $parameters['notify_url'];
		$parameters['success_url'] = $parameters['success_url'];
		$parameters['fail_url'] = $parameters['fail_url'];
		$parameters['business'] = Application_Settings_Abstract::getSettings( 'Payments', 'paypal_email' );
		$parameters['custom'] = $parameters['order_number'];
		
		$parameters['shopping_url'] = $cart['settings']['return_url'];
		$parameters['currency_code'] =  $cart['settings']['currency_abbreviation'];
		$parameters['cmd'] = '_cart';
		$parameters['upload'] = '1';
		
		$counter = 1;
		foreach( $values as $name => $value )
		{
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$parameters['item_name_' . $counter] = $value['subscription_name'];
			$parameters['amount_' . $counter] = $value['price'];
			$parameters['quantity_' . $counter] = $value['multiple'];
			$counter++;
		}
	//	$form = new Ayoola_Form();
	//	var_export( self::getObjectName( __CLASS__ ) );
	//	var_export( $parameters );
		return $parameters;
    } 
	// END OF CLASS
}
