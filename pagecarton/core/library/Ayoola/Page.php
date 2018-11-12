<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Page.php 3.6.2012 11.28 ayoola $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
    
class Ayoola_Page extends Ayoola_Page_Abstract
{
	
    /**       
     * The Page Info
     * 
     * @var array 
     */
	protected static $_currentPageInfo; 
	
    /**
     * 
     * @var string 
     */
	protected static $_canonicalUri;
	
    /**
     * Page Title
     * 
     * @var string 
     */
	public static $title;

    /**
     * Data storage device
     *
     * @var string e.g. Session, File
     */
	protected static $_objectStorageDevice = 'File';
	
    /**
     * Page Description
     * 
     * @var string 
     */
	public static $description;
	
    /**
     * Link for the thumbnail for the page
     * 
     * @var string 
     */
	public static $thumbnail;
	
    /**
     * Allows the htmlHeader to get the correct layout name to use for <base> 
     * 
     * @var string 
     */
	public static $layoutName;
	
    /**
     * Set to true if we are in home page
     * 
     * @var boolean 
     */
	public static $isHome = false;
	
    /**
     * 
     *
     * @param void
     * @return array 
     */
    public static function getAll()
    {		
		$pages = Ayoola_Page_Page::getInstance();
		$pages = $pages->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$pages = $filter->filter( $pages );

		$pages += Ayoola_Page_Layout_Pages::getPages( $data['layout_name'], 'list-url' ) ? : array();
		asort( $pages );
		return array_unique( $pages );
	}
	
    /**
     * Get Page Info as available in $_currentPageInfo class property
     *
     * @param void
     * @return array The Page Info
     */
    public static function getPageCssFile()
    {		
		$cssFile = self::getCurrentPageInfo( 'document_url' );
		if( ! $cssFile )
		{
			if( $cssFile = Application_Settings_Abstract::getSettings( 'Page', 'default_layout' ) )
			{
				$table = Ayoola_Page_PageLayout::getInstance();
				if( $cssFile = $table->selectOne( null, array( 'layout_name' => $cssFile ) ) )
				{
			//		var_export( $cssFile );
					$cssFile = @$cssFile['document_url'];
				}
			}
		}
		return $cssFile ? : '/css/pagecarton.css';
	//	return $cssFile;
    } 
	
    /**
     * GET THE PAGE INFO FROM THE DATABASE
     *
     * @param string URL
     * @return array The Page Info
     */
    public static function getInfo( $url = null )
    {		
		do
		{
			$id = Ayoola_Application::getPathPrefix() . $url;
//			$id = $url . Ayoola_Application::getPathPrefix();
			$storage = self::getObjectStorage( array( 'id' => $id,  ) );
			if( $info = $storage->retrieve() )
			{ 
				break; 
			}
			
			
			$tableName = 'Ayoola_Page_Page';		
		//	$table = new $tableName;		
			$table = $tableName::getInstance();		
	   // 	self::v( array( 'url' => $url ) );
	  //  	self::v( $table->selectOne( null, array( 'url' => $url ) ) );
			if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'id' => $id ) ) )
			{
		//		self::v( $id );
		//		self::v( $info );
				$info['cache_info'] = serialize( $storage );
				$storage->store( $info ); 
				break; 
			}
			$table = $tableName::getInstance( $tableName::SCOPE_PROTECTED );
			$table->getDatabase()->setAccessibility( $tableName::SCOPE_PROTECTED );
	   // 	self::v( $info );
			if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'work-arround-1-333' => true ) ) )
			{ 
				//	remove info we dont want
				if( @in_array( 'private', $info['page_options'] ) )
				{
					//	We are not allowed to access parent page.
			//		var_export( self::$_currentPageInfo );
					$info = array();
					return false; 
				//	throw new Ayoola_Exception( 'PAGE INHERITANCE NOT ALLOWED: ' . $url );
				}
				@$info['page_options'] = array_combine( $info['page_options'], $info['page_options'] );
				unset( $info['title'], $info['description'], $info['layout_name'], $info['page_options']['template'], $info['cover_photo'] );
		//		self::v( $info );
			//		var_export( self::$_currentPageInfo );
				$info['cache_info'] = serialize( $storage );
				$storage->store( $info );
				break; 
			}
		}
		while( false );
//		self::v( $info );
		return $info;
		
    } 
	
    /**
     * Get Page Info as available in $_currentPageInfo class property
     *
     * @param void
     * @return array The Page Info
     */
    public static function getCurrentPageInfo( $infoToGet = null )
    {		
	//	if( is_null( self::$_currentPageInfo ) ){ self::setCurrentPageInfo(); }
		if( ! self::$_currentPageInfo ){ self::setCurrentPageInfo(); }
	//	self::setCurrentPageInfo();
		if( is_null( $infoToGet ) ){ return self::$_currentPageInfo; }
		if( array_key_exists( $infoToGet, self::$_currentPageInfo ) ){ return self::$_currentPageInfo[$infoToGet]; }
    } 
	
    /**
     * Sets Page Info and save it as a class property $_currentPageInfo
     *
     * @param array Info
     */
    public static function setCurrentPageInfo( Array $info = array() )
    {
		// Open the XML file
		require_once 'Ayoola/Xml.php';
		$xml = new Ayoola_Xml();
		$url = Ayoola_Application::getRuntimeSettings( 'real_url' );
		
		//	strip thet artificial get from it.
		$url = Ayoola_Application::getPresentUri( $url );
	//	var_export( $url );
	//	var_export( self::getInfo( $url ) );

		if( empty( self::$_currentPageInfo ) )
		{
			self::$_currentPageInfo = self::getInfo( $url );
		}
	//	exit( microtime( true ) - Ayoola_Application::getRuntimeSettings( 'start_time' ) . '<br />' );
	//	var_export( self::$_currentPageInfo );
		self::$_currentPageInfo = is_array( self::$_currentPageInfo ) ? array_merge( self::$_currentPageInfo, $info ) : $info;
		return self::$_currentPageInfo;
    } 
	
    /**
     * Build Query Strings
     *
     * @param array Query Strings
     * @return string Query Strings
     */
    public static function buildQueryStrings( Array $queryStrings = array(), $appendAllGet = true )
    {
		//if( is_null( $queryStrings ) ){ $queryStrings = $_GET; }
		$queryStrings = true == $appendAllGet ? array_merge( $_GET, $queryStrings ) : $queryStrings;
		$queryStrings = http_build_query( $queryStrings );
		return $queryStrings;
    }
	
    /**
     * Append Query Strings to the End of the Current URL
     *
     * @return string The URL with the query String appended
     */
    public static function appendQueryStrings( Array $queryStrings = array(), $uri = null, $appendAllGet = true )
    {
		if( is_null( $uri ) ){ $uri = Ayoola_Application::getPresentUri(); }
		$queryString = self::buildQueryStrings( $queryStrings, $appendAllGet );
		$url = Ayoola_Application::getUrlPrefix() . rtrim( $uri, '/' ) . '/?' . $queryString;
		return $url;
    }
	
    /**
     * 
     *
     * @return string
     */
    public static function getBreadcrumb( $url = null )
    {
		$page = $url ? : Ayoola_Application::getPresentUri();
		$currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' );
      //     var_export( $page );
		switch( Ayoola_Application::$mode )
		{
			case 'profile_url':
					//	POST mode
					$pages = array();
					
					//	Home
					$pages[] = self::getInfo( '/' );
					
					//	module
			//		if( self::getInfo( '/members' ) )
					{
		//				$pages[] = self::getInfo( '/members' );
					}
//var_export( Ayoola_Application::$GLOBAL );
					//	profile_url
					if( Ayoola_Application::$GLOBAL['profile']['profile_url'] )
					{
						$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['profile']['profile_url'] ), 'title' => Ayoola_Application::$GLOBAL['profile']['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile']['profile_description'] );
					}
					
					//	Page
					$pageInfo = self::getInfo( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
					$pageInfo['url'] = $page;
					$pages[] = $pageInfo;
				
				return $pages;
				 
			break;
			case 'profile':
					//	POST mode
					$pages = array();
					
					//	Home
					$pages[] = self::getInfo( '/' );
					
					//	module
			//		if( self::getInfo( '/members' ) )
					{
				//		$pages[] = self::getInfo( '/members' );
					}
					
					//	username
				//	if( Ayoola_Application::$GLOBAL['username'] )
					{
			//			$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['username'] ), 'title' => Ayoola_Application::$GLOBAL['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile_description'] );
					}
					
					//	Page
					$pageInfo = self::getInfo( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
					$pageInfo['url'] = $page;
					$pages[] = $pageInfo;
				
				return $pages;
				 
			break;
			case 'module':
				// module mode
				$pages = self::getPageCrumbs( $page );

		//		self::v( $page );
		//		self::v( $pages );
				
				//	final word 
				$title = ucwords( array_pop( explode( '/', Ayoola_Application::getRuntimeSettings( 'url' ) ) ) );
				if( class_exists( $title ) && method_exists( $title, 'getObjectTitle' ) && $title::getObjectTitle() )
				{
					$title = $title::getObjectTitle() ? : $title;
				}
				else
				{
					$title = str_ireplace( array( 'Ayoola_', 'Application_', 'Article_', 'Object_', 'Classplayer_', ), '', $title );  
					$title = ucwords( implode( ' ', explode( '_', $title ) ) );
					$title = ucwords( implode( ' ', explode( '-', $title ) ) );
					
					//	Delete generic names
				//	$title = ucwords( implode( ' ', explode( 'Ayoola ', $title ) ) );
				//	$title = ucwords( implode( ' ', explode( 'Application ', $title ) ) );
		//			$title = ucwords( implode( ' ', explode( 'Object ', $title ) ) );
		//			$title = ucwords( implode( ' ', explode( 'Classplayer ', $title ) ) );
				}
				$pages[] = array( 'url' => Ayoola_Application::getRuntimeSettings( 'url' ), 'title' => $title, 'description' => null );
			//	$pages[] = array( 'url' => Ayoola_Application::getRuntimeSettings( 'url' ), 'title' => , 'description' => null );
				//	Category
			//		var_export( $pages );
				return $pages;
			break;
			case 'post':
				//	POST mode
				$pages = array();
				
				//	Home
				$pages[] = self::getInfo( '/' );   
				//	Article gan gan
				$pages[] = array( 'url' => Ayoola_Application::$GLOBAL['post']['article_url'], 'title' => Ayoola_Application::$GLOBAL['post']['article_title'] );
			//	var_export( $categoryName );
				return $pages;  
			break;
			default:
		//		var_export( $currentUrl );
		//		var_export( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
			//		var_export( Ayoola_Application::$mode );
			switch( $page )
			{
				case '/tools/classplayer':
				case '/object':
				case '/pc-admin':
				case '/widgets':
				case '/widget':
		//		case true:
					//	Do nothing.
					//	 had to go through this route to process for 0.00
			//		var_export( __LINE__ );
					if( @$_REQUEST['url'] )
                    {
                        $page = $_REQUEST['url'];
                        $editorMode = true;
                        break;
                    }
				break;
				default:
      //      var_export( $currentUrl );
				break;
			}
				
			//	$currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' );
/*				$sections = self::splitUrl( $page );
				$table = Ayoola_Page_Page::getInstance();
				$pages = $table->select( null, array( 'url' => $sections ), array( 'work-arround-111' => true ) );
				$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
			//	var_export( $currentUrl );
		//			if( $moduleInfo = Ayoola_Page::getInfo( $curentPage ) )
				$pages2 = $table->select( null, array( 'url' => $sections ), array( 'work-arround-333' => true ) );
				$pages = self::sortMultiDimensionalArray( $pages, 'url' );
				$pages2 = self::sortMultiDimensionalArray( $pages2, 'url' );
			//	var_export( $pages );
				$pages = $pages + $pages2;
*/			//		var_export( $pages );
				$pages = self::getPageCrumbs( $page );
				if( Ayoola_Application::getRuntimeSettings( 'real_url' ) == '/404' )
				{
					$pages[] = self::getInfo( '/404' );
				}
				//	Don't shuffle again so that we can priotize local copy.'
			//	$pages = self::sortMultiDimensionalArray( $pages, 'url' );
				return $pages;
			break;
		}
	}
	
    /**
     *
     * @return string
     */
    public static function getPageCrumbs( $page )
    {
		$sections = self::splitUrl( $page );
		$table = Ayoola_Page_Page::getInstance();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
		$pages = $table->select( null, array( 'url' => $sections ), array( 'work-arround-ddd111' => true ) );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
	//	var_export( $currentUrl );
//			if( $moduleInfo = Ayoola_Page::getInfo( $curentPage ) )
		$pages2 = $table->select( null, array( 'url' => $sections ), array( 'work-arround-333' => true ) );
	//	self::v( $pages );
		$pages = self::sortMultiDimensionalArray( $pages, 'url' );
		$pages2 = self::sortMultiDimensionalArray( $pages2, 'url' );
	//	var_export( $pages );
		$pages = $pages + $pages2;
		return $pages;
	}
	
    /**
     *
     * @return string
     */
    public static function splitUrl( $uri )
    {
		$page = explode( '/', $uri );
//		array_unshift( $page, '' );
		$curentPage = null;
		$sections = array();
		do
		{
			$curentPage =  '' . rtrim( $curentPage, '/' ) . '/';
			$curentPage = $curentPage . '' . array_shift( $page ) . '';
		//	$curentPage .= array_shift( $page );
		//	var_export( $curentPage );
			$sections[] = $curentPage;
		}
		while( $page );
		return $sections;
	}
	
    /**
     * Returns the canonical url
     *
     * @return string
     */
    public static function getCanonicalUri( $uri = null, $setMainCanonicalUri = true )
    {
		//	Sending a uri param will reset canonical url
		if( ! is_null( self::$_canonicalUri ) && ! $uri )
		{
			return self::$_canonicalUri;
		}
	//	$uri = Ayoola_Application::getRuntimeSettings( 'url' );
		$uri = $uri ? : Ayoola_Application::getRequestedUri();
	//	var_export( $uri );

		//	Look in the links table for SEO friendly and short URLS
		$table = Application_Link::getInstance();
	//\\	var_export( $table->select() );
		if( $link = $table->selectOne( null, array( 'link_url' => $uri ) ) )
		{
			$uri = '/' . $link['link_name'] . '/';
		}
		if( $uri == Ayoola_Application::$_homePage || ! $uri || $uri == '/' )
		{
			self::$isHome = true;
			$uri = '/'; 
		}
		if( $setMainCanonicalUri )
		{
			self::$_canonicalUri = $uri;
		}
	//	var_export( $url );
		return $uri;
    }
	
    /**
     * Returns the canonical url
     *
     * @return string
     */
    public static function getCanonicalUrl( $uri = null )
    {
	//	$uri = Ayoola_Application::getRuntimeSettings( 'url' );
		$uri = self::getCanonicalUri( $uri, false );
				//	if( $_SERVER['SERVER_PORT'] != '80' ){ break; }
		$url = self::getHomePageUrl() . $uri;  
	//	var_export( $url );
		return $url;
    }
	
    /**
     * Returns the home page url
     *
     * @return string
     */
    public static function getHomePageUrl()
    {
		$domain = self::getDefaultDomain();
		$url = self::getRootUrl() . Ayoola_Application::getUrlPrefix();  
		return $url;
    }
	
    /**
     * Returns the home page url
     *
     * @return string
     */
    public static function getRootUrl()
    {
		$domain = self::getDefaultDomain();
		$url = Ayoola_Application::getDomainSettings( 'protocol' ) . '://' . $domain . self::getPortNumber();  
		return $url;
    }
	
    /**
     * Returns the default domain name
     *
     * @return string
     */
    public static function getDefaultDomain()
    {
	//	var_export( Ayoola_Application::getDomainSettings( 'domain_name' ) );
		$domainName = Ayoola_Application::getDomainSettings( 'domain_name' );
		if( ! @Ayoola_Application::getDomainSettings( 'dynamic_domain' ) )
		{
			$domainName = ( Ayoola_Application::getDomainSettings( 'enforced_destination' ) ? : Ayoola_Application::getDomainSettings( 'domain_name' ) ) ? : Ayoola_Application::getDomainName();
		}
	//	exit();
		return $domainName;
	//	return Ayoola_Application::getDomainName();
    }
	
    /**
     * Returns the default domain name
     *
     * @return string
     */
    public static function getPortNumber()  
    {
		return ( $_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' ? '' : ( ':' . $_SERVER['SERVER_PORT'] ) );
    }
	
    /**
     * Returns the link for thumbnail for the page
     *
     * @return string
     */
    public static function getThumbnail()
    {
		if( is_null( self::$thumbnail ) ){ self::$thumbnail = '/img/logo.png'; }
		return Ayoola_Doc::uriToDedicatedUrl( self::$thumbnail );
    }
	
    /**
     * Returns the current url with query string
     *
     * @return string
     */
    public static function getCurrentUrl()
    {
		$queryString = self::buildQueryStrings();
		$currentUrl = rtrim( Ayoola_Application::getRequestedUri(), '/' );
	//	if
		switch( Ayoola_Application::$mode )
		{
			case 'document':
			
			break;
			case 'post':
			
			break;
			default:
				$currentUrl .= '/';
			break;
		}
		$currentUrl =  '' . Ayoola_Application::getUrlPrefix() . '' . $currentUrl . '?' . $queryString;
		return $currentUrl;
    }
	
    /**
     * Returns the Previous url usually saved as a url parameter
     *
     * @return string
     */
    public static function getPreviousUrl( $default = '' )
    {
		$url = empty( $_REQUEST['previous_url'] ) ? $default : $_REQUEST['previous_url'];
		return urldecode( $url );
    }
	
    /**
     * Sets the Previous url usually saved as a url parameter
     *
     * @param string
     * @return string
     */
    public static function setPreviousUrl( $url = null )
    {
		$url .= stripos( $url, '?' ) ? '&' : '?';
		if( @$_GET['previous_url'] )
		{
			$url .= 'previous_url=' . urlencode( $_GET['previous_url'] );
		//	var_export( $url );
			return $url;
		}
	//	$currentUrl = Ayoola_Application::getRequestedUri();
		$currentUrl = self::getCurrentUrl();
	//	var_export( $currentUrl );

		//	https://www.example.com/account/signin?previous_url=https://www.example.com/account is being blocked by some servers
		//	probably because of xss
		//	$_POST, $_GET was being cleared
	//	$url .= 'previous_url=' . urlencode( Ayoola_Application::getDomainSettings( 'protocol' ) . '://' . Ayoola_Page::getDefaultDomain() .  self::getPortNumber() . '' . $currentUrl );
		$url .= 'previous_url=' . urlencode( '//' . Ayoola_Page::getDefaultDomain() .  self::getPortNumber() . '' . $currentUrl );
	return $url;
    }	
	
    /**
     * Returns the path to a particular page
     *
     * @param string The Uri to the Page
     * @return string The path to the Page
     */
    public static function getPagePaths( $pageUri = null )
    {
		if( is_null( $pageUri ) ){ $pageUri = Ayoola_Application::getPresentUri(); }
		if( $pageUri === '/' ){ $pageUri = ''; }
		require_once 'Ayoola/Filter/UriToPath.php';
		$filter = new Ayoola_Filter_UriToPath;
		return $filter->filter( $pageUri );
    }
    
}
