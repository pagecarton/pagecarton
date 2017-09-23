<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_Order_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Order_Order_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_Order_Abstract extends Application_Subscription_Checkout implements Application_Subscription_Checkout_Order_Interface
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_Order';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'order_id' );
	
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
		
		$fieldset->addElement( array( 'name' => 'username', 'type' => 'InputText', 'value' => @$values['username'] ) );
		$fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) );
//		$fieldset->addElement( array( 'name' => 'order', 'description' => 'Order', 'type' => 'TextArea', 'value' => @var_export( $values['order'], true ) ) );
		$fieldset->addElement( array( 'name' => 'order_api', 'description' => 'Payment', 'type' => 'InputText', 'value' => @$values['order_api'] ) );
		$fieldset->addElement( array( 'name' => 'order_status', 'type' => 'InputText', 'value' => @$values['order_status'] ) );
		$fieldset->addElement( array( 'name' => 'order_random_code', 'type' => 'InputText', 'value' => @$values['order_random_code'] ) );
//		$fieldset->addElement( array( 'name' => 'currency', 'type' => 'InputText', 'value' => @$values['currency'] ) );
		
	//	$fieldset->addFilters( 'enabled', array( 'HtmlSpecialCharsDecode' => null  ) );
		$fieldset->addFilters( array( 'Trim' => null, 'Escape' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
