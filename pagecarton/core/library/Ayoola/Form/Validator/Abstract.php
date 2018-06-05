<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Validator_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Friday 18th of May 2018 01:40PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Ayoola_Form_Validator_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'validator_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'validator_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_Validator';
	
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
        $fieldset->addElement( array( 'name' => 'validator_title', 'type' => 'InputText', 'value' => @$values['validator_title'] ) ); 

			$i = 0;
			$newForm = new Ayoola_Form( array( 'name' => 'xxx', ) );
			$newForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true, 'no_required_fieldset' => true ) );
			$newForm->wrapForm = false;
			do
			{
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->allowDuplication = true;
				$newFieldSet->duplicationData = array( 'add' => '+ Add New Validation Below', 'remove' => '- Remove Above Validation', 'counter' => 'group_counter', );
				$newFieldSet->container = 'span';
				$newFieldSet->addLegend( 'Validator <span name="group_counter">' . ( $i + 1 ) . '</span>' );
				$newFieldSet->addElement( array( 'name' => 'validators', 'label' => 'Validator Class',  'multiple' => 'multiple', 'placeholder' => 'e.g. Sample_Validator_Class', 'type' => 'InputText', 'value' => @$values['validators'][$i] ) );
                if( ! empty( $values['parameters'][$i] ) )
                {
              //     var_export( $values['parameters'][$i] );
                    $values['parameters'][$i] = json_encode( $values['parameters'][$i] );
                }
				$newFieldSet->addElement( array( 'name' => 'parameters', 'label' => 'Validation Parameters',  'multiple' => 'multiple', 'placeholder' => '{}', 'type' => 'TextArea', 'value' => @$values['parameters'][$i] ) );
				
				$newForm->addFieldset( $newFieldSet );    
			//	self::v( $i );   
			//	var_export();
				$i++;
			}
			while( ! empty( $values['group_names'][$i] ) );
//			$fieldset = new Ayoola_Form_Element;
			$fieldset->addElement( array( 'name' => 'xxxx', 'type' => 'Html', 'value' => '' ), array( 'html' => $newForm->view(), 'fields' => 'validators,parameters' ) );
    //    $fieldset->addElement( array( 'name' => 'validators', 'type' => 'InputText', 'value' => @$values['validators'] ) ); 
   //     $fieldset->addElement( array( 'name' => 'parameters', 'type' => 'InputText', 'value' => @$values['parameters'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
