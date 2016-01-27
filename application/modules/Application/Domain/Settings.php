<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Domain_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Settings extends Application_Settings_Abstract
{
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		$values = unserialize( @$values['settings'] );
//		var_export( $values );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;
	//	$form->oneFieldSetAtATime = true;
		
		//	domain options
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addLegend( 'Domain Options' );
		$options = array( 'sub_domains' => 'Allow sub-domains. (Requires wildcard domains on the web-server)', ) + array( 'domain_registration' => 'Allow domain name registration.', );
		$fieldset->addElement( array( 'name' => 'domain_options', 'label' => 'Domain Options', 'type' => 'Checkbox', 'value' => @$values['domain_options'] ), $options );
	//	$form->addFieldset( $fieldset );
		
		//	Domain name reg
		if( @in_array( 'domain_registration', $this->getGlobalValue( 'domain_options' ) ) )
		{
		//	$fieldset = new Ayoola_Form_Element;
			$fieldset->addLegend( 'Domain Registration Options' );
			$options = array( 'private_domain_registration' => 'Allow Private Domain Registration', 'domain_auto_renewal' => 'Allow Auto Renewal of Domain Names', );
			$fieldset->addElement( array( 'name' => 'domain_registration_options', 'label' => 'Domain Registration Options', 'type' => 'Checkbox', 'value' => @$values['domain_registration_options'] ), $options );
			$fieldset->addElement( array( 'name' => 'domain_name_default_price', 'type' => 'InputText', 'placeholder' => '0.00', 'value' => @$values['domain_name_default_price'] ) );
			$fieldset->addRequirement( 'domain_name_default_price', array( 'NotEmpty' => null ) );
	//		$fieldset->addRequirement( 'domain_name_default_price', array( 'NotEmpty' => null, 'WordCount' => array( 100, 1000 ) ) );
			if( @in_array( 'private_domain_registration', $this->getGlobalValue( 'domain_registration_options' ) ) )
			{
				$fieldset->addElement( array( 'name' => 'private_domain_registration_price', 'type' => 'InputText', 'placeholder' => '0.00', 'value' => @$values['private_domain_registration_price'] ) );
				$fieldset->addRequirement( 'private_domain_registration_price', array( 'NotEmpty' => null ) );
			//	$fieldset->addRequirement( 'private_domain_registration_price', array( 'NotEmpty' => null, 'WordCount' => array( 100, 1000 ) ) );
			} 
			
			//	OPTIONAL ADDITIONAL SUBSCRIPTIONS
			$options = new Application_Subscription_Subscription;
			$options = $options->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'subscription_name', 'subscription_label');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'optional_subscriptions', 'type' => 'Checkbox', 'value' => @$values['optional_subscriptions'] ), $options ); 
	//		$form->addFieldset( $fieldset );
		}
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
