<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Xml.php 4.9.12 11.52 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 * @see Ayoola_Dbase_Table_Abstract_Exception
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';
require_once 'Ayoola/Dbase/Table/Abstract/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Table_Abstract_Xml extends Ayoola_Dbase_Table_Abstract implements Ayoola_Dbase_Table_Interface
{

    /**
     * The DataTypes of the Table
     *
     * @param array
     * 
     */
    protected $_dataTypes;

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.01';

    /**
     * The Version of the general table module
     *
     * @param int
     */
    protected static $_version = '1.00';

    /**
     * The Accessibility of the Table
     *
     * @param string
     */
    protected $_accessibility = SELF::DEFAULT_SCOPE;

    /**
     * 
     *
     * @param array
     */
    protected static $_tableInfo;

    /**
     * 
     *
     * @param bool
     */
    protected static $_alreadyRan;

    /**
     * This property determines how this table relates to other table
     *
     * @param string
     */
    protected $_relationship = SELF::DEFAULT_SCOPE;
	
	const DEFAULT_SCOPE = SELF::SCOPE_PRIVATE;
	const SCOPE_PRIVATE = 'PRIVATE';
	const SCOPE_PROTECTED = 'PROTECTED';
	const SCOPE_PUBLIC = 'PUBLIC';
	
    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct()
    {
    //    get_class( $this );
 
     //   if( empty( static::$_alreadyRan[get_class( $this )] ) )
        {
     //     var_export( get_class( $this ) );
          //  everything here needs to be ran only once
          $this->init(); 
          static::$_alreadyRan[get_class( $this )] = true;
       }
    }
	
    /**
     * Initialize the Table
     *
     * @param void
     */
    public function init()
    {
		//	We are using the XML Adapter
		require_once 'Ayoola/Dbase.php';
		$database = new Ayoola_Dbase( array( 'adapter' => 'Xml' ) );
		parent::__construct( $database );
 		$this->selectDatabase();
		$adapter = $this->getDatabase()->getAdapter();
		$adapter->setTableName( $this->getTableName() );
		$adapter->setAccessibility( $this->_accessibility );
		$adapter->setRelationship( $this->_relationship );
		$adapter->_resultKeyReArrange = true;		
		//var_export( self::$_accessibility );
	//	var_export( $this->_accessibility ); 
		//	Attempts to create the table if not exist
		 static::$_tableInfo = $this->query( 'TABLE', 'DESCRIBE' );
		
		//	Attempts to automagically update table versions
 		do
		{
 	//		var_export(  static::$_tableInfo );
   //    exit();   
      $backupFile =  static::$_tableInfo['filename'] . '.backup';
  //			var_export( $backupFile );
   //      exit();   
      
			if( ! $this->exists() && ! file_exists( $backupFile ) )
	//		if( ! $this->exists() )
			{
     //   var_export(  static::$_tableInfo );
				//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
				break;
			}
			if( ! empty(  static::$_tableInfo['table_info'] ) && ( empty(  static::$_tableInfo['table_info']['table_version'] ) || empty(  static::$_tableInfo['table_info']['module_version'] ) ) )
			{ 
				break; 
			}
			if( ! empty(  static::$_tableInfo['table_info'] ) && ( @ static::$_tableInfo['table_info']['table_version'] === $this->_tableVersion && @ static::$_tableInfo['table_info']['module_version'] === self::$_version ) )
			{ 
				break; 
			}
 	//
			$previousAccessibility = $this->_accessibility;
			$previousRelationship = $this->_relationship;
			$adapter->setAccessibility( self::SCOPE_PRIVATE );
			$adapter->setRelationship( self::SCOPE_PRIVATE );
			$adapter->cache = false;
			$values = $this->select();
 // 		var_export( $values );
	
			//	Backup the previous table
      if( ! empty(  static::$_tableInfo['filename'] ) )
      {
        $backupFile =  static::$_tableInfo['filename'] . '.backup';
        if( file_exists( $backupFile ) )
        { 
          
        //	if( time() - filemtime( $backupFile ) < 86400 )
          if( time() - filemtime( $backupFile ) < 86400 )
          {
            //	Backup in progress. Don't duplicate progress unless its more than one day
            break;
          }
          elseif( empty( $backupFile ) || ! is_string( $backupFile ) )
          {
          
          }
          else
          {
            //	Something has gone wrong, restore the backup automagically.
            $values = include( $backupFile );  
        //    var_export( $values );
            $newBackUpFile = $backUpFile . filemtime( $backupFile );
            Application_Log_View_Error::log( "There is an error on an XML Database. The back up file {$backUpFile} as been copied to {$newBackUpFile} for safe keep." );
  //   		var_export( $backupFile );
  //   		var_export(  static::$_tableInfo );  

            rename( $backUpFile, $newBackUpFile ); 
          }
        }
        else
        {
          file_put_contents( $backupFile, '<?php return ' . var_export( $values, true ) . ';' );		
        }
      //	copy(  static::$_tableInfo['filename'], $backupFile );
      }
			//	Store the values in the backup file
		//	var_export( $values );
		try
    {
        $this->drop();
    }	
    catch( Exception $e )
    {
      null;
    }		
    try
    {
        $this->create();
    }	
    catch( Exception $e )
    {
      null;
    }
			
			
		//	exit( __LINE__ );
		//	set_time_limit( 86400 ); //	We may need time to update a very large table
		//	ignore_user_abort( true );
			foreach( $values as $each )
			{ 
			//	$this->insert( $each, array( 'record_row_id' => $each[$this->getTableName() . '_id'] ) ); 
			//	set_time_limit( 30 );
				try
				{
					$this->insert( $each ); 
				}
				catch( Exception $e )
				{  
					null;
				}
			}
			if( count( $values ) === count( $this->select() ) )
			{ 
				unlink( $backupFile );
			//	var_export( $values );
			//	var_export( $this->select() );
			//	copy( $backupFile,  static::$_tableInfo['filename'] ) ? : copy( $backupFile, $backupFile . '.backup' ); 
			}
			else
			{
		//		var_export( $values );
		//		var_export( $this->select() );
			}
			$adapter->setAccessibility( $previousAccessibility );
			$adapter->setRelationship( $previousRelationship );
 		}
		while( false );
 	//	var_export(  static::$_tableInfo );
		
   }

    /**
     * Returns true if the table exists
     *
     * @return boolean
     */
    public function exists()
    {
		return $this->query( 'TABLE', 'EXISTS', $this->getTableName() );
    }

    /**
     * Select all records matching a criteria
     *
     * @param Array Fields to Select
     * @param Array The Criteria
     * @return Array
     */
    public function select( Array $fieldsToSelect = null, Array $where = null, Array $options = null )
    {
/*	  	if( ! $this->exists() )
			{
     
				//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
				return array();
			}
*/      
      try
      {
           //  implement cache at this level
       if( ! empty(  static::$_tableInfo['filename'] ) )
        {
          $filename = str_ireplace( Ayoola_Application::getDomainSettings( APPLICATION_PATH ), '',  static::$_tableInfo['filename'] );

          //   we must always refresh list because we don't know if the table was just created
          $globalFiles = Ayoola_Loader::getValidIncludePaths( $filename, array( 'refresh_list' => 1 ) );
          $class = @static::$_tableInfo['table_info']['table_class'] ? : get_class( $this );
         // Application_Breadcrumb::v( static::$_tableInfo['table_info']['table_class'] );
       //   Application_Breadcrumb::v( static::$_tableInfo['table_info'] );
         $arguments =  $class . ' ' . md5( serialize( func_get_args() ) )  . ' ' . $_SERVER['HTTP_HOST'];
          
        //	require_once 'Ayoola/Filter/Name.php';
        //	$filter = new Ayoola_Filter_Name();
        //	$file = $filter->filter( $arguments );
          $filter = new Ayoola_Filter_Name();
          $filter->replace = DS;
          $file = str_ireplace( '_', DS, $filter->filter( $arguments ) );
          $dir = Ayoola_Dbase_Adapter_Xml_Table_Abstract::getCacheDirectory();
          $file = $dir . DS . $file;
  //        Application_Breadcrumb::v( $file );
  //        var_Export( $file );
  //        var_Export( $arguments );
     //     $catchPath =  static::$_tableInfo['table_info']['table_class'];
          if( is_file( $file ) )
          {
        //    var_export( $file );
	        	$cacheTime = filemtime( $file );
            foreach( $globalFiles as $each )
            {
       //       var_export();
		      	  if( $cacheTime <= @filemtime( $each ) )
              { 
                  unlink( $file ); 
                  break;
              }
            }
          }
          if( is_file( $file ) && empty( $options['disable_cache'] ) )
          {
            
      //     Application_Breadcrumb::v(  static::$_tableInfo );
     //      Application_Breadcrumb::v(  static::$_tableInfo['table_info']['table_class'] );
            $stored = json_decode( file_get_contents( $file ), true );
            if( $stored['files'] === $globalFiles )
            {
                if( empty( $globalFiles ) && ! empty( $stored['result'] ) )
                {
                    //  where did we get record when files are empty
                    // plugins ?
          //        var_export( $stored['result'] );
         //         var_export( $globalFiles );
          //        var_export( $stored['files'] );
                }
             //   return $stored['result'];  
            }
            else
            {
                //  refresh if the global files have changed
       //         var_export( $globalFiles );
            }
          }
       //   var_export( $catchPath );
          //  str_ireplace
        }
        else
        {
          //  none should come here tho
      //     var_export(  static::$_tableInfo );
    //      return array();
        }
		    $result = $this->query( 'TABLE', 'FETCH', $fieldsToSelect, $where, $options );
        if( ! empty( $file ) )
        {
       //   var_export( $file );
        //  var_export( file_get_contents( $file ) );
          Ayoola_Doc::createDirectory( dirname( $file ) );
          file_put_contents( $file, json_encode( array( 'result' => $result, 'files' => $globalFiles ) ) );
        }
      }
      catch( Exception $e )
      {
				return array();
      }
		
	//	krsort( $result );
		
	//	  return array_map( 'unserialize', array_unique( array_map( 'serialize', $result ) ) );
		  return $result;
    }

    /**
     * Select one records matching a criteria
     *
     * @param Array The Criteria
     * @return Array
     */
    public function selectOne( Array $fieldsToSelect = null, Array $where = null, Array $options = null )
    {
		$data = $this->select( $fieldsToSelect, $where, $options );
		if( ! empty( $data ) ){ $data = array_shift( $data ); }
		return $data;
    }

    /**
     * Insert a record into the database
     *
     * @param Array Values
     * @return boolean
     */
    public function insert( Array $values, $options = null )
    {
		if( ! $this->exists() )
		{
			try
			{ 
				$this->create(); 
			}
			catch( Ayoola_Dbase_Table_Abstract_Exception $e )
			{ 
				null; 
			}
		}
		else
		{
		//	var_export( $this->exists() );
		}
		$values = $this->filterValues( $values );
		$result = $this->query( 'TABLE', 'INSERT', $values, $options );
    static::$_alreadyRan[get_class( $this )] = true;
    return $result;
    }

    /**
     * Updates one or more record in the database
     *
     * @param Array Values
     * @param Array Criteria
     * @return boolean
     */
    public function update( Array $values, Array $where = null )  
    {
	  	if( ! $this->exists() )
			{
     
				//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
				return false;
			}
		$values = $this->filterValues( $values );
		$result = $this->query( 'TABLE', 'UPDATE', $values, $where );
    static::$_alreadyRan[get_class( $this )] = true;
    return $result;
    }

    /**
     * Deletes on or more records in the database
     *
     * @param Array Criteria
     * @return boolean
     */
    public function delete( Array $where = null )
    {
	  	if( ! $this->exists() )
			{
     
				//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
				return false;
			}
		  $result = $this->query( 'TABLE', 'DELETE', $where );
      static::$_alreadyRan[get_class( $this )] = true;
      return $result;
    }

    /**
     * Creates a table in the current database
     *
     * @param Array Datatypes
     * @return boolean
     */
    public function create( Array $dataTypes = null )
    {
		if( is_null( $dataTypes ) ){ $dataTypes = $this->getDataTypes(); }
		//	exit( get_class( $this ) );
		//	exit( var_export( $dataTypes ) );
//var_export( $this->_tableVersion );
		//	var_export( $dataTypes );
		  $result = $this->query( 'TABLE', 'CREATE', array( 'table_name' => $this->getTableName(), 
														'table_version' => $this->_tableVersion,
														'module_version' => self::$_version,
														'table_class' => get_class( $this ) ), $dataTypes );
      static::$_alreadyRan[get_class( $this )] = true;
      return $result;
    }

    /**
     * Alters a table
     *
     * @param string Table Name
     * @param Array Datatypes
     * @return boolean
     */
    public function alter( $tableName = null, Array $dataTypes = null )
    {
		$result = $this->query( 'TABLE', 'ALTER', $tableName, $dataTypes );
		if( ! is_null( $tableName ) ){ $this->getDatabase()->getAdapter()->setTableName( $tableName ); }
    static::$_alreadyRan[get_class( $this )] = true;
		return $result;
		
    }

    /**
     * Destroys table
     *
     * @return boolean
     */
    public function drop()
    {
		  $result = $this->query( 'TABLE', 'DROP' );
      static::$_alreadyRan[get_class( $this )] = true;
      return $result;
   }

    /**
     * Get the table info
     *
     * @return array
     */
    public function describe()
    {
		return $this->query( 'TABLE', 'DESCRIBE' );
    }

    /**
     * Select Classname as the database
     *
     * @param Classname
     */
    public function selectDatabase( $className = null )
    {
		if( is_null( $className ) )
		{
			$className = get_class( $this );
			$className = explode( '_', $className );
			array_pop( $className );
			$className = implode( '_', $className );
		//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
		//		var_export( $className );
		//		var_export( get_class( $this ) );
			}
		}
//	if( ! $class = Ayoola_Loader::loadClass( $className ) ){ $className = null; }
		$this->getDatabase()->getAdapter()->select( $className );
    }

    /**
     * Filter values that their keys are not a field on the table
     *
     * @param array
     * @return array
     */
    public function filterValues( Array $values )
    {	
		 static::$_tableInfo = $this->query( 'TABLE', 'DESCRIBE' );
		foreach( $values as $key => $value )
		{
			if( ! array_key_exists( $key,  static::$_tableInfo['data_types'] ) ){ unset( $values[$key] ); }
		}
		return $values;
    }

    /**
     * Returns the _dataTypes property
     *
     * @param void
     * @return array
     */
    public function getDataTypes()
    {	
		if( is_array( $this->_dataTypes ) )
		{
			return $this->_dataTypes;
		}
		throw new Ayoola_Dbase_Table_Abstract_Exception( 'No Datatype on file. Set with ' . __CLASS__ . '::setDataTypes()' );
    }

    /**
     * Sets the _dataTypes property
     *
     * @param array
     */
    public function setDataTypes( Array $dataTypes )
    {	
		$this->_dataTypes = $dataTypes;
    }

    /**
     * View the Table
     *
     * @return string
     */
    public function view( Array $fieldsKey = null, Array $where = null )
    {
		return $this->query( 'TABLE', 'VIEW', $fieldsKey, $where );
    }
	// END OF CLASS
}
