<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Payment
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Payment.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Payment
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_Payment extends Application_Settings_Abstract
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Settings';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$settings = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'default_currency', 'label' => 'Default Currency', 'required' => 'required', 'description' => 'Default Currency', 'type' => 'InputText', 'value' => @$settings['default_currency'] ) );
		$fieldset->addLegend( 'Currency Settings' );
		$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'google_merchant_id', 'label' => 'Merchant ID', 'value' => $settings['google_merchant_id'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'google_secret_key', 'label' => 'Secret Key', 'value' => $settings['google_secret_key'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Google Checkout' );
		$form->addFieldset( $fieldset );
		
	//	$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'paypal_email', 'label' => 'Paypal Email', 'value' => $settings['paypal_email'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Paypal' );
		$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'voguepay_merchant_id', 'label' => 'Merchant ID', 'value' => @$settings['voguepay_merchant_id'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'VoguePay' );
		$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'simplepay_username', 'label' => 'Username', 'value' => @$settings['simplepay_username'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'SimplePay' );
		$form->addFieldset( $fieldset );
		
		//	payment options
		$fieldset = new Ayoola_Form_Element;
		$options = new Application_Subscription_Checkout_CheckoutOption(); 
		$options = $options->select();
//		var_export( $options );
		foreach( $options as $key => $each )
		{
			$api = 'Application_Subscription_Checkout_' . $each['checkoutoption_name'];
			$options[$key]['checkoutoption_logo'] = $each['checkoutoption_logo'];
		//	if( ! $api::isValidCurrency() ){ unset( $options[$key] ); }
		//	var_export( $api );
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'checkoutoption_name', 'checkoutoption_logo');
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'allowed_payment_options', 'label' => 'Please select the most convenient payment option for you to receive.', 'type' => 'Checkbox', 'value' => @$settings['allowed_payment_options'] ), $options ); 	
		$fieldset->addElement( array( 'name' => 'order_notes', 'label' => 'Please enter a message to always display to user while checking out.', 'type' => 'TextArea', 'value' => @$settings['order_notes'] ) ); 	
		$fieldset->addLegend( 'Payment Options' );
		
		
		$form->addFieldset( $fieldset );

/* 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'company_name', 'placeholder' => 'E.g. SkyLine Limited', 'label' => 'Company, Organization or Website Name', 'value' => @$settings['company_name'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'about_us', 'placeholder' => 'Enter a short description about this organization or website...', 'value' => @$settings['about_us'], 'type' => 'TextArea' ) );
		$fieldset->addLegend( 'Company Information' );
		$form->addFieldset( $fieldset );
		
		//	timezone
		$storage = $this->getObjectStorage( array( 'id' => 'timezones', 'device' => 'File', 'time_out' => 1640000, ) ); 

	//	if( ! $storage->retrieve() ) 
		{
			$timezones = array();
			$offsets = array();
			$now = new DateTime();

			foreach (DateTimeZone::listIdentifiers() as $timezone) {
				$now->setTimezone(new DateTimeZone($timezone));
				$offsets[] = $offset = $now->getOffset();
				
				//	Get gmt offset
				$hours = intval($offset / 3600);
				$minutes = abs(intval($offset % 3600 / 60));
				
				//	Name
				$name = $timezone;
				$name = str_replace('/', ', ', $name);
				$name = str_replace('_', ' ', $name);
				$name = str_replace('St ', 'St. ', $name);				
				$timezones[$timezone] = $name . ' (' . 'GMT' . ( $offset ? sprintf('%+03d:%02d', $hours, $minutes) : '' ) . ') ';
			}

			array_multisort($offsets, $timezones);
			$storage->store( $timezones );
		}
		$fieldset->addElement( array( 'name' => 'time_zone', 'label' => 'Time Zone', 'value' => @$settings['time_zone'], 'type' => 'Select' ), $storage->retrieve() );
		
		//	Contact
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'phone_number', 'placeholder' => 'e.g. +234-803-123-1234', 'label' => 'Phone Number', 'value' => @$settings['phone_number'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'email', 'placeholder' => 'e.g. info@OurWebsite.com', 'label' => 'E-mail', 'value' => @$settings['email'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'full_address', 'placeholder' => 'e.g. 119 Ring Road, Ibadan, Oyo State, Nigeria.', 'label' => 'Full Address', 'value' => @$settings['full_address'], 'type' => 'InputText' ) );

		$fieldset->addLegend( 'Contact Information' );
		$form->addFieldset( $fieldset );
 */	//	$form->addFieldset( $fieldset );
				
//		var_export( $fieldsets );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
