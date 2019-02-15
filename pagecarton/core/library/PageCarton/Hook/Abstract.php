<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Hook_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Monday 14th of May 2018 01:10AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PageCarton_Hook_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'hook_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'hook_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_Hook';
	
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

		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;
		$fieldset->placeholderInPlaceOfLabel = false;
        
        $fieldset->addElement( array( 'name' => 'class_name', 'type' => 'InputText', 'value' => @$values['class_name'] ) ); 
        $fieldset->addElement( array( 'name' => 'hook_class_name', 'type' => 'InputText', 'value' => @$values['hook_class_name'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
