<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Price_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Price_Abstract
 */
 
require_once 'Application/Subscription/Price/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Price_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Price_ShowAll extends Application_Subscription_Price_Abstract
{
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscription_name' );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $this->getDbData() ){ return $this->setViewContent( '<p>There are no packages to show</p>', true ); }
			$this->setViewContent( self::getXml()->saveHTML(), true );
		}
		catch( Exception $e ){ return $this->setViewContent( '<p>Error with subscription package</p>', true ); }
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
		$table = $this->_xml->createElement( 'table' );
		$row = $this->_xml->createElement( 'tr' );
		$row = $table->appendChild( $row );
		//	Sort
		$sort = Application_Subscription::sortPriceList( $this->getDbData() );
	//	var_export( $sort );
		$done  = array();
		$counter = 0;
		foreach( $sort as $name => $value )
		{
			//	levels
			if( isset( $done[$name] ) ){ continue; }
			$done[$name] = true;
			ksort( $value );
			$priceInfo = array_shift( $value );
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', ++$counter );
		//	$columnNode->setAttribute( 'colspan', 3 );
			$row->appendChild( $columnNode );
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$link = $this->_xml->createElement( 'a', $priceInfo['subscription_label'] );
			$link->setAttribute( 'href', '/' . strtolower( $priceInfo['subscription_name'] ) . '/' );
			$columnNode = $this->_xml->createElement( 'th' );
			$columnNode->appendChild( $link );
			$row->appendChild( $columnNode );
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
			$filter = new $filter();
			$priceInfo['price'] = $filter->filter( $priceInfo['price'] );
			$columnNode = $this->_xml->createElement( 'td', 'From ' . $priceInfo['price'] );
			$row->appendChild( $columnNode );
			$link = $this->_xml->createElement( 'a', '+' );
			$link->setAttribute( 'href', '/tools/classplayer/get/object_name/Application_Subscription/?subscription_name=' . $priceInfo['subscription_name'] );
			$link->setAttribute( 'rel', 'spotlight;width=300px;height=300px' );
			$link->setAttribute( 'title', 'Add ' . $priceInfo['subscription_label'] . ' to Cart' );
			$columnNode = $this->_xml->createElement( 'th' );
			$columnNode->appendChild( $link );
			$row->appendChild( $columnNode );
			$row = $this->_xml->createElement( 'tr' );
			$row = $table->appendChild( $row );
			$columnNode = $this->_xml->createElement( 'td', htmlentities( $priceInfo['subscription_description'] ) );
		//	var_export( $priceInfo['subscription_description'] );
			$columnNode->setAttribute( 'colspan', 2 );
			$row->appendChild( $columnNode );
		}
		$this->_xml->appendChild( $table );
    } 
	// END OF CLASS
}
