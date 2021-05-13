<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Order_Status_Abstract
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Wednesday 12th of May 2021 03:18PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Application_Subscription_Checkout_Order_Status_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'status_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'status_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_Order_Status';
	
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
        $fieldset->addElement( array( 'name' => 'code', 'label' => 'Status Code', 'placeholder' => 'e.g. 24', 'type' => 'InputText', 'value' => @$values['code'] ) );         
        $fieldset->addElement( array( 'name' => 'title', 'label' => 'Status Title', 'placeholder' => 'e.g. Order Shipped', 'type' => 'InputText', 'value' => @$values['title'] ) );         
        $fieldset->addElement( array( 'name' => 'message', 'label' => 'Order Status Message', 'placeholder' => 'Enter template message to send to customer when order status is set to this one...', 'type' => 'TextArea', 'value' => @$values['message'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
