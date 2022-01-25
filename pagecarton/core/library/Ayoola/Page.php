<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * Link for the favicon for the page
     * 
     * @var string 
     */
	public static $favicon;
	
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
    public static function getAll( array $data = null )
    {		
		$pages = Ayoola_Page_Page::getInstance();
		$pages = $pages->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$pages = $filter->filter( $pages );

		$pages += Ayoola_Page_Layout_Pages::getPages( @$data['layout_name'] ? : Ayoola_Page_Editor_Layout::getDefaultLayout(), 'list-url' ) ? : array();
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
			if( $cssFile = Ayoola_Page_Editor_Layout::getDefaultLayout() )
			{
				$table = Ayoola_Page_PageLayout::getInstance();
				if( $cssFile = $table->selectOne( null, array( 'layout_name' => $cssFile ) ) )
				{

					$cssFile = @$cssFile['document_url'];
				}
			}
		}
		return $cssFile ? : '/css/pagecarton.css';

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

        $id = md5( Ayoola_Application::getApplicationNameSpace() . $url );

        $storage = self::getObjectStorage( array( 'id' => $id,  ) );			
        $tableName = 'Ayoola_Page_Page';		

        $table = $tableName::getInstance();		

        if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'id' => $id ) ) )
        {
          $info['cache_info'] = serialize( $storage );
          $storage->store( $info ); 
          break; 
        }
        $table = $tableName::getInstance( $tableName::SCOPE_PROTECTED );
        $table->getDatabase()->setAccessibility( $tableName::SCOPE_PROTECTED );


        if( $info = $table->selectOne( null, array( 'url' => $url ), array( 'work-arround-1-333' => true ) ) )
        { 
          //	remove info we dont want
          if( @in_array( 'private', $info['page_options'] ) )
          {
            //	We are not allowed to access parent page.

            $info = array();
            return false; 

          }

          @$info['page_options'] = array_combine( $info['page_options'], $info['page_options'] );

          //  had to remove this beecause it removes set layout for page in plugins
          //unset( $info['title'], $info['description'], $info['layout_name'], $info['page_options']['template'], $info['cover_photo'] );

          $info['cache_info'] = serialize( $storage );
          $storage->store( $info );
          break; 
        }

        //	get info for theme pages
        $themeName = Ayoola_Page_Editor_Layout::getDefaultLayout();

        if( $themeName && Ayoola_Page_Layout_Pages::isValidThemePage( $url, $themeName ) )
        { 
          //	just what we need
          @$info = array( 'url' => $url );
          $file = 'documents/layout/' . $themeName . '/pagesettings';
          $globalFile = Ayoola_Loader::checkFile( $file );
          if( is_file( $globalFile ) )
          if( $settings = json_decode( file_get_contents( $globalFile ), true ) )
          if( ! empty( $settings[$url] ) && is_array( $settings[$url] ) )
          {
              $info += $settings[$url];
          }
          
          $info['cache_info'] = serialize( $storage );
          $storage->store( $info );
          if( empty( $info['auth_level'] ) )
          {
            $info['auth_level'] = array( 0 );
          }

          break; 
        }
        return false;
      }
      while( false );
      //var_export( $info );
      //exit();
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

      if( ! self::$_currentPageInfo ){ self::setCurrentPageInfo(); }

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

		if( empty( self::$_currentPageInfo ) )
		{
			self::$_currentPageInfo = self::getInfo( $url );
        }
        elseif( ! empty( $info ) )
        {
            $info = self::__( $info );
        }

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

		switch( Ayoola_Application::$mode )
		{
			case 'profile_url':
					//	POST mode
					$pages = array();
					
					//	Home
					$pages[] = self::getInfo( '/' );
					
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
										
					//	Page
					$pageInfo = self::getInfo( Ayoola_Application::getRuntimeSettings( 'real_url' ) );
					$pageInfo['url'] = $page;
					$pages[] = $pageInfo;
				
				return $pages;
				 
			break;
			case 'module':
				// module mode
				$pages = self::getPageCrumbs( $page );

				
				//	final word 

                $title = explode( "/", strtolower( Ayoola_Application::getRuntimeSettings( 'url' ) ) );
                $title = array_pop( $title );
                $title = ucwords( $title );
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

				}
				$pages[] = array( 'url' => Ayoola_Application::getRuntimeSettings( 'url' ), 'title' => $title, 'description' => null );

				//	Category

				return $pages;
			break;
			case 'post':
				//	POST mode
				$pages = array();
				
				//	Home
				$pages[] = self::getInfo( '/' );
                
                
				//	Article gan gan
				$pages[] = array( 'url' => Ayoola_Application::$GLOBAL['post']['article_url'], 'title' => Ayoola_Application::$GLOBAL['post']['article_title'] );

				return $pages;  
			break;
			default:

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

                        if( @$_REQUEST['url'] )
                        {
                            $page = $_REQUEST['url'];
                            $editorMode = true;
                            break;
                        }
                    break;
                    default:

                    break;
                }

				$pages = self::getPageCrumbs( $page );
				if( Ayoola_Application::getRuntimeSettings( 'real_url' ) == '/404' )
				{
					$pages[] = self::getInfo( '/404' );
				}
				//	Don't shuffle again so that we can priotize local copy.'

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
		$table = Ayoola_Page_Page::getInstance( __METHOD__ );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );
		$pages = $table->select( null, array( 'url' => $sections ), array( 'work-arround-ddd111' => true ) );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );

		$pages2 = $table->select( null, array( 'url' => $sections ), array( 'work-arround-333' => true ) );

		$pages = self::sortMultiDimensionalArray( $pages, 'url' );
		$pages2 = self::sortMultiDimensionalArray( $pages2, 'url' );

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

		$curentPage = null;
		$sections = array();
		do
		{
			$curentPage =  '' . rtrim( $curentPage, '/' ) . '/';
			$curentPage = $curentPage . '' . array_shift( $page ) . '';

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

		$uri = $uri ? : Ayoola_Application::getRequestedUri();

		//	Look in the links table for SEO friendly and short URLS

	//	if( $link = $table->selectOne( null, array( 'link_url' => $uri ) ) )
		{

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

		return $uri;
    }
	
    /**
     * Returns the canonical url
     *
     * @return string
     */
    public static function getCanonicalUrl( $uri = null )
    {

		$uri = self::getCanonicalUri( $uri, false );

		$url = self::getHomePageUrl() . $uri;  

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
		$url = self::getRootUrl() . Ayoola_Application::getRealPathPrefix();  
		return $url; 
    }
	
    /**
     * 
     *
     * @return string
     */
    public static function getProtocol()
    {
        if( ! $protocol = Ayoola_Application::getDomainSettings( 'protocol' ) )
        {
            $protocol = 'http';
            if( ( $_SERVER['SERVER_PORT'] == 443 && ! empty( $_SERVER['HTTPS'] ) ) || $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' )
            {
                $protocol = 'https';
            }    
        }
        return $protocol;
    }
	
    /**
     * Returns the home page url
     *
     * @return string
     */
    public static function getRootUrl()
    {

		$domain = self::getDefaultDomain();
		$url = self::getProtocol() . '://' . $domain . self::getPortNumber() . @$_SERVER['CONTEXT_PREFIX'];   
		return $url;
    }
	
    /**
     * Returns the default domain name
     *
     * @return string
     */
    public static function getDefaultDomain()
    {

		$domainName = Ayoola_Application::getDomainSettings( 'domain_name' );
		if( ! @Ayoola_Application::getDomainSettings( 'dynamic_domain' ) )
		{
			$domainName = ( Ayoola_Application::getDomainSettings( 'enforced_destination' ) ? : Ayoola_Application::getDomainSettings( 'domain_name' ) ) ? : Ayoola_Application::getDomainName();
		}

		return $domainName;

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
     * Returns the link for thumbnail for the page
     *
     * @return string
     */
    public static function getFavicon() 
    {
		if( is_null( self::$favicon ) ){ self::$favicon = '/favicon.ico'; }
		return Ayoola_Doc::uriToDedicatedUrl( self::$favicon );  
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
        $url = urldecode( $url );
		return $url;
    }
	
    /**
     * Sets the Previous url usually saved as a url parameter
     *
     * @param string
     * @return string
     */
    public static function setPreviousUrl( $url = null )
    {
		if( $url )
		{
			$url .= stripos( $url, '?' ) ? '&' : '?';
		}
		
		if( @$_GET['previous_url'] )
		{
			$url .= 'previous_url=' . urlencode( $_GET['previous_url'] );

			return $url;
		}

		$currentUrl = self::getCurrentUrl();

		$host = $_SERVER['HTTP_HOST'];
		if( ! stripos( $host, ':' ) )
		{
			$host = $host .  self::getPortNumber();
		}

		//	https://www.example.com/account/signin?previous_url=https://www.example.com/account 
		//	is being blocked by some servers
		//	probably because of xss
		//	$_POST, $_GET was being cleared

		$url .= 'previous_url=' . urlencode( '//' . $host . '' . $currentUrl );
	return $url;
    }	
	
    /**
     * Returns the path to a particular page
     *
     * @param string The Uri to the Page
     * @return array The paths to the Page files
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
