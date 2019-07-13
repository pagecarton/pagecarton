<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
	//	var_export( $this->getParameter() );
		
		self::$_apiName = $this->getParameter( 'checkoutoption_name' ) ? : array_pop( explode( '_', get_class( $this ) ) );
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		if( ! self::isValidCurrency() )
		{  
			$this->setViewContent( "<p class='badnews'>ERROR - Invalid Currency ({$values['settings']['currency_abbreviation']}).  " . static::$_apiName . " does not process this currency type.</p>" ); 
			$this->setViewContent( "<p class='badnews'>Please select other payment methods.</p>" ); 
			return;  
		}
	//	$this->setViewContent( self::__( '<p></p><h4></h4>' ) );
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
	//	self::$_apiName = self::$_apiName ? : ( $this->getParameter( 'checkoutoption_name' ) ? : array_pop( explode( '_', get_class( $this ) ) ) );
		$parameters['notify_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/tools/classplayer/get/object_name/Application_Subscription_Checkout_Callback/api/' . self::$_apiName . '/order_id/' . Application_Subscription_Checkout::getOrderNumber( '' . self::$_apiName . '' ) . '/';
		$parameters['success_url'] = 'http://' . Ayoola_Page::getDefaultDomain() .  '/widgets/Application_Subscription_Checkout_Confirmation/api/' . self::$_apiName . '/status/1/';
		$parameters['fail_url'] = 'http://' . Ayoola_Page::getDefaultDomain() .  '/widgets/Application_Subscription_Checkout_Confirmation/api/' . self::$_apiName . '/status/0/';
		$parameters['edit_url'] = 'http://' . Ayoola_Page::getDefaultDomain() . '/onlinestore/cart/';
		$parameters['total'] = 0.00;
		$parameters['product_name'] = '';
		$parameters['product_description'] = '';   
		$parameters['order_number'] = Application_Subscription_Checkout::getOrderNumber( '' . self::$_apiName . '' );
		$parameters['logo'] = Ayoola_Doc::uriToDedicatedUrl( '/img/logo.png' );
		$parameters['customer_email'] = Ayoola_Application::getUserInfo( 'email' );
		
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
     * Returns _formAction
     * 
     */
	static function checkStatus( $orderNumber )
    {		
		$table = Application_Subscription_Checkout_Order::getInstance();
		if( ! $orderInfo = $table->selectOne( null, array( 'order_id' => $orderNumber ) ) )
		{
			return false;
		}
		//	Code to change check status goes heres
	//	if( )
		return true;
    } 
	
    /**
     * Creates the form for checkout
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		$formAttributes = array();
		$formFields = array();
		if( $parameters = $this->getParameter() )
		{
			if( is_array( $parameters['form_attribute_name'] ) && is_array( $parameters['form_attribute_value'] ) )
			{
				$formAttributes = array_combine( $parameters['form_attribute_name'], $parameters['form_attribute_value'] );
			}
			if( is_array( $parameters['default_form_field_name'] ) && is_array( $parameters['default_form_field_value'] ) )
			{
				$formFields = array_combine( $parameters['default_form_field_name'], $parameters['default_form_field_value'] );
			}
			$defaultParameters = Application_Subscription_Checkout_Abstract_HtmlForm::getDefaultParameters();
			if( ! $cart = self::getStorage()->retrieve() ){ return; }
			$values = $cart['cart'];
		//	var_export( $values );
			foreach( $values as $name => $value )
			{
				if( ! isset( $value['price'] ) )
				{
					$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
				}  
				@$defaultParameters['product_name'] .= '  ' . $value['multiple'] . ' x ' . ( $value['subscription_label'] ? : $value['subscription_name'] ) . ' // ';
				@$defaultParameters['product_description'] .= ' ' .  @$value['subscription_description'] . '... ';
				@$defaultParameters['total'] += $value['price'] * $value['multiple'];
				@$counter++;
			}
			if( ! empty ( $parameters['default_parameter_fields'] ) )
			foreach( $parameters['default_parameter_fields'] as $key => $value )
			{
				$formFields[$parameters['custom_parameter_fields'][$key]] = $defaultParameters[$parameters['default_parameter_fields'][$key]];
			}
		}
		
		$form = new Ayoola_Form( $formAttributes + array( 'name' => $this->getObjectName(), 'data-not-playable' => 'true', 'method' => 'POST', 'action' => static::getFormAction(), 'accept-charset' => "utf-8" ) );  
		$fieldset = new Ayoola_Form_Element();	
		$fieldset->hashElementName = false; 
		$formFields = $formFields ? : static::buildRequest();
		$fieldset->addElement( array( 'name' => 'xxx', 'type' => 'Html', 'value' => '' ), array( 'html' => @$parameters['checkoutoption_logo'] ) );
		foreach( $formFields as $key => $value )
		{
			$fieldset->addElement( array( 'name' => $key, 'type' => 'Hidden', 'value' => $value ) );
			$fieldset->addRequirement( $key, array( 'DefiniteValue' => $value ) );
		}
		$fieldset->addLegend( 'You have selected ' . self::$_apiName );
		$form->addFieldset( $fieldset );
		$form->submitValue = 'Continue with ' . static::$_apiName;
		$this->setForm( $form );
    }
	// END OF CLASS
}
