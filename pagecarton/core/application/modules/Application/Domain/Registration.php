<?php
/**
 * PageCarton Content Management System
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
		//	var_export( $this->getObjectStorage()->retrieve() );
/*  			$parameters = $this->getObjectStorage()->retrieve();
			$class = 'Application_Domain_Registration_CheckAvailability';			
	//		if( false !== @$this->getParameter( $class ) )
			{ 
				$class = new $class();
				$class->initOnce();
		//		$this->setViewContent( $class->view(), true );
			}
		//	var_export( $parameters );
/* 			if( ! empty( $parameters['suggestions'] ) ){ $this->setParameter( $parameters ); }
			else
			{
				return;
			}
 */		//	$this->setParameter( array( 'suggestions' => array( 'abc.com' ) ) );
 		//	$this->setViewContent( '<h2>Avalaible domain name(s)</h2>' );
			$this->createForm( 'Register', 'Register domain name' );
/* 			var_export( $this->getParameter() );
			exit();
 */			$this->setViewContent( $this->getForm()->view() );
		//	var_export( $this->getObjectStorage()->retrieve() );
	//		var_export( __LINE__ );
			if( ! $values = $this->getForm()->getValues() )
			{ 
			//	var_export( $this->getForm()->getBadnews() );
				return false; 
			}
			
			//	Register the selection in the shopping cart
			
			
			
	//		var_export( $values );
		//	$class->init();
			$this->subscribe( $values );
			
			
			
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews boxednews centerednews">' . $e->getMessage() . '</p>' ); 
			return false; 
		}		
		
    } 
	
    /**
     * 
     * 
     */
/* 	public function subscribe( array $values )
    {
		if( ! empty( $values['options'] ) )
		{
			foreach( $values['options'] as $each )
			{
				$class = new Application_Subscription();
				$class->setIdentifier( array( 'subscription_name' => $each ) );
				$class->subscribe( $values );
			}
		}
		$this->setViewContent( Application_Subscription::getConfirmation(), true );
    } 
 */	
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
				$values['checkout_requirements'] = "billing_address";
				
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
		$this->setViewContent( Application_Subscription::getConfirmation(), true );
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
				throw new Ayoola_Object_Exception( 'INVALID CLASS: ' . $mode );
			}
			$class = new $mode();
		//	if( ! method_exists( $class, 'createForm' ) ){ continue; }
			$fieldsets = $class->getForm()->getFieldsets();
			$form->actions += $class->getForm()->actions;
		//	var_export( $form->actions );
 		//	var_export( $this->getParameter() );
		//	exit();
			foreach( $fieldsets as $fieldset )
			{
			//	$fieldset->appendElement = false;
				$fieldset->getLegend() ? : $fieldset->addLegend( 'Domain name registration' );
		//		$fieldset->addElement( array( 'type' => 'html', 'name' => 'e' ), array( 'html' => '<div class="goodnews">' . self::$_requirementOptions[$each]['goodnews'] . '</div>' ) );
				$form->addFieldset( $fieldset );
			}
		}
/* //		if( $this->getParameter( 'suggestions' ) )
		{
		
			//	Domain options
		//	@$_POST['options'] = $_POST['options'] ? : $_POST[Ayoola_Form::hashElementName( 'options' )];
			$optionValue = $this->getGlobalValue( 'options' );
			$parameters = $this->getObjectStorage()->retrieve();
		//	var_export( $optionValue );
			if( $optionValue )
			{
				$parameters['options'] = $optionValue;
				$this->getObjectStorage()->store( $parameters );
			}
			elseif( @$parameters['options'] && ! $_POST )
			{
				$optionValue = $parameters['options'];
			}
			

 			$fieldset = new Ayoola_Form_Element;		
			$options = array( 'domain-name-registration' => 'Domain Name Registration: Register the selected domain names with us.', 'website-hosting' => 'Website Hosting: You need to host your domain before it can be accessible on the World Wide Web.', 'website-design' => 'Website Design: Employ the services of our professionals to design your website.	' );
			$fieldset->addElement( array( 'name' => 'options', 'label' => 'Please select appropriate domain related services you would subscribe for:', 'description' => 'Please select appropriate services.', 'type' => 'Checkbox', 'value' => @$optionValue ? : $values['options'] ), $options );
			$fieldset->addRequirement( 'options', array( 'NotEmpty' => null ) );
			$fieldset->addLegend( 'Website hosting and other related services:' );
			$form->addFieldset( $fieldset );
			//	var_export( $_POST );
			if( @$parameters['options'] )
			foreach( $parameters['options'] as $each )
			{
			//	if( ! is_array( $_POST['options'] ) || ! in_array( $key, $_POST['options'] ) ){ continue; }
			//	var_export( $_POST['options'] );
// 				var_export( $each );
//				exit();
 				$class = new Application_Subscription( array( 'no_init' => true ) );
		//		$class->setSe( array( 'subscription_name' => $each ) );
				$class->setIdentifier( array( 'subscription_name' => $each ) );
			//	$options = $options->getForm()->getFieldsets();
			//	var_export( $each );
			//	$fieldset->addElement( array( 'name' => 'webhosting', 'label' => 'Web Hosting', 'type' => 'Radio', 'value' => $options ) );
			//	$fieldset->addLegend( $legend );
				$fieldsets = $class->getForm()->getFieldsets();
		//		var_export( $fieldsets );
			//	var_export( $this->getParameter() );
			//	exit();
				foreach( $fieldsets as $fieldset )
				{
				//	$fieldset->appendElement = false;
					$fieldset->getLegend() ? : $fieldset->addLegend( 'Register a domain name' );
			//		$fieldset->addElement( array( 'type' => 'html', 'name' => 'e' ), array( 'html' => '<div class="goodnews">' . self::$_requirementOptions[$each]['goodnews'] . '</div>' ) );
					$form->addFieldset( $fieldset );
				}
				
			}
 		
			
		}
 */		
		if( $this->getGlobalValue( 'suggestions' ) || $this->getGlobalValue( 'unavailable' ) )
		{
			$suggestions = array_merge( $this->getGlobalValue( 'suggestions' ) ? : array(), $this->getGlobalValue( 'unavailable' ) ? : array() );
		//		var_export( $this->getGlobalValue( 'unavailable' ) );
		//		var_export( $suggestions );
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
			//	$options = range( 1, 5 );
			//	$options = array_combine( $options, $options );
				foreach( range( 1, 5 ) as $value )
				{
				//	unset( $options[$key] );
					$period = $value < 2 ? 'year' : 'years';
					$options[$value] = $value . ' ' . $period . ' (' . $filter->filter( $price * $value ) . ') ';
				}
				$fieldset->addElement( array( 'name' => 'no_of_yrs_for_' . $each, 'label' => 'Length', 'type' => 'Select', 'value' => @$values['no_of_yrs_for_' . $each] ), $options );
				$fieldset->addRequirement( 'no_of_yrs_for_' . $each, array( 'NotEmpty' => null ) );
			
				$options = array( 'No', 'Yes' => 'Yes' );
			//	var_export( $domainSettings['domain_registration_options'] );
			//	var_export( $domainSettings );
				$options = array();
				if( array_key_exists( 'domain_registration_options', $domainSettings ) && in_array( 'domain_auto_renewal', $domainSettings['domain_registration_options'] ) )
				{
					$options += array( 'domain_auto_renewal' => 'Auto renew: Protect your domain from accidental expiration. (' . $filter->filter( 0 ) . '/yr)' );
			//		$fieldset->addElement( array( 'name' => 'domain_auto_renew_for_' . $each, 'label' => 'Auto renew: Protect your domain from accidental expiration', 'type' => 'Select', 'value' => @$values['domain_auto_renew_for_' . $each] ), $options );
			//		$fieldset->addRequirement( 'domain_auto_renew_for_' . $each, array( 'NotEmpty' => null ) );
				}
				if( array_key_exists( 'domain_registration_options', $domainSettings ) && in_array( 'private_domain_registration', $domainSettings['domain_registration_options'] ) )
				{
					$options += array( 'private_domain_registration' => 'Private Registration: shield your personal information from the public while preserving your rights. (' . $filter->filter( @$domainSettings['private_domain_registration_price'] ) . '/yr)' );
		//			$fieldset->addElement( array( 'name' => 'domain_privacy_for_' . $each, 'label' => 'Private Registration: shield your personal information from the public while preserving your rights.', 'type' => 'Select', 'value' => @$values['domain_privacy_for_' . $each] ), $options );
			//		$fieldset->addRequirement( 'domain_privacy_for_' . $each, array( 'NotEmpty' => null ) );
				}
				$fieldset->addElement( array( 'name' => 'options_for_' . $each, 'label' => 'Other additional service options for ' . $each, 'type' => 'Checkbox', 'value' => @$values['options_for_' . $each] ), $options );
				
				$fieldset->addLegend( '<strong>' . $each . '</strong>: Select the number of years of domain registration and other service options for ' . $each );
				$form->addFieldset( $fieldset );
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
		//		require_once 'Ayoola/Filter/SelectListArray.php';
		//		$filter = new Ayoola_Filter_SelectListArray( 'subscription_name', 'subscription_label'); 
		//		$options1 = $filter->filter( $options );
				if( $newOption )
				{
					$fieldset = new Ayoola_Form_Element;	
					$fieldset->addElement( array( 'name' => 'optional_subscriptions', 'label' => 'Optional subscriptions', 'type' => 'Checkbox', 'value' => @$values['optional_subscriptions'] ), $newOption );
					$fieldset->addLegend( '<strong>Recommended</strong> Services: You might also be interested in the following service options ' );
					$form->addFieldset( $fieldset );				
/* 					if( $this->getGlobalValue( 'optional_subscriptions' ) )
					{
						foreach( $this->getGlobalValue( 'optional_subscriptions' ) as $eachSubscription )
						{
							$class = new Application_Subscription( array( 'no_init' => true ) );
							$class->setIdentifier( array( 'subscription_name' => $eachSubscription ) );
							$fieldsets = $class->getForm()->getFieldsets();
							foreach( $fieldsets as $fieldset )
							{
						//		$fieldset->getLegend() ? : $fieldset->addLegend( 'Register a domain name' );
								$form->addFieldset( $fieldset );
							}
						}
					}
 */				}
				
			}
			
		}
		//		var_export( $this->getGlobalValue( 'unavailable' ) );
		//	Register how many yrs
				$requirements = array( 
								//		array( 'requirement' => 'user-registration' ), 
							//			array( 'requirement' => 'address', 'requirement_legend' => 'Billing Address', 'parameters' => array( 'location_prefix' => 'billing_address' ), 'requirement_goodnews' => 'Please provide a valid billing address. If you are paying with a debit or credit card, you must ensure this information matches the one listed with your card issuer.' ), 
										array( 'requirement' => 'address', 'requirement_legend' => 'Domain Contact', 'parameters' => array( 'location_prefix' => 'domain_contact' ), 'requirement_goodnews' => 'Provide information for the domain WHOIS contact information. This information will not be used if you select the private domain registration option.' ), 
										array( 'requirement' => 'phone-number' ), 
										array( 'requirement' => 'email-address' ), 
									);
	//	var_export( $requirements );
		$form->setFormRequirements( $requirements );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
