<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Element_Type_Abstract
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Friday 7th of August 2020 01:59PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Ayoola_Form_Element_Type_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'type_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'type_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_Element_Type';
	
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

		$fieldset = new Ayoola_Form_Element;
        $fieldset->addElement( array( 'name' => 'type_name', 'type' => 'InputText', 'value' => @$values['type_name'] ) );         
        $options = Ayoola_Object_Widget::getInstance()->select();
        //  var_export( $options );
        $filter = new Ayoola_Filter_SelectListArray( 'class_name', 'class_name' );
        $options = $filter->filter( $options );
        foreach( $options as $key => $value )
        {
            if( ! Ayoola_Loader::loadClass( $value ) )
            {
                unset( $options[$key] );
            }
        }
        if( empty( $options ) )
        {
            $options[''] = 'No widgets created yet'; 
        }
        else
        {
            $options = array( '' => 'Select' ) + $options;
            if( empty( $options[@$values['hook_class_name']] ) )
            {
                $options[@$values['hook_class_name']] = $values['hook_class_name'];
            }
        }
        $fieldset->addElement( array( 'name' => 'type_widget', 'label' => 'Widget for Form Element', 'placeholder' => 'Class widget to affect by this', 'type' => 'Select', 'value' => @$values['type_widget'], 'onchange' => 'if( this.value == \'__custom\' ){ var a = prompt( \'Custom Widget Class Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }' ), $options + array( '__custom' => 'Custom Widget' ) ); 
        $fieldset->addRequirements( array( 'NotEmpty' => null ) );

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
