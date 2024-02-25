<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Level_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Level_Exception 
 */
 
require_once 'Application/Subscription/Level/Exception.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Level_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Level_Abstract extends Ayoola_Abstract_Table
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
	protected $_tableClass = 'Application_Subscription_SubscriptionLevel';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscriptionlevel_id', 'subscription_id' );
	
    /**
     * creates the form for creating and editing Levels
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//var_export( $_POST );
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'subscriptionlevel_name', 'label' => 'Category Name', 'Category Name' => 'subscriptionlevel_name', 'description' => 'Give this category a name', 'type' => 'InputText', 'value' => @$values['subscriptionlevel_name'] ) );
		$fieldset->addElement( array( 'name' => 'subscriptionlevel_description', 'label' => 'Description', 'description' => 'Describe the product category.', 'type' => 'TextArea', 'value' => @$values['subscriptionlevel_description'] ) );
		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'enabled', 'description' => 'Enable subscription to this category?', 'type' => 'Select', 'value' => @$values['enabled'] ), $options );
		
		//	Cover photo
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => '', 'placeholder' => 'Screen shot for product or service level', 'type' => 'Hidden', 'value' => @$values['document_url'] ) );
		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['document_url'] ? : null ), 'field_name' => $fieldName, 'width' => '900', 'height' => '300', 'crop' => true, 'field_name_value' => 'url' ) ) ) );

		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
	//	$fieldset->addRequirements( array( 'NotEmpty' => null ) );
		$fieldset->addRequirement( 'subscriptionlevel_name', array( 'WordCount' => array( 3,100 )  ) );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addElement( array( 'name' => 'subscription_id', 'type' => 'Hidden' ) );
		$info = $this->getIdentifier();
		$fieldset->addFilter( 'subscription_id', array( 'DefiniteValue' => $info['subscription_id'] ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
