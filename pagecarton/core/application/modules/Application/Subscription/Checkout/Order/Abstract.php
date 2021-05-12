<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Order_Order_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_Order_Abstract extends Application_Subscription_Checkout implements Application_Subscription_Checkout_Order_Interface
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
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
        $form->submitValue = $submitValue;
		$fieldset = new Ayoola_Form_Element;
		
		$fieldset->addElement( array( 'name' => 'username', 'type' => 'InputText', 'value' => @$values['username'] ) );
		$fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) );

        if( empty( $values['order_api'] ) )
        {
            $fieldset->addElement( array( 'name' => 'order_api', 'description' => 'Payment', 'type' => 'InputText', 'value' => @$values['order_api'] ) );
        }
        

        $stages = array_unique( static::$checkoutStages );


        if( ! array_key_exists( $values['order_status'], $stages ) && in_array( $values['order_status'], $stages ) )
        {
            $keyStages = array_flip( $stages );
            $values['order_status'] = $keyStages[$values['order_status']];
        }

        //var_export( $stages );
        if( $dynamicStages = Application_Subscription_Checkout_Order_Status::getInstance()->select() )
        {
            foreach( $dynamicStages as $each )
            {
                $stages[$each['code']] = $each['title'];
            }

        }
        if( isset( $_REQUEST['status_change'] ) )
        {
            $values['order_status'] = $_REQUEST['status_change'];

            if( $stageInfo = Application_Subscription_Checkout_Order_Status::getInstance()->selectOne( null, array( 'code' => $values['order_status'] ) ) )
            {
                $fieldset->addElement( array( 'name' => 'order_message', 'type' => 'TextArea', 'label' => 'Order Status Message', 'placeholder' => 'Enter notification message to send to customer...', 'value' =>  $stageInfo['message'] ? : @$values['order_message'] ) );
            }
        }

		$fieldset->addElement( array( 'name' => 'order_status', 'type' => 'Select', 'onchange' => 'location.search += \'&status_change=\'+this.value', 'value' => @$values['order_status'] ), $stages );

        

        $fieldset->addFilters( array( 'Trim' => null, 'Escape' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
