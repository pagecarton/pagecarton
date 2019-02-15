<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Google_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Xml.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Google_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Google_Xml extends Application_Subscription_Checkout_Google
{
	
    /**
     * The Mechant ID
     * 
     * @var string
     */
	protected $_mechantId = '241634264157584';
	
    /**
     * The Mechant Key
     * 
     * @var string
     */
	protected $_mechantKey = 't0WlrtpiujC44szqrx6zxQ';
	
    /**
     * The Xml Nameserver
     * 
     * @var string
     */
	const ATTRIBUTE_XMLNS = 'http://checkout.google.com/schema/2';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		$this->setViewContent( $this->request(), true );
    } 
	
    /**
     * Construct the header used in request
     * 
     */
	public function getHeaders()
    {
		$headers = array();
		$headers[] = "Authorization: Basic " . base64_encode( $this->_mechantId . ':' . $this->_mechantKey ); 
		$headers[] = "Content-Type: application/xml; charset=UTF-8";
		$headers[] = "Accept: application/xml; charset=UTF-8";
		$headers[] = "User-Agent: {$_SERVER['HTTP_HOST']} (" . __METHOD__ . ")";
		return $headers; 
    } 
	
    /**
     * Requests the Google Checkout
     * 
     */
	public function request()
    {
		$request = curl_init( 'https://checkout.google.com/api/checkout/v2/merchantCheckout/Merchant/' . $this->_mechantId . '/' );
		// set URL and other appropriate options
	//	curl_setopt( $request, CURLOPT_URL, 'https://checkout.google.com/api/checkout/v2/checkout/Merchant/' . $this->_mechantId . '/' );
		curl_setopt( $request, CURLOPT_POST, true );
		curl_setopt( $request, CURLOPT_HTTPHEADER, $this->getHeaders() );
	//	echo $this->getXml()->saveXML();
		curl_setopt( $request, CURLOPT_POSTFIELDS, $this->getXml()->saveXML() );
		curl_setopt( $request, CURLOPT_HEADER, true );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );

		// grab URL and pass it to the browser
		$response = curl_exec( $request );
		if( ! $response ){ return null; }
		// close cURL resource, and free up system resources
		curl_close( $request );
		$delimeter = "\r\n";
		list( $header, $body ) = explode( $delimeter . $delimeter, $response );
		$header = explode( $delimeter, $header );
		foreach( $header as $key => $value )
		{ 
			list( $newKey, $newValue ) = array_map( 'trim', explode( ':', $value ) );
		//	unset( $header[$key] );
			$header[$newKey] = $newValue;
		}
		list( ,$statusCode ) = explode( ' ', $header[0] );
	//	var_export( $header );
		if( ! isset( $statusCode ) || $statusCode != 200 )
		{
			$this->getForm()->setBadnews( 'There was an error connecting to Google API' );
		}
		$xml = new Ayoola_Xml();
		$xml->loadXML( $body );
		$body = $xml->getCDataValues();
		header( 'Location: ' . $body['redirect-url'] );
		exit( $body['redirect-url'] );
	//	var_export( $body );
	//	return $body;
	} 
	
    /**
     * Creates the digitally signed cart in xml
     * 
     * @param void
     * @return void
     */
	public function getXml()
    {
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		$values = $values['cart'];
		$xml = new Ayoola_Xml();
		$documentElement = $xml->createElement( 'checkout-shopping-cart' );
		$documentElement->setAttribute( 'xmlns', self::ATTRIBUTE_XMLNS );
		$documentElement = $xml->appendChild( $documentElement );
		$shoppingCart = 'shopping-cart';
		$shoppingCart = $xml->createElement( $shoppingCart );
		$shoppingCart = $documentElement->appendChild( $shoppingCart );
		$items = 'items';
		$items = $xml->createElement( $items );
		$items = $shoppingCart->appendChild( $items );
		$itemsKeys = array( 'item-name' => 'subscription_name', 'item-description' => 'subscription_description', 'unit-price' => 'price', 'quantity' => 'multiple', 'merchant-item-id' => 'price_id' );
		foreach( $values as $name => $value )
		{
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$item = 'item';
			$item = $xml->createElement( $item );
			$item = $items->appendChild( $item );
			foreach( $itemsKeys as $itemKey => $valueKey )
			{
				if( array_key_exists( $valueKey, $value ) )
				{
					$node = $xml->createElement( $itemKey, $value[$valueKey] );
					$node = $item->appendChild( $node );
					if( $itemKey == 'unit-price' ){ $node->setAttribute( 'currency', 'USD' ); }
				}
				
			}
		}
		unset( $itemsKeys );
		$flowSupport = $xml->createElement( 'checkout-flow-support' );
		$flowSupport = $documentElement->appendChild( $flowSupport );
		$checkoutFlowSupport = $xml->createElement( 'merchant-checkout-flow-support' );
		$checkoutFlowSupport = $flowSupport->appendChild( $checkoutFlowSupport );
		$tag = $xml->createElement( 'edit-cart-url', 'http://' . DOMAIN . '/onlinestore/cart/' );
		$tag = $checkoutFlowSupport->appendChild( $tag );
		$tag = $xml->createElement( 'continue-shopping-url', 'http://' . DOMAIN . '/onlinestore/' );
		$tag = $checkoutFlowSupport->appendChild( $tag );
/* 		echo $xml->saveXML();
		exit();
 */		return $xml;
    } 
	// END OF CLASS
}
