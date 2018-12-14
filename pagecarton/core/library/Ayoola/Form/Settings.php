<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Settings
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
 * @package    Ayoola_Form_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Settings extends Application_Settings_Abstract
{
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	$values = unserialize( @$values['settings'] );
	//	$settings = unserialize( @$values['settings'] );
	//	$settings = @$values['data'] ? : unserialize( @$values['settings'] );
		$values = @$values['data'] ? : unserialize( @$values['settings'] );
	//	var_export( $values );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->submitValue = $submitValue ;

		$fieldset = new Ayoola_Form_Element();

		$authLevel = new Ayoola_Access_AuthLevel;  
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
		unset( $authLevel[97] );
		unset( $authLevel[98] );
		if( self::hasPriviledge() )
		{
	//		unset( $authLevel[98] );
		}
		$authLevel[99] = 'Admin';
		$authLevel[98] = 'Owner';
		$authLevel[1] = 'Registered Users';
		$authLevel[0] = 'Public';
		ksort( $authLevel );
		$fieldset->addElement( array( 'name' => 'coders_access_group', 'label' => 'Allow HTML Codes from', 'type' => 'SelectMultiple', 'value' => @$values['coders_access_group'] ), $authLevel );    
		$fieldset->addElement( array( 'name' => 'session_delay_time', 'label' => 'Time to wait before accepting form values (secs)', 'type' => 'InputText', 'placeholder' => 'e.g. 30', 'value' => @$values['session_delay_time'] ) );    
		
		$options = array(
							'allow_external_form_values' => 'Allow values from external forms',
				//			'blacklist_code_from_public' => 'Reject Code from Public Users'
		);
		$fieldset->addElement( array( 'name' => 'options', 'label' => '', 'type' => 'Checkbox', 'value' => @$values['options'] ), $options );
		
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
