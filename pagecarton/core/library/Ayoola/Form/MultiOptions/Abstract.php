<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_MultiOptions_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Friday 18th of May 2018 05:03PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Ayoola_Form_MultiOptions_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'multioptions_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'multioptions_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_MultiOptions';
	
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
        $fieldset->addElement( array( 'name' => 'multioptions_title', 'label' => 'Options Name', 'type' => 'InputText', 'value' => @$values['multioptions_title'] ) ); 
        $fieldset->addRequirement( 'multioptions_title', array( 'WordCount' => array( 3, 100 )  ) ); 

        $options = Ayoola_Object_Dbase::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'class_name', 'class_name');
        $options = $filter->filter( $options );  

        $fieldset->addElement( array( 'name' => 'db_table_class', 'label' => 'Database',  'onchange' => 'ayoola.div.manageOptions( { database: "Ayoola_Object_Dbase", values: "class_name", labels: "class_name", element: this } );', 'type' => 'Select', 'value' => @$values['db_table_class'] ), $options + array( '__manage_options' => '[Manage Databases]' ) ); 

        $database = $this->getGlobalValue( 'db_table_class' );
        if( Ayoola_Loader::loadClass( $database ) )
        {
            $options = array_keys( $database::getInstance()->getDataTypes() );
            $options = array_combine( $options, $options );
            $fieldset->addElement( array( 'name' => 'values_field', 'label' => 'Values Field', 'type' => 'Select', 'value' => @$values['values_field'] ), $options ); 
            $fieldset->addRequirement( 'values_field', array( 'InArray' => array_keys( $options )  ) ); 
            $fieldset->addElement( array( 'name' => 'label_field', 'label' => 'Labels Field', 'type' => 'Select', 'value' => @$values['label_field'] ), $options ); 
            $fieldset->addRequirement( 'label_field', array( 'InArray' => array_keys( $options )  ) ); 
}


		$fieldset->addLegend( $legend );  
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
