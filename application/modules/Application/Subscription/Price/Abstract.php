<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Price_Exception 
 */
 
require_once 'Application/Subscription/Price/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Price_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;

    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Price';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'price_id', 'subscriptionlevel_id' );
	
    /**
     * creates the form for creating and editing Levels
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
		$fieldset->addElement( array( 'name' => 'price', 'placeholder' => '0.00', 'description' => 'Enter a price for this product category.', 'type' => 'InputText', 'value' => @$values['price'] ) );
		$fieldset->addRequirement( 'price', array( 'WordCount' => array( 1, 10 )  ) );
		$fieldset->addFilter( 'price', array( 'float' => null ) );
		
		
		$cycle = new Application_Subscription_Cycle;
		$cycle = $cycle->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'cycle_id', 'cycle_name');
		$cycle = $filter->filter( $cycle );
		$fieldset->addElement( array( 'name' => 'cycle_id', 'label' => 'Billing Cycle', 'description' => 'Define the billing bundle or cycle.', 'type' => 'Select', 'value' => @$values['cycle_id'] ), $cycle );
		$fieldset->addRequirement( 'cycle_id', array( 'Int' => null, 'InArray' => array_keys( $cycle )  ) );
		unset( $cycle );
		$fieldset->addElement( array( 'name' => 'show_multiple_settings', 'type' => 'Checkbox', 'label' => 'Show options to allow multiple subscriptions for this item', 'value' => @$values['show_multiple_settings'] ), array( 1 => 'Yes' ) );
		if( $this->getGlobalValue( 'show_multiple_settings' ) || @$values['min_quantity'] )
		{
			$fieldset->addElement( array( 'name' => 'min_quantity', 'placeholder' => 'Minimum multiple items user can add to cart', 'type' => 'InputText', 'value' => @$values['min_quantity'] ) );
			$fieldset->addFilter( 'min_quantity', array( 'Int' => null ) );
			$fieldset->addElement( array( 'name' => 'max_quantity', 'placeholder' => 'Maximum multiple items user can add to cart', 'type' => 'InputText', 'value' => @$values['max_quantity'] ) );
			$fieldset->addRequirement( 'max_quantity', array( 'NotEmpty' => null  ) );
			$fieldset->addFilter( 'max_quantity', array( 'Int' => null ) );
			
			$fieldset->addElement( array( 'name' => 'allowed_multiples', 'placeholder' => 'Increments to use for item multiples', 'type' => 'InputText', 'value' => @$values['allowed_multiples'] ) );
			$fieldset->addRequirement( 'allowed_multiples', array( 'NotEmpty' => null  ) );
			$fieldset->addFilter( 'allowed_multiples', array( 'Int' => null ) );
		}
		
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addElement( array( 'name' => 'subscriptionlevel_id', 'type' => 'Hidden' ) );
		$info = $this->getIdentifier();
		$fieldset->addFilter( 'subscriptionlevel_id', array( 'DefiniteValue' => $info['subscriptionlevel_id'] ) );
	//	$fieldset->addRequirements( array( 'NotEmpty' => null  ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
