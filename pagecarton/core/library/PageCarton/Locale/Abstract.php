<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Locale_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 5th of August 2018 01:59PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PageCarton_Locale_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'locale_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'locale_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_Locale';
	
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
        $fieldset->addElement( array( 'name' => 'locale_name', 'type' => 'InputText', 'placeholder' => 'e.g. Yoruba', 'value' => @$values['locale_name'] ) ); 
        $fieldset->addElement( array( 'name' => 'native_name', 'type' => 'InputText', 'placeholder' => 'e.g. Yorùbá', 'value' => @$values['native_name'] ) ); 

        $options = array();
        if( class_exists( 'ResourceBundle') )
        {
            $options = ResourceBundle::getLocales( '' );
        }

        $options = array_combine( $options, $options );
        $fieldset->addElement( array( 'name' => 'locale_code',  'onchange' => 'ayoola.div.manageOptions( { database: "", listWidget: "", values: "", labels: "", element: this } );', 'type' => 'Select', 'value' => @$values['locale_code'] ), array( '' => 'Please Select' ) + $options + array( '__custom' => '[Custom Locale Code]' ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
