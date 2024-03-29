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
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
        //Application_Subscription::reset();

		$this->_objectTemplateValues['no_of_distinct_items'] = 0;
		$this->_objectTemplateValues['no_of_items'] = 0;
		$this->_objectTemplateValues['total_price'] = 0;
		$this->_objectTemplateValues['grand_total_price'] = 0;
		$this->_objectTemplateValues['currency'] = ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' );
		$this->cartUpdate();
		if( ! $data = $this->getCartContents() )
		{ 
			return $this->setViewContent(  '' . self::__( '<span class="boxednews centerednews badnews">Your shopping cart is empty.</span>' ) . '', true  );
		}
        //var_export( $data );

        //
        self::getXml()->saveHTML();
		$this->setViewContent(  '' . self::__( '<div class="">' . $this->cartDiv . '</div>' ) . '', true  );
    } 
	
    /**
     * The method does the whole Class Process
     * 
     */
	public function getCartContents()
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
		$div = $this->_xml->createElement( 'div' );
		$div->setAttribute( 'style', 'overflow:auto;max-width:100%;'  );
		$table = $this->_xml->createElement( 'table' );
		$div->appendChild( $table );
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		$table->setAttribute( 'class', 'pc-table'  );
		if( ! $data = $this->getCartContents() )
		{ 
			$columnNode = $this->_xml->createElement( 'td', 'There is no item in the shopping cart' );
			$row->appendChild( $columnNode );
			return $this->_xml->appendChild( $table );
		}
		$values = $data['cart'];
		$deleteMessage = null;
		if( $data['settings']['read_only'] ){ $deleteMessage = 'Read-only carts cannot be edited on this website.'; }
		$mode = @$_GET[$this->getObjectName()];
		switch( $mode )
		{
			case 'mini':
				$tableColumns = array( 'item', 'total' );
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
				$tableColumns = array( 'item', 'total' );
			break;
		}
		if( ! @$tableColumns ){ return $this->_xml->appendChild( $table ); }				

		foreach( $tableColumns as $column )
		{
			$columnNode = $this->_xml->createElement( 'th', ! is_null( $this->getParameter( $column . '_label' ) ) ? $this->getParameter( $column . '_label' ) : $column );    
			$row->appendChild( $columnNode );
		}
		$cartID = md5( serialize( $values ) );

        $deleteUrl = $deleteMessage ? $data['settings']['edit_cart_url'] : Ayoola_Page::appendQueryStrings( array( 'cart_action' => 'empty', 'cart_id' => $cartID  ) );
        
		$columnNode = @$this->_xml->createHTMLElement( 'td', '<a style="color:red" href="' . $deleteUrl . '" title="' . $deleteMessage . ' Empty Cart"><i class="fa fa-trash" aria-hidden="true"></i></a>' );
		$columnNode->setAttribute( 'align', 'center'  );
		$columnNode->setAttribute( 'style', 'color: red; text-align: center;'  );
		$row->appendChild( $columnNode );
		$filter = 'Ayoola_Filter_Currency';
		$filter::$symbol = $data['settings']['currency_abbreviation'] ? : ( Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$' );
		$filter::$symbol .=  '';
		$filter = new $filter;
		
		//	Calculate culmulative price
		$totalPrice = 0.00;
		$noOfItems = 0;

        $cartDiv = '';
        foreach( $values as $name => $value )
		{
            $cartID = md5( serialize( $value ) );
            $divRow = '';
            
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

			$columnNode = $this->_xml->createElement( 'td' );
			$text = $this->_xml->createTextNode( $value['multiple'] . ' x' );
			$columnNode->appendChild( $text );
            $row->appendChild( $columnNode );
			$value['item_link'] = @$itemLink =  $value['url'] ? : 'javascript:;' ;
            
            $eLink = 'readonly disabled';
            if( empty( $value['readonly'] ) )
            {
                $eLink = '';
                //$eLink = '<a href="' . $value['item_link'] . '">' . $value['multiple'] .  '</a>';
            }

            //$divRow .= '<span class="multiple-cart-column">' . $eLink . '</span>';
            $divRow .= '<input ' . $eLink . ' class="multiple-cart-column" value="' . $value['multiple'] . '" onchange="location.search += \'&edit=\'+this.value+\'&cart_id=' . $cartID . '&cart_action=edit\'">';

            $link = $this->_xml->createElement( 'a', htmlspecialchars( $value['subscription_label'] ) );
			$columnNode = $this->_xml->createElement( 'td' );
			$text = $this->_xml->createTextNode( $value['subscription_label'] );
			$columnNode->appendChild( $text );

			if( ! @$value['classplayer_url'] && @$value['subscription_object_name'] )
			{

            }
			@$value['classplayer_url'] = $value['classplayer_url'] ? ';classPlayerUrl=' . $value['classplayer_url'] . ';' . 'spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() : null;

            $link->setAttribute( 'href', $value['item_link'] );
			$link->setAttribute( 'rel', $value['classplayer_url'] );
			$link->setAttribute( 'title', @$value['subscription_description'] );   
			$columnNode = $this->_xml->createElement( 'td' );
			$columnNode->appendChild( $link );
			$row->appendChild( $columnNode );

            $eLink = '' . $value['subscription_label'] .  '';
            if( empty( $value['readonly'] ) )
            {
                $eLink = '<a href="' . $value['item_link'] . '">' . $value['subscription_label'] .  '</a>';
            }
            $divRow .= '<span class="item-cart-column">' . $eLink . '</span>';


			$value['price'] = $filter->filter( $value['price'] );
			$value['total'] = $filter->filter( $value['total'] );

            $value['multiple'] = $value['multiple'] . ' ' . $value['cycle_label'];
			foreach( $tableColumns as $column )
			{
				if( array_key_exists( $column, $value ) )
				{
					$columnNode = $this->_xml->createElement( 'td' );
					$text = $this->_xml->createTextNode( $value[$column] );
					$columnNode->appendChild( $text );
					$columnNode->setAttribute( 'align', 'center'  );
					$row->appendChild( $columnNode );
                    $divRow .= '<span class="x-cart-column">' . $value[$column] .  '</span>';

				}
			}
			$value['delete_url'] = $deleteUrl2 = $deleteMessage ? $deleteMessage : Ayoola_Page::appendQueryStrings( array( 'cart_action' => 'delete', 'cart_id' => $cartID  ) );
			$columnNode = @$this->_xml->createHTMLElement( 'td',  '<a  style="color:red" href="' . $value['delete_url'] . '" title="' . $deleteMessage . ' Delete: ' . $value['subscription_label'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a>' );
			$columnNode->setAttribute( 'align', 'center'  );
            $columnNode->setAttribute( 'style', 'color: red; text-align: center;'  );
			$row->appendChild( $columnNode );

            $del = '<i class="fa fa-trash" aria-hidden="true"></i>';
            if( empty( $value['readonly'] ) )
            {
                $del = '<a  style="color:red" href="' . $value['delete_url'] . '" title="' . $deleteMessage . ' Delete: ' . $value['subscription_label'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            $divRow .= '<span class="delete-cart-column">' . $del . '</span>';

			$table->appendChild( $row );
			$this->_objectTemplateValues[] = $value;  
            $divRow = '<div class="pc-cart-row">' . $divRow . '</div>'; 
            $cartDiv .= $divRow;
		}

		if( empty( $totalSurcharge ) )
		{
			$totalSurcharge = 0;
		}
		
		//	Total
		$row = $this->_xml->createElement( 'tr' );
		$columnNode = $this->_xml->createElement( 'td' );
		$columnNode->setAttribute( 'colspan', count( $tableColumns ) - 1 );
		$row->appendChild( $columnNode );
		$columnNode = $this->_xml->createElement( 'th' );		
		$grandTotalPrice = $totalPrice + $totalSurcharge;
		$text = $this->_xml->createTextNode( $filter->filter( $grandTotalPrice ) );
		$columnNode->appendChild( $text );
		$columnNode->setAttribute( 'align', 'center'  );
		$row->appendChild( $columnNode );
        $divRow = '<input type="button" value="Save" class="multiple-cart-column">';
        $divRow .= '<span class="item-cart-column">Total Due</span>';
        $divRow .= '<span class="x-cart-column">' . $filter->filter( $grandTotalPrice )  .  '</span>';
        $divRow .= '<span class="delete-cart-column">' . '<a style="color:red" href="' . $deleteUrl . '" title="' . $deleteMessage . ' Empty Cart"><i class="fa fa-trash" aria-hidden="true"></i></a>'  .  '</span>';

        $divRow = '<div class="pc-cart-row">' . $divRow . '</div>'; 
        $cartDiv .= $divRow;

        $this->cartDiv = $cartDiv;

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
        $this->_objectData = $this->_objectTemplateValues;
    } 
	// END OF CLASS
}
