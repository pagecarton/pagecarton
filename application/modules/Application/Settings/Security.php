<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Security
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Security.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Security
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_Security extends Application_Settings_Abstract
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
		$settings = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		//	Option to reset keys
	//	$html = '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Api_Reset/">Reset Keys</a>';
	//	$option = array( 'html' => $html );
	//	$fieldset->addElement( array( 'name' => 'reset_keys', 'type' => 'Html' ), $option );
		
		//	Company Info
	//	$fieldset->addElement( array( 'name' => 'private_key', 'label' => 'Private Key', 'disabled' => 'disabled', 'value' => @$settings['private_key'], 'type' => 'InputText' ) );
	//	$fieldset->addElement( array( 'name' => 'public_key', 'label' => 'Public Key', 'value' => @$settings['public_key'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'application_salt', 'description' => 'Enter a random value to use to produce hashes', 'value' => @$settings['application_salt'], 'type' => 'InputText' ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 12, 100 ) ) );
	//	$fieldset->addElement( array( 'name' => 'application_id', 'disabled' => 'disabled', 'value' => @$settings['application_id'], 'type' => 'InputText' ) );
	//	$fieldset->addRequirement( 'application_id', array( 'Int' => null ) );
	//	$fieldset->addElement( array( 'name' => 'random_key', 'label' => 'Random Key', 'value' => @$settings['random_key'], 'type' => 'InputText' ) );
		$options = array( 'allow' => 'Allow connection to this app?', 'pre-register' => 'Force pre-registration' );
		$fieldset->addElement( array( 'name' => 'options', 'label' => 'Options', 'value' => @$settings['options'], 'type' => 'Checkbox' ), $options );
	//	$fieldset->addRequirement( 'options', array( 'ArrayKeys' => $options ) );
		$fieldset->addLegend( 'Security Settings' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
