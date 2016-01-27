<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

abstract class Application_Settings_SettingsName_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Settings_SettingsName';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );
	
    /**
     * creates the form for creating and editing cycles
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'settingsname_name', 'description' => 'Give this settings a name', 'type' => 'InputText', 'value' => @$values['settingsname_name'] ) );
		}

		$fieldset->addElement( array( 'name' => 'document_url', 'description' => 'Thumbnail for this settings', 'type' => 'InputText', 'value' => @$values['document_url'] ) );
		
/* 		$list = new Ayoola_Object_Table_ViewableObject();
		$list = $list->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'object_name', 'class_name');
		$list = $filter->filter( $list );
		$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this settings', 'type' => 'Select', 'value' => @$values['object_name'] ), array( 0 => 'Select Object' ) + $list );
		$fieldset->addRequirement( 'object_name', array( 'InArray' => array_keys( $list )  ) );
		unset( $list );
 */		$fieldset->addElement( array( 'name' => 'class_name', 'placeholder' => 'Class name', 'description' => 'Which object will play this settings', 'type' => 'InputText', 'value' => @$values['class_name'] ) );
		$fieldset->addRequirement( 'class_name', array( 'WordCount' => array( 10, 50 ) ) );

		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'settingsname_editable', 'description' => '', 'type' => 'Select', 'value' => @$values['settingsname_editable'] ), $options );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
	//	$fieldset->addRequirements( array( 'NotEmpty' => null ,'WordCount' => array( 6,1000 ) ) );
		if( is_null( $values ) )
		{		
			$fieldset->addRequirement( 'settingsname_name', array( 'Name' => null, 'WordCount' => array( 3,100 )  ) );
		}
		$fieldset->addRequirement( 'settingsname_editable', array( 'InArray' => array_keys( $options ) ) );
		$fieldset->addFilters( array( 'Trim' => null, 'Escape' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
