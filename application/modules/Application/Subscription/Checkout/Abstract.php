<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_Abstract
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
 * @package    Application_Subscription_Checkout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_Abstract extends Application_Subscription_Checkout implements Application_Subscription_Checkout_Interface
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_CheckoutOption';
	
    /**
     * Namespace for withdrawal. Useful for storage
     *
     * @var string
     */
	public static $withdrawalNamespace = __CLASS__;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'checkoutoption_id' );
	
    /**
     * Plays the API that is selected
     * 
     */
	public static function getWithdrawalApi( $className )
    {
		$className = $className . '_Withdraw';
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $className ) )
		{ 
			throw new Application_Subscription_Checkout_Exception( 'WITHDRAWAL IS NOT YET ENABLED ON YOUR ACCOUNT WITH THE CHOSEN PAYMENT METHOD. PLEASE CHOOSE ANOTHER METHOD. PLEASE CHOOSE ANOTHER METHOD OR CONTACT CUSTOMER SERVICE.' ); 
		}
		return $className;
    } 
	
    /**
     * creates the form for creating and editing cycles
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
	//	var_export( $values['object_name'] );	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		if( is_null( $values ) )
		{		
			$fieldset->addElement( array( 'name' => 'checkoutoption_name', 'description' => 'Give this chekout option a name', 'type' => 'InputText', 'value' => @$values['checkoutoption_name'] ) );
		}
		
		$fieldset->addElement( array( 'name' => 'checkoutoption_logo', 'description' => 'Mark up for checkout option acceptance logo', 'type' => 'TextArea', 'value' => @$values['checkoutoption_logo'] ) );
/* 		
		$list = new Ayoola_Object_Table_ViewableObject();
		$list = $list->select();
 		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'object_name', 'class_name');
		$list = $filter->filter( $list );
		ksort( $list );
 		$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this checkout option', 'type' => 'Select', 'value' => @$values['object_name'] ), array( 0 => 'Select Object' ) + $list );
		$fieldset->addRequirement( 'object_name', array( 'InArray' => array_keys( $list )  ) );
		unset( $list );
 */ 	$fieldset->addElement( array( 'name' => 'object_name', 'description' => 'Which object will play this checkout option', 'type' => 'InputText', 'value' => @$values['object_name'] ) );
//		$fieldset->addRequirement( 'object_name', array( 'InArray' => array_keys( $list )  ) );

		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'enabled', 'description' => 'Allow subscription to this package', 'type' => 'Select', 'value' => @$values['enabled'] ), $options );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addRequirements( array( 'NotEmpty' => null ,'WordCount' => array( 6,1000 ) ) );
		if( is_null( $values ) )
		{		
			$fieldset->addRequirement( 'checkoutoption_name', array( 'WordCount' => array( 3,100 )  ) );
		}
		$fieldset->addRequirement( 'enabled', array( 'InArray' => array_keys( $options ),'WordCount' => array( 1,1 )  ) );
	//	$fieldset->addFilters( 'enabled', array( 'HtmlSpecialCharsDecode' => null  ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
