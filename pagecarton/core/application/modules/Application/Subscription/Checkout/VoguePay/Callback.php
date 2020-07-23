<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_VoguePay_Callback
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Callback.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_VoguePay_Callback
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_VoguePay_Callback extends Application_Subscription_Checkout_Callback
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
	//	if( ! $identifier = $this->getIdentifier() ){ return false; }
	
		//	RETRIEVE THE XML OF THE ORDER INFO FROM 
		$url = "https://voguepay.com/?v_transaction_id={$_REQUEST['v_transaction_id']}&type=xml";
		$xml = new Ayoola_Xml();
		$xml->loadXml( self::fetchLink( $url ) );
		$response = $xml->getTextNodesData();
		$response['order_status'] = $response['status'];
		$response['order_id'] = $response['merchant_ref'];
		$response['order_random_code'] = $response['transaction_id'];
		$stages = Application_Subscription_Checkout::$checkoutStages;
	//	var_export( $orderInfo );
		switch( $response['order_status'] )
		{
			case 'Approved':
				$response['order_status'] = $stages[99];
			break;
			case 'Pending':
				$response['order_status'] = $stages[1];
			break;
			case 'Failed':
				$response['order_status'] = $stages[0];
			break;
			case 'Disputed':
				$response['order_status'] = $stages[2];			
			break;
			default:
		//		exit( 'INVALID RESPONSE' );
			break;
		}
		$this->processResponse( $response );
	} 
	// END OF CLASS
}
