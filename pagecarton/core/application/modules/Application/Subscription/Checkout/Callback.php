<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Callback
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
 * @package    Application_Subscription_Checkout_Callback
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Callback extends Application_Subscription_Checkout_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'api' );
	
    /**
     * Table where to store orders
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_Order';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		//	Identifiers are required
		if( ! $identifier = $this->getIdentifier() ){ return false; }
		$this->getApi();
		
    } 
	
    /**
     * Plays the API that is selected
     * 
     */
	public static function getApi( $checkoutOptionName = null )
    {
	//	if( ! $identifier = self::getIdentifier() ){ return false; }
		$table = Application_Subscription_Checkout_CheckoutOption::getInstance();
		$data = $table->selectOne( null, array( 'checkoutoption_name' => $checkoutOptionName ? : $_GET['api'] ) );
	//	var_export( $data );
		$className = $data['object_name'] . '_Callback';
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $className ) )
		{ 
			throw new Application_Subscription_Exception( 'INVALID CALLBACK FOR CHECKOUT API' ); 
		}
		return $className::viewInLine();
    } 
	
    /**
     * This works for inheriting classes
     * 
     * @param array Response from the API
     */
	protected function processResponse( array $response )
    {
		//	Check if the notification is valid
		
		
		if( ! $identifier = $this->getIdentifier() ){ return false; }
		if( ! $orderInfo = $this->getDbTable()->selectOne( null, array( 'order_id' => $response['order_id'] ) ) )
		{ 
			echo 'INVALID ORDER';
			return false; 
		}
		//	Notify Admin
		$mailInfo = array();
		$mailInfo['subject'] = 'Order info';
		$mailInfo['body'] = '"' . var_export( $orderInfo, true ) . '"';
	//	var_export( $newCart );
		@Ayoola_Application_Notification::mail( $mailInfo );
		if( $orderInfo['order_api'] != $identifier['api'] )		
		{ 
			echo 'INVALID API';
			return false; 
		}
		$stages = Application_Subscription_Checkout::$checkoutStages;
	//	var_export( $orderInfo );
		if( $orderInfo['order_status'] == $response['order_status'] )
		{ 
			echo 'DUPLICATE NOTIFICATION';
			return false; 
		}
		//	Treat the callback methods
		$values = unserialize( $orderInfo['order'] );
		foreach( $values['cart'] as $each )
		{ 
			$each['order_status'] = $response['order_status'];
			$each['transactionmethod'] = $identifier['api'];
			$each['currency_abbreviation'] = $values['settings']['currency_abbreviation'];
			if( ! isset( $each['callback'] ) ){ break; }
			$callback = array_map( 'trim', explode( ',', $each['callback'] ) );
			foreach( $callback as $eachCallback )
			{
				//	Notify Admin
				if( ! $eachCallback ){ break; }
				if( ! Ayoola_Loader::loadClass( $eachCallback ) )
				{ 
					throw new Application_Subscription_Exception( 'INVALID CALLBACK - ' . $eachCallback );
				}
				$eachCallback::callback( $each ); 
/* 				$mailInfo['subject'] = 'Call back done';
				$mailInfo['body'] = '"' . var_export( $eachCallback, true ) . '"';
			//	var_export( $newCart );
				@Ayoola_Application_Notification::mail( $mailInfo );
 */			}
			
		}
		$this->getDbTable()->update( array( 'order_random_code' => $response['order_random_code'], 'order_status' => $response['order_status'] ), array( 'order_id' => $response['order_id'] ) );
		
		echo 'OK';
		return;
		
    } 
	// END OF CLASS
}
