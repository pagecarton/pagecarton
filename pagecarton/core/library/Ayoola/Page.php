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
				$table = new Ayoola_Page_PageLayout();
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
		
			//	Cache to speed things up a bit
			require_once 'Ayoola/Storage.php';
			$storage = new Ayoola_Storage();
			$storage->storageNamespace = __METHOD__ . $url;
			$storage->setDevice( 'File' );
			if( $info = $storage->retrieve() )
			{ 
				//	var_export( unserialize( $info['cache_info'] ) );
				break; 
			}
			
			
			$tableName = 'Ayoola_Page_Page';		
			$table = new $tableName();		
	    	//	var_export( array( 'url' => $url ) );
			if( $info = $table->selectOne( null, array( 'url' => $url ) ) )
			{ 
				$info['cache_info'] = serialize( $storage );
				$storage->store( $info ); 
				break; 
			}
			$table->getDatabase()->setAccessibility( $tableName::SCOPE_PROTECTED );
  			
		//	var_export( $table->selectOne( null, array( 'url' => $url ), array( 'disable_cache' => true ) ) );
			
			//	We need to cache this to save load time
	//		if( $info = $table->selectOne( null, array( 'url' => $url ) ) )
	//		if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'disable_cache' => true ) ) )
			if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'work-arround-1-333' => true ) ) )
			{ 
				if( @in_array( 'private', $info['page_options'] ) )
				{
					//	We are not allowed to access parent page.
			//		var_export( self::$_currentPageInfo );
					$info = array();
					return false; 
					throw new Ayoola_Exception( 'PAGE INHERITANCE NOT ALLOWED: ' . $url );
				}
			//		var_export( self::$_currentPageInfo );
				$info['cache_info'] = serialize( $storage );
				$storage->store( $info );
				break; 
			}
		}
		while( false );
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
			//		var_export( Ayoola_Application::$mode );
		switch( Ayoola_Application::$mode )
		{
			case 'profile_url':
					//	POST mode
					$pages = array();
					
					//	Home
					$pages[] = self::getInfo( '/' );
					
					//	module
					if( self::getInfo( '/members' ) )
					{
						$pages[] = self::getInfo( '/members' );
					}
//var_export( Ayoola_Application::$GLOBAL );
					//	profile_url
					if( Ayoola_Application::$GLOBAL['profile_url'] )
					{
						$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['profile_url'] ), 'title' => Ayoola_Application::$GLOBAL['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile_description'] );
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
					if( self::getInfo( '/members' ) )
					{
						$pages[] = self::getInfo( '/members' );
					}
					
					//	username
					if( Ayoola_Application::$GLOBAL['username'] )
					{
						$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['username'] ), 'title' => Ayoola_Application::$GLOBAL['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile_description'] );
					}
					
					//	Page
					$pageInfo = self::getInfo( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
					$pageInfo['url'] = $page;
					$pages[] = $pageInfo;
				
				return $pages;
				 
			break;
			case 'module':
				// module mode
				$pages = array();
				
				$table = new Ayoola_Page_Page();
				$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
				$pages = $table->select( null, array( 'url' => self::splitUrl( $page ) ) );

			//	var_export( $pages );
				
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
				
 				//	posts
	//			if( self::getInfo( '/post' ) )
				{
	//				$pages[] = self::getInfo( '/post' );
				}

/* 					
				//	username
				if( @Ayoola_Application::$GLOBAL['username'] )  
				{
					$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['username'] ), 'title' => Ayoola_Application::$GLOBAL['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile_description'] );
				}
 */ 				
 
 					
/* 				//	profile_url
				if( @Ayoola_Application::$GLOBAL['profile_url'] )
				{
					$pages[] = array( 'url' => ( '/' . Ayoola_Application::$GLOBAL['profile_url'] ), 'title' => Ayoola_Application::$GLOBAL['display_name'], 'description' => Ayoola_Application::$GLOBAL['profile_description'] );
				}
 */
				//	Article gan gan
				$pages[] = array( 'url' => Ayoola_Application::$GLOBAL['article_url'], 'title' => Ayoola_Application::$GLOBAL['article_title'] );
			//	var_export( $categoryName );
				return $pages;  
			break;
			default:
		//		var_export( $currentUrl );
		//		var_export( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
				
			//	$currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' );
				$sections = self::splitUrl( $page );
				$table = new Ayoola_Page_Page();
				$pages = $table->select( null, array( 'url' => $sections ), array( 'work-arround-111' => true ) );
				$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
			//	var_export( $currentUrl );
		//			if( $moduleInfo = Ayoola_Page::getInfo( $curentPage ) )
				$pages2 = $table->select( null, array( 'url' => $sections ), array( 'work-arround-333' => true ) );
				$pages = self::sortMultiDimensionalArray( $pages, 'url' );
				$pages2 = self::sortMultiDimensionalArray( $pages2, 'url' );
			//	var_export( $pages );
				$pages = $pages + $pages2;
			//		var_export( $pages );
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
		$table = new Application_Link();
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
/* 		$table = new Application_Domain();
	//	$domains = $table->insert( array( 'domain_name' => 'ayoola' . rand( 0, 100 ), 'domain_default' => 1 ) );
		if( ! $domain = $table->selectOne( null, array( 'domain_default' => '1' ) ) )
		{
			if( ! $domain = $table->selectOne( null, array( 'domain_type' => 'primary_domain' ) ) )
			{
				$domain = $table->selectOne();
			}
		}
 */	//	@$domain = $domain['domain_name'] ? : str_ireplace( 'www.', '', $_SERVER['HTTP_HOST'] );
		return ( Ayoola_Application::getDomainSettings( 'enforced_destination' ) ? : Ayoola_Application::getDomainSettings( 'domain_name' ) ) ? : Ayoola_Application::getDomainName();
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
		$currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' );
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
		$currentUrl = self::getCurrentUrl();
	//	var_export( $currentUrl );
		$url .= 'previous_url=' . urlencode( Ayoola_Application::getDomainSettings( 'protocol' ) . '://' . Ayoola_Page::getDefaultDomain() .  self::getPortNumber() . '' . $currentUrl );
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
