<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert		Ayoola
 * @package    	Application_Domain_Registration
 * @copyright  	Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    	http://pagecarton.com/about/license
 * @version    	$Id: Registration.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Registration
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration extends Application_Domain_Registration_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Domain Name Search';      
	
    /**
     * 
     * 
     */
	public function init()
    {
		try
		{
			$this->createForm( 'Continue Registration', 'Register domain name' );
 			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() )
			{ 
				return false; 
			}
			$this->subscribe( $values );  

			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . ( $this->getParameter( 'url_to_go' ) ? : '/cart' ) );
			exit();
			
			
			
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( self::__( '<p class="badnews boxednews centerednews">' . $e->getMessage() . '</p>' ) ); 
			return false; 
		}		
		
    } 
	
    /**
     * 
     * 
     */
	public function subscribe( array $values )
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$suggestions = array_merge( @$values['suggestions'] ? : array(), @$values['unavailable'] ? : array() );
	//	var_export( $this->getGlobalValue( 'suggestions' ) );
	//	var_export( $values );
		if( ! empty( $suggestions ) )
		{
			foreach( $suggestions as $each )
			{
				$sub = array_map( 'trim', explode( '.', $each ) );
				
				//	the first is the domain
				array_shift( $sub );
			
				//	the remaining is subdomain
				$tld = implode( '.', $sub );
				$price = self::getTldPrice( $tld );
				$class = new Application_Subscription();
				
				//	Domain Reg
				$values['domain_name'] = $each;
				$values['subscription_name'] = "{$each} (Domain Name Registration)";
				$values['subscription_label'] = $values['subscription_name'];
			//	$values['item'] = $values['account_id'];
				$values['price'] = $price;
		//		$values['currency_abbreviation'] = $values['currency_abbreviation'];
				$values['cycle_name'] = 'Per/Year';
				$values['cycle_label'] = 'year(s)';
				$values['price_id'] = $each . '_' . $values['price'];
				$values['subscription_description'] = "Domain name registration charges for ({$each})";
				$values['url'] = "javascript:;";
				$values['callback'] = "Application_Domain_Order_Process";
			//	$values['checkout_requirements'] = "billing_address";
				
				//	After we checkout this is where we want to come to
				$values['classplayer_link'] = "/tools/classplayer/get/object_name/Application_Domain_Registration/";
				$values['object_id'] = $each;
				$values['multiple'] = $values['no_of_yrs_for_' . $each];
				$class->subscribe( $values );
				
				//	PRIVATE REG
				$domainSettings = Application_Settings_Abstract::getSettings( 'Domains' ) ? : array();
			//	var_export( $domainSettings['domain_registration_options'] );
				if( @$values['options_for_' . $each] && in_array( 'private_domain_registration', $values['options_for_' . $each] ) )
				{
					$values['subscription_name'] = "{$each} (Private Registration)";
					$values['subscription_label'] = $values['subscription_name'];
					$values['price'] = $domainSettings['private_domain_registration_price'];
			//		$values['currency_abbreviation'] = $values['currency_abbreviation'];
					$values['cycle_name'] = 'Per/Year';
					$values['cycle_label'] = 'year(s)';
					$values['price_id'] = $each . '_private_';
					$values['subscription_description'] = "Private Registration: shield your personal information from the public while preserving your rights. For ({$each})";
					$values['url'] = "javascript:;";
					
					//	After we checkout this is where we want to come to
					$values['classplayer_link'] = "/tools/classplayer/get/object_name/Application_Domain_Registration/";
				//	$values['object_id'] = $each;
					$values['multiple'] = $values['no_of_yrs_for_' . $each];
					$class->subscribe( $values );
				}
				
				
			}
		}

	//	$this->setViewContent( Application_Subscription::getConfirmation(), true );
    } 
	
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
	//	$form->formNamespace = get_class( $this ) . md5( @array_pop( $this->getParameter( 'suggestions' ) ) );
		
		//	Check availability first
		$mode = 'Application_Domain_Registration_CheckAvailability';
		if( $mode )
		{
			if( ! Ayoola_Loader::loadClass( $mode ) )
			{
				return false;
			//	throw new Ayoola_Object_Exception( 'INVALID CLASS: ' . $mode );
			}
			$class = new $mode( $this->getParameter() );
		//	if( ! method_exists( $class, 'createForm' ) ){ continue; }
			$fieldsets = $class->getForm()->getFieldsets();
			$form->actions += $class->getForm()->actions;
			foreach( $fieldsets as $fieldset )
			{
		//		$fieldset->getLegend() ? : $fieldset->addLegend( 'Domain name registration' );
				$form->addFieldset( $fieldset );
			}
		}
		if( $this->getGlobalValue( 'suggestions' ) || $this->getGlobalValue( 'unavailable' ) )
		{ 
			$suggestions = array_merge( $this->getGlobalValue( 'suggestions' ) ? : array(), $this->getGlobalValue( 'unavailable' ) ? : array() );
			$domainSettings = Application_Settings_Abstract::getSettings( 'Domains' ) ? : array();
				
			//	Filter the price to display unit in domain price
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
			$filter = new $filter();
			foreach( $suggestions as $each )
			{
				$fieldset = new Ayoola_Form_Element;	
				$sub = array_map( 'trim', explode( '.', $each ) );
				
				//	the first is the domain
				array_shift( $sub );
			
				//	the remaining is subdomain
				$tld = implode( '.', $sub );
				$price = self::getTldPrice( $tld );
			//	$price = $filter->filter( $price );
			
				$options = array();
				foreach( range( 1, 5 ) as $value )
				{
				//	unset( $options[$key] );
					$period = $value < 2 ? 'year' : 'years';
					$priceText = $price ? ' (' . $filter->filter( $price * $value ) . ') ' : null;
					$options[$value] = $value . ' ' . $period . $priceText;
				}
				$fieldset->addElement( array( 'name' => 'no_of_yrs_for_' . $each, 'label' => 'Length', 'type' => 'Select', 'value' => @$values['no_of_yrs_for_' . $each] ), $options );
		//		$fieldset->addRequirement( 'no_of_yrs_for_' . $each, array( 'NotEmpty' => null ) );
			
				$selectOption = array( 'No', 'Yes' => 'Yes' );
				$options = array();
				{
					$options += array( 'domain_auto_renewal' => 'Auto renew: Protect your domain from accidental expiration. (' . $filter->filter( 0 ) . '/yr)' );
				}
				{
					$options += array( 'private_domain_registration' => 'Private Registration: shield your personal information from the public while preserving your rights. (' . $filter->filter( @$domainSettings['private_domain_registration_price'] ) . '/yr)' );
				}
				$options ? $fieldset->addElement( array( 'name' => 'options_for_' . $each, 'label' => 'Other additional service options for ' . $each, 'type' => 'Checkbox', 'value' => @$values['options_for_' . $each] ), $options ) : null;
				
				$fieldset->addLegend( '<strong>' . $each . '</strong>: Select the number of years of domain registration and other service options for ' . $each );
			//	$form->addFieldset( $fieldset );
			}
			if( @$domainSettings['optional_subscriptions'] )  
			{
				$options = new Application_Subscription_Price;
			//	var_export( $domainSettings['optional_subscriptions'] );
				$options = $options->select( null, array( 'subscription_name' => $domainSettings['optional_subscriptions'] ) );
			//	var_export( $options );
				$newOption = array();
				$priceList = array();
				foreach( $options as $eachSubscription )
				{
					if( isset( $priceList[$eachSubscription['subscription_name']] ) && $priceList[$eachSubscription['subscription_name']] < $eachSubscription['price'] )
					{
						continue;
					}
					$newOption[$eachSubscription['subscription_name']] = '<span title="' . $eachSubscription['subscription_description'] . '"><strong>' . $eachSubscription['subscription_label'] . '</strong>: ' . $eachSubscription['subscription_description'] . ' (From ' . $filter->filter( $eachSubscription['price'] ) . ' ' . $eachSubscription['cycle_name'] . ')</span>';
					$priceList[$eachSubscription['subscription_name']] = $eachSubscription['price'];
				}
				if( $newOption )
				{
					$fieldset = new Ayoola_Form_Element;	
					$fieldset->addElement( array( 'name' => 'optional_subscriptions', 'label' => 'Optional subscriptions', 'type' => 'Checkbox', 'value' => @$values['optional_subscriptions'] ), $newOption );
					$fieldset->addLegend( '<strong>Recommended</strong> Services: You might also be interested in the following service options ' );
					$form->addFieldset( $fieldset );				
 				}
				
			}
			
		}

        $fieldset = new Ayoola_Form_Element;	
        $fieldset->addElement( array( 'name' => 'firstname', 'label' => 'First Name', 'placeholder' => 'e.g. John', 'type' => 'InputText', 'value' => @$values['firstname'] ) );
        $fieldset->addElement( array( 'name' => 'lastname', 'label' => 'Last Name', 'placeholder' => 'e.g. Smith', 'type' => 'InputText', 'value' => @$values['lastname'] ) );
        $fieldset->addElement( array( 'name' => 'organization_name', 'label' => 'Organization Name', 'placeholder' => 'e.g. Sethlene Inc.', 'type' => 'InputText', 'value' => @$values['organization_name'] ) );
        $fieldset->addElement( array( 'name' => 'email', 'label' => 'Contact Email Address', 'placeholder' => 'e.g. email@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) );        

        $country = PageCarton_Country::getInstance()->select();  
        $options = array();
        foreach( $country as $each )
        {
            if( empty( $options[$each['dial_code']] ) )
            {
                $options[$each['dial_code']] = '+' . $each['dial_code'] . ' (' . $each['country_name'] . ')';
            }
            else
            {
                $options[$each['dial_code']] .= ' & ' . ' (' . $each['country_name'] . ')';
            }
        }
        ksort( $options );
        $fieldset->addElement( array( 'name' => 'country_code', 'label' => 'Contact Phone Number', 'placeholder' => '234', 'style' => 'width:50px;', 'type' => 'Select', 'value' => @$values['country_code'] ), $options );
        $fieldset->addElement( array( 'name' => 'phone_number', 'label' => '', 'placeholder' => '8032100555', 'style' => 'width:150px;', 'type' => 'InputText', 'value' => @$values['phone_number'] ) );
        $fieldset->addRequirement( 'firstname', array( 'NotEmpty' => null ) );
        $fieldset->addRequirement( 'lastname', array( 'NotEmpty' => null ) );

        $fieldset->addRequirement( 'email', array( 'EmailAddress' => null ) );
        $fieldset->addRequirement( 'country_code', array( 'NotEmpty' => null, 'Digits' => null ) );
        $fieldset->addRequirement( 'phone_number', array( 'NotEmpty' => null, 'Digits' => null ) );
        $fieldset->addLegend( 'Domain Contact Information' );
        $form->addFieldset( $fieldset );				


		//	Domain Contact
		$fieldset = new Ayoola_Form_Element;	
		$fieldset->addElement( array( 'name' => 'street_address', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 119 State Road', 'type' => 'InputText', 'value' => @$values['street_address'] ) );
		$fieldset->addElement( array( 'name' => 'street_address2', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Apt. H3', 'type' => 'InputText', 'value' => @$values['street_address2'] ) );
		$fieldset->addElement( array( 'name' => 'city', 'label' => 'City', 'placeholder' => 'e.g. Ibadan', 'type' => 'InputText', 'value' => @$values['city'] ) );
		$fieldset->addElement( array( 'name' => 'province', 'label' => 'State/Province', 'placeholder' => 'e.g. OY', 'type' => 'InputText', 'value' => @$values['province'] ) );
		$fieldset->addElement( array( 'name' => 'zip', 'label' => 'Zip/Postal Code', 'type' => 'InputText', 'value' => @$values['zip'] ) );
		$fieldset->addElement( array( 'name' => 'country', 'label' => 'Country', 'type' => 'InputText', 'value' => @$values['country'] ) );
		$fieldset->addRequirement( 'street_address', array( 'NotEmpty' => null ) );
		$fieldset->addRequirement( 'city', array( 'NotEmpty' => null ) );
		$fieldset->addRequirement( 'country', array( 'NotEmpty' => null ) );
		$fieldset->addLegend( 'Domain Address Information' );
		$form->addFieldset( $fieldset );				

		$this->setForm( $form );
    } 
	// END OF CLASS
}
