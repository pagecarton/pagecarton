<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
 * @package    Application_Subscription_Cart
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Cart extends Application_Subscription_Abstract
{
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
	protected static $_noOfDinstinctItems = 0;
	
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
		$this->_objectTemplateValues['total_price'] = ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' ) . '0';
	//	$this->_objectTemplateValues['currency'] = ;
		$this->cartUpdate();
		if( ! $data = self::getStorage()->retrieve() )
		{ 
			return $this->setViewContent( '<span class="boxednews centerednews badnews">Your shopping cart is empty.</span>', true );
		}
		$this->setViewContent( '<div class="">' . self::getXml()->saveHTML() . '</div>', true );
	//	$this->setViewContent( Application_Subscription_Checkout::viewInLine() );
	//	var_export( $this->_xml );
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
		$table = $this->_xml->createElement( 'table' );
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$table->setAttribute( 'class', 'pc-table'  );
		if( ! $data = self::getStorage()->retrieve() )
		{ 
		//	return $this->setViewContent( '<span class="boxednews centerednews badnews">You have no item in your shopping cart.</span>', true );
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
				

		foreach( $tableColumns as $column )
		{
		//	var_export( $column );
			$columnNode = $this->_xml->createElement( 'th', $column );
			$row->appendChild( $columnNode );
		}
		$cartID = md5( serialize( $values ) );
	//	var_export( $deleteMessage );
	//	var_export( $data['settings']['edit_cart_url'] );
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
		$totalPrice = 0;
		foreach( $values as $name => $value )
		{
			$cartID = md5( serialize( $value ) );
			//	Count the number of items
			++self::$_noOfDinstinctItems; 
			self::$_noOfItems += $value['multiple'];
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$value['total'] = (float) floatval( $value['price'] ) * floatval( $value['multiple'] );
			$totalPrice = (float) $value['total'] + $totalPrice;
			$row = $this->_xml->createElement( 'tr' );
			$link = $this->_xml->createElement( 'a', $value['subscription_label'] );
			$columnNode = $this->_xml->createElement( 'td' );
			$text = $this->_xml->createTextNode( $value['subscription_label'] );
			$columnNode->appendChild( $text );
		//	var_export( $value );
			@$itemLink = $value['url'] ? : 'javascript:;';
			if( ! @$value['classplayer_url'] && @$value['subscription_object_name'] )
			{
	//			$value['classplayer_url'] = '/tools/classplayer/get/object_name/' .  $value['subscription_object_name'] . '/';
			}
			@$value['classplayer_url'] = $value['classplayer_url'] ? ';classPlayerUrl=' . $value['classplayer_url'] . ';' . 'spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() : null;
		//		var_export( $value['classplayer_url'] );
			$link->setAttribute( 'href', $itemLink );
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
			$deleteUrl2 = $deleteMessage ? $value['link'] : Ayoola_Page::appendQueryStrings( array( 'cart_action' => 'delete', 'cart_id' => $cartID  ) );
			$columnNode = @$this->_xml->createHTMLElement( 'td',  '<a href="' . $deleteUrl2 . '" title="' . $deleteMessage . ' Delete: ' . $value['subscription_label'] . '">x</a>' );
			$columnNode->setAttribute( 'align', 'center'  );
			$row->appendChild( $columnNode );
			$table->appendChild( $row );
		}
		$row = $this->_xml->createElement( 'tr' );
		$columnNode = $this->_xml->createElement( 'td' );
		$columnNode->setAttribute( 'colspan', count( $tableColumns ) - 1 );
		$row->appendChild( $columnNode );
		$columnNode = $this->_xml->createElement( 'th' );
		$totalPrice = $filter->filter( $totalPrice );
		$text = $this->_xml->createTextNode( $totalPrice );
		$columnNode->appendChild( $text );
		$columnNode->setAttribute( 'align', 'center'  );
		$row->appendChild( $columnNode );
		$table->appendChild( $row );
		$this->_xml->appendChild( $table );
		$this->_objectTemplateValues['no_of_distinct_items'] = self::$_noOfDinstinctItems;
		$this->_objectTemplateValues['no_of_items'] = self::$_noOfItems;
		$this->_objectTemplateValues['total_price'] = $totalPrice;
		$this->_objectTemplateValues['empty_cart_url'] = $deleteUrl;

    } 
	// END OF CLASS
}
