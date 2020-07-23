<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
	protected static $_accessLevel = array( 99, 98 );
	
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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue;
	
 		$fieldset->addElement( array( 'name' => 'settingsname_title', 'placeholder' => 'Settings Title', 'type' => 'InputText', 'value' => @$values['settingsname_title'] ? : @$values['settingsname_name']  ) );
        $fieldset->addRequirement( 'settingsname_title', array( 'WordCount' => array( 3, 50 ) ) );
        
        $options = Ayoola_Object_Embed::getWidgets() + array( '__custom' => 'Custom Widget' );
        if( @$values['class_name'] && empty( $options[$values['class_name']] ) )
        {
            $options[$values['class_name']] = $values['class_name'];
        }

  		$fieldset->addElement( array( 'name' => 'class_name',  'onchange' => "if( this.value == '__custom' ){  var a = prompt( 'Custom Parameter Name', '' ); if( ! a ){ this.value = ''; return false; } var option = document.createElement( 'option' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }", 'label' => 'Settings Widget', 'type' => 'Select', 'value' => @$values['class_name'] ), $options );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
