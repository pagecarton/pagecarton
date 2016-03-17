<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Doc.php 12/18/2011 7.54PM ayoola $
 */

/**
 * @see Ayoola_
 */

require_once 'Ayoola/Doc/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc extends Ayoola_Doc_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     *
     * @var boolean
     */
	protected static $_counter = 0;

    /**
     * The paths to each document
     *
     * @var array
     */
	protected $_paths = array();
	
    /**
     * The Adapter to document
     *
     * @var Ayoola_Doc_Adapter
     */
	protected $_adapter;
	
    /**
     * The FullPath to the Current Document Directory
     *
     * @var string
     */
	protected static $_documentDirectory;
	
    /**
     * The title of the Document
     *
     * @var string
     */
	public $title;

    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_instance;

    /**
     * Constructor
     *
     * @param The path to the document
     * 
     */
    public function init()
    {
	//	$paths = (array) $this->getParameter( 'option' );
	//	var_export( $this->getParameter() );
/* 		foreach( $paths as $path )
		{
			$path ? $this->loadFile( $path ) : null;
		}
 */ }
	
    /**
     * Returns a singleton Instance
     *
     * @param void
     * @return self
     */
    public static function getInstance()
    {
		//if( is_null( self::$_instance ) ){ self::$_instance = new self; }
		return new self;
    } 	
	
    /**
     * Loads the file in format safe for output
     *
     * @param The path to the document
     * @return boolean
     */
    public function loadFile( $path )
    {
	//	var_export( $path );
		require_once 'Ayoola/Loader.php';
		if( ! $absolutePath = Ayoola_Loader::checkFile( $path ) )
		{
	//	var_export( $path );
			require_once 'Ayoola/Doc/Exception.php';
			throw new Ayoola_Doc_Exception( basename( $path ) . ' Not Found' );	
			return false;
		}
	//	var_export( $path );
		if( $includePaths = array_keys( Ayoola_Loader::getValidIncludePaths( $path ) ) )
		{
			$documentDirectory = array_shift( $includePaths );
			$documentDirectory = $documentDirectory . DS . DOCUMENTS_DIR;
			self::setDocumentDirectory( $documentDirectory );
						//var_export( self::getDocumentDirectory() );
					//	var_export( $documentDirectory );
		}
	//	var_export( var_export( $documentDirectory ) );
		$this->setPaths( $absolutePath );
    } 
	
    /**
     * Sets the _adapters to a value
     *
     * @param string File Extention 
     * @return Ayoola_Doc_Adapter
     * @see Ayoola_Doc_Adapter
     */
    public function setAdapter( $paths = null )
    {
		$paths = $paths ? : $this->getPaths();
		require_once 'Ayoola/Doc/Adapter.php';
//		var_export( $paths );
        $this->_adapter = new Ayoola_Doc_Adapter( $paths );
    } 
	
    /**
     * Loads the adapter
     *
     * @param string File Extention 
     * @return Ayoola_Doc_Adapter
     * @see Ayoola_Doc_Adapter
     */
    public function getAdapter( $paths = null )
    {
		if( null ===$this->_adapter )
		{
			$this->setAdapter( $paths );
		}
		//var_export( $this->_adapter );
		return $this->_adapter;
    } 
	
    /**
     * Coverts a document uri to its dedicated domain counterparts 
     *   
     * @param string URI 
     * @return string http://{time()}.document.{domain}/{uri} or {uri}
     */
    public static function uriToDedicatedUrl( $uri, array $options = null )
    {
		$useQueryStrings = true;
		do
		{
		//	break;
			if( ! $uri || ! is_string( $uri ) ){ break; }
			if( strpos( $uri, '//' ) !== false ){ break; }   
			
			//	Localhosts
			if( ! strpos( $_SERVER['HTTP_HOST'], '.' ) )
			{
				$useQueryStrings = true;
		//		break; 
			}
			if( $_SERVER['SERVER_PORT'] != '80' ){ break; }
			if( Ayoola_Application::getUrlPrefix() && $uri[0] === '/' )
			{
				$uri = Ayoola_Application::getUrlPrefix() . $uri;
			}
		//	var_export( $uri . '<br>' );
			try
			{
			//	$filePath = DOCUMENTS_DIR . $file;
				$storage = new Ayoola_Storage(); 
				$storage->storageNamespace = __CLASS__ . $uri . '-x-xxxxx';
				$storage->setDevice( 'File' );
			//	self::v( $storage->retrieve() );
				if( ! $dedicatedUrl = $storage->retrieve() OR $options['disable_cache'] )  
				{
					$j = new Ayoola_Doc( array( 'option' => $uri ) );
					$j = str_replace( '/', DS, $j::getDocumentDirectory() . $uri ); 
				//	var_export( $j );
		//			var_export( __LINE__ );
					$j = filemtime( $j );
					$domain = Ayoola_Page::getDefaultDomain();
					$domain = DOMAIN;
					$dedicatedUrl = $j ? "http://{$j}.document.{$domain}" . $uri : $uri;
					if( $useQueryStrings )
					{
						$dedicatedUrl = "http://{$domain}{$uri}?document_time={$j}";					
					}
					
					$storage->store( $dedicatedUrl );
				}
			}
			catch( Exception $e ){ null; }
		}
		while( false );
		@$dedicatedUrl = $dedicatedUrl ? : $uri;
		return $dedicatedUrl;
    } 
	
    /**
     * This method sets the _documentDirectory to a value
     *
     * @param string The Document Directory
     * @return null
     */
    public static function setDocumentDirectory( $directory = null )
    {
		$directory = $directory ? : DOCUMENTS_DIR;
		self::$_documentDirectory = $directory;
    } 
	
    /**
     * This method returns the _documentDirectory property
     *
     * @param 
     * @return string The Document Directory
     */
    public static function getDocumentDirectory()
    {
		if( is_null( self::$_documentDirectory ) ){ self::setDocumentDirectory(); }
		return self::$_documentDirectory;
    } 
	
    /**
     * Returns the value of the _path property
     *
     * @return string Path to the Document
     */
    public function getPaths()
    {
        return (array) $this->_paths;
    } 
	
    /**
     * Sets the _path property to a value
     *
     * @param The path to the document
     */
    public function setPaths( $paths )
    {
		if( is_array( $paths ) )
		{
			$this->_paths = array_merge( $this->getPaths(), $paths );
		}
		elseif( is_string( $paths ) )
		{
			$this->_paths[] = $paths;
		}
    } 
	
    /**
     * Retrieves URI from directory
     *
     * @param string The path to the document
     * @return string URI
     */
    static public function pathToUri( $path )
    {	
		//	Retrieve the url from the path
		require_once 'Ayoola/Loader.php';
		$dirPaths[] = self::getDocumentDirectory();
		$fullDirPath = Ayoola_Loader::checkDirectory( self::getDocumentDirectory() );
		if( $fullDirPath
			&& ! in_array( $fullDirPath, $dirPaths )
			){ $dirPaths[] = $fullDirPath; }
		$paths = explode( PS, get_include_path() );
		foreach( $paths as $dirPath )
		{
			$dirPath = $dirPath . DS . DOCUMENTS_DIR;
			$path = str_ireplace( DS, '/', $path );		
			$dirPath = str_ireplace( DS, '/', $dirPath );		
			$uri = str_ireplace( $dirPath, '', $path );
		//	var_export( $dirPath );
			if( $uri != $path ){ break; }
		}
		//var_export( self::getDocumentDirectory() );
		//var_export( $path );
		return $uri;
    } 
	
    /**
     * Retrieves URI from directory
     *
     * @param mixed The path(s) to the document
     * @return array Array of URIs
     */
    static public function pathsToUris( $paths )
    {	
		//	We allow scalar and array values
		$paths = (array) $paths;
		$values = array();
		foreach( $paths as $key => $value )
		{
			$key = self::pathToUri( $key );
			$value = self::pathToUri( $value );
			$values[$key] = $value;
		}
		//var_export( $uris );
		return $values;
    } 
	
    /**
     * Retrieve the files present in a directory
     *
     * @param string The Directory to look in
     * @return array filenames in the searched directories
     */
    static public function getFiles( $directory, array $options = null )
    {
		$files = array();
		if ( ! is_dir( $directory ) ) 
		{
			throw new Ayoola_Doc_Exception( 'Invalid Directory - ' . $directory );
		}
		if ( ! ( $handle = opendir( $directory ) ) ) 
		{
			throw new Ayoola_Doc_Exception( 'Directory cannot be opened  for reading - ' . $directory );
		}
		while ( ( $filename = readdir( $handle ) ) !== false ) 
		{
			$file = $directory . DS . $filename;
						//var_export( $file );
		//	self::v( $file ); 
			if( is_file( $file ) )
			{
				if( in_array( $file, $files ) )
				{
					continue;
				}
	//			$files[$file] = $file; 
				$key = $file;
				if( ! empty( $options['key_function'] ) )
				{
					$key = $options['key_function'];
					$key = $key( $file );
		//			self::v( $options['key_function'] ); 
		//			self::v( $file ); 
		//			self::v( $key ); 
				}
				if( isset( $files[$key] ) ) 
				{
					while( isset( $files[$key] ) )
					{
						if( is_numeric( $key ) )
						{
							$key = strval( $key + ++self::$_counter + microtime( false) );
						}
						else
						{
							@$oldKey = $oldKey ? : $key;
							$key = $oldKey . @$counterI++;
						}
				//		self::v( $key ); 
					}
				}
				$files[$key] = $file; 
			}
			elseif( trim( $filename, '.' ) && is_dir( $file ) && @$options['return_directories'] )
			{
				$files[$file] = $file; 
			}
			
			if( $filePath = Ayoola_Loader::checkFile( $file ) )
			{ 
				//	self::v( $filePath );
 				if( in_array( $filePath, $files ) )
				{
				//	self::v( $filePath );
					continue;
				}
				//	self::v( $options['key_function'] ); 
 				$key = $filePath;
				if( ! empty( $options['key_function'] ) )
				{
					$key = $options['key_function'];
					$key = $key( $filePath );
				}
				if( isset( $files[$key] ) ) 
				{
					while( isset( $files[$key] ) )
					{
						if( is_numeric( $key ) )
						{
							$key = strval( $key + ++self::$_counter + microtime( false) );
						//	self::v( $filePath ); 
						}
						else
						{
							@$oldKey = $oldKey ? : $key;
							$key = $oldKey . @$counterI++;
						}
					}
				}
				$files[$key] = $filePath; 
		//		var_export( $key );
			}
		}
		closedir( $handle );
		ksort( $files, SORT_NUMERIC );
	//	( $files );
		$files = array_unique( $files );
	//	self::v( $files );
		return $files;
    } 
	
    /**
     * Retrieve the files present in a directory
     *
     * @param string The Directory to look in
     * @return array filenames in the searched directories
     */
    static public function getFilesRecursive( $directory, array $options = null )
    {
		$files = self::getFiles( $directory, $options );
	//	var_export( $files );
		$directories = self::getDirectoriesRecursive( $directory );
		foreach( $directories as $directory )
		{
		//	$files = array_merge( $files, self::getFiles( $directory, $options ) );
		//	$files = @array_merge( $files, self::getFiles( $directory, $options ) ) ? : array();
		//	self::v( self::getFiles( $directory, $options ) );
		//	$files = @array_merge( $files, self::getFiles( $directory, $options ) ) ? : array();
			$files += self::getFiles( $directory, $options ) ? : array();
		}
	//	self::v( $files );
		ksort( $files, SORT_NUMERIC );
		return $files;
    } 
	
    /**
     * Retrieve the directories present in a directory
     *
     * @param string The Directory to look in
     * @return array Directories in the searched directories
     */
    static public function getDirectories( $directory )
    {
		$directories = array();
		if ( ! is_dir( $directory )) 
		{
			throw new Ayoola_Doc_Exception( 'Invalid Directory - ' . $directory );
		}
		if ( ! ( $handle = opendir( $directory ) ) ) 
		{
			throw new Ayoola_Doc_Exception( 'Directory cannot be opened  for reading - ' . $directory );
		}
		while ( ( $innerDirectoryBaseName = readdir( $handle ) ) !== false ) 
		{
			$innerDirectory = $directory . DS . $innerDirectoryBaseName;
			if ( trim( $innerDirectoryBaseName, '.' ) &&  is_dir( $innerDirectory )  )
			{ 
				$directories[] = $innerDirectory; 
			}
		}
		closedir( $handle );
		//var_export( $directories );
		return $directories;
    } 
	
    /**
     * Recursive version of getDocumentDirectories Method
     *
     * @param string The Directory to look in
     * @return array Directories in the searched directories
     */
    static public function getDirectoriesRecursive( $directory )
    {
		$recursiveDirectories = array();
		$directories = self::getDirectories( $directory );
		foreach( $directories as $directory )
		{
			$method = __FUNCTION__;
			$directorySDirectory = self::$method( $directory );
			$recursiveDirectories = array_merge( $recursiveDirectories, $directorySDirectory );
		}
		$recursiveDirectories = array_merge( $directories, $recursiveDirectories );
		//var_export( $directory );
		return $recursiveDirectories;
    } 
	
    /**
     * Attempts to remove dirs recursively in case
     *
     * @param string Path to Directory to be deleted
     * @return void
     */
    public static function removeDirectory( $dir, $deleteContent = false )
    {
		if( ! $deleteContent )
		{
			// Remove dir recursively
			while( is_dir( $dir ) && rmdir( $dir ) ){ $dir = dirname( $dir ); }
		}
		else
		{
			self::deleteDirectoryPlusContent( $dir );
		}
		return ! is_dir( $dir );
    } 
	
    /**
     * Attempts to remove dirs recursively in case
     *
     * @param string Path to Directory to be deleted
     * @return void
     */
	public static function deleteDirectoryPlusContent($path) {
		if (!is_dir($path)) {
			throw new Ayoola_Doc_Exception("$path is not a directory");
		}
		if (substr($path, strlen($path) - 1, 1) != '/') {
			$path .= '/';
		}
		$dotfiles = glob($path . '.*', GLOB_MARK);
		$files = glob($path . '*', GLOB_MARK);
		$files = array_merge($files, $dotfiles);
		foreach ($files as $file) {
			if (basename($file) == '.' || basename($file) == '..') {
				continue;
			} else if (is_dir($file)) {
				self::deleteDirectoryPlusContent($file);
			} else {
				unlink($file);
			}
		}
		rmdir($path);
		return ! is_dir( $path );
	}	
	
	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.0.1
	 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @param       string   $permissions New folder creation permissions
	 * @return      bool     Returns true on success, false on failure
	 */
	public static function recursiveCopy($source, $dest, $permissions = 0755)  
	{
		// Check for symlinks
		if( is_link( $source ) ) {  
			return symlink(readlink($source), $dest);
		}

		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest, $permissions);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			self::recursiveCopy("$source/$entry", "$dest/$entry", $permissions);
		}

		// Clean up
		$dir->close();
		return true;
	}

	/**
     * Creates the directory
     * For the page and for template
     * @param void
     * @return boolean
     */
    public static function createDirectory( $dir, $permission = 0700, $recursive = true )
    {
		if( ! is_dir( $dir ) )
		{
		//	var_export( $dir );    
			if( ! mkdir( $dir, $permission, true ) ){ return false; }
		}
		//	Returns true altogether because this is not required per say
		return true;
    } 

	/**
     * 
     * @param void
     * @return boolean
     */
    public static function getLogo()
    {
		$logo = self::uriToDedicatedUrl( '/img/logo.png' );
		return $logo;
    } 
	
    /**
     * This method sets the _classOptions property to a value
     *
     * @param directory to Look in
     * @return void
     */
    public function setClassOptions( $directory = null )
    {
		$options = $this->getDbData();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'document_url', 'document_name');
		$options = $filter->filter( $options );
//		var_export( $options );
		$options = self::pathsToUris( $options );
		$this->_classOptions = (array) $options;
//		var_export( $this->_classOptions );
	} 	
	
    /**
     * This method returns the _classOptions property
     *
     * @param void
     * @return array
     */
    public function getClassOptions()
    {
		if( null === $this->_classOptions )
		{
			$this->setClassOptions();
		}
		//var_export( $this->_classOptions );
		return (array) $this->_classOptions;
    } 	
	
    /**
     * This method return the value of _viewOption property
     *
	 * @return mixed
     */
    public function getViewOption()
    {
		return $this->_viewOption;
    } 	
	
    /**
     * This method sets the _viewOption property to a value
     *
     * @param mixed The Value for the ViewableObjecect Option URI Path
     * @return string
     */
    public function setViewOption( $value )
    {
	//	var_export( $value );
	//	var_export( is_file( $value ) );
		$path = array();
		$path['include'] = $value;
		if( $value )
		{
			if( ! is_file( $value ) )
			{
				require_once 'Ayoola/Filter/UriToPath.php';
				$filter = new Ayoola_Filter_UriToPath();
				$path = $filter->filter( $value );
			}
		}
		$this->loadFile( $path['include'] ); 
	//	try{ $this->loadFile( $path['include'] ); } 
		//	Document not found
	//	catch( Ayoola_Doc_Exception $e ){ return false; }
	//	var_export( $path );
		$this->_viewOption = $path;
    } 
	
    /**
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object				
     * @param mixed Parameter set from the layout editor
     * @return null
     */
    public function setViewParameter( $parameter )
	{
		//	If there is a view parameter, we should be in inline mode
		$this->getAdapter()->setInlineViewMode( true );
		$this->getAdapter()->title = $parameter;
	}

    /**
     * Makes this class viewable for ayoola class player
     *
     * @param string Filename
     * @return string Mark-up
     */
    public static function viewInLine( $viewParameter = null, $viewOption = null )
    {
		if( is_null( $viewParameter ) && is_null( $viewOption ) )
		{
			throw new Ayoola_Doc_Exception( 'We must have either a view parameter or option to run this method' );
		}
		$docView = self::getInstance();
		$docView->setViewParameter( $viewParameter );
		$docView->setViewOption( $viewOption );
		return $docView->view();
    } 	

    /**
     * Makes this class viewable for ayoola class player
     *
     * @param void
     * @return string Mark-up
     */
    public function view()
    {	
		//var_export( $this->getPaths() );
		$this->getAdapter()->setPaths( $this->getPaths()  );
		return $this->getAdapter()->view();
    } 
	
    /**
     * Force the download of the document
     *
     * @param void
     * @return void
     */
    public function download()
    {
		$this->getAdapter( $this->getPaths() )->download();
    } 
	// END OF CLASS
}
