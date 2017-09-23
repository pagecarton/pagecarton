<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_VoguePay
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: VoguePay.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_VoguePay
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_VoguePay extends Application_Subscription_Checkout_Abstract_HtmlForm
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => '', 'blacklist' => 'ALL' );

    /**
     * Form Action
     * 
     * @var string
     */
	protected static $_formAction = 'https://voguepay.com/pay/';
	
    /**
     * The Mechant ID
     * 
     * @var string
     */
	protected static $_merchantId = '135-6041';
		
    /**
     * Creates the request
     * 
     * @param void
     * @return array
     */
	protected static function buildRequest()
    {
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		$values = $values['cart'];
		
		//	Initialize array for the POST parameters
		$parameters = static::getDefaultParameters();
		$parameters['notify_url'] = $parameters['notify_url'];
		$parameters['success_url'] = $parameters['success_url'];
		$parameters['fail_url'] = $parameters['fail_url'];
		$parameters['total'] = $parameters['total'];
		$parameters['v_merchant_id'] = Application_Settings_Abstract::getSettings( 'Payments', 'voguepay_merchant_id' );
		$parameters['merchant_ref'] = $parameters['order_number'];
		
		$counter = 1;
		foreach( $values as $name => $value )
		{
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$parameters['item_' . $counter] = $value['subscription_name'];
			$parameters['description_' . $counter] = $value['subscription_description'];
			$parameters['price_' . $counter] = $value['price'] * $value['multiple'];
			$parameters['total'] += $parameters['price_' . $counter];
			$counter++;
		}
	//	$form = new Ayoola_Form();
	//	var_export( self::getObjectName( __CLASS__ ) );
	//	var_export( $values );
		return $parameters;
    } 
	// END OF CLASS
}
