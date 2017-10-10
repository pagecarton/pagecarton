<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table
 * @see Ayoola_Dbase_Adapter_Xml_Table_Exception
 */
require_once 'Ayoola/Dbase/Adapter/Xml/Exception.php';
require_once 'Ayoola/Dbase/Adapter/Xml/Table.php';




/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Adapter_Xml_Table_Abstract extends Ayoola_Dbase_Adapter_Xml_Table
{

    /**
     * The allowed Keywords
     *
     * @var array
     */
	protected static $_allowedKeywords = 
	array(	
			'UNIQUE' => array( 'type' => 'REFERENCE_KEY' ), 'PRIMARY' => array( 'type' => 'REFERENCE_KEY' ), 
			'INT' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'intval' ), 
			'ARRAY' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'serialize' ), 
			'JSON' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'json_encode' ), 
//			'BOOL' => array( 'type' => 'DATA_TYPE', 'filterDataType' => create_function( '$object', 'return (bool) $object;' ) ), 
			'INPUTTEXT' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'strval' ), 
			'TEXTAREA' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'strval' ), 
			'FLOAT' => array( 'type' => 'DATA_TYPE', 'filterDataType' => 'floatval' ),
			'FOREIGN_KEYS' => array( 'type' => 'OPERATION', 'setDataTypes' => 'setTableForeignKeys' ),
			'RELATIVES' => array( 'type' => 'OPERATION', 'setDataTypes' => 'setTableRelatives' )  
		);

    /**
     * DataType Filters
     *
     * @param array
     */
    protected static $_dataTypeFilters = array( 'serialize' => array( 'array' => 'serialize', 'string' => 'unserialize', ), 
												'json_encode' => array( 'array' => 'json_encode', 'string' => 'json_decode', 'second_parameter' => true, ),
												'json_decode' => array( 'array' => 'json_encode', 'string' => 'json_decode', 'second_parameter' => true, ),
												);

    /**
     * Whether to use cache result in query
     *
     * @param boolean
     */
    protected $_useCacheResult = true; 
	
    /**
     * Name of the cache file
     *
     * @var string
     */
	protected $_cacheFilename;
	
    /**
     * Table Data Types
     *
     * @var array
     */
	protected $_tableDataTypes;
	
    /**
     * Table Relations
     *
     * @var array
     */
	protected $_tableRelatives;
	
    /**
     * Table Foreign Keys
     *
     * @var array
     */
	protected $_tableForeignKeys;

    /**
     * Error Message to throw if inserting an ambiguous value into a unique field
     *
     * @var string
     */
	const ERROR_INSERT_AMBIGUOUS = 'Ambiguous value on a unique column';

    /**
     * Error Message to throw if inserting an ambiguous value into a unique field
     *
     * @var string
     */
	const ERROR_INSERT_ID = 'Ambiguous row ID';

    /**
     * The Tagname of the Document Element
     *
     * @var string
     */
	const TAGNAME_DOCUMENT_ELEMENT = 'DOCUMENT_ELEMENT';
	
    /**
     * The Tagname of the Data Types
     *
     * @var string
     */
	const TAGNAME_DATA_TYPES = 'DATA_TYPES';
	
    /**
     * The Tagname of the Data Types
     *
     * @var string
     */
	const TAGNAME_RELATIVES = 'RELATIVES';
	
    /**
     * The Tagname of the Data Types
     *
     * @var string
     */
	const TAGNAME_FOREIGN_KEYS = 'FOREIGN_KEYS';
	
    /**
     * The Tagname of the TABLE SETTINGS
     *
     * @var string
     */
	const TAGNAME_TABLE_SETTINGS = 'TABLE_SETTINGS';
	
    /**
     * The Tagname of the REFERENCE KEYS
     *
     * @var string
     */
	const TAGNAME_REFERENCE_KEYS = 'REFERENCE_KEYS';
	
    /**
     * The Tagname of the RECORDS
     *
     * @var string
     */
	const TAGNAME_RECORDS = 'RECORDS';
	
    /**
     * tAG NAME FOR tABLE FIELD
     *
     * @var STRING
     */
	const TAG_TABLE_FIELD = 'TABLE_FIELD';
	
    /**
     * tAG NAME FOR tABLE FIELD
     *
     * @var STRING
     */
	const ATTRIBUTE_ROW_ID = 'ROW_ID';
	
    /**
     * tAG NAME FOR FIELD KEY
     *
     * @var STRING
     */
	const ATTRIBUTE_FIELD_KEY = 'FIELD_KEY';
	
    /**
     * tAG NAME FOR tABLE ROW
     *
     * @var STRING
     */
	const TAG_TABLE_ROW = 'TABLE_ROW';
		
    /**
     * Return the directory of the cache files
     *
     */
    public static function getCacheDirectory()
    {
		return CACHE_DIR . DS . Ayoola_Application::getDomainSettings( 'domain_name' ) . DS . 'XMLDB';
    }
		
    /**
     * Return the directory of the cache files of a particular table
     *
     */
    public function getTableCacheDirectory( $tableName )
    {
		$name = explode( '_', get_class( $this ) );
		return self::getCacheDirectory() . DS  . array_pop( $name ) . DS . str_ireplace( '_', DS, $this->className . DS . $tableName );
    }
		
    /**
     * Sets the filename of the cache file
     *
     */
    public function setCacheFilename()
    {
	//	var_export( get_class( $this ) );
		$arguments = md5( serialize( func_get_args() ) . '' . $_SERVER['HTTP_HOST'] );
		
	//	require_once 'Ayoola/Filter/Name.php';
	//	$filter = new Ayoola_Filter_Name();
	//	$file = $filter->filter( $arguments );
		$file = strtolower( implode( DS, str_split( $arguments, 2 ) ) );
		$file = $this->getTableCacheDirectory( $this->getTableName() ) . DS . $file . $this->getTableName();
		$this->_cacheFilename = $file;
    }
		
    /**
     * Returns the filename of the cache file
     *
     */
    public function getCacheFilename()
    {
      if( is_null( $this->_cacheFilename ) ){ $this->setCacheFilename( func_get_args() ); }
      return $this->_cacheFilename;
    } 
		
		
    /**
     *
     */
    public function clearCache( $clearAll = null )
    {
		try
		{
			$directory = self::getCacheDirectory();
			if( is_null( $clearAll ) ){ $directory = dirname( $this->getCacheFilename() ); }
			$this->setCacheFilename();

			//	Delete the files 
			$files = Ayoola_Doc::getFilesRecursive( $directory );
			foreach( $files as $file ){ unlink( $file ); }
			@Ayoola_Doc::removeDirectory( $directory );
			//var_export( $table );
		}catch( Exception $e ){ return false; }
		return true;
    } 
	
    /**
     * Check Valid Table
     *
     * @param string The table 
     * @return bool 
     */
    public static function checkValidTable( $class )
    {
		if( ! is_object( $class ) )
		{ 
			if( ! $path = Ayoola_Loader::loadClass( $class ) ){ return false; }
			$class = new $class;
		}
		if( ! $class instanceof Ayoola_Dbase_Table_Interface ){ return false; }
		return true;
    } 

    /**
     * Sets Related Tables
     *
     * @return array
     */
    public function setRelatives()
    {
        $node = $this->getXml()->createElement( self::TAGNAME_RELATIVES );
		foreach( $this->getTableRelatives() as $field => $tableList )
		{
			$relatedTables = array_map( 'trim', explode( ' ', $tableList ) );
			foreach( $relatedTables as $table )
			{
				if( ! $class = Ayoola_Loader::loadClass( $table ) ){ continue; }
				if( ! method_exists( $table, 'getInstance' ) ){ continue; }
				@$class = $table::getInstance();
				if( ! $class instanceof Ayoola_Dbase_Adapter_Interface ){ continue; }
				$node->setAttribute( $table, $field );
			//	var_export( get_class( $class ) );  
			}
		}
		return $node;
    } 

    /**
     * Gets Related Tables
     *
     * @return array
     */
    public function getRelatives()
    {
		$relatives = array();
		if( $node = $this->getXml()->getElementsByTagName( self::TAGNAME_RELATIVES )->item( 0 ) )
        {
            $relatives = $this->getXml()->getTagAttributes( $node ); 
        }
		return $relatives;
    } 

    /**
     * Sets Foreign Keys
     *
     * @return array
     */
    public function setForeignKeys()
    {
        $node = $this->getXml()->createElement( self::TAGNAME_FOREIGN_KEYS );
	//	var_export( $this->getTableForeignKeys() );
		foreach( $this->getTableForeignKeys() as $field => $tableList )
		{
			$relatedTables = array_map( 'trim', explode( ' ', $tableList ) );
			foreach( $relatedTables as $table )
			{
				if( ! $class = Ayoola_Loader::loadClass( $table ) ){ continue; }
				if( ! method_exists( $table, 'getInstance' ) ){ continue; }
				@$class = $table->getInstance();
				if( ! $class instanceof Ayoola_Dbase_Adapter_Interface ){ continue; }
				$node->setAttribute( $table, $field );
			}
		}
		return $node;
    } 

    /**
     * Gets Foreign Keys
     *
     * @return array
     */
    public function getForeignKeys()
    {
		$relatives = array();
		if( $node = $this->getXml()->getElementsByTagName( self::TAGNAME_FOREIGN_KEYS )->item( 0 ) )
        {
            $relatives = $this->getXml()->getTagAttributes( $node ); 
        }
		return $relatives;
    } 
	
    /**
     * Returns the field list of the Foreign Tables
     *
     * @return array 
     */
    public function getForeignFieldList()
    {	
		$fieldList = array();
		//var_export( $this->getForeignKeys() );
		return $fieldList;
    } 
	
    /**
     * Return an object of the foreign table
     *
     * @param string The table of the foreign
     */
    public static function getForeignTable( $table )
    {
//		var_export( $table );
		if( ! self::checkValidTable( $table ) ){ throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "INVALID FOREIGN TABLE" ); }
		return new $table;
    } 

    /**
     * Sets data types of fields on a table
     *
     * @param array FieldList array( 'field' => 'dataType' );
     * @return DOMNode
     */
    public function setDataTypes( $fieldList, Array $keys = array() )
    {
        $node = $this->getXml()->createElement( self::TAGNAME_DATA_TYPES );
        foreach( $fieldList as $field => $keywords )
		{
		//	$keywords = strtoupper( $keywords );
			foreach( self::explodeDataType( $keywords ) as $keyword )
			{
				//	Introducing this so that Keywords can be assigned parameters
				$keyword = array_map( 'trim', explode( '=', $keyword ) );
			//	var_export( $keyword[0] );
				self::checkValidKeyword( $keyword[0] );
				if( ! empty( self::$_allowedKeywords[$keyword[0]][__FUNCTION__] ) )
				{
					$callback = self::$_allowedKeywords[$keyword[0]][__FUNCTION__];
					$this->$callback( array( $field => $keyword[1] ) );
				}
			}
			$node->setAttribute( $field, $keywords ); 
		}
       // $node->appendChild( $this->setReferenceKeys( $keys ) );
        return $node;
    } 

    /**
     * Gets data types of fields on a table
     *
     * @return array
     */
    public function getDataTypes()
    {
		if( $node = $this->getXml()->getElementsByTagName( self::TAGNAME_DATA_TYPES )->item( 0 ) )
        {
            return $this->getXml()->getTagAttributes( $node ); 
        }
		else
		{
		}
	//	echo file_get_contents( $this->getFilename( true ) ); 
		$filename = $this->getFilename( true );
		
		//	DEBUG
	//	var_export( $filename );
	//	var_export( $this->getXml()->getElementsByTagName( self::TAGNAME_DATA_TYPES ) );
	//	var_export( $this->getXml()->view() );
		if(	! is_file( $filename ) )
		{
		//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
			return array();
		}
		elseif(	trim( file_get_contents( $filename ) ) === '' )
		{
			//	Avoid errors of when this is empty
			unlink( $filename );
			Application_Log_View_Error::log( "'{$filename}' is an empty file and has been removed from the server. " );
			return array();
		}
		else
		{
		//	is_file( $filename ) ? $this->getXml()->load( $filename ) : null;
		
			//	Giving last chance helps in some cases
			$this->getXml()->load( $filename );
			if( $node = $this->getXml()->getElementsByTagName( self::TAGNAME_DATA_TYPES )->item( 0 ) )
			{
			//	var_export( $this->getXml()->getTagAttributes( $node ) );
				return $this->getXml()->getTagAttributes( $node ); 
			}
//			var_export( $this->getXml()->view() );
	//		exit();
			$newFilename = $filename . time() . '.malformed.xml';
			if( ! @rename( $filename, $newFilename  ) )
			{
				return array();
			}
			@unlink( $filename );
			try
			{ 
				Application_Log_View_Error::log( "'{$filename}' has some errors, so it has been renamed '{$newFilename}' " );
			}
			catch( Exception $e )
			{
				null;
			}
		}
		
	//	echo unlink( $this->getFilename( true ) ); 
	//	throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid Datatype on " . $filename ); 
	//	throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid Datatype on " . basename ( $this->getFilename( true ) ) );
    } 

    /**
     * Returns the Data Type of a particular field
     *
     * @param string Field key
     * @return DOMNode
     */
    public function getFieldDataType( $fieldKey )
    {
		$dataTypes = $this->getDataTypes();
		if( empty( $dataTypes[$fieldKey] ) )
        {
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "{$fieldKey} not found in field list" );
        }
		$dataTypes[$fieldKey] = self::explodeDataType( $dataTypes[$fieldKey] );
        return $dataTypes[$fieldKey];
    } 

    /**
     * Explodes Data types into an array
     *
     * @param void
     * @return array
     */
    public static function explodeDataType( $dataTypes )
    {
		//if( is_array( $dataTypes ) ) { var_export( $dataTypes ); exit(); }
		$dataTypes = array_map( 'trim', explode( ',', $dataTypes ) );
        return $dataTypes;
    } 

    /**
     * Checks if a datatype is valid
     *
     * @param string Data Type
     * @throws Ayoola_Dbase_Adapter_Xml_Table_Exception
     */
    public static function checkValidKeyword( $keyword )
    {
		if( ! array_key_exists( $keyword, self::$_allowedKeywords ) )
		{
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid Keyword {$keyword}" );
		}
    } 

    /**
     * Checks if a datatype is valid
     *
     * @param string Data Type
     * @throws Ayoola_Dbase_Adapter_Xml_Table_Exception
     */
    public static function checkValidDataType( $dataType )
    {
		self::checkValidKeyword( $dataType );
		if( self::$_allowedKeywords[$dataType]['type'] != 'DATA_TYPE' )
		{
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid Data Type {$dataType}" );
		}
    } 

    /**
     * Filters Data Type to Required
     *
     * @param mixed Data to be filtered
     * @param string Data Type
     */
    public static function filterDataType( $value, $dataType )
    {
		$dataType = self::explodeDataType( $dataType );
		foreach( $dataType as $keyword )
		{ 
			$keyword = array_map( 'trim', explode( '=', $keyword ) );
			$keyword = $keyword[0];
			//self::checkValidDataType( $keyword ); 
			try{ self::checkValidDataType( $keyword ); }
			catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e ){ continue; }
			$dataTypeToUse = $keyword;
			break;
		}
		if( empty( $dataTypeToUse ) )
		{
		//	throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "No data type filter for {$value}" );
		//	var_export( $value );
		// 	var_export( $dataType );
			return $value;
		}
		if( ! empty( self::$_allowedKeywords[$keyword][__FUNCTION__] ) )
		{
			$callback = self::$_allowedKeywords[$keyword][__FUNCTION__];
			$valueType = gettype( $value );
			if( @self::$_dataTypeFilters[$callback][$valueType] )
			{
				$callback = self::$_dataTypeFilters[$callback][$valueType];
			}
			if( ! is_callable( $callback ) )
			{ 
				throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid callback function {$callback}" ); 
			}
	 //		var_export( $value );
			
			@$value = self::$_dataTypeFilters[$callback]['second_parameter'] ? $callback( $value, self::$_dataTypeFilters[$callback]['second_parameter'] ) :  $callback( $value ); 
	//		var_export( $callback );
	//		var_export( $value );
		}
		//	var_export( $value );
		//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
			//	var_export( $callback ); 
			//	var_export( self::$_dataTypeFilters[$callback]['second_parameter'] );
			}
		return $value;
    } 

    /**
     * Checks if a referenceKey is valid
     *
     * @param string referenceKey
     * @throws Ayoola_Dbase_Adapter_Xml_Table_Exception
     */
    public static function checkValidReferenceKey( $referenceKey )
    {
		self::checkValidKeyword( $referenceKey );
		if( self::$_allowedKeywords[$referenceKey]['type'] != 'REFERENCE_KEY' )
		{
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Invalid Data Type {$referenceKey}" );
		}
    } 

    /**
     * Retrieve Reference Keys from the Data Types
     *
     * @param string Data Type
     * @return string Reference Key
     */
    public static function retrieveReferenceKey( $dataType )
    {
		$dataType = self::explodeDataType( $dataType );
		foreach( $dataType as $keyword )
		{ 
			try{ self::checkValidReferenceKey( $keyword ); }
			catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e ){ continue; }
		//	var_export( $keyword );
			return $keyword;
		}
    } 

    /**
     * Checks the Data Type that The table Expects
     *
     * @param string The Field Key
     */
    public static function getDataTypeKeyValue( $key )
    {
		if( array_key_exists( $key, $this->getTableDataTypes() ) )
		{
			return $this->getTableDataTypes( $key );
		}
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "{$key} is not available on table {$this->getTableName}" );
    } 

    /**
     * This method sets the _tableDataTypes property to a value
     *
     * @param array
     * @return null
     */
    public function setTableDataTypes( $dataTypes )
    {
        $this->_tableDataTypes = (array) $dataTypes;
    } 
	
    /**
     * This method returns the _tableDataTypes property
     *
     * @param void
     * @return array | string
     */
    public function getTableDataTypes( $key = null )   
    {
		if( is_null( $this->_tableDataTypes ) ){ $this->setTableDataTypes( $this->getDataTypes() ); }
		if( ! is_null( $key ) )
		{
			if( ! array_key_exists( $key, $this->_tableDataTypes ) )
			{    
		//		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "DataType is not found for $key" );
				return false;
			}
			return $this->_tableDataTypes[$key];
		}
        return (array) $this->_tableDataTypes;
    } 

    /**
     * This method sets the _tableRelatives property to a value
     *
     * @param array array( column => relatedTables )
     * @return null
     */
    public function setTableRelatives( Array $relatives )
    {
		//var_export( $relatives );
		foreach( $relatives as $key => $value )
		{
			$this->_tableRelatives[$key] = $value;
		}
    } 

    /**
     * This method returns the _tableRelatives property
     *
     * @return array array( column => relatedTables )
     */
    public function getTableRelatives()
    {
		return (array) $this->_tableRelatives;
    } 

    /**
     * This method sets the _tableForeignKeys property to a value
     *
     * @param array array( column => relatedTables )
     * @return null
     */
    public function setTableForeignKeys( Array $relatives )
    {
		//var_export( $relatives );
		foreach( $relatives as $key => $value )
		{
			$this->_tableForeignKeys[$key] = $value;
		}
    } 

    /**
     * This method returns the _tableForeignKeys property
     *
     * @return array array( column => Tables )
     */
    public function getTableForeignKeys()
    {
		return (array) $this->_tableForeignKeys;
    } 

    /**
     * This method sets the row id attribute to a record row
     *
     * @param DOMNode
     * @return DOMNode
     */
    public static function setRecordRowId( DOMNode $recordRow, $value )
    {
		$value = intval( $value );
		$recordRow->setAttribute( self::ATTRIBUTE_ROW_ID, $value );
		return $recordRow;
    } 
	
    /**
     * This method returns the  row id attribute of a recordRow
     *
     * @param DOMNode
     * @return int
     */
    public static function getRecordRowId( DOMElement $recordRow )
    {
		if( $recordRow->hasAttribute( self::ATTRIBUTE_ROW_ID ) )
		{
			return (int) $recordRow->getAttribute( self::ATTRIBUTE_ROW_ID );
		}
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Row ID not available on a record Row" );
    } 

    /**
     * Sets Records on the Table
     *
     * @param DOMElement
     * @return DOMElement
     */
    public function setRecords( DOMElement $node = null )
    {	
		if( ! $records = $this->getXml()->getElementsByTagName( self::TAGNAME_RECORDS )->item( 0 ) )
		{ 	
			$records = $this->getXml()->createElement( self::TAGNAME_RECORDS );	 
		}
		if( $node instanceof DOMElement )
		{ 	
			$node = $this->getXml()->importNode( $node, true );
			switch( $node->tagName )
			{
				case self::TAGNAME_RECORDS:
				$records = $node;
				break;
				case self::TAG_TABLE_ROW:
				$records->appendChild( $node );
				break;
				default:
				throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( 'Invalid Record Format' );
			}
		}
		return $records;
    } 

    /**
     * Gets the document record
     *
     * @return DOMNode
     */
    public function getRecords()
    {
		if( $node = $this->getXml()->getElementsByTagName( self::TAGNAME_RECORDS )->item( 0 ) )
        {
           return $node; 
        }
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( 'No Records on Table' );
    } 

    /**
     * Get the value of a field in a row
     *
     * @param DOMNode
     * @return string
     */
    public static function getRowId( DOMNode $row )
    {
		$id = $row->getAttribute( self::ATTRIBUTE_ROW_ID );
		if( $id ){ return (int) $id; }
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( 'Row ID not Found' );
    } 

    /**
     * Get the value of a field in a row
     *
     * @param DOMNode
     * @return string
     */
    public static function getFieldKey( DOMNode $field )
    {
		$key = $field->getAttribute( self::ATTRIBUTE_FIELD_KEY );
		if( $key ){ return $key; }
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( 'Row ID not Found' );
    } 

    /**
     * Get the value of a field in a row
     *
     * @param DOMNode
     * @param string
     * @return string
     */
    public function getFieldValue( DOMNode $row, $fieldKey )
    {
		$this->getXml()->setId( self::ATTRIBUTE_FIELD_KEY, $row );
		foreach( $row->childNodes as $field )
		{
			if( self::getFieldKey( $field ) == $fieldKey )
			{
				foreach( $field->childNodes as $value )
				{
					if( $value instanceof DOMCDATASection ){ return $value->data; } 
				}
			}
		}
		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "{$fieldKey} is not found in record row " . self::getRowId( $row ) );
    } 

    /**
     * Returns true if a value presently exists in the document
     *
     * @param string Field Key
     * @param mixed value to check for
     * @return boolean
     */
    public function checkDuplicateValue( $key, $value )
    {
		$select =  $this->query( 'SELECT', null, array( $key => $value ) );
	//	var_export( $select );
//		var_export( $value );
		if( ! empty( $select ) ){ $select = array_shift( $select ); }
		return ! empty( $select[$key] );
	} 
	
    /**
     * This method returns the  row id attribute of a recordRow
     *
     * @param DOMNode
     * @param mixed Field Key
     * @param mixed Value for the Field
     * @param boolean Set to true to allow updating of field values
     * @return int
     */
    public function setRowColumnValue( DOMNode $recordRow, $key, $value, $allowOverwrite = false )
    {
		$dataTypes = $this->getTableDataTypes( $key );
		$value = self::filterDataType( $value, $dataTypes  );
		//var_export( self::retrieveReferenceKey( $dataTypes ) );
		$value = is_string( $value ) ? htmlspecialchars( $value ) : $value;
		if( self::retrieveReferenceKey( $dataTypes ) == 'UNIQUE' && $this->checkDuplicateValue( $key, $value ) )
		{
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( self::ERROR_INSERT_AMBIGUOUS . ' "' . $key . ': ' . $value . '"'  );
		}
		foreach( $recordRow->childNodes as $field )
		{
			if( $key != self::getFieldKey( $field ) ){ continue; }
			if( $allowOverwrite === true )
			{ 
				while( $field->hasChildNodes() ){ $field->removeChild( $field->firstChild ); }
				$newfieldTag = $field;
				break;
			}
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( self::ERROR_INSERT_ID );
		}
		if( empty( $newfieldTag ) )
		{ 
			$newfieldTag = $this->getXml()->createElement( self::TAG_TABLE_FIELD );
			$newfieldTag->setAttribute( self::ATTRIBUTE_FIELD_KEY, $key  );
		}
		//while( $fieldKey->hasChildNodes() ){ $fieldKey->removeChild( $fieldKey->firstChild ); }
	//	var_export( $value );
		$value = $this->getXml()->createCDATASection( $value );
		$newfieldTag->appendChild( $value );
		$recordRow->appendChild( $newfieldTag );
		return true;
	}
	// END OF CLASS
}
