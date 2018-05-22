<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserPhoneNumber_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserPhoneNumber_Exception      
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserPhoneNumber_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_UserPhoneNumber_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'userphonenumber_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_UserPhoneNumber';
	
    /**
     * Sets _dbData
     * 
     */
	public function setDbData()
    {
		$table = $this->getDbTable();
		return $this->_dbData = self::getUserRecord( $table );
    } 
	
    /**
     * Sets _identifierData
     * 
     */
	public function setIdentifierData( $identifier = null )
    {
		if( is_null( $identifier ) ){ $identifier = $this->getIdentifier(); }
		$table = $this->getDbTable();
		return $this->_identifierData = self::getUserRecord( $table, $identifier );
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->setSubmitButton( $submitValue );
		$form->formNamespace = get_class( $this );
		$form->submitValue = $submitValue ;
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->id = __CLASS__;
		$dialCodes = array();
			
		do
		{
			do
			{
				//	Store the countries in the memory
			//	$storage = $this->getObjectStorage( 'country' );
				
				//	We now store countries as a cache
				$storage = $this->getObjectStorage( array( 'id' => 'country', 'device' => 'File', ) );  
				$storageForDialCodes = $this->getObjectStorage( array( 'id' => 'country-dial-codes', 'device' => 'File', ) );  
				if( $options = $storage->retrieve() )
				{
					$dialCodes = $storageForDialCodes->retrieve();
					break;
				}
				//	Check where our user information is being saved.
				if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
				{
					$database = 'cloud';  
				}
				$list = array();
				switch( $database )
				{
					case 'cloud':
						$response = Application_Country_Api::send( array() );
					//	var_export( $response );
						if( is_array( $response['data'] ) )
						{
							$list = $response['data'];
						}
						
					break;
					case 'relational':
						$list = new Application_Country();
						$list = $list->select();
					break;
				}
				$options = array();
				foreach( $list as $each )
				{
					$options[$each['country_id']] = '' . $each['dial_code'] . ' (' . $each['country_abbreviation'] . ')';
					$dialCodes[$each['country_id']] = $each['dial_code'];
				}
				asort( $options );
				asort( $dialCodes );
		//		self::v( $dialCodes );
		//		self::v( $this->getGlobalValue( 'country_id' ) );
				//	Store for subsequent use
				$storage->store( $options );
				$storageForDialCodes->store( $dialCodes );
			}
			while( false );
			
		//	ksort( $list );
			//	Plus sign
			$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => ' + ' ) );

			//	Country Id
			$defaultCountryValue = ( @$values['country_id'] ? : $this->getObjectStorage( 'country_id_value' )->retrieve() ) ? : Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'country_id' );
			
		//	var_export( $defaultCountryValue );
			if( ! empty( $options ) )
			{
				$fieldset->addElement( array( 'name' => 'country_id', 'label' => 'Country Code', 'style' => 'min-width:10%;max-width:25%;display:inline;margin-right:0;', 'type' => 'Select', 'value' => $defaultCountryValue ), array( 0 => 'Choose country code' ) + ( is_array( $options ) ? $options : array()  ) ); 
				$fieldset->addRequirement( 'country_id', array( 'InArray' => array_keys( ( is_array( $options ) ? $options : array()  ) )  ) );
			}
			//	Save country id in memory
			$this->getGlobalValue( 'country_id' ) ? $this->getObjectStorage( 'country_id_value' )->store( $this->getGlobalValue( 'country_id' ) ) : null;
			
			//	Dial Code
			if( $this->getGlobalValue( 'country_id' ) )
			{
				$fieldset->addElement( array( 'name' => 'dial_code', 'type' => 'Hidden', 'value' => null ) );
				$fieldset->addFilter( 'dial_code', array( 'DefiniteValue' => $dialCodes[$this->getGlobalValue( 'country_id' )] ) );
			} 
			//	Phone number
			$fieldset->addElement( array( 'name' => 'phonenumber', 'label' => 'Phone Number', 'style' => 'max-width:60%;display:inline;margin-left:0;', 'placeholder' => 'e.g. 8031234567', 'type' => 'InputText', 'value' => @$values['phonenumber'] ) );	
			$fieldset->addFilter( 'phonenumber', array( 'Digits' => null ) );
			$fieldset->addRequirement( 'phonenumber', array( 'WordCount' => array( 5, 20 ) ) );

			//	Retrieve the phonenumber_id
			$fieldset->addElement( array( 'name' => 'phonenumber_id', 'type' => 'Hidden', 'value' => null ) );	
	//		var_export( $phoneNumber );
			$phoneNumber = $this->getGlobalValue( 'phonenumber' );
			$countryId = $this->getGlobalValue( 'country_id' );
			if( $phoneNumber && $countryId )
			{
				$fieldset->addFilter( 'phonenumber_id', array( 'Digits' => null, 'PrimaryId' => array( 'table' => new Application_PhoneNumber, 'insert' => array( 'phonenumber' => $phoneNumber, 'country_id' => $countryId ) ) ) );
			}
		//	$fieldset->addRequirement( 'phonenumber_id', array( 'NotEmpty' =>null, 'WordCount' => array( 1, 20 ) ) );
		}
		while( false );
		
		
		$fieldset->addFilters( 'Trim::Escape' );
		$fieldset->addLegend( "$legend" );
		$form->addFieldset( $fieldset );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
