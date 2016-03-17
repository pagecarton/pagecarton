<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserLocation_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserLocation_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserLocation_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_UserLocation_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'userlocation_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User_UserLocation';
	
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
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		$values = $values ? : $this->getParameter( 'form_preset_values' ); 
		//	var_export( $values );
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->setSubmitButton( $submitValue );
		$form->formNamespace = get_class( $this );
		$form->submitValue = $submitValue ;
		require_once 'Ayoola/Form/Element.php';
		$fieldset = new Ayoola_Form_Element;
		$prefix = $this->getParameter( 'location_prefix' );
		$fieldset->id = __CLASS__ . $prefix;
		
		
		//	Use Geolocation to detect location
		Application_Javascript::addFile( '//maps.google.com/maps/api/js' );
		Application_Javascript::addFile( '/js/geotext/geotext-1.0.js' );
		Application_Javascript::addCode
		( 
			'jQuery(function() 
			{ 
				new GeoText();  
			});' 
		);
		  
		do
		{
			do
			{
				//	Check where our user information is being saved.
				if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
				{
					$database = 'cloud';
				}

				//	Store the countries in the memory
			//	$storage = $this->getObjectStorage( 'country' );
				
				//	We now store countries as a cache
			//	$storage = $this->getObjectStorage( array( 'id' => 'country', 'device' => 'File', 'time_out' => 1640000, ) ); 
				$storage = $this->getObjectStorage( array( 'id' => 'countryxx', 'device' => 'File', ) );
				if( $listCountry = $storage->retrieve() )
				{
					break;
				}
				$listCountry = array();
				switch( $database )
				{
					case 'relational':
						$listCountry = new Application_Country();
						$listCountry = $listCountry->select();
					break;
					default:
						$response = Application_Country_Api::send( array() );
				//		var_export( $response );
						if( is_array( $response['data'] ) )
						{
							$listCountry = $response['data'];
						}
						
					break;
				}
			//		self::v( $database );
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'country', 'country' );
				$listCountry = $filter->filter( $listCountry );
				
				//	Store for subsequent use
				$storage->store( $listCountry );
			}
			while( false );
			
			//	Country Id
		//	$result = $this->getObjectStorage( array( 'id' => 'country', 'device' => 'File', ) );
		//	var_export( $this->getObjectStorage( 'country_id_value' )->retrieve() );
	//		$listCountry = array();
			
			//	Only bring this out if country has been selected
		//	if( ! $defaultCountryValue ) 
			{
		//		break;
			} 
			//	Save country id in memory
			$this->getGlobalValue( 'country_id' ) ? $this->getObjectStorage( 'country_id_value' )->store( $this->getGlobalValue( 'country_id' ) ) : null;
			
			//	Province
			$listProvince = array();
			do
			{  
				//	Store the provinces in the memory
			//	$storage = $this->getObjectStorage( 'province-' . $this->getGlobalValue( 'country_id' ) ); 
				
				//	We now store province info as a cache
				$storage = $this->getObjectStorage( array( 'id' => 'province-' . $this->getGlobalValue( 'country_id' ), 'device' => 'File', ) );
				if( $listProvince = $storage->retrieve() )
				{
					break;
				}
				switch( $database )
				{
					case 'cloud':
						$response = Application_Province_Api::send( array( 'country_id' => $this->getGlobalValue( 'country_id' ) ? : $this->getObjectStorage( 'country_id_value' )->retrieve() ) );
					//	var_export( $response );
						if( is_array( $response['data'] ) )
						{
							$listProvince = $response['data'];
						}
						
					break;
					case 'relational':
						$listProvince = new Application_Province();
						@$province_id = $global['province_id'] ? : $values['province_id'];
						$listProvince = $listProvince->select( null, 'countryprovince', array( 'country_id' => $this->getGlobalValue( 'country_id' ) ? : $this->getObjectStorage( 'country_id_value' )->retrieve() ) );
					break;
				}
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'province_id', 'province' );
				$listProvince = $filter->filter( $listProvince );
				
				//	Store for subsequent use
				$storage->store( $listProvince );
			}
			while( false );
		//	ksort( $list );
		//	var_export( $prefix );
			
			//	Street Address
			$fieldset->addElement( array( 'name' => 'street_address', 'class' => 'geotext[street-long]', 'label' => 'Address line 1', 'placeholder' => 'e.g. 119 State Road', 'type' => 'InputText', 'value' => @$values['street_address'] ) );			
			$fieldset->addRequirement( 'street_address', array( 'WordCount' => array( 6, 50 ) ) );
						
			//	Street Address 2
			$fieldset->addElement( array( 'name' => 'street_address2', 'label' => 'Address line 2', 'placeholder' => 'e.g. Apt H-3', 'type' => 'InputText', 'value' => @$values['street_address2'] ) );			
		//	$fieldset->addRequirement( 'street_address2', array( 'WordCount' => array( 6, 50 ) ) );

			
			//	City
			$fieldset->addElement( array( 'name' => 'city', 'class' => 'geotext[city]', 'label' => 'City or town', 'placeholder' => 'e.g. Ibadan', 'type' => 'InputText', 'value' => @$values['city'] ) );	
			$fieldset->addRequirement( 'city', array( 'WordCount' => array( 2, 30 ) ) );
			
			//	Retrieve the city_id
			$fieldset->addElement( array( 'name' => 'city_id', 'type' => 'Hidden', 'value' => null ) );	
	//		var_export( $phoneNumber );
			if( $this->getGlobalValue( 'city' ) && $this->getGlobalValue( 'province_id' ) )
			{
				$fieldset->addFilter( 'city_id', array( 'Digits' => null, 'PrimaryId' => array( 'table' => new Application_City, 'insert' => array( 'city' => $this->getGlobalValue( 'city' ), 'province_id' => $this->getGlobalValue( 'province_id' ) ) ) ) );
			}
		//	Province ID
			$fieldset->addElement( array( 'name' => 'province_id', 'class' => 'geotext[state-long]', 'label' => 'State, Province or Region', 'e.g.' => 'e.g. Lagos', 'description' => 'Please select a state', 'type' => 'InputText', 'value' => ( @$values['province_id'] ? : $this->getObjectStorage( 'province_id_value' )->retrieve() ) ? : Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'province_id' ) ), array( 0 => 'Please select...' ) + $listProvince );
	//		$listProvince ? $fieldset->addRequirement( 'province_id', array( 'InArray' => array_keys( $listProvince )  ) ) : null;
			
						
			//	Only bring this out if country and province have been selected
			if( @ ! $this->getGlobalValue( 'province_id' ) )
			{
			//	break;
			}
			//	Save country id in memory
			$this->getGlobalValue( 'province_id' ) ? $this->getObjectStorage( 'province_id_value' )->store( $this->getGlobalValue( 'province_id' ) ) : null;
		
			 
			//	Zip
			$fieldset->addElement( array( 'name' => 'zip', 'class' => 'geotext[zip]', 'label' => 'Zip/Postal Code', 'description' => 'e.g. 23401', 'type' => 'InputText', 'value' => @$values['zip'] ) );			
			$fieldset->addElement( array( 'name' => 'longitude', 'class' => 'geotext[longitude]', 'type' => 'Hidden', 'value' => @$values['longitude'] ) );			
			$fieldset->addElement( array( 'name' => 'latitude', 'class' => 'geotext[latitude]', 'type' => 'Hidden', 'value' => @$values['latitude'] ) );			
		//	$fieldset->addRequirement( 'zip', array( 'WordCount' => array( 3, 10 ) ) );
			if( $listCountry ) 
			{
				$defaultCountryValue = ( @$values['country_id'] ? : $this->getObjectStorage( 'country_id_value' )->retrieve() ) ? : Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'country_id' );
				$fieldset->addElement( array( 'name' => 'country', 'class' => 'geotext[country-long]', 'label' => 'Country', 'type' => 'Select', 'value' => $defaultCountryValue ), $listCountry );  
		//		$fieldset->addElement( array( 'name' => 'country_id', 'class' => 'geotext[country-long]', 'label' => 'Country', 'type' => 'Hidden', 'value' => $defaultCountryValue ), $listCountry );
			//	$fieldset->addElement( array( 'name' => 'country_x', 'class' => 'geotext[country]', 'label' => 'Country', 'type' => 'InputText', 'value' => null ) );
			//	$fieldset->addElement( array( 'name' => 'country_xx', 'class' => 'geotext[country-long]', 'label' => 'Country', 'type' => 'InputText', 'value' => null ) );
				
				$listCountry ? $fieldset->addRequirement( 'country', array( 'InArray' => array_keys( array( 0 => 'Please select...' ) + $listCountry )  ) ) : null;
			}
			else
			{
		//		break;  
			}
			//	Prefixes
		//	if( $prefix )
			{
				//	Country name
				if( $this->getGlobalValue( 'country_id' ) )
				{
					$fieldset->addElement( array( 'name' => $prefix . '_country', 'type' => 'Hidden', 'value' => null ) );
					$fieldset->addFilter( $prefix . '_country', array( 'DefiniteValue' => @$listCountry[$this->getGlobalValue( 'country_id' )] ) );
				}
			//	var_export( $listCountry[$this->getGlobalValue( 'country_id' )] );
				//	Province Name
				if( $this->getGlobalValue( 'province_id' ) )
				{
					$fieldset->addElement( array( 'name' => $prefix . '_province', 'type' => 'Hidden', 'value' => null ) );
					$fieldset->addFilter( $prefix . '_province', array( 'DefiniteValue' => @$listProvince[$this->getGlobalValue( 'province_id' )] ) );
				}
				//	Prefixed Street Address
				$fieldset->addElement( array( 'name' => $prefix . '_street_address', 'type' => 'Hidden', 'value' => null ) );			
				$fieldset->addFilter( $prefix . '_street_address', array( 'DefiniteValue' => $this->getGlobalValue( 'street_address' ) ) );
				
				//	Prefixed street add 2
				$fieldset->addElement( array( 'name' => $prefix . '_street_address2', 'type' => 'Hidden', 'value' => null ) );			
				$fieldset->addFilter( $prefix . '_street_address2', array( 'DefiniteValue' => $this->getGlobalValue( 'street_address2' ) ) );
				
				//	City
				$fieldset->addElement( array( 'name' => $prefix . '_city', 'type' => 'Hidden', 'value' => null ) );	
				$fieldset->addFilter( $prefix . '_city', array( 'DefiniteValue' => $this->getGlobalValue( 'city' ) ) );
				
				//	Zip
				$fieldset->addElement( array( 'name' => $prefix . '_zip', 'type' => 'Hidden', 'value' => null ) );	
				$fieldset->addFilter( $prefix . '_zip', array( 'DefiniteValue' => $this->getGlobalValue( 'zip' ) ) );
			
			}

		}
		while( false );		  
		
		$fieldset->addFilters( 'Trim::Escape' );
		$fieldset->addLegend( "$legend" );
		$form->addFieldset( $fieldset );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
