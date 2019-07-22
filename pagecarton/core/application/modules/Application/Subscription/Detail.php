<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Detail
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Detail.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Detail
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Detail extends Application_Subscription_Abstract
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
			if( ! $data = $this->getIdentifierData() ){ return $this->setViewContent(  '' . self::__( 'Subscription Package Not Found' ) . '', true  ); }
			$pageInfo = array(
				'description' => $data['subscription_description'],
				'title' => trim( $data['subscription_label'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), ' -' )
			);
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			$this->setViewContent( self::getXml()->saveHTML(), true );
		}
		catch( Exception $e )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  ); 
			return $this->setViewContent( self::__( '<p>Error with subscription package</p>' ) ); 
		}
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
		$data = $this->getIdentifierData();
		$this->_xml = new Ayoola_Xml();
		$span = $this->_xml->createElement( 'span' );
		$priceList = new Application_Subscription_Price;
		$priceList = $priceList->select( null, $this->getIdentifier() );
		$sort = Application_Subscription::sortPriceList( $priceList );
		foreach( $sort as $name => $value )
		{
			ksort( $value );
			//	levels
			if( $value )
			{
			}
			$value = array_values( $value );
			foreach( $value as $key => $priceInfo )
			{
				if( ! isset( $done[$value[$key]['subscriptionlevel_name']] ) )
				{ 
					$done[$value[$key]['subscriptionlevel_name']] = true;
					
					$ul = $this->_xml->createElement( 'ul' );
					$ul->setAttribute( 'style', "width:100%;display:inline-block;" );
					$ul = $span->appendChild( $ul );
					$h4 = $this->_xml->createElement( 'h4' );
					$text = $this->_xml->createTextNode( $value[$key]['subscriptionlevel_name'] );
					$h4->appendChild( $text );
					$h4 = $ul->appendChild( $h4 );
					$img = $this->_xml->createElement( 'img' );
					$img->setAttribute( 'src', $value[$key]['document_url'] );
					$img->setAttribute( 'style', "float:left;height:120px;padding:1em;" );
					$img = $ul->appendChild( $img );
					$text = $this->_xml->createTextNode( $value[$key]['subscriptionlevel_description'] );
					$p = $this->_xml->createElement( 'p' );
					$p->appendChild( $text );
					$p = $ul->appendChild( $p );
					$editMessage = null;
					if( self::hasPriviledge() )
					{
						$editMessage .= '<span class="goodnews">
											<a title="Edit ' . $value[$key]['subscriptionlevel_name'] . ' pricing" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Price_List/subscriptionlevel_id/' . $value[$key]['subscriptionlevel_id'] . '/subscription_id/' . $value[$key]['subscription_id'] . '/"> $ </a>  
											<a title="Edit ' . $value[$key]['subscriptionlevel_name'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Editor/subscriptionlevel_id/' . $value[$key]['subscriptionlevel_id'] . '/subscription_id/' . $value[$key]['subscription_id'] . '/"> - </a>  
											<a class="badnews" title="Delete ' . $value[$key]['subscriptionlevel_name'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Delete/subscriptionlevel_id/' . $value[$key]['subscriptionlevel_id'] . '/subscription_id/' . $value[$key]['subscription_id'] . '/"> x </a> 
										</span>';
						$p = $this->_xml->createHTMLElement( 'p', $editMessage );
						$p = $ul->appendChild( $p );
					}
					
					//	use table for pricing
					$table = $this->_xml->createChild( 'table', $ul );
					$tr = $this->_xml->createChild( 'tr', $table );
					$th = $this->_xml->createElement( 'th' );
					$text = $this->_xml->createTextNode( 'Pricing options for "' . $value[$key]['subscriptionlevel_name'] . '"' );
					$th->appendChild( $text );
					$th->setAttribute( 'colspan', 3 );
					$th = $tr->appendChild( $th );
					$optionCount = 1;
				}
				$filter = 'Ayoola_Filter_Currency';
				$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
				$filter = new $filter();
				$priceInfo['price'] = $filter->filter( $priceInfo['price'] );
		//		$td = $this->_xml->createHTMLElement( 'span', $priceInfo['price'] . ' per ' . $priceInfo['cycle_name'] . '<br />' );
			//	$td = $ul->appendChild( $td );
				$tr = $this->_xml->createChild( 'tr', $table );
				$th = $this->_xml->createHTMLElement( 'td', 'Option ' . $optionCount++ );
				$th = $tr->appendChild( $th );
				$th = $this->_xml->createHTMLElement( 'td', $priceInfo['price'] . ' ' . $priceInfo['cycle_name'] . '<br />' );
				$th = $tr->appendChild( $th );
				$subscriptionName = $value[$key]['subscription_name'];
				$link = "/tools/classplayer/get/object_name/Application_Subscription/subscription_name/{$subscriptionName}/{$subscriptionName}price_id/{$priceInfo['price_id']}/";
	//			$th = $this->_xml->createHTMLElement( 'td', "<a rel='spotlight;width=300px;height=300px;' href='{$link}' title='Add to cart'><input onClick='ayoola.spotLight.showLinkInIframe( \"{$link}\" ); return false;' type='button' value='Add to cart' /></a>" );
				$th = $this->_xml->createHTMLElement( 'td', "<input onClick='ayoola.spotLight.showLinkInIFrame( \"{$link}\" ); return false;' type='button' value='Add to cart' />" );
				$th->setAttribute( 'style', 'text-align:right;' );
				$th = $tr->appendChild( $th );
 			}
		}
	//	var_export( $priceInfo );
		$this->_xml->appendChild( $span );
    } 
	// END OF CLASS
}
