<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_RequestOtherInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: RequestOtherInfo.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_RequestOtherInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_RequestOtherInfo extends Application_User_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true; 
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init() 
    {
		$this->createForm( 'Continue...', 'Optional Information' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
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
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$form->formNamespace = get_class( $this ) . $values['user_id'];
		//$form->setCaptcha( true ); // Adds captcha
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;  
		$fieldset->addElement( array( 'name' => 'referral_name', 'type' => 'InputText', 'value' => @$values['referral_name'] ) );
		$fieldset->addElement( array( 'name' => 'referral_phone_number', 'type' => 'InputText', 'value' => @$values['referral_phone_number'] ) );
		$fieldset->addElement( array( 'name' => 'referral_email', 'type' => 'InputText', 'value' => @$values['referral_email'] ) );
		$option = array( 'search_engine' => 'Google and other search engine', 'advertisement' => 'Advertisement', 'email' => 'Email/Newsletter', 'facebook' => 'Facebook', 'friend' => 'Family or Friend', 'others' => 'Others' );
		$fieldset->addElement( array( 'name' => 'how_did_you_hear_about_us', 'type' => 'Select', 'value' => @$values['how_did_you_hear_about_us'] ), $option );
		$fieldset->addRequirement( 'how_did_you_hear_about_us', array( 'ArrayKeys' => $option ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
