<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Checkout_DirectDeposit_Abstract
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
 * @package    Application_Subscription_Checkout_DirectDeposit_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Checkout_DirectDeposit_Abstract extends Application_Subscription_Checkout_DirectDeposit
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_DirectDeposit_Account';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'account_id' );
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	var_export( $values['object_name'] );	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		Application_Javascript::addFile( '//cdn.ckeditor.com/4.5.6/full-all/ckeditor.js' );
		
		if( ! $values )
		{
			$fieldset->addElement( array( 'name' => 'account_name', 'description' => 'A unique name for account information', 'type' => 'InputText', 'value' => @$values['account_name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'account_info', 'description' => 'HTML to display account information', 'class' => 'ckeditor', 'type' => 'TextArea', 'value' => @$values['account_info'] ) );  
		$fieldset->addElement( array( 'name' => 'account_currency', 'description' => 'Account Currency Code e.g. USD', 'type' => 'InputText', 'value' => @$values['account_currency'] ) );
		$fieldset->addElement( array( 'name' => 'account_country_code', 'description' => 'Account Country Code e.g. US', 'type' => 'InputText', 'value' => @$values['account_country_code'] ) );
 
		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'account_enabled', 'description' => 'Enable this account information?', 'type' => 'Radio', 'value' => @$values['account_enabled'] ), $options );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 2,1000 ) ) );
		$fieldset->addRequirement( 'account_enabled', array( 'InArray' => array_keys( $options ),'WordCount' => array( 1,1 )  ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
