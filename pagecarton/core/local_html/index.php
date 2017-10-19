<?php 

		//	Report all PHP errors 
		error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
		ini_set( 'display_errors', "0" );	 
		ini_set( "memory_limit","512M" );	 
		defined('DS') || define('DS', DIRECTORY_SEPARATOR);
		defined('PS') || define('PS', PATH_SEPARATOR);
		
		
		//    look for this path prefix dynamically
		$currentDir = explode( '/', str_replace( array( '/', '\\' ), '/', dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
		$tempDir = explode( '/', str_replace( array( '/', '\\' ), '/', rtrim( $_SERVER['DOCUMENT_ROOT'], '/\\' ) ) );  

		$prefix = null;
		if( $currentDir !== $tempDir )
		{
			$prefix = array_diff( $currentDir, $tempDir );
			if( implode( '/', $currentDir ) === implode( '/', $tempDir + $prefix ) && trim( implode( '/', $prefix ) ) )
			{
				$prefix = '/' . implode( '/', $prefix );  
			}
		}
		//	var_export( $tempDir );
		//	var_export( $prefix );
		defined('PATH_PREFIX') || define('PATH_PREFIX', $prefix );
		defined('PC_PATH_PREFIX') || define('PC_PATH_PREFIX', $prefix );
	//	var_export( PATH_PREFIX );

		$oldDir = dirname( $_SERVER['DOCUMENT_ROOT'] ); 
		$oldDir = realpath( $oldDir ) ? : $oldDir; 
	//	var_export( $_SERVER['DOCUMENT_ROOT'] );
	//	var_export( dirname( $_SERVER['DOCUMENT_ROOT'] ) );
	//	var_export( $oldDir );
		
	//	exit();
		
		//	Check if the new compact dir is available, overides the old structure.
		$newDir = $oldDir . DS . 'pagecarton';
		
		//	introducing separate core dir to make this one easily replaceable during upgrades
		$newDir2 = $newDir . DS . 'core';
		
		$oldAppPath = $oldDir . DS . 'application';
		$newAppPath = $newDir . DS . 'application';
		$newLibaryPath = $newDir . DS . 'library';
		$dirToUse = $oldDir;
		
		if( is_dir( $newDir ) )
		{
			$dirToUse = $newDir;
			
			//	check if we have application in the old dir, so we can copy to the new dir.
			if( is_dir( $oldAppPath ) && ! is_dir( $oldAppPath . '.old'  ) )
			{
				$source = $oldAppPath;
				$dest = $newAppPath;   
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
				function xcopy($source, $dest, $permissions = 0755)
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
						xcopy("$source/$entry", "$dest/$entry", $permissions);
					}

					// Clean up
					$dir->close();
					return true;
				}
				xcopy( $source, $dest );
				rename( $oldAppPath, $oldAppPath . '.old' );
			}
		}
		if( is_dir( $newDir2 ) )
		{
			//	No need to copy existing files since this will be done once upgrade is done.
			$dirToUse = $newDir2;
		}
		$appPath = $dirToUse . DS . 'application';
		$libaryPath = $dirToUse . DS . 'library';
		
		//	Parent of all dir /pagecarton
		defined('PC_BASE') || define('PC_BASE', $newDir );   
		defined('APPLICATION_DIR') || define('APPLICATION_DIR', $dirToUse );
		
		//	Application_dir is where site/app specific is stored
		defined( 'APPLICATION_PATH' ) || define( 'APPLICATION_PATH', $appPath );
		
	//   Define path to classes
		defined('EXTENSIONS_PATH') || define('EXTENSIONS_PATH', APPLICATION_DIR  . DS . 'extensions' );
		
		//   Define path to libraries
		defined('LIBRARY_PATH') || define('LIBRARY_PATH', $libaryPath );
		
		//	Stop writing cache in the pagecarton dir
	//	$tempDir = $oldDir . DS . 'temp' . DS';	
		
		defined('PC_TEMP_DIR') || define( 'PC_TEMP_DIR', $newDir . DS . 'temp' );
	//	var_export( PC_TEMP_DIR );
		//	port number mess up cache
		$tempDir = str_replace( ':', DS, 'cache' . DS . $_SERVER['HTTP_HOST'] ) . DS . $prefix;
		defined('CACHE_DIR') || define( 'CACHE_DIR', PC_TEMP_DIR . DS . $tempDir );
		
		//   Define value of extension for files
		defined('EXT') || define('EXT', '.php');
		//   Define value of extension for files
		defined('TPL') || define('TPL', '.phtml');	
		//   Define value of extension for files
		defined('EXT_DATA') || define('EXT_DATA', '.xml');
		defined('FILE_JS') || define('FILE_JS', '.js');
		defined('FILE_CSS') || define('FILE_CSS', '.css');
		
		
	//	Define application environment
		defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
		
		
	//   Define path to pages
		defined('PAGE_PATH') || define('PAGE_PATH', 'pages');
		defined('PAGE_INCLUDES') || define('PAGE_INCLUDES', PAGE_PATH  . DS . 'includes');
		defined('PAGE_DATA') || define('PAGE_DATA', PAGE_PATH  . DS . 'data');
		defined('PAGE_TEMPLATE') || define('PAGE_TEMPLATE', PAGE_PATH  . DS . 'templates');
		
	//   Define path to layout
		defined('LAYOUT_PATH') || define( 'LAYOUT_PATH', PAGE_PATH  . DS . 'layouts' );
		defined('LAYOUT_FILE') || define('LAYOUT_FILE', LAYOUT_PATH  . DS . 'body' . TPL);
		
	//   Define path to classes
		defined('MODULES_PATH') || define('MODULES_PATH', APPLICATION_PATH  . DS . 'modules' );
		
		
	//   Define path to libraries
		//defined('DOCUMENTS_DIR') || define('DOCUMENTS_DIR', APPLICATION_PATH  . DS . 'documents');
		defined('DOCUMENTS_DIR') || define( 'DOCUMENTS_DIR', 'documents' );
		defined('AYOOLA_MODULE_FILES') || define( 'AYOOLA_MODULE_FILES', 'module_files' );
		defined('XML_DATABASES_DIR') || define( 'XML_DATABASES_DIR', 'databases' );

	//   Specify the beginning of GET parameters in URLs
		defined('GET') || define( 'GET', '/get/' );  

	//	The Domain Name
		defined('DOMAIN') || define('DOMAIN', $_SERVER['HTTP_HOST'] );
		
		//	Bring in our libraries.
		set_include_path( LIBRARY_PATH . PS . MODULES_PATH . PS . APPLICATION_PATH  );
	//	Detects the Url and path
		require_once 'Ayoola/Application.php';
		defined('URI') || define( 'URI', Ayoola_Application::getPresentUri() );

		require_once 'Ayoola/Page.php';
		$pagePaths = Ayoola_Page::getPagePaths( URI );
	//	var_export( $pagePaths );
		defined('PAGE_INCLUDE_FILE') || define( 'PAGE_INCLUDE_FILE', $pagePaths['include'] );
		defined('PAGE_TEMPLATE_FILE') || define( 'PAGE_TEMPLATE_FILE', $pagePaths['template'] );
		defined('PAGE_DATA_FILE') || define( 'PAGE_DATA_FILE', $pagePaths['data'] );
		
	//	echo "<br />\n"; foreach($_SERVER as $key=>$val) {echo  '$_SERVER['.$key."] = $val<br />\n";}	
	//   Include prerequisite    
	//	don't run if the installer is active and we are not the admin
		require_once 'Ayoola/Application.php';  
		Ayoola_Application::run();   
