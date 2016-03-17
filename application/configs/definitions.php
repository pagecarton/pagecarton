<?php 
    //   Define value of extension for files
    defined('EXT') || define('EXT', '.php');
    //   Define value of extension for files
    defined('TPL') || define('TPL', '.phtml');	
    //   Define value of extension for files
    defined('EXT_DATA') || define('EXT_DATA', '.xml');
    defined('FILE_JS') || define('FILE_JS', '.js');
    defined('FILE_CSS') || define('FILE_CSS', '.css');
	
	defined('DS') || define('DS', DIRECTORY_SEPARATOR);
	defined('PS') || define('PS', PATH_SEPARATOR);
	
//	Define application environment
	defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
	
//  Define dir of  application
	defined( 'APPLICATION_PATH' ) || define( 'APPLICATION_PATH', APPLICATION_DIR . DS . 'application' );
    
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
    defined('LIBRARY_PATH') || define('LIBRARY_PATH', APPLICATION_DIR  . DS . 'library' );
    
//   Define path to libraries
	//defined('DOCUMENTS_DIR') || define('DOCUMENTS_DIR', APPLICATION_PATH  . DS . 'documents');
    defined('DOCUMENTS_DIR') || define( 'DOCUMENTS_DIR', 'documents' );
    defined('AYOOLA_MODULE_FILES') || define( 'AYOOLA_MODULE_FILES', 'module_files' );
    defined('XML_DATABASES_DIR') || define( 'XML_DATABASES_DIR', 'databases' );
    defined('CACHE_DIR') || define( 'CACHE_DIR', APPLICATION_DIR . DS . 'cache' );

//   Specify the beginning of GET parameters in URLs
	defined('GET') || define('GET', '/get/');

//	The Domain Name
    defined('DOMAIN') || define('DOMAIN', $_SERVER['HTTP_HOST'] );

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