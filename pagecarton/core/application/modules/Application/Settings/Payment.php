<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_Payment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Payment.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_Payment
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_Payment extends PageCarton_Settings
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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$settings = unserialize( @$values['settings'] );
		$settings = @$values['data'] ? : unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;
 
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'default_currency', 'label' => 'Default Currency', 'required' => 'required', 'description' => 'Default Currency', 'type' => 'InputText', 'value' => @$settings['default_currency'] ) );
		$fieldset->addLegend( 'Currency Settings' );
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
		$filter = new Ayoola_Filter_SelectListArray( 'checkoutoption_name', 'checkoutoption_name');    
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'allowed_payment_options', 'label' => 'Available Payment Methods <a rel="spotlight" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Subscription_Checkout_List/">(Options)</a>', 'type' => 'Checkbox', 'value' => @$settings['allowed_payment_options'] ), $options ); 	
		$fieldset->addElement( array( 'name' => 'order_notes', 'label' => 'Please enter a message to always display to user while checking out.', 'type' => 'TextArea', 'value' => @$settings['order_notes'] ) );         	
		$fieldset->addElement( array( 'name' => 'order_confirmation_message', 'label' => 'Order Confirmation Message', 'type' => 'TextArea', 'value' => @$settings['order_confirmation_message'] ) );         	
		
		//	Order form configuration
		$options = new Ayoola_Form_Table(); 
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'order_form', 'label' => 'Select order form <a rel="spotlight" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Form_List/">(Forms)</a>', 'type' => 'Select', 'value' => @$settings['order_form'] ), array( '' => 'Please select...' ) + $options ); 	
		$fieldset->addLegend( 'Payment Options' );
		
		
		$form->addFieldset( $fieldset );
		
		
		//	surcharges like taxes and shipping etc
		$fieldset = new Ayoola_Form_Element;
		
		$i = 0;
		//	Build a separate demo form for the previous group
		$surchargeForm = new Ayoola_Form( array( 'name' => 'surcharges...' )  );
		$surchargeForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
		$surchargeForm->wrapForm = false;
		
		do
		{
			
			//	Put the questions in a separate fieldset
			$surchargeFieldset = new Ayoola_Form_Element; 
			$surchargeFieldset->allowDuplication = true;
			$surchargeFieldset->duplicationData = array( 'add' => '+ Add New Surcharge Below', 'remove' => '- Remove Above Surcharge', 'counter' => 'subgroup_counter', );
			$surchargeFieldset->container = 'div';   
			
			$surchargeFieldset->addElement( array( 'name' => 'surcharge_title', 'label' => ' ', 'placeholder' => 'Title e.g. Sales Tax', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$settings['surcharge_title'][$i] ) );
		//	$surchargeFieldset->addElement( array( 'name' => 'surcharge_fixed_constant_rate', 'label' =>  ' ', 'placeholder' => 'Constant Rate e.g. 500', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$settings['surcharge_fixed_constant_rate'][$i] ) );
			$surchargeTypes = array(
				'' => 'Select Surcharge Type...',
				'percentage' => 'Percentage of Total Order',
				'constant' => 'Fixed (Constant) Amount',
				'not-calculated' => 'Not Calculated Automatically',
			);
			$surchargeFieldset->addElement( array( 'name' => 'surcharge_type', 'label' =>  ' ', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$settings['surcharge_type'][$i] ), $surchargeTypes );   
			$surchargeFieldset->addElement( array( 'name' => 'surcharge_value', 'label' => ' ', 'placeholder' => 'Surcharge Value', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$settings['surcharge_value'][$i] ) );  
									
			$i++;
			$surchargeFieldset->addLegend( 'Surchage  <span name="subgroup_counter">' . $i . '</span> of <span name="subgroup_counter_total">' . ( ( count( @$settings['surcharge_title'] ) ) ? : 1 ) . '</span>' );			   			
			$surchargeForm->addFieldset( $surchargeFieldset );
		//	self::v( $i );  
		}
		while( isset( $settings['surcharge_title'][$i] ) );
		
		
		//	Put the questions in a separate fieldset
	//	$categoryFieldset = new Ayoola_Form_Element; 
	//	$categoryFieldset->allowDuplication = false;
	//	$categoryFieldset->placeholderInPlaceOfLabel = true;
	//	$categoryFieldset->container = 'span';
	//	var_export( $settings );
		
		//	add previous categories if available
		$fieldset->addElement( array( 'name' => 'group', 'type' => 'Html', 'value' => '' ), array( 'html' => $surchargeForm->view(), 'fields' => 'surcharge_title,surcharge_type,surcharge_value' ) );

		$fieldset->addLegend( 'Surcharges - Taxes, etc.' );
		
		
		$form->addFieldset( $fieldset );

				
//		var_export( $fieldsets );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
