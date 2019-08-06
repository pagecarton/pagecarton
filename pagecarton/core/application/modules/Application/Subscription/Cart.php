<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Cart
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Cart.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */  
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Cart
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Cart extends Application_Subscription_Abstract
{
	/**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Shopping Cart'; 
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;

    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The total number of items in the cart
     * 
     * @var int
     */
	protected static $_noOfItems = 0;
	
    /**
     * The total number of distinct items in the cart
     * 
     * @var int
     */
	protected $_noOfDinstinctItems = 0;
	
    /**
     * The total price amount of items in the cart
     * 
     * @var double
     */
//	protected $_cummulativePrice = 0.00;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->_objectTemplateValues['no_of_distinct_items'] = 0;
		$this->_objectTemplateValues['no_of_items'] = 0;
		$this->_objectTemplateValues['total_price'] = 0;
		$this->_objectTemplateValues['currency'] = ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' );
		$this->cartUpdate();
		if( ! $data = $this->getCartContents() )
		{ 
			return $this->setViewContent(  '' . self::__( '<span class="boxednews centerednews badnews">Your shopping cart is empty.</span>' ) . '', true  );
		}
		$this->setViewContent(  '' . self::__( '<div class="">' . self::getXml()->saveHTML() . '</div>' ) . '', true  );
	//	$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
	//	var_export( $this->_xml );
    } 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function getCartContents()
    {
		//	We can now preload cart from code
		if( ! $data = $this->getParameter( 'cart' ) )
		{ 
			if( ! $data = self::getStorage()->retrieve() )
			{ 
				
			}	
		}
		return $data;
    } 
	
    /**
     * The method does the whole Class Process
     * 
     */
	public static function clear()
    {
		if( self::getStorage()->clear() )
		{ 
			return true;
		}	
    } 
	
    /**
     * Returns the Xml
     * 
     * @return Ayoola_Xml
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		$this->_xml = new Ayoola_Xml();
	//	$values = array();
		$div = $this->_xml->createElement( 'div' );
		$div->setAttribute( 'style', 'overflow:auto;max-width:100%;'  );
		$table = $this->_xml->createElement( 'table' );
		$div->appendChild( $table );
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$table->setAttribute( 'class', 'pc-table'  );
		if( ! $data = $this->getCartContents() )
		{ 
		//	return $this->setViewContent(  '' . self::__( '<span class="boxednews centerednews badnews">You have no item in your shopping cart.</span>' ) . '', true  );
			$columnNode = $this->_xml->createElement( 'td', 'There is no item in the shopping cart' );
			$row->appendChild( $columnNode );
			return $this->_xml->appendChild( $table );
		}
		$values = $data['cart'];
		$deleteMessage = null;
		if( $data['settings']['read_only'] ){ $deleteMessage = 'Read-only carts cannot be edited on this website.'; }
	//	var_export( $data );
		$mode = @$_GET[$this->getObjectName()];
		switch( $mode )
		{
			case 'mini':
				$tableColumns = array( 'item', 'price', 'total' );
			break;
			case 'cart_no_of_items':
				$columnNode = $this->_xml->createElement( 'th', 'Item Count' );
				$row->appendChild( $columnNode );
				$columnNode = $this->_xml->createElement( 'td' );
				$text = $this->_xml->createTextNode( count( $values ) );
				$columnNode->appendChild( $text );
				$row->appendChild( $columnNode );
			break;
			case 'cart_total_price':
				$columnNode = $this->_xml->createElement( 'th', 'Amount Due' );
				$row->appendChild( $columnNode );
				$columnNode = $this->_xml->createElement( 'td' );
				$text = $this->_xml->createTextNode( count( $values ) );
				$columnNode->appendChild( $text );
				$row->appendChild( $columnNode );
			break;
			default:
		//		$tableColumns = array( 'item', 'price', 'cycle_name', 'multiple', 'total' );
				$tableColumns = array( 'item', 'price', 'multiple', 'total' );
			break;
		}
		if( ! @$tableColumns ){ return $this->_xml->appendChild( $table ); }
		//	var_export( $this->getParameter() );
				

		foreach( $tableColumns as $column )
		{
		//
			$columnNode = $this->_xml->createElement( 'th', ! is_null( $this->getParameter( $column . '_label' ) ) ? $this->getParameter( $column . '_label' ) : $column );    
			$row->appendChild( $columnNode );
		}
		$cartID = md5( serialize( $values ) );

        $deleteUrl = $deleteMessage ? $data['settings']['edit_cart_url'] : Ayoola_Page::appendQueryStrings( array( 'cart_action' => 'empty', 'cart_id' => $cartID  ) );
		$columnNode = @$this->_xml->createHTMLElement( 'td', '<a href="' . $deleteUrl . '" title="' . $deleteMessage . ' Empty Cart">x</a>' );
		$columnNode->setAttribute( 'align', 'center'  );
		$row->appendChild( $columnNode );
	//	$table->appendChild( $caption );
		$filter = 'Ayoola_Filter_Currency';
		$filter::$symbol = $data['settings']['currency_abbreviation'] ? : ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' );
		$filter::$symbol .=  '';
		$filter = new $filter;
		
		//	Calculate culmulative price
		$totalPrice = 0.00;
		$noOfItems = 0;
	//	var_export( $values );
		foreach( $values as $name => $value )
		{
			$cartID = md5( serialize( $value ) );
			//	Count the number of items
			++$this->_noOfDinstinctItems; 
			$noOfItems += $value['multiple'];
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$value['total'] = (float) floatval( $value['price'] ) * floatval( $value['multiple'] );
			$totalPrice = (float) $value['total'] + $totalPrice;
			$row = $this->_xml->createElement( 'tr' );
	//		var_export( $value['subscription_label'] );
			$link = $this->_xml->createElement( 'a', htmlspecialchars( $value['subscription_label'] ) );
			$columnNode = $this->_xml->createElement( 'td' );
			$text = $this->_xml->createTextNode( $value['subscription_label'] );
			$columnNode->appendChild( $text );
		//	var_export( $value );
			
			$value['item_link'] = @$itemLink =  $value['url'] ? : 'javascript:;' ;
			if( ! @$value['classplayer_url'] && @$value['subscription_object_name'] )
			{
	//			$value['classplayer_url'] = '/tools/classplayer/get/object_name/' .  $value['subscription_object_name'] . '/';
			}
			@$value['classplayer_url'] = $value['classplayer_url'] ? ';classPlayerUrl=' . $value['classplayer_url'] . ';' . 'spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() : null;
		//		var_export( $value['classplayer_url'] );
			$link->setAttribute( 'href', $value['item_link'] );
			$link->setAttribute( 'rel', $value['classplayer_url'] );
			$link->setAttribute( 'title', @$value['subscription_description'] );   
			$columnNode = $this->_xml->createElement( 'td' );
			$columnNode->appendChild( $link );
			$row->appendChild( $columnNode );
			$value['price'] = $filter->filter( $value['price'] );
			$value['total'] = $filter->filter( $value['total'] );
	//		if( ! @$value['cycle_label'] ){ var_export( $value ); }
			$value['multiple'] = $value['multiple'] . ' ' . $value['cycle_label'];
			foreach( $tableColumns as $column )
			{
				if( array_key_exists( $column, $value ) )
				{
		//	var_export( $column );
		//	var_export( $value[$column] );
					$columnNode = $this->_xml->createElement( 'td' );
					$text = $this->_xml->createTextNode( $value[$column] );
					$columnNode->appendChild( $text );
					$columnNode->setAttribute( 'align', 'center'  );
					$row->appendChild( $columnNode );
				}
			}
		//	var_export( $value );
		//	var_export( md5( serialize( $value ) ) );
			$value['delete_url'] = $deleteUrl2 = $deleteMessage ? $deleteMessage : Ayoola_Page::appendQueryStrings( array( 'cart_action' => 'delete', 'cart_id' => $cartID  ) );
			$columnNode = @$this->_xml->createHTMLElement( 'td',  '<a href="' . $value['delete_url'] . '" title="' . $deleteMessage . ' Delete: ' . $value['subscription_label'] . '">x</a>' );
			$columnNode->setAttribute( 'align', 'center'  );
			$row->appendChild( $columnNode );
			$table->appendChild( $row );
			$this->_objectTemplateValues[] = $value;   
		}
		
		//	surcharges
		$paymentSettings = Application_Settings_Abstract::getSettings( 'Payments' );
		$totalSurcharge = 0.00;

		if( ! empty( $paymentSettings['surcharge_title'] ) )
		{
			foreach( $paymentSettings['surcharge_title'] as $key => $eachSurcharge )
			{
				if( empty( $paymentSettings['surcharge_title'][$key] ) )
				{
					continue;
				}
				$surchargeText = '';
				$surchargePrice = 0;
				$paymentSettings['surcharge_value'][$key] = intval( $paymentSettings['surcharge_value'][$key] );
				switch( @$paymentSettings['surcharge_type'][$key] )
				{
					case 'percentage':   
						if( ! empty( $paymentSettings['surcharge_value'][$key] ) && intval( $paymentSettings['surcharge_value'][$key] ) <= 100 )
						{  
							$surchargeText .= '+ ' . $paymentSettings['surcharge_value'][$key] . '% of total order';
							$surchargePrice += ( $paymentSettings['surcharge_value'][$key]/100 ) * $totalPrice;
							$surchargePricText = $this->_xml->createTextNode( $filter->filter( $surchargePrice ) );
						}
					break;
					case 'constant':
						if( ! empty( $paymentSettings['surcharge_value'][$key] ) )
						{  
							$surchargeText .= '+ ' . $filter->filter( $paymentSettings['surcharge_value'][$key] ) . ' fixed charge.';
							$surchargePrice += $paymentSettings['surcharge_value'][$key];
							$surchargePricText = $this->_xml->createTextNode( $filter->filter( $surchargePrice ) );
						}
					break;
					case 'not-calculated':
						$surchargeText .= 'Not Calculated.  ' . $paymentSettings['surcharge_value'][$key] . '';
					//	$surchargePrice += $paymentSettings['surcharge_value'][$key];
						$surchargePricText = $this->_xml->createTextNode( '--' );
					break;
				}
			//	var_export( $surchargeText );
				$surchargeText = $surchargeText ? $paymentSettings['surcharge_title'][$key] . ' (' . $surchargeText . ')' : $paymentSettings['surcharge_title'][$key];
		//		var_export( $surchargeText );
				$row = $this->_xml->createElement( 'tr' );
				
				$columnNode = @$this->_xml->createHTMLElement( 'td', $surchargeText );
				$columnNode->setAttribute( 'colspan', count( $tableColumns ) - 1 );
				$row->appendChild( $columnNode );
				$columnNode = $this->_xml->createElement( 'td' );
			//	$surchargePrice = $filter->filter( $surchargePrice );
				$totalSurcharge += $surchargePrice;
				$columnNode->appendChild( $surchargePricText );
				$columnNode->setAttribute( 'align', 'center'  );
				$row->appendChild( $columnNode );
				$table->appendChild( $row );
			}
		}
		
		//	Total
		$row = $this->_xml->createElement( 'tr' );
		$columnNode = $this->_xml->createElement( 'td' );
		$columnNode->setAttribute( 'colspan', count( $tableColumns ) - 1 );
		$row->appendChild( $columnNode );
		$columnNode = $this->_xml->createElement( 'th' );
		
	//	$totalPrice += $surchargePrice;
		$grandTotalPrice = $totalPrice + $totalSurcharge;
	//	$totalPrice = $filter->filter( $totalPrice );
		$text = $this->_xml->createTextNode( $filter->filter( $grandTotalPrice ) );
		$columnNode->appendChild( $text );
		$columnNode->setAttribute( 'align', 'center'  );
		$row->appendChild( $columnNode );
		$table->appendChild( $row );
		
		
		$this->_xml->appendChild( $div );
		$this->_objectTemplateValues['no_of_distinct_items'] = $this->_noOfDinstinctItems;
		$this->_objectTemplateValues['no_of_items'] = $noOfItems;
		$this->_objectTemplateValues['total_surcharge'] = $totalSurcharge;
		$this->_objectTemplateValues['total_price'] = $totalPrice;
		$this->_objectTemplateValues['grand_total_price'] = $grandTotalPrice;
		$this->_objectTemplateValues['empty_cart_url'] = $deleteUrl;
		$this->_objectTemplateValues['total_discount'] = 0.00;
		$this->_objectTemplateValues['delivery_price'] = 0.00;

    } 
	// END OF CLASS
}
