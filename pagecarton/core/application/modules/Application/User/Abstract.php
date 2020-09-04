<?php
/**
 * PageCarton
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     *
     * @var Ayoola_Access_LocalUser
     */
	protected static $_localTable;
	
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
     * 
     * @var string
     */
	protected $_sortColumn = 'creation_time';
	
    /**
     * Other tables used for storing user related data
     *
     * @var array
     */
	protected $_otherTables = array( 'UserEmail', 'UserSettings', 'UserPassword', 'UserPersonalInfo', 'UserActivation' );
	
    /**
     * 
     * @param array
     * @return mixed
     */
    public static function getUsers( $where = array() )  
    {
		$userTable = 'Ayoola_Access_LocalUser';
		$userTable = $userTable::getInstance( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setAccessibility( $userTable::SCOPE_PROTECTED );
		$userTable->getDatabase()->getAdapter()->setRelationship( $userTable::SCOPE_PROTECTED );
		$response = $userTable->select( null, $where + array( 'access_level' => 99 ), array( 'disable_cache' => true ) );
		return $response;
    } 
	
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
			$database = 'private';
		}
		switch( $database )
		{
			case 'relational':
				$data = $this->getDbTable()->select( null, strtolower( implode( ', ', $this->_otherTables ) ), $this->_dbWhereClause );
				$this->_dbData = $data;
			break;
			case 'cloud':
			case 'file':
				// Find user in the LocalUser table
				$table = "Ayoola_Access_LocalUser";
				$table = $table::getInstance( $table::SCOPE_PUBLIC );
				$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PUBLIC );
				$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PUBLIC );

				//	Filter the result to save time
				$sortFunction2 = function( & $key, & $values )
				{ 
					$values = $values["user_information"];
                    if( empty( $values["username"] ) || empty( $values["email"] ) )
                    {
                        $values = false;
                    }
                    $key = $values["username"];	
                }; 
				$this->_dbData = $table->select( null, ( $this->_dbWhereClause ? : array() ) );	
				$this->_sortColumn = $this->getParameter( 'sort_column' ) ? : $this->_sortColumn;
				if( $this->_sortColumn )    
				{
					$this->_dbData = self::sortMultiDimensionalArray( $this->_dbData, $this->_sortColumn );
				}
				else
				{
					krsort( $this->_dbData );
					$this->_dbData = array_values( $this->_dbData );
				}
			break;
			case 'private':
				// Find user in the LocalUser table
				$table = "Ayoola_Access_LocalUser";
				$table = $table::getInstance( $table::SCOPE_PRIVATE );
				$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
				$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );

				//	Filter the result to save time
				$sortFunction2 = function( & $key, & $values )
				{ 
					$values = $values["user_information"];
                    if( empty( $values["username"] ) || empty( $values["email"] ) )
                    {
                        $values = false;
                    }
                    $key = $values["username"];	
                }; 
				$this->_dbData = $table->select( null, ( $this->_dbWhereClause ? : array() ) );	
				$this->_sortColumn = $this->getParameter( 'sort_column' ) ? : $this->_sortColumn;
				if( $this->_sortColumn ) 
				{
					$this->_dbData = self::sortMultiDimensionalArray( $this->_dbData, $this->_sortColumn );
				}
				else
				{
					krsort( $this->_dbData );
					$this->_dbData = array_values( $this->_dbData );
				}
			break;
		
		}
    } 
	
    /**
     * Overides the parent class
     * 
     */
	public static function getLocalTable()
    {
		if( ! self::$_localTable )
		{
			self::$_localTable = new Ayoola_Access_LocalUser();
		}
		return self::$_localTable;
	}
	
    /**
     * Overides the parent class
     * 
     */
	public static function getUserInfo( $identifier )
    {
		if( ! is_array( $identifier ) )
		{
			$identifier = array( 'user_id' => $identifier );
		}
		//	Check from local table
		$table = self::getLocalTable();

		//	look in all lookable places for login info
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
		
		if( $info = $table->selectOne( null, $identifier ) )
		{
			if( $info['user_information'] )  
			{
				$info = $info['user_information'];
			}
		}
		return $info;

	}
	
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData( $identifier = NULL )
    {
		
		do
		{
			//	Check from local table
            $table = $this->getDbTable();
            if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
            {
                $database = 'private';
            }
            switch( $database )
            {
                case 'private':
                    // Find user in the LocalUser table
                    $table = "Ayoola_Access_LocalUser";
                    $table = $table::getInstance( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
                break;
    			case 'file':
                    // Find user in the LocalUser table
                    $table = "Ayoola_Access_LocalUser";
                    $table = $table::getInstance( $table::SCOPE_PUBLIC . "xyz" );
                    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PUBLIC );
                    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PUBLIC );
                break;
            }
    
			$identifierInfo = $this->getIdentifier();
			$where = array( $this->getIdColumn() => array( $identifierInfo[$this->getIdColumn()], strtolower( $identifierInfo[$this->getIdColumn()] ) ) );
			if( $info = self::getUserInfo( $where ) )
			{
				$this->_identifierData = $info;
				break;
			}
			//	case 'relational':
			if( $database === 'relational' )
			{
				$data = $table->selectOne( null, strtolower( implode( ', ', $this->_otherTables ) ), $this->getIdentifier() );

				$this->_identifierData = $data;
				break;
			}		
		}
		while( false );
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
		if( @$_REQUEST['previous_url'] )
		{
			$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => $this->getObjectName() ) );
		}
		
		$form->submitValue = $submitValue ;

		$form->formNamespace = get_class( $this ) . $values['user_id'];

		require_once 'Ayoola/Form/Element.php';

		$additionalForms = array();
		if( ! empty( $_REQUEST['personal_info'] ) || $this->getParameter( 'personal_info' ) || @$values['firstname'] )
		{
			$personal = new Ayoola_Form_Element;
			$personal->id = __CLASS__ . 'personal';
			$personal->addElement( array( 'name' => 'firstname', 'placeholder' => 'Your given name', 'type' => 'InputText', 'value' => @$values['firstname'] ) );
			$personal->addRequirement( 'firstname','Name:: WordCount=>2;;20' );
			$personal->addElement( array( 'name' => 'lastname', 'placeholder' => 'Your family name', 'type' => 'InputText', 'value' => @$values['lastname'] ) );
			$personal->addRequirement( 'lastname','Name:: WordCount=>2;;20' );
			$option = array( 'M' => 'Male', 'F' => 'Female' );
			$personal->addElement( array( 'name' => 'sex', 'type' => 'Select', 'value' => @$values['sex'] ), $option );
			$personal->addRequirement( 'sex','InArray=>' . implode( ';;', array_keys( $option ) ) . ':: WordCount=>1;;1' );
			//	retrieve birthday
			if( @$values['birth_date'] )
			{
				switch( strlen( $values['birth_date'] ) )
				{
					case 8:
						$values['birth_year'] = $values['birth_date'][0] . $values['birth_date'][1] . $values['birth_date'][2] . $values['birth_date'][3];
						$values['birth_month'] = $values['birth_date'][4] . $values['birth_date'][5];
						$values['birth_day'] = $values['birth_date'][6] . $values['birth_date'][7];
					break;
					default:
						@list( $values['birth_year'], $values['birth_month'], $values['birth_day'] ) = explode( '-', $values['birth_date'] );
					break; 
				}
			}
			
			//	Month
			$options = array_combine( range( 1, 12 ), array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ) );
			$birthMonthValue = intval( @strlen( $values['birth_month'] ) === 1 ? ( '0' . @$values['birth_month'] ) : @$values['birth_month'] );
			$birthMonthValue = intval( $birthMonthValue ?  : $this->getGlobalValue( 'birth_month' ) );
			$personal->addElement( array( 'name' => 'birth_month', 'label' => 'Date of Birth', 'style' => 'min-width:10%;max-width:25%;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthMonthValue ), array( 'Month' ) + $options ); 
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
			if( strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 )
			{
				$personal->addFilter( 'birth_day', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'birth_day' ) ) );
			}
			
			//	Year
			//	Age must start from 13 yrs
			$options = range( date( 'Y' ) - 13, 1900 );
			$options = array_combine( $options, $options );
			$personal->addElement( array( 'name' => 'birth_year', 'label' => '', 'style' => 'min-width:10%;max-width:25%;display:inline-block;margin-right:0;', 'type' => 'Select', 'value' => @$values['birth_year'] ), array( 'Year' ) + $options );
			
			//	Birthday combined
			$personal->addElement( array( 'name' => 'birth_date', 'label' => 'Date of Birth', 'placeholder' => 'YYYY-MM-DD', 'type' => 'Hidden', 'value' => @$values['birth_date'] ) );
			$dob = $this->getGlobalValue( 'birth_year' );
			$dob .= strlen( $this->getGlobalValue( 'birth_month' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_month' ) ) : $this->getGlobalValue( 'birth_month' );
			$dob .= strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_day' ) ) : $this->getGlobalValue( 'birth_day' );
			$personal->addFilter( 'birth_date', array( 'DefiniteValue' => $dob ) );
			$this->getParameter( 'no_legend' ) ?  null : $personal->addLegend( "$legend Personal Information" );
		}
		$account = new Ayoola_Form_Element;
		$account->id = __CLASS__ . 'account';
		$description = 'Leave blank if you don\'t intend to change password.';   
		if( is_null( $values ) )
		{ 
			$account->addElement( array( 'name' => 'email', 'label' => 'Email Address', 'placeholder' => ' e.g. email@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) );

			//	username could no longer be needed
			if( ! $this->getParameter( 'email_not_required' ) )
			{ 
				$account->addRequirement( 'email', array( 'EmailAddress' => null, 'DuplicateUser' => array( 'email' ) ) );
			}
			//	username could no longer be needed
			if( ! $this->getParameter( 'no_username' ) )
			{ 

				$account->addElement( array( 'name' => 'username', 'label' => 'Username', 'placeholder' => 'Choose a username', 'type' => 'InputText', 'onchange' => 'ayoola.addShowProfileUrl( this );', 'onfocus' => 'ayoola.addShowProfileUrl( this );', 'onkeyup' => 'ayoola.addShowProfileUrl( this );', 'value' => @$values['username'] ) );
				$account->addRequirement( 'username', array( 'Username' => null, 'DuplicateUser' => array( 'username' ) ) );
			}			
			if( ! $this->getParameter( 'no_password' ) )
			{ 

				$account->addElement( array( 'name' => 'password', 'label' => 'Password', 'autocomplete' => 'new-password', 'placeholder' => 'Choose a password', 'type' => 'InputPassword' ) );
				if( is_null( $values ) )
				{ 
					$account->addRequirement( 'password','WordCount=>6;;180' ); 
				}
				$account->addElement( array( 'name' => 'password2', 'autocomplete' => 'new-password', 'label' => 'Confirm password', 'placeholder' => 'Confirm password', 'type' => 'InputPassword' ) );
				if( $this->getGlobalValue( 'password2' ) ) //	If I am using fake values
				{ 
					$account->addRequirement( 'password2', array( 'DefiniteValueSilent' => $this->getGlobalValue( 'password' ) ) );

				}
			}
			$this->getParameter( 'no_legend' ) ?  null : $account->addLegend( "$legend" );
			$ip = sprintf( "%u", ip2long( long2ip( ip2long( $_SERVER['REMOTE_ADDR'] ) ) ) );
			$ipType = is_null( $values ) ? 'creation_ip' : 'modified_ip';
			$account->addElement( "name=>$ipType:: type=>Hidden" );
			$account->addFilter( $ipType, 'DefiniteValue=>' . $ip );
			$account->addFilter( 'email', 'Trim' );
			$account->addFilter( 'username', 'Trim' );

		}
	
		if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
		{

		}
		if( $database !== 'cloud' || is_null( $values ) )
		{
			if( $userOptions = Application_Settings_Abstract::getSettings( 'UserAccount', 'user_options' ) )
			{

			}
			$userGroupToCreate = ( @$values['access_level'] ? : $this->getParameter( 'user_group' ) ) ? : @$_REQUEST['user_group'];

			if( $userGroupToCreate )
			{

			}
			if( is_array( $userOptions ) && in_array( 'allow_level_selection', $userOptions ) )
			{
				$authLevel = new Ayoola_Access_AuthLevel;
				$authLevel = $authLevel->select();
				$options = array();
				foreach( $authLevel as $each )
				{
					if( is_array( $each['auth_options'] ) && in_array( 'allow_signup', $each['auth_options'] ) )
					{

						$options[$each['auth_level']] =  "{$each['auth_name']}"; 

						if( $each['auth_level'] == $userGroupToCreate )  
						{
							if( ! empty( $each['additional_forms'] ) && is_array( $each['auth_options'] ) && in_array( 'attach_forms', $each['auth_options'] ) ) 
							{

								$additionalForms = array_merge( $additionalForms, $each['additional_forms'] );
							}

							// we found what we are looking for
							$options = array();
							break;
						}
					}
				}
				if( $options && empty( $values['access_level'] ) )
				{
					$account->addElement( array( 'name' => 'user_group', 'label' => 'Account Type', 'type' => 'Select', 'required' => 'required', 'value' => $userGroupToCreate ), $options );  
					$account->addRequirement( 'user_group', array( 'Int' => null, 'InArray' => array_keys( $options )  ) );
					unset( $authLevel );
				}
				else
				{
					$account->addElement( array( 'name' => 'user_group', 'type' => 'Hidden', 'value' =>  $userGroupToCreate ) );  
				}

			}
			else 
			{
				$account->addElement( array( 'name' => 'user_group', 'type' => 'Hidden', 'value' =>  $userGroupToCreate ) );  
			}
			$form->addFieldset( $account );
			
			@$personal ? $form->addFieldset( $personal ) : null;

			foreach( $additionalForms as $formName )
			{
				if( empty( $formName ) )
				{
					continue;
				}
				$parameters = array( 'form_name' => $formName, 'default_values' => $values );  

				$class = new Ayoola_Form_View( $parameters );

				$fieldsets = $class->getForm()->getFieldsets();
				foreach( $fieldsets as $fieldset ) 
				{
					$fieldset->appendElement = false;
					$form->addFieldset( $fieldset );
				}
			}

		}
		if( ! is_null( $values ) && self::hasPriviledge( 98 ) )
		{
			$settings = new Ayoola_Form_Element;
			$settings->id = __CLASS__ . 'settings';
			$authLevel = new Ayoola_Access_AuthLevel;
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
			$authLevel = $filter->filter( $authLevel );
			if( ! self::hasPriviledge() )
			{
				unset( $authLevel[99], $authLevel['99'] );
			}
			$settings->addElement( array( 'name' => 'access_level', 'description' => 'Access level of user on this website.', 'type' => 'Select', 'value' => @$values['access_level'] ), $authLevel );
			$settings->addRequirement( 'access_level', array( 'Int' => null, 'InArray' => array_keys( $authLevel )  ) );
			unset( $authLevel );
			$option = array( 'No', 'Yes' );
			$settings->addElement( array( 'name' => 'enabled', 'description' => 'Enable this account', 'type' => 'Select', 'value' => @$values['enabled'] ), $option );
			$settings->addElement( array( 'name' => 'approved', 'description' => 'Approve this account', 'type' => 'Select', 'value' => @$values['approved'] ), $option );
			$settings->addElement( array( 'name' => 'verified', 'description' => 'Mark account as verified', 'type' => 'Select', 'value' => @$values['verified'] ), $option );
			$this->getParameter( 'no_legend' ) ?  null : $settings->addLegend( "$legend Settings" );
			$form->addFieldset( $settings );
		}
		$supplementaryForm = Application_Settings_CompanyInfo::getSettings( 'UserAccount', 'supplementary_form' );

		if( $supplementaryForm )
		{
			$parameters = array( 'form_name' => $supplementaryForm, 'default_values' => $values );  

			$orderFormClass = new Ayoola_Form_View( $parameters );
			
			if( $orderFormClass->getForm() )
			{
				foreach( $orderFormClass->getForm()->getFieldsets() as $each )  
				{

					
					$form->addFieldset( $each );
				}
				$form->submitValue = 'Continue...';
			}
		}
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
