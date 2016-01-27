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
		return $cssFile ? : '/css/ayoola_default_style.css';
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
			if( $info = $table->selectOne( null, array( 'url' => $url ) ) )
			{ 
				$info['cache_info'] = serialize( $storage );
				$storage->store( $info ); 
				break; 
			}
			
			$table->getDatabase()->setAccessibility( $tableName::SCOPE_PROTECTED );
	//		var_export( $table->select() );
			
			//	We need to cache this to save load time
	//		if( $info = $table->selectOne( null, array( 'url' => $url ) ) )
	//		if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'disable_cache' => true ) ) )
			if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'work-arround-333' => true ) ) )
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
		$url = rtrim( $uri, '/' ) . '/?' . $queryString;
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
				//	POST mode
				$pages = array();
				
				$table = new Ayoola_Page_Page();
				$pages = $table->select( null, array( 'url' => self::splitUrl( $page ) ) );
			//	var_export( $currentUrl );
			//	var_export( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
		//		$pages = array_merge( $pages,  ? : array() );
/* 				//	Home
				$pages[] = self::getInfo( '/' );
				
				//	module
				$pages[] = self::getInfo( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
 */				
				// Posts
			//	$pages[] = array( 'url' => Application_Article_Abstract::getPostUrl(), 'title' => 'Posts', 'description' => '' );
				
				// Category
				if( ! empty( $_REQUEST['category'] ) )
				{
					$table =  new Application_Category();
					
					$childCategory = $_REQUEST['category'];
					$i = 0;
					do
					{
						//	Count to prevent infinite loop
						$i++;
						if( $categoryInfo = $table->selectOne( null, array( 'child_category_name' => $childCategory ) ) )
						{
							//	parent categories
							$pages[] = array( 'url' => $categoryInfo['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $categoryInfo['category_name'] . '/' ), 'title' => $categoryInfo['category_label'], 'description' => $categoryInfo['category_description'] );
						}
						$childCategory = @$categoryInfo['category_name'];
					}
					while( $categoryInfo && $i < 5 );
					if( $categoryInfo = $table->selectOne( null, array( 'category_name' => $_REQUEST['category'] ) ) )
					{
					
						//	present category last
						$pages[] = array( 'url' => $categoryInfo['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $categoryInfo['category_name'] . '/' ), 'title' => $categoryInfo['category_label'], 'description' => $categoryInfo['category_description'] );
					}
				//			var_export( Ayoola_Application::$GLOBAL );
				}
				
				//	Category
				if( ! empty( Ayoola_Application::$GLOBAL['category_name'] ) )
				{
					do
					{
						$categoryName = array_shift( Ayoola_Application::$GLOBAL['category_name'] );
					}
					while( $categoryName && $categoryName === Ayoola_Application::$GLOBAL['article_type'] );
					$categoryInfo = $table->selectOne( null, array( 'category_name' => $categoryName ) );
					//	var_export( $categoryInfo );
					$pages[] = array( 'url' => $categoryInfo['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $categoryInfo['category_name'] . '/' ), 'title' => $categoryInfo['category_label'], 'description' => $categoryInfo['category_description'] );
				}
				return $pages;
			break;
			case 'post':
				//	POST mode
				$pages = array();
				
				//	Home
				$pages[] = self::getInfo( '/' );
				
				//	members
				if( self::getInfo( '/members' ) )
				{
					$pages[] = self::getInfo( '/members' );
				}
				
/* 				//	username
				if( Ayoola_Application::$GLOBAL['username'] )
				{
					try
					{
						if( $userInfo = Ayoola_Access::getAccessInformation( Ayoola_Application::$GLOBAL['username'] ) )
						{
							$pages[] = array( 'url' => ( '/' . $userInfo['username'] ), 'title' => $userInfo['display_name'], 'description' => $userInfo['profile_description'] );
						}
					}
					catch( Exception $e )
					{
						//;
					//	var_export( $e->getMessage() );
					//	var_export( $e->getTraceAsString() );
					}
				}
 */				
				//	Use profiles instead of usernames
				
				
				// Posts
				$pages[] = array( 'url' => Application_Article_Abstract::getPostUrl(), 'title' => 'Posts', 'description' => '' );
				
				// Article Type
				$table =  new Application_Category();
				if( $categoryInfo = $table->selectOne( null, array( 'category_name' => Ayoola_Application::$GLOBAL['article_type'] ) ) )
				{
					$pages[] = array( 'url' => $categoryInfo['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $categoryInfo['category_name'] . '/' ), 'title' => $categoryInfo['category_label'], 'description' => $categoryInfo['category_description'] );
			//			var_export( Ayoola_Application::$GLOBAL );
				}
				
				//	Category
				if( ! empty( Ayoola_Application::$GLOBAL['category_name'] ) )
				{
					$i = 0;
					do
					{
						$categoryName = is_array( Ayoola_Application::$GLOBAL['category_name'] ) ? array_shift( Ayoola_Application::$GLOBAL['category_name'] ) : Ayoola_Application::$GLOBAL['category_name'];
						$i++;
						if( $i == 5 ){ break; }
					}
					while( ( $categoryName == Ayoola_Application::$GLOBAL['article_type'] || ! $categoryName ) && Ayoola_Application::$GLOBAL['category_name'] );
					if( $categoryInfo = $table->selectOne( null, array( 'category_name' => $categoryName ) ) )
					{
					//	var_export( $categoryName );
						//	var_export( $categoryInfo );
						$pages[] = array( 'url' => $categoryInfo['category_url'] ? : ( '' . Application_Article_Abstract::getPostUrl() . '/category/' . $categoryInfo['category_name'] . '/' ), 'title' => $categoryInfo['category_label'], 'description' => $categoryInfo['category_description'] );
					}
				}
				
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
				if( Ayoola_Application::getRuntimeSettings( 'real_url' ) == '/404' )
				{
					$pages[] = self::getInfo( '/404' );
				}
				$pages = self::sortMultiDimensionalArray( $pages, 'url' );
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
		$domain = self::getDefaultDomain();
		$url = 'http://' . $domain . $uri;
	//	var_export( $url );
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
		return Ayoola_Application::getDomainName();
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
		$url .= 'previous_url=' . urlencode( $currentUrl );
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
