<?php
/**
 * PageCarton Content Management System 
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Exception 
 */
 
require_once 'Application/Article/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Article_Abstract extends Application_Blog_Abstract
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
	protected static $_accessLevel = 99; 
	
    /**
     * Url used in "Playing" article posts
     *
     * @var string
     */
	protected static $_postUrl;
	
    /**
     * Options to force on writers or editors
     *
     * @var array
     */
	protected static $_forcedValues;
	
    /**
     * Options to force on writers or editors
     *
     * @var array
     */
	protected static $_optionalValues = array(  );
	
    /**
     * 
     *
     * @var array
     */
	protected static $_otherFormFields = array(  );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'article_name' );
	
    /**
     * Error messages to show in List of Posts
     * 
     * @var array
     */
	protected $_badnews = array();
	
    /**
     * Module files directory namespace
     * 
     * @var string
     */
	protected static $_moduleDir = 'articles';	
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article';	
	
    /**
     * returns the article folder
     * 
     */
	public static function getFolder()  
    {
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . static::$_moduleDir );
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . static::$_moduleDir; 
	}
	
    /**
     * returns the article folder
     * 
     */
	public static function getSecondaryFolder()  
    {
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . static::$_moduleDir );
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES . DS . '2' .  DS . static::$_moduleDir; 
	}
	
    /**
     * 
     * 
     */
	public static function sanitizeData( &$data )
    {

	}
	
    /**
     * Save the article
     * 
     */
	public static function saveArticle( $values )
    {
	//	$text = '<?php return ' . var_export( $values, true ) . ';';
		$values['file_size'] = intval( strlen( var_export( $values, true ) ) );
		$values['file_size'] += intval( filesize( Ayoola_Doc::uriToDedicatedUrl( @$data['download_url'] ) ) );
		
		
	//	$head = array_change_key_case(get_headers("http://example.com/file.ext", TRUE));
	//	$filesize = $head['content-length'];		
		$values['file_size'] += intval( filesize( @$values['download_path'] ) );
		if( $values['file_size'] > 15000000 )
		{
			$secondaryValues = array_intersect_key( array( 'document_url_base64' => $values['document_url_base64'], 'download_base64' => $values['download_base64'], ), $values );
			file_put_contents( self::getSecondaryFolder() . $values['article_url'], '<?php return ' . var_export( $secondaryValues, true ) . ';' );
		}
	//	unset( $_POST, $_REQUEST );
		return file_put_contents( self::getFolder() . $values['article_url'], '<?php return ' . var_export( $values, true ) . ';' );
	}
	 
    /**
     * Returns the url used in displaying posts
     * 
     * @param void
     */
	public static function getPostUrl() 
    {
		if( self::$_postUrl ){ return self::$_postUrl; }
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		self::$_postUrl = rtrim( @$articleSettings['post_url'] ? : '/article/', '/' );
		return self::$_postUrl;
	}
		
    /**
     * Overides the parent class
     * 
     */
	public function setDbData( array $data = null )
    {
		if( is_array( $data ) )
		{
			$this->_dbData = $data;
			return true;
		}
		
		@$categoryId = $_GET['category']; 
		if( $this->getParameter( 'ignore_category_query_string' ) )
		{
			// switch $_GET['category'] off for this instance 
			@$categoryId = null; 
		}
		@$categoryId = $this->getParameter( 'category' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_id' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_name' ) ? : $categoryId;
		if( $this->getParameter( 'post_with_same_category' ) && @Ayoola_Application::$GLOBAL['category_name'] )
		{
			$categoryId = @Ayoola_Application::$GLOBAL['category_name'];
		}
//		var_export( $this->getParameter( 'category_name' ) );
	//	self::v( $categoryId );
		$categoryName = null;
		$table = new Application_Category();
		if( $categoryId && is_numeric( $categoryId ) ) 
		{
			
			$category = $table->selectOne( null, array( 'category_id' => $categoryId ) );
			$this->_badnews[] = 'There are no recent posts in the "' . $category['category_label'] . '" category.';
		}
		elseif( $categoryId && is_string( $categoryId ) )
		{
			//	Get the numeric category ID from the  DB
			$category = $table->selectOne( null, array( 'category_name' => $categoryId ) );
		
		//	self::v( $categoryId );
			$this->_badnews[] = 'There are no recent posts in the "' . ( @$category['category_label'] ? : $categoryId ) . '" category.';
			
			if( ! $category )
			{
				$this->_dbData = array();
				return false;   
			//	throw new Application_Article_Exception( 'INVALID CATEGORY: ' . $categoryId );
			//	$this->setViewContent( '<p>Showing articles from ', true );
			}
			$categoryId = @$category['category_id'];
			$categoryName = @$category['category_name'] ? : 'workaround_avoid_error_in_search';
			$categoryName = '(' . $categoryName . ')';
			$category['category_description'] = $category['category_description'] ? : ' Latest Posts in the "' . $category['category_label'] . '" category on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() );
			
			//	Add the category to title and description?
			if( $this->getParameter( 'build_meta_data' ) )
			{
				$pageInfo = array(
					'description' => Ayoola_Page::getCurrentPageInfo( 'description' ) . $category['category_description'] ,
					'title' => trim( $category['category_label'] . ' ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
				//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
				Ayoola_Page::setCurrentPageInfo( $pageInfo );
			}
			//	Reset canonical url only if category is in the url
			if( ! empty( $_GET['category'] ) && $_GET['category'] == $category['category_name'] )
			{
				//	Reset canonical url
				Ayoola_Page::getCanonicalUri( self::getPostUrl() );
				Ayoola_Page::getCanonicalUri( self::getPostUrl() . '/category/' . $category['category_name'] . '/' );
			}
		}
		elseif( $categoryId && is_array( $categoryId ) )
		{
			//	
		//	var_export( $categoryId );
			$categoryName = count( $categoryId ) === 1 ? ( '(' . $categoryId[key( $categoryId )] . ')' ) : ( '(' . implode( ')|(', $categoryId ) . ')' );
			$categoryId = null;
		}
	//	self::v( $categoryId );
	//	self::v( $categoryName );
	//	var_export( $categoryId );
		$path = self::getFolder();
		if( $this->getParameter( 'show_post_by_me' ) )
		{
			$this->setParameter( array( 'username_to_show' => Ayoola_Application::getUserInfo( 'username' ) ) );
		}
		elseif( $this->getParameter( 'show_profile_posts' ) && @Ayoola_Application::$GLOBAL['username'] )
		{
			$this->setParameter( array( 'username_to_show' => Ayoola_Application::$GLOBAL['username'] ) );
		}
		elseif( $this->getParameter( 'search_mode' ) && @$_REQUEST['q'] )
		{
			switch( $this->getParameter( 'search_mode' ) )
			{
				case 'phrase':
					$command = "find $path -type f -print0 | xargs -0 egrep -l \"*" . $_REQUEST['q'] . "*\"";
			//		$pattern = implode('\|', $contents_list) ;
					exec( $command, $output );
					$path = implode( ' ', $output ); 
				break;
				case 'keyword':
					$keywords = array_map( 'trim', explode( ' ', $_REQUEST['q'] ) );
					$keywordPaths = null;
					while( $keywords )
					{
						$keyword = array_shift( $keywords );
						$command = "find $path -type f -print0 | xargs -0 egrep -l \"*" . $keyword . "*\"";
				//		$pattern = implode('\|', $contents_list) ;
						exec( $command, $output );
						$keywordPaths .= implode( ' ', $output ); 
					}
					$path = $keywordPaths ? : $path; 
				break;
			}
		//	var_export( $path ); 
		//	var_export( $command );
		}
		if( $this->getParameter( 'username_to_show' ) )
		{
	//		var_export( count( $files ) );
			//	Removing dependence on Ayoola_Api for showing posts
		//	$path = self::getFolder();
			$command = "find $path -type f -print0 | xargs -0 egrep -l \"'username' => '" . $this->getParameter( 'username_to_show' ) . "'\"";
	//		$pattern = implode('\|', $contents_list) ;
			exec( $command, $output );
			$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts xxx xxxx xxxx';
		//	var_export( $path );
		} 
		if( $this->getParameter( 'article_types' ) )
		{
	//		var_export( count( $files ) );
			//	//	Show this here to avoid looping in Article_ShowAll
		//	$path = self::getFolder();
			$command = "find $path -type f -print0 | xargs -0 egrep -l \"'article_type' => '" . $this->getParameter( 'article_types' ) . "'\"";
	//		$pattern = implode('\|', $contents_list) ;
			exec( $command, $output );
			$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts xxx xxxx xxxx';
		//	var_export( $path );
		
		} //	For profiles
		elseif( $this->getParameter( 'access_level' ) )
		{
	//		var_export( count( $files ) );
			//	//	Show this here to avoid looping in Article_ShowAll
		//	$path = self::getFolder();
			$command = "find $path -type f -print0 | xargs -0 egrep -l \"'access_level' => '" . $this->getParameter( 'access_level' ) . "'\"";
	//		$pattern = implode('\|', $contents_list) ;
			exec( $command, $output );
			$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts xxx xxxx xxxx';
		//	var_export( $path );
		}
		elseif( @$_REQUEST['type'] )
		{
			$typeInfo = new Ayoola_Access_AuthLevel;
			if( $typeInfo = $typeInfo->selectOne( null, array( 'auth_name' => $_REQUEST['type'] ) ) )
			{
				$command = "find $path -type f -print0 | xargs -0 egrep -l \"'access_level' => '" . $typeInfo['auth_level'] . "'\"";
				exec( $command, $output );
				$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts xxx xxxx xxxx';
			}
		//	var_export( $typeInfo );
		//	var_export( $path );
		}
	
		if( $categoryId || $categoryName )
		{
		//	$this->_dbWhereClause['category_id'] = $categoryId;
		//	$this->setViewContent( '<p>Showing articles from ', true );
		//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 ) 
			{
		//		var_export( count( $files ) );
				//	Removing dependence on Ayoola_Api for showing posts
				$categoryId = $categoryId ? : 'workaround_avoid_error_in_search';
				$categoryId = 'category id has been finally switched off'; 
				$command = "find $path -type f -print0 | xargs -0 egrep -lzo \" => (')?($categoryId)|$categoryName(')?(,)\"";
	//			$pattern = implode('\|', $contents_list) ;  
				exec( $command, $output );
		//		var_export( '<br />' );
		//		var_export( $this->_dbData );
		//		var_export( $command );
			//	self::v( $command );
			//	self::v( $output );
		//		var_export( '<br />' );
				$this->_dbData = $output;
			}
		}
		elseif( ! empty( $_GET['tag'] ) )
		{
			switch( $_GET['tag'] )
			{
				case 'mine';
					$pageInfo = array(
						'description' => 'Manage my posts on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
						'title' => trim( 'My Posts ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
					);
					//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
					Ayoola_Page::setCurrentPageInfo( $pageInfo );
					$this->_dbWhereClause['username'] = Ayoola_Application::getUserInfo( 'username' );
		//			if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
					{
				//		var_export( count( $files ) );
						//	Removing dependence on Ayoola_Api for showing posts
					//	$path = self::getFolder();
						$command = "find $path -type f -print0 | xargs -0 egrep -l \"'username' => '{$this->_dbWhereClause['username']}'\"";
				//		$pattern = implode('\|', $contents_list) ;
						exec( $command, $output );
					//	var_export( '<br />' );
				//		var_export( $this->_dbData );
				//		var_export( $command );
				//		var_export( $output );
				//		var_export( '<br />' );
						$this->_dbData = $output;
					}
					$this->_badnews[] = 'You have not created any post yet.';
				break;
				case 'trend';
					$pageInfo = array(
						'description' => 'View trending posts on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
						'title' => trim( 'Trending Posts ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
					);
					//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
					Ayoola_Page::setCurrentPageInfo( $pageInfo );
					$values = Application_HashTag_Abstract::getAll( 'articles' );
					$this->_dbData = array();
			//		var_export( $values );
					$this->_badnews[] = 'The trending posts have not been collated yet. Please check back later.';					
					foreach( $values as $each )
					{
						if( ! is_array( $each ) ){ continue; }
						$this->_dbData[] = key( $each );
						
					}
				//	if( ! empty( $values[$_GET['tag']] ) )
					{
			//			$this->_dbData = array_keys( $values[$_GET['tag']] );				
					}
					$this->_dbData = array_unique( $this->_dbData );
					return true;
				break;
				default;
					$pageInfo = array(
						'description' => 'View trending posts with hash tag #"' . $_GET['tag'] . '" on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
						'title' => trim( '#' . $_GET['tag'] . ' - Trending Posts ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
					);
										
					//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
					Ayoola_Page::setCurrentPageInfo( $pageInfo );
					$values = Application_HashTag_Abstract::getTrending( 'articles' );
					$this->_dbData = array();
					if( ! empty( $values[$_GET['tag']] ) )
					{
						$this->_dbData = array_keys( $values[$_GET['tag']] );				
						$this->_badnews[] = 'There are not recent posts with the hash tag #' . $_GET['tag'] ;					
					}
					return true; 
				break;
			
			}
			//	Reset canonical url
			//	Reset canonical url
			Ayoola_Page::getCanonicalUri( self::getPostUrl() );
			Ayoola_Page::getCanonicalUri( self::getPostUrl() . '/tag/' . $_GET['tag'] . '/' );
		//	var_export( $this->_dbData );
		}
		elseif( ! empty( $_GET['by'] ) )
		{
			$pageInfo = array(
				'description' => 'Recent posts by "' . $_GET['by'] . '" on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() ),
				'title' => trim( 'Posts by ' . $_GET['by'] . ' - Trending Posts ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
			);
			//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			$this->_dbWhereClause['username'] = $_GET['by'];			
			$this->_badnews[] = $this->_dbWhereClause['username'] . ' does not have any recent posts. ';
	//		if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
		//		var_export( count( $files ) );
				//	Removing dependence on Ayoola_Api for showing posts
			//	$path = self::getFolder();
				$command = "find $path -type f -print0 | xargs -0 egrep -l \"'username' => '{$this->_dbWhereClause['username']}'\"";
				
	//			$pattern = implode('\|', $contents_list) ;
				exec( $command, $output );
		//		var_export( '<br />' );
		//		var_export( $this->_dbData ); 
		//		var_export( $command );
		//		var_export( $output );
		//		var_export( '<br />' );
				$this->_dbData = $output;
			} 
			
			//	Reset canonical url
			//	Reset canonical url
			Ayoola_Page::getCanonicalUri( self::getPostUrl() );
			Ayoola_Page::getCanonicalUri( self::getPostUrl() . '/by/' . $_GET['by'] . '/' );
		}
		else
		{
		//	Removing dependence on Ayoola_Api for showing posts
			try
			{
			
				if( $path === self::getFolder() )
				{
					$sortFunction = create_function
					( 
						'$filePath', 
						'
						if( filesize( $filePath ) > 300000 )
						{
							$result = filectime( $filePath );
							
						}
						else
						{
							$values = @include $filePath;
							$result = @$values[\'article_creation_date\'] ? : ( @$values[\'article_modified_date\'] ? : @$values[\'profile_modified_date\'] );
						}
						return $result;
						'
					); 
				//	$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder(), array( 'key_function' => 'filectime' ) );
					$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder(), array( 'key_function' => $sortFunction ) );
				//	$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder() );
					krsort( $this->_dbData );
				//	self::v( $this->_dbData );
				//	self::v( self::getFolder() );
				//	self::v( Ayoola_Doc::getFilesRecursive( self::getFolder() ) );
			//		var_export( count( $files ) );
					//	Removing dependence on Ayoola_Api for showing posts
				//	$path = self::getFolder();
				}
				else
				{
					$command = "find $path -type f -print0 | xargs -0 egrep -l \"'article_url' => '\"";
			//		$pattern = implode('\|', $contents_list) ;
					exec( $command, $output );
					$this->_dbData = array_unique( $output );
				//	var_export( $output );
				//	var_export( $path );
				}
			//	var_export( $path );
					//	Posts created same time causing issues.
				//	$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder() );
				//		self::v( $this->_dbData );
			}
			catch( Exception $e )
			{ 
				//	Sometimes we have invalid dirs that causes an exception
				null;
			}
  		}
		//	Removing dependence on Ayoola_Api for showing posts
 	//	$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder(), array( 'key_function' => 'filectime' ) );
	//	self::v( $this->_dbData );
		
 		if( ! is_null( $this->_dbData ) )
		{ 
		//	$this->_dbData = array();
			return true; 
		}
		else
		{
			$this->_dbData = array();
			return false; 
		}
 	//	if( ! $response = Application_Article_Api_Select::send( $this->_dbWhereClause ) ){ return false; }
	//	var_export( $response );
	//	if( ! is_array( $response['data'] ) ){ throw new Application_Article_Exception( $response ); }
	//	$this->_dbData = $response['data'];
    } 
	
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
	//	self::v( $this->getParameter( 'update_meta_data' ) );
		// Comes from a file
		if( ! $data = $this->getParameter( 'data' ) )
		{
			$url = @$_GET['article_url'];
			$url = $url ? : $this->getParameter( 'article_url' );
			$url = $url ? : Ayoola_Application::getRequestedUri();
			//	self::v( $url ); 
			$filename = self::getFolder() . $url;
			$data = @include $filename;
		}
		//	var_export( $filename );
		if( ! $data  
			|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) )   
			|| ! self::hasPriviledge( $data['auth_level'] )
		)
		{
	//		return array();
		}
	//	if( get_class( $this ) === 'Application_Article_View' && ( ! $this->getParameter( 'markup_template_object_name' ) || $this->getParameter( 'update_meta_data' ) ) )
		if( get_class( $this ) === 'Application_Article_View' )
		{
			//	dont duplicate
			if( strpos( Ayoola_Page::getCurrentPageInfo( 'title' ), $data['article_title'] ) === false )
			{
				$pageInfo = array(
					'description' => @$data['article_description'],
					'keywords' => @$data['article_tags'],
					'title' => trim( $data['article_title'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
				Ayoola_Page::setCurrentPageInfo( $pageInfo );
			}
		}
		$this->_identifierData = $data;  
    } 
	
    /**
     * Article info
     * 
     * @var array
     */
	protected static $_articleInfo;
	
    /**
     * Get the data of the article. Do this to save memory and load time
     * 
     */
	public static function getArticleInfo( $articleUrl = null )
    {
		if( self::$_articleInfo ){ return self::$_articleInfo; }
	//	$class = get_cla
		$class = new Application_Article_View();
		self::$_articleInfo = $class->getIdentifierData();
		if( ! self::$_articleInfo )
		{ 
			header( 'Location: /404/' ); 
			exit();
		}
		return self::$_articleInfo;
    } 
	
    /**
     * Returns Quick link for article
     * 
     */
	public function getQuickLink( array $data = null )
    {
		
		//	You can edit this article if you are a super user	
		$editLink = null;
	//	Check settings
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		if( self::hasPriviledge( $articleSettings['allowed_writers'] ) )
		{
		//	@$editLink .= ' <a class="goodnews" title="Create a new post" href="' . self::getPostUrl() . '/post/creator/"> + </a> ';
		}
		if( self::hasPriviledge() )
		{
		//	$editLink .= ' <a class="badnews" rel="spotlight;width=300px;height=300px;" title="Advanced Settings" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/"> settings </a> ';
		}
		if( ! $data )
		{ 
			if( ! $data = self::getIdentifierData() )
			{ 
				if( $editLink )
				{ 
					$editLink = ' <span style="display:inline-block;"> ' . $editLink . ' </span> ';
				}	
				return $editLink;
			}
		}
		if( self::isOwner( @$data['user_id'] ) || self::hasPriviledge( $articleSettings['allowed_editors'] ) )
		{
			$editLink .= ' <span class="goodnews"><a  title="Edit this post" href="' . self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'] . '"> edit </a> <a class="badnews" rel="spotlight;width=300px;height=300px;" title="Delete this post" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '"> X </a></span>';
		//	$editLink .= ' <a class="badnews" rel="spotlight;width=300px;height=300px;" title="Delete this post" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '"> X </a> ';
		}
		if( $editLink )
		{ 
			$editLink = ' <span style="display:inline-block;"> ' . $editLink . ' </span> ';
		}	
		return $editLink;
    } 
	
    /**
     * Returns an HTML to display categories
     * 
     * param mixed Category Id
     */
	public static function getCategories( $categoryIds, array $displayOptions = null )
    {
		$html = null;
	//	$html .= $displayOptions['template'] ? null : ' <ul style="list-style:none;display:inline-block;"><strong>Categories:</strong> ';
		$class = new Application_Category;
		$options = $class->select( null, array( 'category_name' => $categoryIds ) ) ? : array();
		
		//	compatibility
		$options += $class->select( null, array( 'category_id' => $categoryIds ) ) ? : array();
//		var_export( $displayOptions ); 
		$i = 0;
		foreach( $options as $each )
		{
			if( $displayOptions['template'] )
			{
				$html .= str_ireplace( array( '{{{category_url}}}', '{{{category_label}}}', '{{{category_name}}}' ), array( self::getPostUrl() . '/category/' . $each['category_name'], $each['category_label'], $each['category_name'] ), $displayOptions['template'] );
				$html .= count( $options ) === ++$i ? null : $displayOptions['glue']; 
			}
			else
			{
				$each['category_label'] = @$_GET['category'] === $each['category_name'] ? "<strong> {$each['category_label']} </strong>" : "<span> {$each['category_label']} </span>";
				$html .= '<a style="" href="' . self::getPostUrl() . '/category/' . $each['category_name'] . '/"> ' . $each['category_label'] . ' </a>';
				$html .= count( $options ) === ++$i ? null : $displayOptions['glue']; 
			}
		}
	//	var_export( $data['category_id'] );
	//	$html .= $displayOptions['template'] ? null :  ' </ul> ';
		return $html;
    } 
	
    /**
     * Returns an HTML to display #hashtags
     * 
     */
	public static function getHashTags( $values )
    {
		$html = null;
		$html .= ' <ul style="list-style:none;display:inline-block;margin-left:0;"> ';
	//	$html .= ' <ul style="list-style:none;display:inline-block;"><strong>Hash Tags:</strong>';
		foreach( $values as $each )
		{
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$value = $filter->filter( strtolower( $each ) );
			if( ! $value ){ continue; }
			$html .= ' <li style="display:inline-block;">';
			$url = '' . self::getPostUrl() . '/tag/' . $value . '/';
			$content = ' <a href="' . $url . '"> #' . $each . ' </a> ';
			$html .= @$_GET['tag'] === $value ? "<strong> {$content} </strong>" : "<span> {$content} </span>";
			
			$html .= ' </li> ';
		}
		$html .= ' </ul> ';
	//	var_export( count( $value ) );
		return $html;
    } 
	
    /**
     * Returns an HTML to display footer for messages
     * 
     */
	public static function filterTime( $data )
    {
		$filter = new Ayoola_Filter_Time();
		$html = null;
	//	var_export( $data['article_modified_date'] );
	//	var_export( $data['article_creation_date'] );
		if( @$data['article_modified_date'] )
		{
		//	$html .= '<strong> Modified: </strong> ';
			$html .= $filter->filter( $data['article_modified_date'] );
		}
		else
		{
		//	$html .= '<strong> Posted: </strong> ';
			$html .= $filter->filter( @$data['article_creation_date'] ? : ( time() - 3 ) ); 
		}
		return $html;
	
	}
    /**
     * Returns an HTML to display footer for messages
     * 
     */
	public static function getFooter( $data )
    {
		$html = null;
		$html .= '<ul style="margin:0;padding:0;list-style:none;display:inline-block;">';
	//	foreach( $values as $each )
		{
			$html .= '<li style="display:inline-block;margin:0.5em;" title="Articles not published are only visible to the writer.">' . self::filterTime( $data ) . '</li>';
			$html .= '<strong>Published:</strong><li style="display:inline-block;margin:0.5em;">' . ( $data['publish'] ? 'Yes' : 'No' ) . '</li>';
		}
		$html .= '</ul>';
	//	var_export( count( $value ) );
		return $html;
    } 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
		//	What form can we use?
		@$formToUse = $values['form_name'] ? : $_REQUEST['form_name'];
		@$formToUse = $values['form_name'] ? : $this->fakeValues['form_name'];
	//	var_export( $formToUse );    
	//	var_export( $this->fakeValues );   
 		$form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true, 'id' => $this->getObjectName() . @$values['article_url'] ) );   		
		$form->setParameter( array( 'no_fieldset' => true ) );
		$form->oneFieldSetAtATime = $this->hashFormElementName;
		$form->submitValue = 'Continue...' ;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = $this->hashFormElementName;
		if( $formToUse && ! @$_REQUEST['default_form'] )
		{
			$class = new Ayoola_Form_View( array( 'no_init' => true ) );
			$class->setParameter( array( 'default_values' => $values, 'form_name' => $formToUse ) );
		//	$class->init();
		//	$class->view();
			$class->createForm( 'Create Post', ''  );
		//	$this->setForm( $class->getForm() );
			$form2 = $class->getForm();
			$fieldsets = $form2->getFieldsets();
		//	$form->setAttributes( array( 'name' => $this->getObjectName() ) );
			$form->requiredElements = is_array( $form->requiredElements ) ? $form->requiredElements : array() + is_array( $form2->requiredElements ) ? $form2->requiredElements : array();
			$form->setParameter( $form2->getParameter() );
			foreach( $fieldsets as $key => $each ) 
			{
			//	var_export( $key )
				
				$each->hashElementName = $this->hashFormElementName;
				$each->addElement( array( 'name' => 'form_name', 'type' => 'Hidden', 'value' => @$formToUse ) );
				$form->addFieldset( $each );
			}
			$this->setForm( $form );
			return;
		}
		else
		{
			//	let the form_name be saved if available
			$fieldset->addElement( array( 'name' => 'form_name', 'type' => 'Hidden', 'value' => @$formToUse ) );
		}
		
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
	//	if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
		{ 
	//		return false; 
		}
	//	$fieldset->placeholderInPlaceOfLabel = true;
		//	Let's know the kind of post that we are working on.
	//	var_export( $this->getParameter( 'article_type' ) ); 
		@$values['article_type'] = $this->getParameter( 'article_type' ) ? : $values['article_type'];   
		if( ! @$values['article_type'] || ! empty( $_REQUEST['article_type'] ) )
		{ 
	//	var_export( $_REQUEST[Ayoola_Form::hashElementName( 'article_type')] ); 
	//	var_export( $_REQUEST['article_type'] ); 
		//	@$values['article_type'] =  ? : $values['article_type']; 
			@$values['article_type'] = $_REQUEST['article_type'] ? : $values['article_type']; 
			@$values['article_type'] = $_REQUEST[Ayoola_Form::hashElementName( 'article_type')] ? : $values['article_type']; 
		}
		$values['article_type'] = $values['article_type'] ? : $this->getGlobalValue( 'article_type' ); 
		$values['article_type'] = $values['article_type'] ? : 'post'; 
		
		//	Set Article Type
		$fieldset->addElement( array( 'name' => 'article_type', 'type' => 'Hidden', 'value' => @$values['article_type'] ? : @$_REQUEST['article_type'] ) );
		
		
	//	if( is_null( $values ) )
		{
/* 			//	Category allows one to enter a category that will be discretely entered.
			$values['category'] = $this->getParameter( 'category' ) ? : @$_REQUEST['category'];
			$fieldset->addElement( array( 'name' => 'category', 'type' => 'Hidden', 'value' => $values['category'] ) );
 */		}
		//	Title
		$fieldset->addElement( array( 'name' => 'article_title', 'label' => ucwords( $values['article_type'] ) . ' Title', 'placeholder' => 'Enter a title for the ' . ucwords( $values['article_type'] ) . ' here...', 'type' => 'InputText', 'value' => @$values['article_title'] ) );
	//	$fieldset->addElement( array( 'name' => 'cs', 'type' => 'Html' ), array( 'html' => '<div style="display:block;min-width:100%;"></div><br />' ) );
	
		//	Description
		$fieldset->addElement( array( 'name' => 'article_description', 'label' => '' . ucwords( $values['article_type'] ) . ' Description', 'placeholder' => 'Describe this ' . ucwords( $values['article_type'] ) . ' in a few words...', 'type' => 'TextArea', 'value' => @$values['article_description'] ) );

		$fieldset->addRequirement( 'article_title', array( 'WordCount' => array( 6,200 ) ) );
		$fieldset->addRequirement( 'article_description', array( 'WordCount' => array( 0, 500 ) ) );
	
		//	Cover photo
	//	$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldName64 = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url_base64' ) : 'document_url_base64' );
	//	var_export( $link );
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => '', 'placeholder' => 'Cover Photo for this ' . ucwords( $values['article_type'] ) . '', 'type' => 'Hidden', 'value' => @$values['document_url'] ) );
	//	if( @$values['document_url_base64'] )
		{ 
			
		//	$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
	//		$size = ( @$articleSettings['cover_photo_width'] ? : '900' ) . 'x' . ( @$articleSettings['cover_photo_height'] ? : '300' );
	//		$imgHtml = '<img title="Cover Photo for this ' . ucwords( $values['article_type'] ) . '" alt="" style="" name="' . $fieldName64 . '_preview_zone_image' . '" src="' . ( ( @$values['document_url_base64'] ) ? ( '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$values['article_url'] ) : ( 'http://placehold.it/' . $size . '&text=' . ( @$element['label'] ? : ( 'Photo (' . $size . ')' ) ) . '' ) ) . '"  class="" onClick=""  >';
	//		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html', 'value' => '' ), array( 'html' => $imgHtml, 'fields' => '' ) );
		}
		$fieldset->addElement( array( 'name' => 'document_url_base64', 'label' => 'Cover Photo', 'data-allow_base64' => true, 'type' => 'Document', 'value' => @$values['document_url_base64'] ) );
	//	if( self::hasPriviledge() )
		{
	//		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['document_url'] ? : $this->getGlobalValue( 'document_url' ) ), 'field_name' => $fieldName, 'width' => @$articleSettings['cover_photo_width'] ? : '900', 'height' => @$articleSettings['cover_photo_height'] ? : '300', 'crop' => true, 'field_name_value' => 'url', 'preview_text' => 'Cover Photo', 'call_to_action' => 'Change cover photo' ) ) ) ); 
		}
	//	$fieldset->addRequirement( 'document_url', array( 'InArray' => array_keys( $option )  ) );
	
		//	options
		$options =  array( 
							'article' => 'Write an article about this', 
							'publish' => 'Publish (Allow the public to see)', 
							'requirement' => 'Request viewers of this post to provide some information about them', 
							'keywords' => 'Enter keywords for this post', 
						);

		if( ! @$values['article_options'] )
		{
			$values['article_options'] = array();
			if( ! isset( $values['publish'] ) || @$values['publish'] )
			{
				$values['article_options'][] = 'publish';
			}
			if( @$values['article_content'] )
			{
				$values['article_options'][] = 'article';
			}
			if( @$values['article_tags'] )
			{
				$values['article_options'][] = 'keywords';
			}
			if( @$values['article_requirements'] )
			{
				$values['article_options'][] = 'requirement';
			}
			
		}
		$fieldset->addElement( array( 'name' => 'article_options', 'label' => '' . ucwords( $values['article_type'] ) . ' Options', 'type' => 'Checkbox', 'value' => $values['article_options'] ), $options );
	
		//	Categories
		$table = new Application_Category();
		
		//	Customized category selection for each post type.
		$parentCategoryName = $values['article_type'] . '-post-category';
		$categoryInfo = $table->selectOne( null, array( 'category_name' => $parentCategoryName ) );
		if( $categoryInfo = $table->select( null, array( 'category_name' => @$categoryInfo['child_category_name'] ) ) )
		{
		//	var_export( $categoryInfo );
		}
		elseif( ! $categoryInfo = $table->select( null, array( 'parent_category_name' => $parentCategoryName ) ) )
	//	if( ! $categoryInfo = $table->select( null ) )
		{
	//		self::v( $parentCategoryName ); 
	//		self::v( $categoryInfo );
			//	fallback
			$categoryInfo = new Application_Article_Category;
			$categoryInfo = $categoryInfo->getPublicDbData();
		}
	//		self::v( $parentCategoryName );
	//		self::v( $categoryInfo );
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$categoryInfo = $filter->filter( $categoryInfo );
		@$values['category_id'] = $values['category_id'] ? : array();
		@$values['category_name'] = $values['category_name'] ? : array();
		$category = $this->getParameter( 'category' ) ? : @$_REQUEST['category']; 
	//	var_export( $category );
		switch( gettype( $category ) )
		{
			case 'string':
			
				//	Automagically convert labels to names 
				$filter = new Ayoola_Filter_Name();
				$filter->replace = '-';
				$access = new Ayoola_Access();
				$category = trim( $filter->filter( strtolower( $category ) ) , '-' );
				$values['category_name'][] = $category;
			break;
			case 'array':
				$values['category_name'] += $category;
			break;
		}
	//	$fieldset->addElement( array( 'name' => 'category_id', 'label' => 'Select appropriate categories to list ' . ucwords( $values['article_type'] ) . '. <a rel="spotlight;" title="Advanced Settings" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/"> Settings </a>', 'type' => 'Checkbox', 'label_style' => 'display: none;', 'style' => 'display: none;', 'value' => @$values['category_id']  ), $values['category_id'] );
		$addCategoryLink = self::hasPriviledge() ? ( '<a rel="spotlight;changeElementId=' . get_class( $this ) . '" title="Add new Category" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_Editor/category_name/' . $parentCategoryName . '/?auto_create_category=1">Add new category </a>' ) : null; 
		$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Select appropriate categories to list ' . ucwords( $values['article_type'] ) . '. ' . $addCategoryLink, 'type' => 'SelectMultiple', 'value' => @$values['category_name']  ), $categoryInfo );
		//	Article 
	
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 
		
		//	Next Level
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = $this->hashFormElementName;
	//	var_export( $values['article_type'] );
		switch( $values['article_type'] )
		{
			case 'examination':
			case 'test':
			case 'quiz':
				//	time
				$fieldset->addElement( array( 'name' => 'quiz_time', 'label' => 'Maximum Time (in secs)', 'type' => 'InputText', 'value' => @$values['quiz_time'] ) );
				$fieldset->addElement( array( 'name' => 'quiz_max_no_of_question', 'label' => 'Maximum number of questions', 'type' => 'InputText', 'value' => @$values['quiz_max_no_of_question'] ) );
				$fieldset->addFilter( 'quiz_time', array( 'Int' => null ) );
				$fieldset->addFilter( 'quiz_max_no_of_question', array( 'Int' => null ) );
				$fieldset->addElement( array( 'name' => 'edit_questions', 'label' => 'Do you wish to edit the questions?', 'type' => 'Select', 'value' => @$values['edit_questions'] ), array( 'No', 'Yes' ) );
		//		$fieldset->addElement( array( 'name' => 'edit_groups', 'label' => 'Do you wish to edit the questions?', 'type' => 'Select', 'value' => @$values['edit_groups'] ), array( 'No', 'Yes' ) );
				$form->addFieldset( $fieldset );
				
				//	New fieldset
				$fieldset = new Ayoola_Form_Element;
				$fieldset->hashElementName = $this->hashFormElementName;
				
				
				if( ! $values || $this->getGlobalValue( 'edit_questions' ) )
				{
				
					$i = 0;
					//	Build a separate demo form for the previous group
					$questionForm = new Ayoola_Form( array( 'name' => 'questions...' )  );
					$questionForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
			//		$form->oneFieldSetAtATime = false;
					$questionForm->wrapForm = false;
				//	$previousFormMarkup .= null;
					$table = new Application_Category;
					$categoryInfoForQuiz = $table->select( null, array( 'category_label' => $this->getGlobalValue( 'article_title' ), ) );
					require_once 'Ayoola/Filter/SelectListArray.php';
					$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
					$categoryInfoForQuiz = $filter->filter( $categoryInfoForQuiz );
					do
					{
						
						//	Put the questions in a separate fieldset
						$questionFieldset = new Ayoola_Form_Element; 
						$questionFieldset->allowDuplication = true;
						$questionFieldset->duplicationData = array( 'add' => 'Add New Question', 'remove' => 'X', );
						$questionFieldset->container = 'span';
						$questionFieldset->addElement( array( 'name' => 'quiz_question', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Question', 'placeholder' => 'Enter question here...', 'title' => 'Double-Click here to launch the advanced editor', 'type' => 'TextArea', 'value' => @$values['quiz_question'][$i] ) );
										
						//	Option 1
						$questionFieldset->addElement( array( 'name' => 'quiz_option1', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'First Option', 'placeholder' => 'Enter option 1', 'type' => 'TextArea', 'value' => @$values['quiz_option1'][$i] ) );
						
						//	Option 2
						$questionFieldset->addElement( array( 'name' => 'quiz_option2', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Second Option', 'placeholder' => 'Enter option 2', 'type' => 'TextArea', 'value' => @$values['quiz_option2'][$i] ) );
						
						//	Option 3
						$questionFieldset->addElement( array( 'name' => 'quiz_option3', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Third Option', 'placeholder' => 'Enter option 3', 'type' => 'TextArea', 'value' => @$values['quiz_option3'][$i] ) );
						
						//	Option 4
						$questionFieldset->addElement( array( 'name' => 'quiz_option4', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Fourth Option', 'placeholder' => 'Enter option 4', 'type' => 'TextArea', 'value' => @$values['quiz_option4'][$i] ) );
						
						//	Solution
						$questionFieldset->addElement( array( 'name' => 'quiz_answer_notes', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Answer Notes and Workings', 'placeholder' => 'Enter the information that will be displayed to user as the answer workings...', 'type' => 'TextArea', 'value' => @$values['quiz_answer_notes'][$i] ) );
						
						//	Correct Answer
						$questionFieldset->addElement( array( 'name' => 'quiz_correct_option', 'multiple' => 'multiple', 'label' => 'Correct Option', 'placeholder' => '', 'type' => 'Select', 'value' => @$values['quiz_correct_option'][$i] ), array_combine( range( 1, 4 ), range( 1, 4 ) ) );
						
						
						if( $categoryInfoForQuiz )
						{
							//	self::v( $this->getGlobalValue( 'group_question' ) );  
					//		$questionFieldset->addElement( array( 'name' => 'question_group_category', 'multiple' => 'multiple', 'label' => 'Question Category <a rel="spotlight;" title="Advanced Settings" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Category_List/"> Manage </a>', 'placeholder' => '', 'type' => 'Select', 'value' => @$values['question_group_category'][$i] ), $categoryInfoForQuiz ); 
						}
						$questionFieldset->addLegend( 'Questions, Options and Answers ' );						
						$questionForm->addFieldset( $questionFieldset );
						$i++;
					//	self::v( $i );  
					}
					while( ! empty( $values['quiz_question'][$i] ) );
					
					
					//	Put the questions in a separate fieldset
					$questionFieldset = new Ayoola_Form_Element; 
					$questionFieldset->allowDuplication = false;
				//	$questionFieldset->placeholderInPlaceOfLabel = true;
					$questionFieldset->container = 'span';
					
			//		if( @$values['quiz_question'] )
					{
						//	add previous questions if available
						$questionFieldset->addElement( array( 'name' => 'previous_forms', 'type' => 'Html', 'value' => '' ), array( 'html' => $questionForm->view(), 'fields' => 'quiz_question,quiz_option1,quiz_option2,quiz_option3,quiz_option4,quiz_correct_option,quiz_answer_notes' ) );
						
					}
				//	self::v( $values );  
				//	self::v( $questionForm->view() );  
					//	Switch off duplication before adding to the main form
					
					//	Add only the last one into the main form
					$form->addFieldset( $questionFieldset );
				}
			break;
			case 'product':
			case 'service':
			case 'subscription':
				$fieldset->addElement( array( 'name' => 'item_old_price', 'type' => $values['article_type'] == 'subscription' ? 'InputText' : 'Hidden', 'value' => @$values['item_old_price'] ) );
				$fieldset->addElement( array( 'name' => 'item_price', 'type' => $values['article_type'] == 'subscription' ? 'InputText' : 'Hidden', 'value' => @$values['item_price'] ) );
				$fieldset->addElement( array( 'name' => 'no_of_items_in_stock', 'type' => $values['article_type'] == 'subscription' ? 'InputText' : 'Hidden', 'value' => @$values['no_of_items_in_stock'] ) );
				$fieldset->addElement( array( 'name' => 'call_to_action', 'placeholder' => 'e.g. Add to Cart', 'type' => 'InputText', 'value' => @$values['call_to_action'] ) );
			//	$fieldset->addElement( array( 'name' => 'subscription_options', 'type' => 'Checkbox', 'value' => @$values['subscription_options'] ), array( 'selections' => 'This product or service has a options to select from e.g. color',  ) );
		//		var_export( $values['subscription_selections'] );
			//	if( ( $this->getGlobalValue( 'subscription_options' ) && in_array( 'selections', $this->getGlobalValue( 'subscription_options' ) ) ) ) 
				{
				//	$fieldset->addElement( array( 'name' => 'subscription_selections', 'placeholder' => 'e.g. blue', 'type' => 'MultipleInputText', 'value' => @$values['subscription_selections'] ), @$values['subscription_selections'] );
				//	$fieldset->addRequirement( 'subscription_selections', array( 'WordCount' => array( 1,300 ), ) );
				}
	//		break;
	//		case 'product':
	//		case 'service':
					$i = 0;
					//	Build a separate demo form for the previous group
					$productForm = new Ayoola_Form( array( 'name' => 'product options' )  );
					$productForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
					$productForm->wrapForm = false;
					do
					{
						
						//	Put the product options in a separate fieldset
						$product = new Ayoola_Form_Element; 
						$product->allowDuplication = true;
						$product->container = 'span';
					
						//	Question
						$product->addElement( array( 'name' => 'option_name', 'multiple' => 'multiple', 'placeholder' => 'Enter option name here...', 'type' => 'InputText', 'value' => @$values['option_name'][$i] ) );
						$product->addElement( array( 'name' => 'option_price', 'multiple' => 'multiple', 'placeholder' => 'Enter option price here...', 'type' => 'InputText', 'value' => @$values['option_price'][$i] ) );
										
						$product->addLegend( 'Product options (if available)' );						
						$productForm->addFieldset( $product );
						$i++;
					//	self::v( $i );  
					}
					while( ! empty( $values['option_name'][$i] ) );
					
					
					//	Put the product options in a separate fieldset
				//	$product = new Ayoola_Form_Element; 
				//	$product->allowDuplication = false;
				//	$product->placeholderInPlaceOfLabel = true;
				//	$product->container = 'span';
					
			//		if( @$values['quiz_question'] )
					{
						//	add previous questions if available
						$fieldset->addElement( array( 'name' => 'previous_forms', 'type' => 'Html', 'value' => '' ), array( 'html' => $productForm->view(), 'fields' => 'option_name,option_price' ) );
						
					}
					//	Add only the last one into the main form
				//	$form->addFieldset( $product );   
				
			break;
			case 'personality':
			case 'organization':
			case 'profile':
			
				//	Profile is the only recognized "post" type
				$_REQUEST['article_type'] = 'profile';
				
			//	$fieldset->addElement( array( 'name' => 'full_legal_name', 'placeholder' => 'e.g. Oladayo Smith', 'type' => 'InputText', 'value' => @$values['full_legal_name'] ) );
			//	$fieldset->addRequirement( 'full_legal_name', array( 'WordCount' => array( 1,100 ), 'Name' => null ) );
			//	$fieldset->addRequirement( 'full_legal_name', array( 'WordCount' => array( 1,100 ) ) );
				
				//	Email
			//	$fieldset->addElement( array( 'name' => 'email', 'placeholder' => 'Enter your e-mail', 'type' => 'InputText', 'value' => @$values['email'] ) );
			//	$fieldset->addElement( array( 'name' => 'phone_number', 'placeholder' => 'e.g. +2348031234567', 'type' => 'InputText', 'value' => @$values['phone_number'] ) );
			//	$this->setParameter( array( 'form' => array( 'requirements' => 'email-address, phone-number, address' ) ) );
				$this->setParameter( array( 'form' => array( 'requirements' => 'address' ) ) );
			//	$form->setFormRequirements( 'email-address, phone-number, address' );
			
		//		$fieldset->addElement( array( 'name' => 'bbm_pin', 'optional' => 'optional', 'placeholder' => 'e.g. 7B34E4EE', 'type' => 'InputText', 'value' => @$values['bbm_pin'] ) );
		//		$fieldset->addRequirement( 'bbm_pin', array( 'WordCount' => array( 8,8 ), 'Alnum' => null ) );
		//		
		//		$fieldset->addElement( array( 'name' => 'bbm_channel', 'optional' => 'optional', 'placeholder' => 'e.g. C003829DB', 'type' => 'InputText', 'value' => @$values['bbm_channel'] ) );
		//		$fieldset->addRequirement( 'bbm_channel', array( 'WordCount' => array( 0,9 ), 'Alnum' => null ) );
				
			//	$fieldset->addElement( array( 'name' => 'tw', 'type' => 'Html' ), array( 'html' => ' Twitter @ ' ) );
		//		$fieldset->addElement( array( 'name' => 'twitter_handle', 'optional' => 'optional', 'placeholder' => 'e.g. @ComeRiver', 'type' => 'InputText', 'value' => @$values['twitter_handle'] ) );
		//		$fieldset->addRequirement( 'twitter_handle', array( 'WordCount' => array( 3,20 ), 'Name' => null ) );

		//		$fieldset->addElement( array( 'name' => 'website', 'placeholder' => 'e.g. www.my-website.com', 'type' => 'InputText', 'value' => @$values['website'] ) );
			//	$fieldset->addRequirement( 'twitter_handle', array( 'WordCount' => array( 3,50 ), 'Name' => null ) );

		//		$fieldset->addElement( array( 'name' => 'interests', 'label' => 'Select ' . ucwords( $values['article_type'] ) . ' interests.', 'type' => 'Checkbox', 'value' => @$values['interests']  ), $categoryInfo ); 
			break;
			case 'poll':
				//	Poll
				$fieldset->addElement( array( 'name' => 'poll_question', 'type' => $values['article_type'] == 'poll' ? 'InputText' : 'Hidden', 'value' => @$values['poll_question'] ) );
				@$values['poll_options'] = is_array( $values['poll_options'] ) ? array_combine( $values['poll_options'], $values['poll_options'] ) : array();
				//	var_export( $values['poll_options'] );
				$fieldset->addElement( array( 'name' => 'poll_options', 'type' => $values['article_type'] == 'poll' ? 'MultipleInputText' : 'Hidden', 'value' => @$values['poll_options'] ), $values['poll_options'] );
				@$values['poll_option_preset_votes'] = is_array( $values['poll_option_preset_votes'] ) ? array_combine( $values['poll_option_preset_votes'], $values['poll_option_preset_votes'] ) : array();
				//	var_export( $values['poll_options'] );
				$fieldset->addElement( array( 'name' => 'poll_option_preset_votes', 'type' => $values['article_type'] == 'poll' ? 'MultipleInputText' : 'Hidden', 'value' => @$values['poll_option_preset_votes'] ), $values['poll_option_preset_votes'] );
			break;
			case 'video':
				//	Downloads
				$fieldset->addElement( array( 'name' => 'video_url', 'type' => 'InputText', 'value' => @$values['video_url'] ) );
			break;
			case 'audio':
			case 'music':
			case 'message':
			case 'e-book':
			case 'document':
			case 'file':
			case 'download':
				//	Downloads
				
				$downloadOptions = array( 
											'require_user_info' => 'Require user infomation before download', 
											'download_notification' => 'Notify me on every download', 
											'version' => 'This document has a version information', 
											'password' => 'Password-protect this file', 
											'private' => 'This document is private. Do not allow the public to have access to it.', 
											'premium' => 'This is a premium file. Users are charged before they can download.', 
										);
				$fieldset->addElement( array( 'name' => 'download_options', 'label' => 'Download Options', 'type' => 'Checkbox', 'value' => @$values['download_options'] ), $downloadOptions  );
				
				if( strlen( @$values['download_base64'] ) < 1999999 )
				{
					$fieldset->addElement( array( 'name' => 'download_base64', 'type' => 'Hidden', 'value' => @$values['download_base64'] )  );
				}
				$name = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'download_url' ) : 'download_url' );
				$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . $name;
				$fieldset->addElement( array( 'name' => 'html_xx', 'type' => 'Html', 'value' => '' ), array( 'html' => '<br><input onClick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' ); return true;" type=\'button\' value="Browse Site..." /> <input onClick="ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.fieldName = \'' . $name . '\'; ayoola.image.clickBrowseButton( { accept: \'\' } );" type=\'button\' value="Browse Device..." />', 'fields' => '' ) );
			//	$fieldset->addElement( array( 'name' => 'xxxx_html', 'value' => '' ) );
				$fieldset->addElement( array( 'name' => 'download_url', 'label' => '', 'placeholder' => 'e.g. http://example.com/path/to/file.mp3', 'type' => 'InputText', 'optional' => 'optional', 'value' => @$values['download_url'] ) );
				$fieldset->addRequirement( 'download_url', array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => $this->getParameter( 'allowed_extensions' ) ? explode( ',', $this->getParameter( 'allowed_extensions' ) ) : null ) ) );

				//	For security reasons, only admins can do this.
				if( Ayoola_Abstract_Table::hasPriviledge() )
				{ 
					$fieldset->addElement( array( 'name' => 'download_path', 'type' => 'InputText', 'value' => @$values['download_path'] ) );
				}
				if( @$values['download_version'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'version', Ayoola_Form::getGlobalValue( 'download_options' ) ) )  
				{
					$fieldset->addElement( array( 'name' => 'download_version', 'type' => 'InputText', 'value' => @$values['download_version'] ) );
				}		
				if( @$values['download_password'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'password', Ayoola_Form::getGlobalValue( 'download_options' ) ) )
				{
					$fieldset->addElement( array( 'name' => 'download_password', 'label' => 'Download Password (Optional)', 'type' => 'InputPassword', 'value' => @$values['download_password'] ) );
				}		
			//	if( @$values['premium'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'premium', Ayoola_Form::getGlobalValue( 'download_options' ) ) )
				{
					$fieldset->addElement( array( 'name' => 'item_price', 'label' => 'How much should the document cost?', 'placeholder' => '0.00', 'type' => 'InputText', 'value' => @$values['item_price'] ) );
					$fieldset->addElement( array( 'name' => 'item_old_price', 'label' => 'Old price, to calculate savings for customer', 'type' => 'InputText', 'value' => @$values['item_old_price'] ) );
					$fieldset->addFilter( 'item_price', array( 'Currency' => null ) );
					$fieldset->addFilter( 'item_old_price', array( 'Currency' => null ) );
					$fieldset->addRequirement( 'item_price', array( 'WordCount' => array( 1,20 ) ) );
					$fieldset->addRequirement( 'item_old_price', array( 'WordCount' => array( 1,20 ) ) );
				}		
			break;
			default:
				
			break;
		}
		//	Inject other form fields
		foreach( static::$_otherFormFields as $eachField )
		{
			$fieldset->addElement( array( 'name' => $eachField, 'type' => 'Hidden', ) );
		}
		
//		$fieldset->addRequirement( 'article_title', array( 'WordCount' => array( 6,200 ) ) );
//		$fieldset->addRequirement( 'article_description', array( 'WordCount' => array( 0, 500 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 
		
		//	Next Level
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = $this->hashFormElementName;
		//	Use tiny editor
		
		//	Application_Javascript::addFile( '/js/objects/tinymce/tinymce.min.js' );
//		Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
		Application_Javascript::addFile( '//cdn.ckeditor.com/4.5.6/full-all/ckeditor.js' );
/* 		$name = Ayoola_Form::hashElementName( 'article_content' );
		$quizQuestions = '' . Ayoola_Form::hashElementName( 'quiz_question' );
		$quizQuestions = '' . Ayoola_Form::hashElementName( 'quiz_option1' );
		$quizQuestions = '' . Ayoola_Form::hashElementName( 'quiz_option2' );
		$quizQuestions = '' . Ayoola_Form::hashElementName( 'quiz_option3' );
		$quizQuestions = '' . Ayoola_Form::hashElementName( 'quiz_option4' );
 */	//	var_export( $name );
	//	var_export( $quizQuestions );
	//	$htmlFields = array( Ayoola_Form::hashElementName( 'quiz_question' ) . '[]', Ayoola_Form::hashElementName( 'quiz_option1' ) . '[]', Ayoola_Form::hashElementName( 'quiz_option2' ) . '[]', Ayoola_Form::hashElementName( 'quiz_option3' ) . '[]', Ayoola_Form::hashElementName( 'quiz_option4' ) . '[]', Ayoola_Form::hashElementName( 'article_content' ), );
		$htmlFields = array( Ayoola_Form::hashElementName( 'quiz_question' ) . '[]', Ayoola_Form::hashElementName( 'article_content' ), );
	//	var_export( $htmlFields );
	
		Application_Javascript::addCode 
		(  
			'ayoola.xmlHttp.setAfterStateChangeCallback
			( 
				function()
				{ 
					try
					{
						//	destroy all instances of ckeditor everytime state changes.
						for( name in CKEDITOR.instances )
						{
							CKEDITOR.instances[name].destroy();
						}
					}
					catch( e )
					{
					
					}
				}
			)' 
		);
	//	foreach( $htmlFields as $each )
		{
 			Application_Javascript::addCode
			( 
				'ayoola.events.add
				( 
					window, "load", 
					function()
					{ 
						ayoola.xmlHttp.callAfterStateChangeCallbacks();
					} 
				);' 
			
			);
 			Application_Javascript::addCode 
			(  
				'ayoola.xmlHttp.setAfterStateChangeCallback
				( 
					function()
					{ 
						//	Retrieve all the stylesheets in the doc and attach them to the editor
						var a = document.getElementsByTagName( "link" );
						var d = new Array();
						for( var b = 0; b < a.length; b++ )
						{
							if( ! a[b].href.search( /css/ ) || a[b].href.search( /css/ ) == -1 ) 
							{ 
								continue; 
							}
							
							d.push( a[b].href );
						}
				//		var a = document.getElementsByName( "" );
						var a = document.getElementsByTagName( "textarea" );
					//	alert( a.length );
						var initCKEditor = function( target )
						{
							CKEDITOR.plugins.addExternal( "uploadimage", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/uploadimage/plugin.js", "" );
							CKEDITOR.plugins.addExternal( "confighelper", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/confighelper/plugin.js", "" );
							CKEDITOR.config.extraPlugins = "confighelper,uploadimage,autogrow,tableresize";
							CKEDITOR.config.removePlugins = "maximize,resize,elementspath";
							CKEDITOR.config.allowedContent  = true;
							CKEDITOR.config.filebrowserUploadUrl = "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Ajax/?";  
							CKEDITOR.replace
							( 
								target,
								{
									height: 50,
									toolbar : 
									[
										{ name: "insert", items: [ "Image", "Table", "SpecialChar" ] },
										{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
										{ name: "paragraph", groups: [ "list", "indent", "blocks", "align" ], items: [ "NumberedList", "BulletedList", "-", "Blockquote", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-" ] },
										{ name: "links", items: [ "Link", "Unlink" ] },
										{ name: "styles", items: [ "Format", "Font", "FontSize" ] },
										{ name: "colors", items: [ "TextColor", "BGColor" ] },
										{ name: "tools", items: [ "Maximize" ] }
									],
									autoGrow_minHeight : 50,
									autoGrow_maxHeight : 800,
								}
							);
						}
						var f = function( e )
						{
						//	alert( e ); 
							try
							{
								try
								{
									//	destroy all instances of ckeditor everytime state changes.
									for( name in CKEDITOR.instances )
									{
										CKEDITOR.instances[name].destroy();
									}
								}
								catch( e )
								{
								
								}
								var target = ayoola.events.getTarget( e );
					//			alert( target ); 
								initCKEditor( target );
							}
							catch( e )
							{
								//	throws exception if article content is not available
							}
						}
						for( var b = 0; b < a.length; b++ )
						{
						//	alert( a[b].name );
							switch( a[b].name  )
							{
								case "article_content":
								case "' . Ayoola_Form::hashElementName( 'article_content' ) . '":
									initCKEditor( a[b] );
								break;
								default:
								//	alert( a[b] ); 
									if( ! a[b].getAttribute( "data-html" ) )
									{
										break;
									}
									ayoola.events.add( a[b], "click", f );
									ayoola.events.add( a[b], "Dblclick", f );
								break;
							}
						}
					}
				)' 
			);
		}
		if( $values['article_type'] == 'article' || $values['article_type'] == 'post' || @$values['article_content'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'article', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) || $values['article_type'] == 'article' || $values['article_type'] == 'post'  )
		{
			$fieldset->addElement( array( 'name' => 'article_content', 'data-html' => '1', 'label' => '' . ucwords( $values['article_type'] ) . ' write up (Article)', 'rows' => '10', 'placeholder' => 'Enter content here...', 'type' => 'TextArea', 'value' => @$values['article_content'] ) );
			$fieldset->addRequirement( 'article_content', array( 'WordCount' => array( 0,100000 ) ) );
	
		}
		if( @$values['article_tags'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'keywords', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) )
		{
			$fieldset->addElement( array( 'name' => 'article_tags', 'label' => '' . ucwords( $values['article_type'] ) . ' Tags', 'placeholder' => 'Enter tags for this ' . ucwords( $values['article_type'] ) . ' separated by comma', 'type' => 'InputText', 'value' => @$values['article_tags'] ) );
	
		}
		//	Tags
		
		//	Time
	//	$date = is_null( $values ) ? 'article_creation_date' : 'article_modified_date';
	//	$fieldset->addElement( array( 'name' => 'article_creation_date', 'type' => 'Hidden' ) );	
	//	$fieldset->addFilter( 'article_creation_date', array( 'DefiniteValue' => @$values['article_creation_date'] ? : time() ) );
		
	//	$fieldset->addElement( array( 'name' => 'article_modified_date', 'type' => 'Hidden' ) );	
	//	$fieldset->addFilter( 'article_modified_date', array( 'DefiniteValue' => time() ) );
		
		
	//	$fieldset->addRequirement( 'category_id', array( 'Int' => null, 'InArray' => array_keys( $options ) ) );
	//	if( self::hasPriviledge() )
		{
			$options = new Ayoola_Access_AuthLevel;
			$options = $options->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Select user levels that can view ' . ucwords( $values['article_type'] ) . '', 'type' => 'Checkbox', 'value' => @$values['auth_level'] ? : array_keys( $options ) ), $options );
			$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $options )  ) );
	//		var_export( array_keys( $options ) );
		}
		if( @$values['requirement_name'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'requirement', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) )
		{
			$options = new Ayoola_Form_Requirement;
			$options = $options->select();
			if( $options ) 
			{
				require_once 'Ayoola/Filter/SelectListArray.php';
				$filter = new Ayoola_Filter_SelectListArray( 'requirement_name', 'requirement_label' );
				$options = $filter->filter( $options );
				if( $values['article_type'] == 'subscription' ) 
				{
					//	Shipping and billing address for subscription
					$options += array( 'billing_address' => 'Billing Address', 'shipping_address' => 'Shipping Address', );
				}
				$fieldset->addElement( array( 'name' => 'article_requirements', 'label' => 'Select information required from viewers of this ' . ucwords( $values['article_type'] ) . ' (advanced)', 'type' => 'Checkbox', 'value' => @$values['article_requirements'] ), $options );
				
			//	$fieldset->addRequirement( 'article_requirements', array( 'InArray' => array_keys( $options )  ) );
			}
	
		}
		
		
		//	Publish
//		$options = array( 'No', 'Yes' );
		//	retain this because we need to bring this up in custom forms
	//	$fieldset->addElement( array( 'name' => 'publish', 'type' => 'submit', 'value' => 'Continue...' ) );
		$fieldset->addElement( array( 'name' => 'publish', 'type' => 'Hidden', 'value' => null ) );

		$fieldset->addFilters( array( 'trim' => null ) );
		$form->addFieldset( $fieldset ); 
		$this->setForm( $form );
    } 
	// END OF CLASS
}
