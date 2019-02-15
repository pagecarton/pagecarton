<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_Order_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Saturday 25th of August 2018 07:41AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Application_Domain_Order_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'order_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'order_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain_Order';
	
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

        $fieldset->addElement( array( 'name' => 'domain_name' . @$values['domain_name'], 'label' => 'Domain Name', 'type' => 'InputText', 'readonly' => 'readonly', 'value' => @$values['domain_name'] ) ); 
        $fieldset->addElement( array( 'name' => 'username', 'type' => 'InputText', 'value' => @$values['username'] ) ); 
        $fieldset->addElement( array( 'name' => 'user_id', 'type' => 'InputText', 'value' => @$values['user_id'] ) ); 
        $fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) ); 
        $fieldset->addElement( array( 'name' => 'street_address', 'type' => 'InputText', 'value' => @$values['street_address'] ) ); 
        $fieldset->addElement( array( 'name' => 'street_address2', 'type' => 'InputText', 'value' => @$values['street_address2'] ) ); 
        $fieldset->addElement( array( 'name' => 'city', 'type' => 'InputText', 'value' => @$values['city'] ) ); 
        $fieldset->addElement( array( 'name' => 'province', 'type' => 'InputText', 'value' => @$values['province'] ) ); 
        $fieldset->addElement( array( 'name' => 'country', 'type' => 'InputText', 'value' => @$values['country'] ) ); 
        $fieldset->addElement( array( 'name' => 'zip', 'type' => 'InputText', 'value' => @$values['zip'] ) ); 
        $fieldset->addElement( array( 'name' => 'active', 'type' => 'Select', 'value' => @$values['active'] ), array( 'No', 'Yes' ) ); 
        $fieldset->addElement( array( 'name' => 'api', 'type' => 'InputText', 'value' => @$values['api'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
