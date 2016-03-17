<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_User_Settings
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
 * @package    Application_User_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Settings extends Application_Settings_Abstract
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
	//	$form->oneFieldSetAtATime = true;
		
		//	User Sign in
		$fieldset = new Ayoola_Form_Element;
		$options = array( 'verified' => 'Email verification', 'enabled' => 'Enabled Account', 'approved' => 'Admin Approval', );
		$fieldset->addElement( array( 'name' => 'signin-requirement', 'label' => 'Sign in requirements', 'value' => @$settings['signin-requirement'], 'type' => 'Checkbox' ), $options );
		$dbOptions = array( 'cloud' => 'Ayoola Cloud (recommended)', 'file' => 'Flat file', 'relational' => 'Relational database' );
		$fieldset->addElement( array( 'name' => 'database', 'label' => 'Look for users in', 'value' => @$settings['database'], 'type' => 'Checkbox' ), $dbOptions );
		$fieldset->addLegend( 'Sign in options' );
		$form->addFieldset( $fieldset );
		
		//	User Sign up
		$fieldset = new Ayoola_Form_Element;
		$options = array( 'disable-signup' => 'Disable new user signup' );
		$fieldset->addElement( array( 'name' => 'signup', 'label' => 'Options', 'value' => @$settings['signup'], 'type' => 'Checkbox' ), $options );
		$fieldset->addLegend( 'Sign up options' );
		$fieldset->addElement( array( 'name' => 'default-database', 'label' => 'Save new users in', 'value' => @$settings['default-database'], 'type' => 'Radio' ), $dbOptions );
		$form->addFieldset( $fieldset );
		
		//	Other options
		$fieldset = new Ayoola_Form_Element;  
		$options = array( 	
							'allow_level_selection' => 'Allow users to select there user groups during signup',
							'allow_level_injection' => 'Allow the possibility of injecting user groups using forms.',

							);
		$fieldset->addElement( array( 'name' => 'user_options', 'label' => 'Options', 'value' => @$settings['user_options'], 'type' => 'Checkbox' ), $options );
		$fieldset->addElement( array( 'name' => 'allowed_access_information', 'value' => @$settings['allowed_access_information'], 'type' => 'MultipleInputText' ) ); 
		$fieldset->addLegend( 'User options' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
