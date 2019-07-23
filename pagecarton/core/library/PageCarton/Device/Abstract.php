<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Device_Abstract
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Tuesday 23rd of July 2019 10:09PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PageCarton_Device_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'device_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'device_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_Device';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );


    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = false;       
        $fieldset->addElement( array( 'name' => 'device_name', 'type' => 'InputText', 'value' => @$values['device_name'] ) );

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
        
        $i = 0;
        $values['environment_value'] = $values['environment_value'] ? : Ayoola_Form::getGlobalValue( 'environment_value' );
        $values['environment_key'] = $values['environment_key'] ? : Ayoola_Form::getGlobalValue( 'environment_key' );
//             var_export(  $values );
        $optionsX = array_combine( array_keys( $_SERVER ), array_keys( $_SERVER ) );
        do
        {
            $fieldsetX = new Ayoola_Form_Element; 
            $fieldsetX->hashElementName = false;
            $fieldsetX->duplicationData = array( 'add' => 'New Detector', 'remove' => 'Remove Detector', 'counter' => 'field_counter', );
//        var_export(  $values['field'][$i] );
//        var_export(  $values['datatype'][$i] );

            $fieldsetX->container = 'div';
            $form->wrapForm = false;

            $fieldsetX->addElement( array( 'name' => 'environment_key', 'label' => 'Enviromental Variable', 'placeholder' => 'Data Type', 'type' => 'Select', 'multiple' => 'multiple', 'value' => $values['environment_key'][$i] ), $optionsX );
            $fieldsetX->addElement( array( 'name' => 'environment_value', 'label' => 'Environmental Value', 'placeholder' => 'Value to look for in the environmental variable ', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['environment_value'][$i] ) );
            $fieldsetX->addElement( array( 'name' => 'equator', 'label' => 'What to Detect', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$values['equator'][$i] ), array( 'Absence', 'Presence', 'RegEx' ) );
            $fieldsetX->allowDuplication = true;  
            $fieldsetX->placeholderInPlaceOfLabel = true;
            $i++;
            $fieldsetX->addLegend( 'How to detect device #<span name="field_counter">' . $i .  '</span>' );
            $form->oneFieldSetAtATime = false;   
            $form->addFieldset( $fieldsetX );
        }
        while( ! empty( $values['environment_key'][$i] ) || ! empty( $values['equator'][$i] ) );

		$this->setForm( $form );
    } 

	// END OF CLASS
}
