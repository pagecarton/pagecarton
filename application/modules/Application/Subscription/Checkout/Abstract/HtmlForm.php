<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Abstract_HtmlForm
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HtmlForm.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Abstract_HtmlForm
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_Abstract_HtmlForm extends Application_Subscription_Checkout_Abstract
{

	
    /**
     * Api Name
     * 
     * @var string
     */
	protected static $_apiName;

    /**
     * Form Action
     * 
     * @var string
     */
	protected static $_formAction;
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => 'NGN', 'blacklist' => 'ALL' );
	
    /**
     * Full info of known currencies
     * 
     * @var array
     */
	protected static $_currencyInfo = array( 'ALL' => 'All Currencies Worldwide', 'NGN' => 'Nigerian Naira', 'USD' => 'United States Dollars' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		self::$_apiName = array_pop( explode( '_', get_class( $this ) ) );
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		if( ! self::isValidCurrency() )
		{  
			$this->setViewContent( "<p class='badnews'>ERROR - Invalid Currency ({$values['settings']['currency_abbreviation']}).  " . static::$_apiName . " does not process this currency type.</p>" ); 
			$this->setViewContent( "<p class='badnews'>Please select other payment methods.</p>" ); 
			return;  
		}
		$this->setViewContent( '<p></p><h4>You have selected ' . self::$_apiName . '.</h4>' );
		$this->setViewContent( $this->getForm()->view() );
    } 
	
    /**
     * Returns default parameters
     * 
     */
	protected static function getDefaultParameters()
    {		
		//	Initialize array for the POST parameters
		$parameters = array();
		$parameters['notify_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Callback/api/' . self::$_apiName . '/order_id/' . Application_Subscription_Checkout::getOrderNumber( '' . self::$_apiName . '' ) . '/';
		$parameters['success_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/onlinestore/confirmation/get/api/' . self::$_apiName . '/status/1/';
		$parameters['fail_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/onlinestore/confirmation/get/api/' . self::$_apiName . '/status/0/';
		$parameters['edit_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/onlinestore/cart/';
		$parameters['total'] = 0.00;
		$parameters['order_number'] = Application_Subscription_Checkout::getOrderNumber( '' . self::$_apiName . '' );
		$parameters['logo'] = Ayoola_Doc::uriToDedicatedUrl( '/img/logo.png' );
		
	//	self::v( $parameters );
		
		//	$this->setViewContent( $this->getForm()->view() );
		return $parameters;
    } 
	
    /**
     * Returns _formAction
     * 
     */
	protected static function getFormAction()
    {		
		//	$this->setViewContent( $this->getForm()->view() );
		return static::$_formAction;
    } 
	
    /**
     * Creates the form for checkout
     * 
     */
	public function createForm()
    {
		$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => 'true', 'method' => 'POST', 'action' => static::getFormAction(), 'accept-charset' => "utf-8" ) );  
		$fieldset = new Ayoola_Form_Element();	
		$fieldset->hashElementName = false; 
		foreach( static::buildRequest() as $key => $value )
		{
			$fieldset->addElement( array( 'name' => $key, 'type' => 'Hidden', 'value' => $value ) );
			$fieldset->addRequirement( $key, array( 'DefiniteValue' => $value ) );
		}
		$fieldset->addElement( array( 'name' => 'voguepaycheckout', 'value' => 'Pay with ' . static::$_apiName, 'type' => 'Submit' ) );
		$fieldset->addLegend( 'Continue with ' . static::$_apiName );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    }
	// END OF CLASS
}
