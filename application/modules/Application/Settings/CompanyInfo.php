<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_CompanyInfo
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: CompanyInfo.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_CompanyInfo
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_CompanyInfo extends Application_Settings_Abstract
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
		
	//	var_export( $settings );
		
		//	Company Info
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
	//	$fieldset->addElement( array( 'name' => 'full_address', 'placeholder' => 'e.g. 119 Ring Road, Ibadan, Oyo State, Nigeria.', 'label' => 'Full Address', 'value' => @$settings['full_address'], 'type' => 'InputText' ) );
		$class = new Application_User_UserLocation_Creator( array( 'form_preset_values' => $settings, 'no_init' => true ) );
		if( $fieldsets = $class->getForm()->getFieldsets() )
		{
			foreach( $fieldsets as $each )
			{
				$form->addFieldset( $each );  
			}
		}
		
		$fieldset->addLegend( 'Contact Information' );
		$form->addFieldset( $fieldset );
		$form->setFormRequirements( 'address' );
	//	$form->addFieldset( $fieldset );
				
//		var_export( $fieldsets );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
