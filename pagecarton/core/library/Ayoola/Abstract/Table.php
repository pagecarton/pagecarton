<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.26.2012 10.08am ayoola $
 */

/**
 * @see Ayoola_Exception 
 * @see Ayoola_Abstract_Playable 
 */
 
require_once 'Ayoola/Exception.php';
require_once 'Ayoola/Abstract/Playable.php';


/**       
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Table extends Ayoola_Abstract_Playable
{
    /**
     * Message to display in the form is a unique record exits
     *
     * @var string
     */
	const MESSAGE_AMBIGUOUS = 'Name already exist, please choose a different name';
	
    /**
     * Form to perfom a specific operation
     *
     * @var Ayoola_Form
     */
	protected $_form;
	
    /**
     * Use this var to pass fake values to Ayoola_Form
     *
     * @var array
     */
	public $fakeValues;
	
    /**
     * Info Used to connect to the db
     * 
     * @var array
     */
	private static $_dbInfo;
	
    /**
     * Table
     *
     * @var Ayoola_Dbase_Table_Interface
     */
	protected $_dbTable;
	
    /**
     * Data from the Database
     *
     * @var array
     */
	protected $_dbData;
	
    /**
     * Where clause to select data from the Database
     *
     * @var array
     */
	protected $_dbWhereClause;
	
    /**
     * Data from the Database that matches the _identifier criteria
     *
     * @var array
     */
	protected $_identifierData;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass;
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn;
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn;
	
    /**
     * Keys for Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array();
	
    /**
     * Identifier that must be verified
     * 
     * @var array $columnName => $tableClass
     */
	protected $_identifierKeysToVerify = array();
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifier;
	
    /**
     * Last Insert Id
     * 
     * @var int
     */
	public $insertId;
	
    /**
     * Returns _tableClass
     * 
     * @return string
     */
	public function getTableClass()
    {
		if( ! is_null( $this->_tableClass ) ){ return $this->_tableClass; }
	//	var_export( get_class( $this ) );
		require_once 'Ayoola/Exception.php';
		throw new Ayoola_Exception( 'CLASS FOR TABLE IS NOT SET FOR ' . get_class( $this )  );
    } 
	
    /**
     * Sets _tableClass
     * 
     * @param string
     */
	public function setTableClass( $className )
    {
		require_once 'Ayoola/Loader.php';
		if( $class = Ayoola_Loader::loadClass( $className ) )
		{
			$this->_tableClass = $columnName;
		}
    } 
	
    /**
     * Returns _idColumn
     * 
     * @return string
     */
	public function getIdColumn()
    {
		if( is_null( $this->_idColumn ) ){ $this->setIdColumn(); }
		return $this->_idColumn;
    } 
	
    /**
     * Sets _idColumn
     * 
     * @param string
     */
	public function setIdColumn( $columnName = null )
    {
		if( is_null( $columnName ) )
		{ 
			$columnName = explode( '_', $this->getTableClass() ); 
			$offset = count( $columnName ) - 1;
			$columnName = $columnName[$offset];
			$columnName = strtolower( $columnName ) . '_id';
		}
		$this->_idColumn = $columnName;
    } 
	
    /**
     * Returns _identifierData
     * 
     * return array
     */
	protected function getIdentifierData()
    {
		if( is_null( $this->_identifierData ) ){ $this->setIdentifierData(); }
		return $this->_identifierData;
    } 
	
    /**
     * Sets _identifierData
     * 
     */
	protected function setIdentifierData( $identifier = null )
    {
		if( is_null( $identifier ) ){ $identifier = $this->getIdentifier(); }
		$table = $this->getDbTable();
	//	var_export( $identifier );
		if( is_array( $this->_dbWhereClause ) )
		{
			$identifier = $identifier ? : array();
			$identifier += $this->_dbWhereClause;
		}
		$this->_identifierData = (array) $table->selectOne( null, $identifier );
	//	var_export( $this->_identifierData );
		
		//	this is needed in Ayoola_Abstract_Viewable::view();
		$this->_objectTemplateValues = $this->_identifierData;
 //		var_export( $this->_identifierData );
   } 
	
    /**
     * Returns _dbData for public use
     * 
     * return array
     */
	public function getPublicDbData()
    {
		return array();
    } 
	
    /**
     * Returns _dbData
     * 
     * return array
     */
	public function getDbData()
    {
		if( is_null( $this->_dbData ) ){ $this->setDbData(); }
		return $this->_dbData;
    } 
	
    /**
     * 
     */
	protected function prepareDbWhereClauseForSearch()
    {
		
		//	power our search box
		if( @$_REQUEST['db_where_clause_field_name'] && @$_REQUEST['search-object'] == $this->getObjectName() )
		{
			$field = $_REQUEST['db_where_clause_field_name'];
			$value = @$_REQUEST['db_where_clause_field_value'];
			if( ! is_array( $field ) )
			{
				$field = array( $field );
			}
			if( ! is_array( $value ) )
			{
				$value = array( $value );
			}
			$dbWhereClause = array_combine( $field, $value );
			$this->_dbWhereClause = array_merge( $dbWhereClause ? : array(), $this->_dbWhereClause ? : array() );
		//	self::v( $this->_dbWhereClause );   
		}
	}
    /**
     * Sets _dbData
     * 
     */
	protected function setDbData()
    {
		$table = $this->getDbTable();
//		var_Export( $this->_dbWhereClause );
		
		//	power our search box
		$this->prepareDbWhereClauseForSearch();
		
		$this->_dbData = (array) $table->select( null, $this->_dbWhereClause );
		$this->_sortColumn = $this->getParameter( 'sort_column' ) ? : $this->_sortColumn;
	//	@$this->_sortColumn = ( $_REQUEST['pc_sort_column'] && $_REQUEST['pc_sort_column_table'] == get_class( $table ) ) ? $_REQUEST['pc_sort_column'] : $this->_sortColumn;
//		var_Export( $this->_sortColumn );
		if( $this->_sortColumn )    
		{
			$this->_dbData = self::sortMultiDimensionalArray( $this->_dbData, $this->_sortColumn );
		}
		else
		{
	//		krsort( $this->_dbData );
			$this->_dbData = array_values( $this->_dbData );
		}
    } 
	
    /**
     * 
     *
     * @param array 
     * @param string
     */
    public static function sortMultiDimensionalArray( $array, $key )
    {
		if( ! is_array( $array ) )
		{
			return false;
		}
		$sortColumn = create_function  
		(
			'$a,$b',
			'
				@$a = $a["' . $key . '"];
				@$b = $b["' . $key . '"];
			return is_numeric( $a ) && is_numeric( $b ) ? ( ( $a < $b ) ? -1 : ( ( $a > $b ) ? 1 : 0 ) ) : strcmp( $a, $b );'
		//	strcmp($a["fruit"], $b["fruit"]);
		
		);  
		usort( $array, $sortColumn );
		return $array;
    } 
	
    /**
     * This method retrieves the DB Table from the object
     *
     * @param void
     * @return Ayoola_Dbase_Table_Interface
     */
    protected function getDbTable()
    {
		if( null === $this->_dbTable ){ $this->setDbTable(); }
		return $this->_dbTable;
    } 
	
    /**
     * This method sets the DbTable to a value
     *
     * @param Ayoola_Dbase_Table_Interface
     */
    protected function setDbTable( Ayoola_Dbase_Table_Interface $table = null )
    {		
		if( null === $table ){ $table = $this->getTableClass(); }
		if( ! Ayoola_Loader::loadClass( $table ) )
		{
			throw new Ayoola_Exception( 'TABLE CLASS NOT FOUND ' . $table );
		}
		//	var_export( static::$_dbInfo );
		$table = new $table( new Ayoola_Dbase( self::$_dbInfo ) );
		$this->_dbTable = $table;
		return $this->_dbTable;
    } 
	
    /**
     * This method inserts a record if record does not exist and retrieve the id
     *
     */
     public static function getPrimaryId( Ayoola_Dbase_Table_Interface $table, array $insertValues, array $selectValues = null, array $options = null )
    {
		//	var_export( $insertValues );
		$selectValues = $selectValues ? : $insertValues;
		do
		{
			//	Check where our user information is being saved.
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
			//	$database = 'cloud';
			}
			$primaryId = rand( 19999, 10000000 );
			switch( $database )
			{
				case 'cloud':
					$table = get_class( $table );
					if( true !== $table::isApiConnectionAllowed() )
					{
						throw new Ayoola_Abstract_Exception( $table . ' CANNOT BE USED WITH AYOOLA API' );
					}
					$response = Ayoola_Api_PrimaryId::send( array( 'table' => $table, 'insert' => $insertValues, 'select' => $selectValues  ) );
				//	var_export( $response );
					if( is_numeric( $response['data'] ) )
					{
						$primaryId = $response['data'];
					}
				break;
				case 'relational':
					if( ! $primaryId = $table->selectOne( null, null, $selectValues ) )
					{
			//			var_export( $insertValues );
						$table->insert( $insertValues );
						$primaryId = $table->getLastInsertId();
			//			var_export( __LINE__ );
						break;
					}
					$primaryId = $primaryId[$table->getTableName() . '_id'];
				break;
			}
			//	var_export( $this->_dbInfo );
			
		}
		while( false );			
		if( ! $primaryId )
		{
	//		throw new Ayoola_Abstract_Exception( 'COULD NOT RETRIEVE A PRIMARY ID' );
		}
		return $primaryId;
	} 
	
    /**
     * Do the class process to require a profile before continuing in class process
     *
     * @return boolean
     */
    public function requireRegisteredAccount()
    {
		if( ! Ayoola_Application::getUserInfo( 'email' ) )
		{
			$class = new Ayoola_Access_AccountRequired();
			$class->initOnce();
			if( ! $formV = $class->getForm()->getValues() )
			{
				$this->setViewContent( '<h3 style="margin: 1em 0;">Login to continue</h3>' );
				$this->setViewContent( $class->view() );
				return false;
			}
			else
			{
		//		$this->setViewContent( '<h2 style="margin: 1em 0;">Continue as @' . Ayoola_Application::getUserInfo( 'email' ) . '</h2>' );
				return true;
			}
		}
		return true;
	}
	
    /**
     * Do the class process to require a profile before continuing in class process
     *
     * @return boolean
     */
    public function requireProfile()
    {
		if( ! $profileInfo = Application_Profile_Abstract::getMyDefaultProfile() )
		{
	//		self::v( Application_Profile_Abstract::getMyProfiles() ); 
	//		var_export( Application_Profile_Abstract::getMyDefaultProfile() );
			//	profile now required for posts
			$class = new Application_Profile_Creator();
			$class->initOnce();
			if( ! $formV = $class->getForm()->getValues() )
			{
				$this->setViewContent( '<h2 style="margin: 1em 0;">Tell us about yourself first...</h2>' );
				$this->setViewContent( $class->view() );
				return false;
			}
			else
			{
			//	$this->setViewContent( '<h2>Continue as @' . $formV['profile_url'] . '</h2>' );
				return true;
			}
		}
		return true;
	}
	
    /**
     * Set the form object
     *
     * @param Ayoola_Form
     */
    public function setForm( Ayoola_Form $form )
    {
		foreach( self::getHooks() as $class )
		{
			$class::hook( $this, __FUNCTION__, func_get_args() );
		}
		
		//	INTRODUCING CALL-TO-ACTION
		if( $this->getParameter( 'call_to_action' ) )
		{
			$form->callToAction = $this->getParameter( 'call_to_action' );
		}
		$this->fakeValues = $this->fakeValues ? : $this->getParameter( 'fake_values' );
		if( count( $this->fakeValues ) )
		{ 
			$form->fakeValues = $this->fakeValues; 
			$form->oneFieldSetAtATime = false; 
		}
		
		//	For markup templates, we need the forms to process once.
		if( $this->getMarkupTemplate() )
		{
			$form->oneFieldSetAtATime = false; 
		}
		if( $formParameters = $this->getParameter( 'form' ) )
		{ 
			$form->setParameter( @$formParameters ? : array() );
			$form->setAttributes( @$formParameters['attributes'] ? : array() );
		}
	//	var_export( 'rgrtg' );
		$this->_form = $form;
    }
	
    /**
     * returns the form object
     *
     * @param void
     * @return string
     */
    public function getForm()
    {	
		if( null === $this->_form )
		{ 
			if( method_exists( $this, 'createForm' ) )
			{
				$this->createForm( null ); 
			}
			else
			{
				$this->_form = new Ayoola_Form();
			}
		}
		return $this->_form;
    }
	
    /**
     * Sets the value of the ID used in identifying a particular package
     *
     * @param array
     */
    public function setIdentifier( Array $values = null )
    {	
		if( null === $values ){ $values = $_REQUEST; }
	//	$this->_identifier = is_array( $this->_identifier ) ? $this->_identifier : array();
		if( empty( $this->_identifierKeys ) && ! empty( $this->_idColumn ) )
		{
			$this->_identifierKeys = array( $this->_idColumn );
		} 
	//	var_export( $this->_identifierKeys );
		
		foreach( $this->_identifierKeys as $value )
		{
//	var_export( $value );
			if( ! array_key_exists( $value, $values ) )
			{ 
	//			var_export( $value );
	//			var_export( $values );
				if( array_key_exists( Ayoola_Form::hashElementName( $value ), $values ) )
				{
					$values[$value] = $values[Ayoola_Form::hashElementName( $value )];
				//	$value = Ayoola_Form::hashElementName( $value );
				}
				elseif( null === $this->getParameter( $value ) )
				{
					throw new Ayoola_Exception( 'ID column is not available' ); 
				}
				else
				{
					//	Allow identifier to be passable with parameter
					$values[$value] = $this->getParameter( $value );
				//	$_GET[$value] = $values[$value];
					
				}
			}
			$this->_identifier[$value] = $values[$value];
		}
    }
	
    /**
     * Returns the value of the ID used in identifying a particular package
     *
     * @param array
     */
    public function getIdentifier( $key = null )
    {	
		if( empty( $this->_identifier ) ){ $this->setIdentifier(); }
		$this->_identifier = $this->_identifier ? : array();
		return array_key_exists( $key, $this->_identifier ) ? $this->_identifier[$key] : $this->_identifier;
    }
	
    /**
     * Check if user is authorized to use identifier
     *
     * @param int user id
     */
    protected function verifyIdentifier()
    {		
		$identifier = $this->getIdentifier();
		//	var_export( $identifier );
		foreach( $this->_identifierKeysToVerify as $columnName => $tableClass )
		{
			$tableClass = new $tableClass();
			$found = $tableClass->selectOne( null, null, array( $columnName => $identifier[$columnName], 'user_id' => Ayoola_Application::getUserInfo( 'user_id' ) ) );
		//	var_export( $identifier );
			if( ! $found ){ throw new Ayoola_Exception( "USER IS NOT AUTHORIZED TO USE IDENTIFER {$columnName}" ); }
		//	var_export( $found );
		}
		return true;
    } 
	
    /**
     * 
     * 
     * @return null
     */
	protected function sendConfirmationMail( Array $values = null )
    {
	//	$values = $this->getForm()->getValues();
	//	if( is_array( $autoValues ) ){ $values = $autoValues; }
		//	confirmation email
		if( $this->getParameter( 'pc_send_confirmation_mail' ) && $this->getParameter( 'body' ) && $this->getParameter( 'to' ) && array_key_exists( $this->getParameter( 'to' ), $values ) )
		{
			$body = self::replacePlaceholders( $this->getParameter( 'body' ), $values );
			$to = $values[$this->getParameter( 'to' )];   
			self::sendMail( array( 'body' => $body, 'to' => $to, ) + $this->getParameter() );
		}
	}
	
    /**
     * Inserts the Data into Storage
     * 
     * @return bool
     */
	protected function insertDb( Array $autoValues = null )
    {
	//	var_export( $autoValues );
		$values = $this->getForm()->getValues();
		if( is_array( $autoValues ) ){ $values = $autoValues; }
		if( ! $values ){ return false; }
		try
		{ 
			$response = $this->getDbTable()->insert( $values ); 
			
			//	confirmation email
			$this->sendConfirmationMail( $values );   
			return $response;
		}
		catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e )
		{
			//$class = get_class( $this->getDbTable()->getDatabase()->getAdapter() );
			if( $e->getMessage() == Ayoola_Dbase_Adapter_Xml_Table_Abstract::ERROR_INSERT_AMBIGUOUS )
			{
				$this->getForm()->setBadnews( self::MESSAGE_AMBIGUOUS );
				$this->setViewContent( $this->getForm()->view(), true );
			}
		//	var_export( $e->getMessage() );			
			return false;
		}
    } 
	
    /**
     * Inserts the Data into Storage
     * 
     * @returns int
     */
	protected function deleteDb( $automated = true )
    {
		try
		{
			if( false == $automated )
			{
				if( ! $values = $this->getForm()->getValues() ){ return false; }
			}
			return $this->getDbTable()->delete( $this->getIdentifier()  );
		}
		catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e )
		{ 
		//	echo $e->getMessage();
			$this->getForm()->setBadnews( 'Error - Cannot alter a protected resource' );
			$this->setViewContent( $this->getForm()->view(), true );
			return false;
		}
    } 
	
    /**
     * creates the form for deleting a record
     * 
     */
	public function createDeleteForm( $name )
    {
		$this->createConfirmationForm( 'Delete ', 'Delete "' . $name . '"' );
    } 
	
    /**
     * creates the form for deleting a record
     * 
     */
	public function createConfirmationForm( $name = null, $description = null, array $options = null  )
    {
        $form = new Ayoola_Form( array( 'name' => @$options['name'] ? : $this->getObjectName(), 'data-not-playable' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$options =  array();
		$name =  $name ? : 'Continue...';
		
		//	This allows the form to go through because the 'data-pc-ignore-field' => 'true' has made the form requirements invisible.
		//	Invisble fields make the form validation always return false;    
		$fieldset->addElement( array( 'name' => 'confirmation-trigger', 'description' =>  $description, 'type' => 'Hidden' ), $options );
		$description ? $fieldset->addElement( array( 'name' => 'confirmation', 'label' => $description, 'type' => 'Radio' ), $options ) : null;
	//	$fieldset->addRequirement( 'action_confirmation', array( 'WordCount' => array( 10, 100 ) ) );
	//	$fieldset->addRequirement( 'confirmation', array( 'DefiniteValue' => 'Yes' ) );
	//	$fieldset->addLegend( $name );
//	var_export( $_POST );

        $form->submitValue = $name;
	//	$fieldset->addRequirement( __METHOD__, array( 'DefiniteValue' => $name ) );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	
    /**
     * Inserts the Data into Storage
     * 
     */
	protected function updateDb( Array $autoValues = null )
    {
		try
		{
			if( is_array( $autoValues ) )
			{ 
				$values = $autoValues; 
			}
			elseif( $values = $this->getForm()->getValues() )
			{
			//	;
			}
			if( ! $values ){ return false; }
	//		var_export( $autoValues );
	//		var_export( $this->getIdentifier() );
	//		var_export( $values );
			if( $response = $this->getDbTable()->update( $values, $this->getIdentifier() ) )
			{
	//		var_export( $response );
				return $response;
			}
	//	var_export( $response );
		}
		catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e )
		{ 
		//	echo $e->getMessage();
			$this->getForm()->setBadnews( 'Error - Cannot alter a protected resource' );
			$this->setViewContent( $this->getForm()->view(), true );
			return false;
		}
		return false;
    } 
	
    /**
     * Inserts a user record on a user specific table
     *
     * @param Ayoola_Dbase_Table_Interface
     * @param array Values to insert
     */
    protected static function setUserRecord( Ayoola_Dbase_Table_Interface $table, array $values )
    {		
	//	$table = new Application_User_UserKolo;
		$values['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
		if( ! $table->insert( $values ) ){ return false; }
		return true;
    } 
	
    /**
     * Retrieves user record on a user specific table
     *
     * @param Ayoola_Dbase_Table_Interface
     */
    public static function getUserRecord( Ayoola_Dbase_Table_Interface $table, array $identifier = null )
    {		
	//	$table = new Application_User_UserKolo;
		$where = array();
		if( is_array( $identifier ) ){ $where = $identifier; }
		$where['user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
		if( ! $table = $table->select( null, null, $where ) ){ return false; }
		return $table;
    } 
	
    /**
     * Splits base64 data that usually would come from user fields
     *
     * @param string base64 data
     * @return array Data info
     */
    public static function splitBase64Data( $data )   
    {		
	//	var_export( $data );
		$baseArray = explode( ',', $data );
		$baseExt = null;
		if( count( $baseArray ) > 1 )
		{
			$data = base64_decode( array_pop( $baseArray ) );
			$baseExt = array_pop( $baseArray );
//	var_export( array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) ) ); 
			$baseExt = array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) );
	//		var_export( $baseExt ); 
		}
		else
		{
			$data = base64_decode( $data );
		}
		return array( 'data' => $data, 'extension' => $baseExt );
    } 
	
    /**
     * Get the runtime "global" value of a form element
     *
     * @param string Element Name
     */
    public function getGlobalValue( $name, $defaultValue = null, $arrayKey = null )
    {		
	//	argu
		$this->fakeValues = $this->fakeValues ? : $this->getParameter( 'fake_values' );
	//	var_export( $defaultValue );
		if( isset( $this->fakeValues[$name] ) )
		{
			$value = $this->fakeValues[$name];
		//	var_export( $value ); 
		}   
		else
		{
			$value = Ayoola_Form::getGlobalValue( $name, $defaultValue );
	//	var_export( $value ); 
		}
	//	if( $name == 'page_options' )
		{
		//	self::v( $this->fakeValues );
		//	self::v( $name );
		//	self::v( $defaultValue );
		//	self::v( $value );
		//	self::v( Ayoola_Form::getGlobalValue( $name, $defaultValue ) );  
		}
	//	var_export( $defaultValue );
		return ! is_null( $arrayKey ) ? @$value[$arrayKey] : $value;
    }    
	// END OF CLASS
}
