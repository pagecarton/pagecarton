<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Mysql.php 1.23.12 8.11 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Interface
 */
 
require_once 'Ayoola/Dbase/Adapter/Interface.php';
require_once 'Ayoola/Dbase/Adapter/Exception.php';
require_once 'Ayoola/Dbase/Adapter/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml extends Ayoola_Dbase_Adapter_Abstract
{

    /**
     * The Accessibility of the Database
     * Public ( Default ) Anybody can select and edit the DB
     * Protected Anybody can select and only the owner can edit the DB
     * Private Only the owner can select and only the owner can edit the DB
     *
     * @param string
     */
    protected $_accessibility;

    /**
     * This property determines how this table relates to other table
     *
     * @param string
     */
    protected $_relationship = SELF::DEFAULT_SCOPE;

    /**
     * Whether to cache and use cache
     *
     * @param boolean
     */
    public $cache = true;

    /**
     * Accessibility Scope
     *
     * @param array
     */
    protected static $_allowedScopes = array( SELF::SCOPE_PRIVATE, SELF::SCOPE_PROTECTED, SELF::SCOPE_PUBLIC );
		
    /**
     * Default Scope Definition
     *
     * @var string
     */
	const DEFAULT_SCOPE = SELF::SCOPE_PRIVATE;
	const SCOPE_PRIVATE = 'PRIVATE';
	const SCOPE_PROTECTED = 'PROTECTED';
	const SCOPE_PUBLIC = 'PUBLIC';
		
    /**
     * The Directory of The Present Table
     *
     * @var string
     */
	protected $_directory;
		
    /**
     * The Global directory
     *
     * @var string
     */
	protected $_globalDirectory;
		
    /**
     * The private directory
     *
     * @var string
     */
	protected $_myDirectory;
		
    /**
     * The private file
     *
     * @var string
     */
	protected $_myFilename;
		
    /**
     * The File of The Present Table
     *
     * @var string
     */
	protected $_filename;
		
    /**
     * The Files in different accessibilty scopes
     *
     * @var array
     */
	protected $_globalFilenames;
		
    /**
     * Xml Object
     *
     * @var Ayoola_Xml
     */
	protected $_xml;
		
    /**
     * The className
     *
     * @var string
     */
	protected $className;
		
    /**
     * Name of the Table
     *
     * @var string
     */
	protected $_tableName;

    /**
     * Constructor
     *
     * @param array Database Info
     * 
     */
    public function __construct( $databaseInfo = null )
    {
		if( ! is_null( $databaseInfo ) ){ $this->setDatabaseInfo( $databaseInfo ); }
    }
	
    /**
     * Selects A Database ( A valid Class in this case )
     *
     * @param string Classname
     * @return null
     */
    public function select( $className = null )
	{
		if( is_null( $className ) ){ $className = get_class( $this ); }
		if( ! $class = Ayoola_Loader::loadClass( $className ) )
		{ 
	//		throw new Ayoola_Dbase_Adapter_Exception( "XMLDB not available for $className" ); 
		}
//		PageCarton_Widget::v( $className );
 //   var_export( $className );
		$this->className =  $className;
		$directory = XML_DATABASES_DIR . DS . str_ireplace( '_', DS, $className );
	//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
		{
		//	var_export( $className );
		//	var_export( $directory );
		}
		$this->setGlobalDirectory( $directory );
	}
	
    /**
     * Sets personal _globalDirectory to a value
     *
     * @param string Directory
     */
    public function setGlobalDirectory( $directory )
	{
		//var_export( $path );
		$this->_globalDirectory = $directory;
		$this->setMyDirectory();
		$this->setDirectory();
		return $this->_globalDirectory;
	}
	
    /**
     * This method returns _globalDirectory
     *
     * @return string the XML Filename
     */
    public function getGlobalDirectory()
    {
		if( $this->_globalDirectory ){ return $this->_globalDirectory; }
		throw new Ayoola_Dbase_Adapter_Exception( 'No Global Directory' );
    } 
	
    /**
     * Sets _directory to a value
     *
     * @param string Directory
     * @return null
     */
    public function setDirectory( $directory = null )
	{
		if( is_null( $directory ) ){ $directory = $this->getGlobalDirectory(); }
		if( ! $path = Ayoola_Loader::checkDirectory( $directory ) )
		{
			//var_export( $directory );
			$path = $this->setMyDirectory();
		}
		//var_export( $directory );
		$this->_directory = $path;
	}
	
    /**
     * This method returns _filename
     *
     * @return string the XML Filename
     */
    public function getDirectory()
    {
		if( $this->_directory ){ return $this->_directory; }
		throw new Ayoola_Dbase_Adapter_Exception( 'No Database Selected' );
    } 
	
    /**
     * Sets personal _myDirectory to a value
     *
     * @param string Directory
     */
    public function setMyDirectory( $directory = null )
	{
	//	var_export( $directory );
	//	var_export( APPLICATION_PATH );
	//	var_export( self::SCOPE_PUBLIC );
	//	var_export( $this->getAccessibility() );
		if( is_null( $directory ) ){ $directory = $this->getGlobalDirectory(); }
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) );
	//	exit(  );
		switch( $this->getAccessibility() )
		{
			case self::SCOPE_PROTECTED:
			case self::SCOPE_PRIVATE:
				$path = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $directory;
	//	var_export( $directory );
	//	var_export( $this->getAccessibility() );
			break;
			
			case self::SCOPE_PUBLIC:
      //	$path = APPLICATION_PATH . DS . $directory;   

        //  CHANING PUBLIC DB BASE TO DEFAULT SINCE IT IS MOST CONSTANT DIR
        //  CORE GETS DELETED ON NEW INSTALL
        $path = SITE_APPLICATION_PATH . DS . $directory;   
          
	//	var_export( $directory );
		//		Ayoola_Page_Creator::v( $path );
	//	var_export( $path );
			break;
			default:
	//	var_export( $directory );
			//	$path = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $directory;
			break;
		
		}
	//	require_once 'Ayoola/Doc.php';
	//	if( ! Ayoola_Doc::createDirectory( $path ) )
	//	{
	//		throw new Ayoola_Dbase_Adapter_Exception( 'Could not create a database - ' . basename( $path ) ); 
	//	}
		//var_export( $path );
		$this->_myDirectory = $path;
		return $this->_myDirectory;

	}
	
    /**
     * This method returns _myDirectory
     *
     * @return string the XML Filename
     */
    public function getMyDirectory()
    {
		if( $this->_myDirectory ){ return $this->_myDirectory; }
		throw new Ayoola_Dbase_Adapter_Exception( 'No Database Selected' );
    } 
	
    /**
     * This method sets _tableName to a Value
     *
     * @param string the XML TableName
     * @return null
     */
    public function setTableName( $tableName )
    {
		$this->_tableName = $tableName;
		$this->setFilename( $this->getTableName() );
    } 
	
    /**
     * This method returns _tableName
     *
     * @return string the XML TableName
     */
    public function getTableName()
    {
		if( $this->_tableName ){ return $this->_tableName; }
		throw new Ayoola_Dbase_Adapter_Exception( 'No Table Selected' );
    } 
	
    /**
     * This method sets _filename to a Value
     *
     * @param string the XML Filename
     * @return null
     */
    public function setFilename( $tableName )
    {
		//var_export( $tableName );
		$this->_filename = self::buildFilename( $this->getDirectory(), $tableName );
		$this->setMyFilename( $tableName );
    } 
	
    /**
     * This method returns _filename
     *
     * @param boolean Set to true if to check file
     * @return string the XML Filename
     */
    public function getFilename( $checkFile = false )
    {
		return self::checkFile( $this->_filename, $checkFile );
    } 
	
    /**
     * This method sets _myFilename to a Value
     *
     * @param string the XML Filename
     * @return null
     */
    public function setMyFilename( $tableName )
    {
		$this->_myFilename = self::buildFilename( $this->getMyDirectory(), $tableName );
    } 
	
    /**
     * This method returns _myFilename
     *
     * @param boolean Set to true if to check file
     * @return string the XML Filename
     */
    public function getMyFilename( $checkFile = false )
    {	
		return self::checkFile( $this->_myFilename, $checkFile );
    } 
	
    /**
     * SUPPLEMENTARY DIR FOR FILES FOR LARGE RECORDS
     *
     * @param void
     * @return string
     */
    public function getMySupplementaryDirectory( $filePath = null )
    {
        if( ! $filePath )
        {
          $filePath = $this->getMyFilename();
        }
		$dir = dirname( $filePath ) . DS . '__' . DS . $this->getTableName();
		return $dir;
	}
	
    /**
     * SUPPLEMENTARY FILES FOR LARGE RECORDS
     *
     * @param void
     * @return array
     */
    public function getSupplementaryFilenames( $filePath = null )
    {
		try
		{
 		//	LOOK FOR SUPPLEMENTARY FILES FOR LARGE RECORDS	
    //    var_export( $this->getGlobalFilenames() );		
     //   if( basename( $this->getMySupplementaryDirectory( $filePath ) ) === 'data' )
        {
      //       PageCarton_Widget::v( $filePath );
     //       PageCarton_Widget::v( Ayoola_Doc::getDirectoriesRecursive( $this->getMySupplementaryDirectory( $filePath ) ) );
   //       PageCarton_Widget::v( Ayoola_Doc::getFilesRecursive( $this->getMySupplementaryDirectory( $filePath ) ) );
        }
	//	  if( $files = Ayoola_Doc::getFilesRecursive( $this->getMySupplementaryDirectory( $filePath ), array( 'key_function' => 'filectime' ) ) )
		  if( $files = Ayoola_Doc::getFilesRecursive( $this->getMySupplementaryDirectory( $filePath ) ) )
      {
    //    var_export( $files );
      }
		}
		catch( Exception $e )
		{
		//	var_export( $e->getMessage() );
		}
		return @$files ? : array();
	}
	
    /**
     * This method sets _globalFilenames to a Value
     *
     * @param string the global directory
     * @return null
     */
    public function setGlobalFilenames( $globalDirectory = null )
    {
		if( is_null( $globalDirectory ) ){ $globalDirectory = $this->getGlobalDirectory(); }
		$globalFilename = self::buildFilename( $globalDirectory, $this->getTableName() );
		str_ireplace( '/', DS, $globalFilename );
	//	var_export( $globalFilename );
		if( $paths = Ayoola_Loader::getValidIncludePaths( $globalFilename, array( 'no_availability_check' => 1 ) ) )
		{
      $supplementaryFiles = array();
      $fakePaths = Ayoola_Loader::getValidIncludePaths( $globalFilename, array( 'no_availability_check' => 1 ) );
   //   var_export( $fakePaths );
      foreach( $fakePaths as $eachPath )
      {
          $supplementaryFiles = ( $supplementaryFiles ? : array() ) + ( $this->getSupplementaryFilenames( $eachPath ) ? : array() );
      }
   //   var_export( $paths );
	//		$this->_globalFilenames = array_merge( $paths, $this->getSupplementaryFilenames() );
			$this->_globalFilenames = array_merge( $paths, $supplementaryFiles );
		} 
		
		
	//	var_export( $dir );
		
	//	var_export( $paths );
	}
    /**
     * This method returns _globalFilenames
     *
     * @return array the XML Filenames
     */
    public function getGlobalFilenames()
    {
		if( is_null( $this->_globalFilenames ) ){ $this->setGlobalFilenames(); }
		return $this->_globalFilenames ? : array();     
    } 
	
    /**
     * This method sets builds filename
     *
     * @param string the Directory
     * @param string the XML Filename
     * @return string filename
     */
    public static function buildFilename( $directory, $tableName )
    {
	
		return str_ireplace( '/', DS, $directory . DS . $tableName . EXT_DATA );
    } 
	
    /**
     * Checks if file exists
     *
     * @param string Filename
     * @param boolean Set to true if to check file
     * @return string the XML Filename
     */
    public static function checkFile( $filename, $checkFile = false )
    {
	//	var_export( is_file( $filename ) );
		require_once 'Ayoola/Loader.php';
		if( ( $checkFile  === true && $path = Ayoola_Loader::checkFile( $filename ) ) || $checkFile  === false && $filename )
		{ 
			return $filename; 
		}
	//	var_export( $filename );
		
		//	cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
		return false;
	//	throw new Ayoola_Dbase_Adapter_Exception( 'No Filename to Check' );
    } 
	
    /**
     * This method sets _xml to a Value
     *
     * @param Ayoola_Xml the XML object
     * @return null
     */
    public function setXml( Ayoola_Xml $xml = null )
    {
		require_once 'Ayoola/Xml.php';
		if( is_null( $xml ) ){ $xml = new Ayoola_Xml(); }
		$this->_xml = $xml;
		try
		{
			$filename = $this->getFilenameAccordingToScope( true );
			if( ! is_file( $filename ) ){ return; }
		}
		catch( Ayoola_Dbase_Adapter_Exception $e ){ return; }
		$this->loadFile( $filename );
    } 
	
    /**
     * This method gets _xml
     *
     * @return Ayoola_Xml the XML object
     */
    public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
        return $this->_xml;
    } 
	
    /**
     * This method sets _accessibility to a value
     *
     * @param string 
     * @throws Ayoola_Dbase_Adapter_Xml_Exception 
     */
    public function setAccessibility( $scope )
    {
		if( in_array( $scope, self::$_allowedScopes ) )
		{
	//	if( $scope === 'PUBLIC' )
		{
	//		echo __LINE__;
		}
			$this->_accessibility = $scope;
			return $this->_accessibility;
		}
		throw new Ayoola_Dbase_Adapter_Xml_Exception( "Invalid scope for accessibility - $scope" );
    } 
	
    /**
     * This method returns _accessibility
     *
     * @return string 
     */
    public function getAccessibility()
    {
		if( in_array( $this->_accessibility, self::$_allowedScopes ) ){ return $this->_accessibility; }
	//	if( $this->_accessibility === 'PUBLIC' )
		{
		//	var_export( $this->_accessibility );
		}
	//	if( $this->_accessibility === 'PUBLIC' )
		{
	//		echo __LINE__;
		}
	//	var_export( $this->_accessibility );
		return $this->setAccessibility( self::DEFAULT_SCOPE );
		return $this->_accessibility;
	} 
	
    /**
     * This method sets _relationship to a value
     *
     * @param string 
     * @throws Ayoola_Dbase_Adapter_Xml_Exception 
     */
    public function setRelationship( $scope )
    {
		if( in_array( $scope, self::$_allowedScopes ) )
		{
			$this->_relationship = $scope;
			return $this->_relationship;
		}
		throw new Ayoola_Dbase_Adapter_Xml_Exception( "Invalid scope for accessibility - $scope" );
    } 
	
    /**
     * This method returns _relationship
     *
     * @return string 
     */
    public function getRelationship()
    {
		if( in_array( $this->_relationship, self::$_allowedScopes ) ){ return $this->_relationship; }
		return $this->setAccessibility( self::DEFAULT_SCOPE );
	} 
	
    /**
     * Returns the Most Appropriate Filename, based on the scope
     *
     * @param string scope
     * @param string set to true to check file vadility
     * @param string Filename
     */
    public function getFilenameAccordingToScope( $checkFile = false, $scope = null )
    {
		if( is_null( $scope ) ){ $scope = $this->getAccessibility(); }
		else
		{
		//	var_export( $scope );
		}
/* 		switch( $scope )
		{
			case self::SCOPE_PRIVATE: 
			case self::SCOPE_PROTECTED:
				$filename = $this->getMyfilename( $checkFile );
				break;
			case self::SCOPE_PUBLIC;
				$filename = $this->getFilename( $checkFile );
				break;
			default:
				throw new Ayoola_Dbase_Adapter_Xml_Exception( $scope . ' is invalid accessibility scope' );	
		}
 */	
		switch( $scope )
		{
			case self::SCOPE_PRIVATE: 
			case self::SCOPE_PROTECTED:
				$filename = $this->getMyfilename( $checkFile );
			//	var_export( $filename );
			break;
			case self::SCOPE_PUBLIC;
			//	There is a bug setting scope to private first by default. lets start again
			
			//	$this->_myFilename = null;
			//	$this->_myDirectory = null;
			//	$this->select();
				$this->setMyDirectory();
				$this->setMyfilename( $this->getTableName() );
				$filename = $this->getMyfilename( $checkFile );
	//	trigger_error( $filename ); 
			//	Ayoola_Page_Creator::v( $filename );
			//	var_export( $checkFile ); 
			break;
			default:
				throw new Ayoola_Dbase_Adapter_Xml_Exception( $scope . ' is invalid accessibility scope' );	
		}
		return $filename;
	} 
	
    /**
     * Save the XML File
     *
     * @param string Filename
     */
    public function saveFile( $filename = null )
    {
		if( is_null( $filename ) ){ $filename = $this->getMyFilename(); }
		//var_export( $this->getMyFilename() );
		if( ! Ayoola_Doc::createDirectory( dirname( $filename ) ) )
		{
			throw new Ayoola_Dbase_Adapter_Exception( 'COULD NOT CREATE A DATABASE - ' . dirname( $filename ) ); 
		}
		
		//	Backup is causing a lot of issues in logs.
	//	$backUpFile = $filename . '.backup';
//	 	if( is_file( $backUpFile ) )
		{
			// log error 
		
		//	if( is_file( $backUpFile ) )
			{ 
			//	$newBackUpFile = $backUpFile . time();
			//	Application_Log_View_Error::log( "There is an error on an XML Database. The back up file $backUpFile as been copied to $newBackUpFile for safe keep." );
			//	copy( $backUpFile, $newBackUpFile ); 
			} // operation ended. Delete backup
			
		} 
	 
	/* 	while( is_file( $backUpFile ) )
		{
			//	Availability of Backup file means that a "saveFile" process is ON
			
			usleep( round( rand( 0, 100 ) * 1000 ) );
		} 
	*/
		//	exit( var_export( $backUpFile ) );
		
	//	if( is_file( $filename ) ){ copy( $filename, $backUpFile ); } // backup file before overwritting
		$this->getXml()->save( $filename );
	//	if( is_file( $backUpFile ) ){ unlink( $backUpFile ); } // operation ended. Delete backup
	} 
	
    /**
     * Loads XML File
     *
     * @param string Filename
     */
    public function loadFile( $filename = null )
    {
		if( is_null( $filename ) ){ $filename = $this->getFilenameAccordingToScope( true ); }
	//	var_export( $filename );
		$handle = fopen( $filename, 'r+' );
	//	if( ! $handle = fopen( $filename, 'r+' ) ){ return false; }
	//	flock( $handle, LOCK_EX ); //	lock the file
 		do
		{
			if( is_resource( $handle ) && flock( $handle, LOCK_EX | LOCK_NB ) ) 
			{ 
				flock( $handle, LOCK_UN );
				break;
			}	
			//	Lock not acquired, try again in:
			usleep( round( rand( 0, 100 ) * 1000 ) ); //	0-100 miliseconds
		}
		while( true );
	//	foreach( $this->getGlobalFilenames() as $filename )
		{
			$this->getXml()->load( $filename );
		}
 		do
		{
			if( is_resource( $handle ) && flock( $handle, LOCK_EX | LOCK_NB ) )
			{ 
			//	flock( $handle, LOCK_UN );
				break;
			}	
			//	Lock not acquired, try again in:
			usleep( round( rand( 0, 100 ) * 1000 ) ); //	0-100 miliseconds
		}
		while( true );
		
	} 

    /**
     * Queries Database Table
     *
     * @throws Ayoola_Dbase_Adapter_Xml_Exception
     */
    public function query( $keyword = null )
	{
		$arguments = func_get_args();
	//	PageCarton_Widget::v( $this ); 
		$keyword = array_shift( $arguments );
    $keyword = ucfirst( strtolower( $keyword ) );
    
  //  $paths = Ayoola_Loader::getValidIncludePaths( $this->_globalDirectory );
	//	PageCarton_Widget::v( $paths ); 
  //	PageCarton_Widget::v( $paths ); \

    //  TRACK CORE CHANGES
    $coreFile = APPLICATION_PATH . DS . $this->_globalDirectory . DS . basename( $this->_myFilename );

    //  TRACK DEFAULT SITE CHANGES
    $defaultFile = SITE_APPLICATION_PATH . DS . $this->_globalDirectory . DS . basename( $this->_myFilename ); 

    
   // var_export( $coreFile );
  //  PageCarton_Widget::v( Ayoola_Application::getDomainSettings( 'domain_name' ) );
  //  PageCarton_Widget::v( filesize( $defaultFile ) );
    $hash = md5( json_encode( $arguments ) . $this->_myFilename . $this->_relationship . $this->_accessibility  . Ayoola_Application::getPathPrefix() . Ayoola_Application::getDomainSettings( 'domain_name' ) ) . @filemtime( $this->_myFilename ). @filemtime( $defaultFile ) . @filemtime( $coreFile );
  //  var_export( $hash );
		$storage = PageCarton_Widget::getObjectStorage( array( 'id' => __CLASS__ . '---wefwfff' . $hash, 'device' => 'File', 'time_out' => 1000000, ) );
		$result = $storage->retrieve();    
		if( false !== $result )
		{
      return $result;
    }
    else
    {
		//  PageCarton_Widget::v( $arguments );
		//  PageCarton_Widget::v( $this->_myFilename );  
		//  PageCarton_Widget::v( $result );
    }
    
		$class = __CLASS__ . '_' . $keyword;
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $class ) )
		{
			require_once 'Ayoola/Dbase/Adapter/Xml/Exception.php';
			throw new Ayoola_Dbase_Adapter_Xml_Exception( $keyword . ' is an invalid keyword' );
		}
		$class = new $class;
		$class->tableClass = get_class( $this );		
//		PageCarton_Widget::v( $this ); 
		foreach( $this as $key => $value )
		{
			require_once 'Ayoola/Reflection/Property.php';
			try
			{ 
				$thisProperty = new Ayoola_Reflection_Property( __CLASS__, $key ); 
				$thisProperty->setAccessible( true );
				$thisProperty->setValue( $class, $value );
				//var_export( $key );
			}
			catch( ReflectionException $e ){  continue; }
		}
    $class = array( $class, __FUNCTION__ );
    $result = call_user_func_array( $class, $arguments );
    $result = false === $result ? null : $result;
    $storage->store( $result );
    return $result;
    
	}
	// END OF CLASS
}
