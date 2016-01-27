<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Exception 
 */
 
require_once 'Application/User/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_Abstract extends Ayoola_Abstract_Table
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'username' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_User';
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'username';
	
    /**
     * Other tables used for storing user related data
     *
     * @var array
     */
	protected $_otherTables = array( 'UserEmail', 'UserSettings', 'UserPassword', 'UserPersonalInfo', 'UserActivation' );
	
    /**
     * Validates form
     * @param void
     * @return boolean
     */
    protected function _validate()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
        if( $values['password'] != $values['password2']  )
		{	
			$this->getForm()->setBadnews( 'The password field does not match the password confirmation field' );
			return false;
		}	
		return true;
    } 

    /**
     * Overides the parent class
     * 
     */
	public function setDbData()
    {
		//	power our search box
		$this->prepareDbWhereClauseForSearch();

		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'cloud';
		}
		switch( $database )
		{
			case 'cloud':
				$response = Ayoola_Api_UserList::send( ( $this->_dbWhereClause ? : array() ) + array( 'method' => 'select' ) );
		//		var_export( $response );
				if( is_array( $response['data'] ) )
				{
					$this->_dbData = $response['data'];
				}
			break;
			case 'relational':
				$data = $this->getDbTable()->select( null, strtolower( implode( ', ', $this->_otherTables ) ), $this->_dbWhereClause );
				//	var_export( $data );
				rsort( $data );
				$this->_dbData = $data;
			break;
			case 'file':
				// Find user in the LocalUser table
				$table = new Ayoola_Access_LocalUser();

				//	var_export( $table->select() ); 
			//	var_export( $hashedCredentials );
				if( $data = $table->select() )
				{
					$this->_dbData = $data;
				}
				//	var_export( $data );
			break;
		
		}
    } 
	
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'cloud';
		}
		
		do
		{
			//	Check from local table
			$table = new Ayoola_Access_LocalUser();
			$identifierInfo = $this->getIdentifier();
	//			var_export( $identifierInfo );
			if( $info = $table->selectOne( null, array( $this->getIdColumn() => array( $identifierInfo[$this->getIdColumn()], strtolower( $identifierInfo[$this->getIdColumn()] ) ) ) ) )
			{
			//	var_export( $info );
			//	var_export( $identifierInfo['username'] );
				if( $info['user_information'] )  
				{
					$this->_identifierData = $info['user_information'];
					break;
				}
			}
		//	$info = array();
			//	var_export( $table->select( null, array( 'username' => @$identifierInfo['username'] ) ) );
		//	var_export( $identifierInfo['username'] );
		//	var_export( $info );
		//	var_export( array( 'username' => array( $identifierInfo['username'], strtolower( $identifierInfo['username'] ) ) ) );
		//	var_export( $table->selectOne( null, array( 'username' => array( 'username' => strtolower( $identifierInfo['username'] ) ) ) ) );
			//	case 'cloud':
			$response = Ayoola_Api_UserList::send( $this->getIdentifier() );
			if( is_array( @$response['data'] ) )
			{
				$this->_identifierData = $response['data'];
				
				//	Localize information 
		//		$table->delete( array( 'username' => array( '', $this->_identifierData['username'] ) ) );
		//		$table->delete( array( 'username' => $this->_identifierData['username'] ) );
		//		var_export( $this->_identifierData );
			//	$table->insert( array( 'username' => $identifierInfo['username'], 'password' => $identifierInfo['password'], 'user_information' => $this->_identifierData, ) );		

				Ayoola_Access_Localize::info( $this->_identifierData );
				break;
			}
			
			//	case 'relational':
			if( $database === 'relational' )
			{
				$data = $this->getDbTable()->selectOne( null, strtolower( implode( ', ', $this->_otherTables ) ), $this->getIdentifier() );
				//	var_export( $data );
				$this->_identifierData = $data;
				break;
			}
	//		break;
		
		}
		while( false );
	//	var_export( $table->drop() );
	//	var_export( $table->select() );
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
		require_once 'Ayoola/Form.php';
		$form = new Ayoola_Form( 'name=>' . $this->getObjectName() );
		if( @$_REQUEST['previous_url'] )
		{
			$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => $this->getObjectName() ) );
		}
		
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$form->formNamespace = get_class( $this ) . $values['user_id'];
		//$form->setCaptcha( true ); // Adds captcha
		require_once 'Ayoola/Form/Element.php';
		$personal = new Ayoola_Form_Element;
		$personal->id = __CLASS__ . 'personal';
		$personal->addElement( array( 'name' => 'firstname', 'placeholder' => 'Your given name', 'type' => 'InputText', 'value' => @$values['firstname'] ) );
		$personal->addRequirement( 'firstname','Name:: WordCount=>2;;20' );
		$personal->addElement( array( 'name' => 'middlename', 'placeholder' => 'Other name', 'type' => 'InputText', 'value' => @$values['middlename'] ) );
//		$personal->addElement( array( 'name' => 'phonenumber', 'placeholder' => 'Mobile Number', 'type' => 'InputText', 'value' => @$values['phonenumber'] ) );
		$personal->addFilter( 'middlename','Name' );
		$personal->addElement( array( 'name' => 'lastname', 'placeholder' => 'Your family name', 'type' => 'InputText', 'value' => @$values['lastname'] ) );
		$personal->addRequirement( 'lastname','Name:: WordCount=>2;;20' );
		$option = array( 'M' => 'Male', 'F' => 'Female' );
		$personal->addElement( array( 'name' => 'sex', 'type' => 'Select', 'value' => @$values['sex'] ), $option );
		$personal->addRequirement( 'sex','InArray=>' . implode( ';;', array_keys( $option ) ) . ':: WordCount=>1;;1' );
		//	retrieve birthday
		if( @$values['birth_date'] )
		{
			@list( $values['birth_year'], $values['birth_month'], $values['birth_day'] ) = explode( '-', $values['birth_date'] );
		}
	//	self::v( $previousBirthdays );
		
		//	Month
		$options = array_combine( range( 1, 12 ), array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ) );
		$birthMonthValue = intval( @strlen( $values['birth_month'] ) === 1 ? ( '0' . @$values['birth_month'] ) : @$values['birth_month'] );
		$birthMonthValue = intval( $birthMonthValue ?  : $this->getGlobalValue( 'birth_month' ) );
	//	var_export( $birthMonthValue );
	//	var_export( $this->getGlobalValue( 'birth_month' ) );
		$personal->addElement( array( 'name' => 'birth_month', 'label' => 'Date of Birth', 'style' => 'min-width:10%;max-width:25%;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthMonthValue ), array( 'Month' ) + $options ); 
		$personal->addRequirement( 'birth_month', array( 'InArray' => array_keys( $options ) ) );
		if( strlen( $this->getGlobalValue( 'birth_month' ) ) === 1 )
		{
			$personal->addFilter( 'birth_month', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'birth_month' ) ) );
		}
		
		//	Day
		$options = range( 1, 31 );
		$options = array_combine( $options, $options );
		$birthDayValue = intval( @strlen( $values['birth_day'] ) === 1 ? ( '0' . @$values['birth_day'] ) : @$values['birth_day'] );
		$birthDayValue = intval( $birthDayValue ?  : $this->getGlobalValue( 'birth_day' ) );
		$personal->addElement( array( 'name' => 'birth_day', 'label' => '', 'style' => 'min-width:10%;max-width:25%;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthDayValue ), array( 'Day' ) +$options );
		$personal->addRequirement( 'birth_day', array( 'InArray' => array_keys( $options ) ) );
		if( strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 )
		{
			$personal->addFilter( 'birth_day', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'birth_day' ) ) );
		}
		
		//	Year
		//	Age must start from 13 yrs
		$options = range( date( 'Y' ) - 13, 1900 );
		$options = array_combine( $options, $options );
		$personal->addElement( array( 'name' => 'birth_year', 'label' => '', 'style' => 'min-width:10%;max-width:25%;display:inline-block;margin-right:0;', 'type' => 'Select', 'value' => @$values['birth_year'] ), array( 'Year' ) + $options );
		$personal->addRequirement( 'birth_year', array( 'InArray' => array_keys( $options ) ) );
		
		//	Birthday combined
		$personal->addElement( array( 'name' => 'birth_date', 'label' => 'Date of Birth', 'placeholder' => 'YYYY-MM-DD', 'type' => 'Hidden', 'value' => @$values['birth_date'] ) );
	//	$personal->addRequirement( 'birth_date','Date=>YYYYMMDD' );
		$dob = $this->getGlobalValue( 'birth_year' );
		$dob .= strlen( $this->getGlobalValue( 'birth_month' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_month' ) ) : $this->getGlobalValue( 'birth_month' );
		$dob .= strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_day' ) ) : $this->getGlobalValue( 'birth_day' );
		$personal->addFilter( 'birth_date', array( 'DefiniteValue' => $dob ) );
		$personal->addFilters( 'Trim::Escape' );
		$personal->addLegend( "$legend Personal Information" );
		$account = new Ayoola_Form_Element;
		$account->id = __CLASS__ . 'account';
	//	$account->placeholderInPlaceOfLabel = true;
		$description = 'Leave blank if you don\'t intend to change password.';
		if( is_null( $values ) )
		{ 
			$account->addElement( array( 'name' => 'email', 'label' => 'Email Address', 'placeholder' => ' e.g. email@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) );
			$account->addRequirement( 'email', array( 'EmailAddress' => null, 'DuplicateUser' => array( 'email' ) ) );
			$account->addElement( array( 'name' => 'username', 'placeholder' => 'Choose a username', 'type' => 'InputText', 'value' => @$values['username'] ) );
			$account->addRequirement( 'username', array( 'Username' => null, 'DuplicateUser' => array( 'username' ) ) );
		//	$account->addRequirement( 'username', array( 'Username' => null ) );
			$description = null;
			if( $userOptions = Application_Settings_Abstract::getSettings( 'UserAccount', 'user_options' ) )
			{
			//	var_export( $userOptions );
			//	$database = 'cloud';
			}
			if( is_array( $userOptions ) && in_array( 'allow_level_selection', $userOptions ) )
			{
			//	$account = new Ayoola_Form_Element;
			//	$account->id = __CLASS__ . 'level';
				$authLevel = new Ayoola_Access_AuthLevel;
				$authLevel = $authLevel->select();
				$options = array();
				foreach( $authLevel as $each )
				{
					if( is_array( $each['auth_options'] ) && in_array( 'allow_signup', $each['auth_options'] ) )
					{
						$options[$each['auth_level']] =  "{$each['auth_name']}: {$each['auth_description']}";
					}
				}
				$account->addElement( array( 'name' => 'user_group', 'label' => 'Account Type', 'type' => 'Select', 'required' => 'required', 'value' => ( @$values['user_group'] ? : $this->getParameter( 'user_group' ) ) ), $options );  
				$account->addRequirement( 'user_group', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
				unset( $authLevel );
			//	$account->addLegend( "Please choose a user Group you want to belong" );
		//		$form->addFieldset( $account );
			}
			else 
			{
				$account->addElement( array( 'name' => 'user_group', 'label' => 'Account Type', 'type' => 'Hidden', 'value' => null ) );  
			}
		}
		$account->addElement( array( 'name' => 'password', 'description' => $description, 'placeholder' => 'Choose a password', 'type' => 'InputPassword' ) );
		if( is_null( $values ) )
		{ 
			$account->addRequirement( 'password','WordCount=>6;;18' ); 
		}
		$account->addElement( array( 'name' => 'password2', 'label' => 'Confirm password', 'placeholder' => 'Confirm password', 'type' => 'InputPassword' ) );
/*		
		@$options = array( $_REQUEST['password'], $_REQUEST[Ayoola_Form::hashElementName( 'password' )] );
		{  }
	//	var_export( $options );
 		if( count( array_unique( $options ) ) !== 1 ) //	If I am using fake values
		{ 
			$account->addRequirement( 'password2', array( 'WordCount' => array( 6, 18 ), 'InArray' => $options ) ); 
		}
 */		if( $this->getGlobalValue( 'password2' ) ) //	If I am using fake values
		{ 
			$account->addRequirement( 'password2', array( 'DefiniteValueSilent' => $this->getGlobalValue( 'password' ) ) ); 
		}
	//	var_export( $_REQUEST['password'] );
		$account->addLegend( "$legend Account Information" );
		$ip = sprintf( "%u", ip2long( long2ip( ip2long( $_SERVER['REMOTE_ADDR'] ) ) ) );
		$ipType = is_null( $values ) ? 'creation_ip' : 'modified_ip';
		$account->addElement( "name=>$ipType:: type=>Hidden" );
		$account->addFilter( $ipType, 'DefiniteValue=>' . $ip );
		$account->addFilters( 'Trim::Escape' );
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{
			$database = 'cloud';
		}
		if( $database !== 'cloud' || is_null( $values ) )
		{
			$form->addFieldset( $account );
			if( $this->getGlobalValue( 'user_group' ) && is_array( $userOptions ) && in_array( 'allow_level_selection', $userOptions ) )
			{
				$authLevel = new Ayoola_Access_AuthLevel;
				$authLevel = $authLevel->selectOne( null, array( 'auth_level' => $this->getGlobalValue( 'user_group' ) ) );
				if( ! empty( $authLevel['additional_forms'] ) && is_array( $authLevel['auth_options'] ) && in_array( 'attach_forms', $authLevel['auth_options'] ) ) 
				{
					foreach( $authLevel['additional_forms'] as $formName )
					{
						$class = new Ayoola_Form_View( array( 'form_name' => $formName ) );
						$fieldsets = $class->getForm()->getFieldsets();
						foreach( $fieldsets as $fieldset ) 
						{
							$fieldset->appendElement = false;
							$form->addFieldset( $fieldset );
						}
					}
				}
				
			}
			$form->addFieldset( $personal );
	//		$form->oneFieldSetAtATime = true;
		}
		if( ! is_null( $values ) )
		{
			$settings = new Ayoola_Form_Element;
			$settings->id = __CLASS__ . 'settings';
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
			$authLevel = $filter->filter( $authLevel );
			$settings->addElement( array( 'name' => 'access_level', 'description' => 'Access level of user on this website.', 'type' => 'Select', 'value' => @$values['access_level'] ), $authLevel );
			$settings->addRequirement( 'access_level', array( 'Int' => null, 'InArray' => array_keys( $authLevel )  ) );
			unset( $authLevel );
			$option = array( 'No', 'Yes' );
			$settings->addElement( array( 'name' => 'enabled', 'description' => 'Enable this account', 'type' => 'Select', 'value' => @$values['enabled'] ), $option );
			$settings->addElement( array( 'name' => 'approved', 'description' => 'Approve this account', 'type' => 'Select', 'value' => @$values['approved'] ), $option );
			$settings->addElement( array( 'name' => 'verified', 'description' => 'Mark account as verified', 'type' => 'Select', 'value' => @$values['verified'] ), $option );
			$settings->addLegend( "$legend Settings" );
			$form->addFieldset( $settings );
		}
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
