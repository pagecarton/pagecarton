<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Exception 
 */
 
require_once 'Application/Subscription/Exception.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Subscription_Abstract extends Ayoola_Abstract_Table
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
     * Storage for Subscription
     *
     * @var Ayoola_Storage
     */
	protected static $_storage;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Subscription';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscription_id' );
	
    /**
     * 
     * @var array
     */
	protected static $_subscriptionRequirements = array( 'billing_address' => 'Billing Address', 'shipping_address' => 'Shipping Address' );
	
	protected static $_requirementOptions = 
		array( 
				'billing_address' => array( 'requirement' => 'address', 'requirement_legend' => 'Billing Address', 'requirement_goodnews' => 'To continue, you are required to provide a valid billing address. If you are paying with a credit card, this address must match the address listed with your bank.', 'parameters' => array( 'location_prefix' => 'billing_address' ) ), 
				'shipping_address' => array( 'requirement' => 'address', 'requirement_legend' => 'Shipping Address', 'requirement_goodnews' => 'To continue, you are required to provide a valid shipping address. Please enter the address you would like your order delivered to.', 'parameters' => array( 'location_prefix' => 'shipping_address' ) ), 
			);
		
	
    /**
     * Creates the form for subscription
     * 
     */
	public static function setFormRequirements( Ayoola_Form $form, $requirements )
    {
		$requirements = is_string( $requirements ) ? array_map( 'trim', explode( ',', $requirements ) ) : $requirements;
		$requirements = is_array( $requirements ) ? $requirements : array();
		//	Regular form elements
		$form->setFormRequirements( $requirements );
		
		//	settle internal requirements
		$internal = array();
		foreach( $requirements as $each )
		{
		//	var_export( $each );
			if( @self::$_requirementOptions[$each] )
			{
			//	var_export( $each );
		//		$internal[] = array( 'requirement_class' => self::$_requirementOptions[$each]['class'], 'requirement_legend' => self::$_requirementOptions[$each]['legend'], 'parameters' => self::$_requirementOptions[$each]['parameters'], 'requirement_goodnews' => str_ireplace( '@@@SUBSCRIPTION_LABEL@@@', $value['subscription_label'] , self::$_requirementOptions[$each]['goodnews'] ) );
				$internal[] = self::$_requirementOptions[$each];
			}
		}
		$form->setFormRequirements( $internal );
	}

    /**
     * Sets a value for the storage property
     *
     * @param Ayoola_Storage
     * @see Ayoola_Storage
     * @return void
     */
	public static function setStorage( Ayoola_Storage $storage )
    {
        self::$_storage = $storage;
    } 
	
    /**
     * Return the persistent storage object
     *
     * @param void
     * @return Ayoola_Storage
     */
    public static function getStorage()
    {
		if( null === self::$_storage )
		{
			//	Use Default Device
			require_once 'Ayoola/Storage.php';
			self::setStorage( new Application_Subscription_Storage() );
        }
		return self::$_storage;
    } 
	
    /**
     * Return the info about a particular price id
     *
     * @param int Price Id
     * @return array
     */
    public static function getPriceInfo( $priceId )
    {
		$priceInfo = new Application_Subscription_Price;
		$priceInfo = $priceInfo->selectOne( null, array( 'price_id' => $priceId ) );
		return $priceInfo;
    } 
	
    /**
     * Detect and process cart update
     *
     * @param void
     * @return bool
     */
    protected function cartUpdate()
    {
		$data = $this->getStorage()->retrieve() ? : array();
		//	var_export( $data );
		@$items = $data['cart'];
		while( ! empty( $_GET['cart_action'] ) &&  ! empty( $_GET['cart_id'] ) )
		{
			if( @$data['settings']['read_only'] ){ break; }
			switch( $_GET['cart_action'] )
			{
				case 'delete':
					foreach( $items as $name => $value )
					{
		//	var_export( $value );
		//	var_export( md5( serialize( $value ) ) );
						if( md5( serialize( $value ) ) == $_GET['cart_id'] ){ unset( $items[$name] ); }
					}
					break;
				case 'empty':
					if( md5( serialize( $items ) ) == $_GET['cart_id'] ){ $items = array(); }
					break;
			}
			$this->cartSave( $items, @$data['settings'] );
			break;
		}
		//	var_export( $items );
		return true;
    } 
	
    /**
     * saves the cart to storage
     *
     * @param void
     * @return bool
     */
    protected function cartSave( $items, $settings )
    {
		//	var_export( $items );
		$data = $items ? array( 'cart' => $items, 'settings' => $settings ) : array(); 
		$this->getStorage()->store( $data ); 
    } 
	
    /**
     * creates the form for creating and editing subscription package
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$form->formNamespace = get_class( $this ) . $values['subscription_name'];
		
	//	var_export( $form->formNamespace );
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		$fieldset->addElement( array( 'name' => 'subscription_label', 'label' => 'Product / Service', 'description' => 'What do you want to sell on this website?', 'type' => 'InputText', 'value' => @$values['subscription_label'] ) );
		$fieldset->addRequirement( 'subscription_label', array( 'WordCount' => array( 3,100 ) ) );
		$fieldset->addElement( array( 'name' => 'subscription_description', 'label' => 'Description', 'description' => 'Briefly Describe this product or service.', 'type' => 'Textarea', 'value' => @$values['subscription_description'] ) );
/* 		
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
		$authLevel = $filter->filter( $authLevel );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Minimum user level that can subscribe to this product or service.', 'type' => 'Select', 'value' => @$values['auth_level'] ), $authLevel );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel )  ) );
		unset( $authLevel );
 */		
/* 		$doc = new Ayoola_Doc_Document;
		$doc = $doc->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_id', 'document_name');
		$doc = array( 0 => 'No Picture' ) + $filter->filter( $doc );
		$fieldset->addElement( array( 'name' => 'document_id', 'label' => 'Screenshot', 'description' => 'Screenshot of product.', 'type' => 'Select', 'value' => @$values['document_id'] ), $doc );
		$fieldset->addRequirement( 'document_id', array( 'InArray' => array_keys( $doc )  ) );
		unset( $doc );
 */		
	
		//	Cover photo
	
		//	Cover photo
	//	$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => '', 'placeholder' => 'Screen shot for product or service', 'type' => 'Hidden', 'value' => @$values['document_url'] ) );
		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['document_url'] ? : null ), 'field_name' => $fieldName, 'width' => '900', 'height' => '300', 'crop' => true, 'field_name_value' => 'url' ) ) ) );
/* 
		$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . Ayoola_Form::hashElementName( 'document_url' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => '<input type=\'button\' value=\'Select Photo\' />', 'placeholder' => 'Screen shot for product or service', 'onClick' => 'ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' );', 'type' => 'InputText', 'value' => @$values['document_url'] ) );
 *//* 		$options =  array( 'No', 'Yes' );
		$fieldset->addElement( array( 'name' => 'enabled', 'description' => 'Enable subscription to this product or service by default?', 'type' => 'Select', 'value' => @$values['enabled'] ), $options );
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
 */		$time = is_null( $values ) ? 'creation_date' : 'modified_date';
		$fieldset->addElement( array( 'name' => $time, 'type' => 'Hidden' ) );
		
//		$fieldset->addRequirements( array( 'WordCount' => array( 1,100 ) ) );
		$fieldset->addRequirement( 'subscription_description', array( 'WordCount' => array( 10, 900 ) ) ); 
		$fieldset->addFilters( array( 'Trim' => null ) );
	//	$fieldset->addFilter( 'auth_level', array( 'Digits' => null ) );
		$fieldset->addFilter( $time, array( 'PresentTime' => null ) );
		$fieldset->addLegend( $legend );

		$options = new Ayoola_Form_Requirement;
		$options = $options->select();
		if( $options )
		{
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'requirement_name', 'requirement_label');
			$options = $filter->filter( $options );
	//		$fieldset->addElement( array( 'name' => 'article_requirements', 'type' => 'Checkbox', 'value' => @$values['article_requirements'] ), $options );
		//	$fieldset->addRequirement( 'article_requirements', array( 'InArray' => array_keys( $options )  ) );
		}
		
		//	Subscription requirements
		$options = $options + self::$_subscriptionRequirements;
		$fieldset->addElement( array( 'name' => 'subscription_requirements', 'label' => 'Select information required from users choosing this product or service', 'value' => @$values['subscription_requirements'], 'type' => 'Checkbox' ), $options );
		
		//	Checkout requirements
		$fieldset->addElement( array( 'name' => 'checkout_requirements', 'label' => 'Select information required from users who chose this product or service during checkout', 'value' => @$values['checkout_requirements'], 'type' => 'Checkbox' ), $options );
		
		$form->addFieldset( $fieldset );
		
		if( $values )
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->addElement( array( 'name' => 'subscription_object_name', 'label' => 'PHP Objects to play', 'type' => 'InputText', 'value' => @$values['subscription_object_name'] ) );
			$fieldset->addLegend( $legend );
			$form->addFieldset( $fieldset );
		}
	//	$fieldset->addRequirement( 'options', array( 'ArrayKeys' => $options ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
