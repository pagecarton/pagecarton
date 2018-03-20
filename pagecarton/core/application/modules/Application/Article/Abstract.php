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

abstract class Application_Article_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = array( 99, 98 );
	
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
     * Module files directory namespace
     * 
     * @var string
     */
	protected static $editorInitialized;	
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article';	
	
    /**
     * Compliments the parent
     * 
     * param array Allowed Access Levels
     * return boolean
     */
	public static function isAllowedToEdit( array $data )   
	{
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		$articleSettings['allowed_editors'][] = 98;
//		$whoCan = array( 98 ) + ( $articleSettings['allowed_editors'] ? : array() );
	//	var_export( $articleSettings['allowed_editors'] ); 
	//	var_export( self::hasPriviledge( $articleSettings['allowed_editors'] )  ); 
		if( 
			self::isOwner( @$data['user_id'] ) 
			|| self::hasPriviledge( $articleSettings['allowed_editors'] ) 
			|| Ayoola_Application::getUserInfo( 'username' ) === $data['username']   
		)
		{ 
		//	var_export( Ayoola_Application::getUserInfo( 'username' ) );
	//		var_export( Ayoola_Application::$GLOBAL['username'] );
			return true; 
		}
		return false;
	}
	
    /**
     * Compliments the parent
     * 
     * param array Allowed Access Levels
     * return boolean
     */
	public static function isAllowedToView( array $data )   
	{
	//	var_export( $data['username'] );
		if( 
				( 
					trim( @$data['publish'] )
					|| self::isOwner( @$data['user_id'] ) 
					|| @in_array( 'publish', @$data['article_options'] ) 
					|| strtolower( Ayoola_Application::getUserInfo( 'username' ) ) === strtolower( $data['username']  )
				)
			&&
				(
					self::hasPriviledge( @$data['auth_level'] ) 
					|| strtolower( Ayoola_Application::getUserInfo( 'username' ) ) === strtolower( $data['username']  )
				)
			
		)
		{ 
			return true; 
		}
		//	var_export( @$data['auth_level'] );  
		return false;
	}
	
    /**
     * 
     * 
     */
	public static function sanitizeData( &$data )
    {

	}
	
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
	public static function getBackupFolder()  
    {
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . static::$_moduleDir );
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES . DS . 'backup' .  DS . static::$_moduleDir; 
	}
	
    /**
     * returns the article folder
     * 
     */
	public static function getSecondaryFolder()  
    {
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES .  DS . static::$_moduleDir );
		return Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . AYOOLA_MODULE_FILES . DS . 'secondary' .  DS . static::$_moduleDir; 
	}
	
    /**
     * Save the article
     * 
     */
	public static function updateProfile( $values )
    {
	//	var_export( $values['username'] );
		if( $values['username'] )
		{
			//	Let's save some info into the owners account
			$profilePath = Application_Profile_Abstract::getProfilePath( $values['username'] );
		//	$profileInfo = self::loadPostData( $profilePath );
			$profileInfo = @include $profilePath;
		//	var_export( $profileInfo );
			@$profileInfo['posts'] = $profileInfo['posts'] ? : array();
			@$profileInfo['profile_url'] = $values['username'];
		//	$profileInfo['posts']['url'][$values['article_url']] = array( 'article_url' => $values['article_url'] );
			$profileInfo['posts']['all'][$values['article_url']] = array( 'article_url' => $values['article_url'], 'file_size' => $values['file_size'] );
			$profileInfo['posts']['size'][$values['article_url']] = $values['file_size'];
			$profileInfo['posts_count_all'] = count( $profileInfo['posts']['all'] );
			$profileInfo['posts_file_size'] = array_sum( $profileInfo['posts']['size'] );
			
			if( intval( $values['auth_level'] ) === 97 )
			{
				$profileInfo['posts']['private'][$values['article_url']] = $values['article_url'];
				$profileInfo['posts_count_private'] = count( $profileInfo['posts']['private'] );     
			}
		//	var_export( $profileInfo );
			Application_Profile_Abstract::saveProfile( $profileInfo );

		}
	}
	
    /**
     * Save the article
     * 
     */
	public static function saveArticle( $values )
    {
//		var_export( $values );
		if( empty( $values['article_url'] ) )
		{
			return false;
		}
	//	$text = '<?php return ' . var_export( $values, true ) . ';';
		$values['file_size'] = intval( strlen( var_export( $values, true ) ) );
		
		$validator = new Ayoola_Validator_UserRestrictions();
		$validator->username = $values['username'];
		if( ! $validator->validate( null ) )
		{
			throw new Application_Article_Exception( $validator->getBadnews() );
		}
		
		
		self::updateProfile( $values );
	//	$head = array_change_key_case(get_headers("http://example.com/file.ext", TRUE));
	//	$filesize = $head['content-length'];		
		
		if( is_file( self::getFolder() . $values['article_url'] ) )
		{
			//	Back up the file before replacing it. 
			$backupFolder = self::getBackupFolder() . $values['article_url'] . DS . time() . '.backup';    
			Ayoola_Doc::createDirectory( dirname( $backupFolder ) );
			copy( self::getFolder() . $values['article_url'], $backupFolder );
		}
 		
	//	if( $values['file_size'] > 5000000 )
		if( ! empty( $values['document_url_base64'] ) ||  ! empty( $values['download_base64'] ) )
		{
			$secDir = self::getSecondaryFolder() . $values['article_url'];
			Ayoola_Doc::createDirectory( dirname( $secDir ) );
			$secondaryValues = array( 'document_url_base64' => $values['document_url_base64'], 'download_base64' => $values['download_base64'], );
			unset( $values['document_url_base64'], $values['download_base64'] );
			$values['has_secondary_data'] = true;   
			file_put_contents( $secDir, json_encode( $secondaryValues ) );
	//		file_put_contents( $secDir, '<?php return ' . var_export( $secondaryValues, true ) . ';' );
		}
		$values['file_size'] += intval( filesize( Ayoola_Doc::uriToDedicatedUrl( @$data['download_url'] ) ) );
		$values['file_size'] += intval( filesize( @$values['download_path'] ) );
 	//	unset( $_POST, $_REQUEST );

	 	//	we now using json
		file_put_contents( self::getFolder() . $values['article_url'], json_encode( $values ) ); 

		// and we want to use tables for sorting categories and all
		$table = Application_Article_Table::getInstance();
		if( $table->select( null, array( 'article_url' => $values['article_url'] ) ) )
		{
			$table->delete( array( 'article_url' => $values['article_url'] ) );
		}
		$table->insert( $values );

	 	return true;

	//	return file_put_contents( self::getFolder() . $values['article_url'], '<?php return ' . var_export( $values, true ) . ';' );   
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
		self::$_postUrl = rtrim( @$articleSettings['post_url'] ? : '/posts/', '/' );
		return self::$_postUrl;
	}
	
    /**
     * Overides the parent class
     * 
     */
	public static function loadPostData( $data )
    {
		
		if( is_array( $data ) )
		{
			if( ! empty( $data['article_url'] ) )
			{
				$data = $data['article_url'];
			}
		}
		if( ! is_file( $data ) )
		{
			$data = self::getFolder() . $data;
			if( ! is_file( $data ) )
			{
				return false;
			}
		}

		//	now we using JSON for this
		if( ! $jsonData = json_decode( file_get_contents( $data ), true ) )
		{
			//	compatibility

			
			//	Check file before it is included.
			// Get the shell output from the syntax check command
			$output = shell_exec('php -l "'.$data.'"');
		
			// Try to find the parse error text and chop it off
			$syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
		
			// If the error text above was matched, throw an exception containing the syntax error
		//	var_export( $output );
		//	var_export( $count );  
			if( ! $data || $count > 0 )  
			{
				return false;
			}
			$data = include $data;


			if( @$data['has_secondary_data'] )
			{
				$filename = self::getSecondaryFolder() . $data['article_url'];
				if( is_file( $filename ) )
				{

					//	Check file before it is included.
					// Get the shell output from the syntax check command
					$output = shell_exec('php -l "'.$filename.'"');
				
					// Try to find the parse error text and chop it off
					$syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
				
					// If the error text above was matched, throw an exception containing the syntax error
				//	var_export( $output );
				//	var_export( $count );  
					if( $count > 0 )
					{
						return false;
					}
					if( $data2 = include $filename )
					{
						$data += $data2;
					}

				}
			}
			if( $data )
			{
				//	Change to json
			//	var_export( __LINE__ );
				try
				{
					self::saveArticle( $data );
				}
				catch( Exception $e )
				{
					//	some error came up about table.xml not available.
			//		var_export( $e->getMessage() );
				}
		//		var_export( __LINE__ );
			}
		}
		else
		{
			$data = $jsonData;
			if( @$data['has_secondary_data'] )
			{
				$filename = self::getSecondaryFolder() . $data['article_url'];
				if( is_file( $filename ) )
				{
					//	Check file before it is included.
				// Get the shell output from the syntax check command
					if( $data2 = json_decode( file_get_contents( $filename ), true ) )
					{
						$data += $data2;
					}
				}
			}
		}
	//	var_export( $jsonData );
	//	var_export( $data );
		return $data;
	}
	
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
		// Comes from a file
		if( ! $data = $this->getParameter( 'data' ) )
		{
			$url = Ayoola_Application::getRequestedUri();
			$url = @$_GET['article_url'] ? : $url;
			$url = $this->getParameter( 'article_url' ) ? : $url;
		//	self::v( $url ); 
			$filename = self::getFolder() . $url;
			$data = self::loadPostData( $filename );
 		}
//		self::v( $this->getParameter( 'article_url' ) );
//		self::v( $url );
//		self::v( $data );

	//	if( get_class( $this ) === 'Application_Article_View' && ( ! $this->getParameter( 'markup_template_object_name' ) || $this->getParameter( 'update_meta_data' ) ) )
		if( get_class( $this ) === 'Application_Article_View' )
		{
			//	dont duplicate
			if( strpos( Ayoola_Page::getCurrentPageInfo( 'title' ), $data['article_title'] ) === false )
			{
				$pageInfo = array(
					'description' => @$data['article_description'],
					'title' => trim( $data['article_title'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
				Ayoola_Page::setCurrentPageInfo( $pageInfo );

				//	Log into the database 
				$table = Application_Article_Views::getInstance();
				$table->insert( array(
										'username' => Ayoola_Application::getUserInfo( 'username' ),
										'article_url' => $data['article_url'],
										'timestamp' => time(),
								) 
				);
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
		if( self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
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
		$class = Application_Category::getInstance();
		$options = $class->select( null, array( 'category_name' => $categoryIds ) ) ? : array();
		
		//	compatibility
		$options += $class->select( null, array( 'category_id' => $categoryIds ) ) ? : array();
//		var_export( $displayOptions ); 
		$i = 0;
		foreach( $options as $each )
		{
			if( $displayOptions['template'] )
			{
				$html .= str_ireplace( array( '{{{category_url}}}', '{{{category_label}}}', '{{{category_name}}}' ), array( Ayoola_Application::getUrlPrefix() . self::getPostUrl() . '/category/' . $each['category_name'], $each['category_label'], $each['category_name'] ), $displayOptions['template'] );
				$html .= count( $options ) === ++$i ? null : $displayOptions['glue']; 
			}
			else
			{
				$each['category_label'] = @$_GET['category'] === $each['category_name'] ? "<strong> {$each['category_label']} </strong>" : "{$each['category_label']}";
				$html .= '<a style="" href="' . Ayoola_Application::getUrlPrefix() . self::getPostUrl() . '/category/' . $each['category_name'] . '/"> ' . $each['category_label'] . ' </a>';
				$html .= count( $options ) === ++$i ? null : $displayOptions['glue']; 
			}
		}
	//	var_export( $data['category_id'] );
	//	$html .= $displayOptions['template'] ? null :  ' </ul> ';
		return $html;
    } 
	
    /**
     * Sets up JS Required to autoload new posts
     * 
     * @param string Post List ID
     * @param int Post List ID
     * 
     */
	public function autoLoadNewPosts( $postListId, $offset = 0 )
    {
	//	var_export( $values );
		if( empty( $_GET['pc_post_list_autoload'] ) && ( $this->getParameter( 'pagination' ) || $this->getParameter( 'pc_post_list_autoload' ) ) )
		{
			Application_Javascript::addFile( '/js/objects/infinite-scroll.js' );	
			Application_Javascript::addCode
			( 
				'
				var pc_autoloadPostPageNumber_' . $postListId . ' = "' . $offset . '";
				var options = 
				{
					distance: ' . ( $this->getParameter( 'autoload_distance' ) ? : 500 ) . ',
					callback: function( done ) 
					{
						var a = document.createElement( "div" );
						a.innerHTML = "<div title=\"Loading more...\" style=\"text-align: center;\"><img alt=\"Loading more...\" src=\"' . Ayoola_Application::getUrlPrefix() . '/loading.gif?document_time=1\" ></div>";
						var b = document.getElementById( "' . $postListId . '" );
						b.appendChild( a );
						var url = "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . get_class( $this ) . '/?pc_post_list_autoload=1&pc_post_list_id=' . $postListId . '&list_page_number=" + pc_autoloadPostPageNumber_' . $postListId . ';
						var ajax = ayoola.xmlHttp.fetchLink( { url: url, container: b, noSplash: true, insertBefore: true } );
						var v = function()
						{
							if( ayoola.xmlHttp.isReady( ajax ) )
							{	
								var b = document.getElementById( "' . $postListId . '" );
								b.innerHTML = "";
								if( ! ajax.responseText )
								{ 
									return false;
								}
								if( ajax.responseText.indexOf( "pc_no_post_to_show" ) > -1 )
								{ 
									return false;
								}
								
								pc_autoloadPostPageNumber_' . $postListId . '++;
								done();
							}		
						}	
						ayoola.events.add( ajax, "readystatechange", v );
						// 1. fetch data from the server
						// 2. insert it into the document
						// 3. call done when we are done
					//	done();
					}
				}
					
				// setup infinite scroll
				infiniteScroll(options);		
				' 
			);	
		}
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
	public static function getDefaultPostView( $data )
    {

		if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )
		{

		}
		$link = 'a title="View ' . $data['article_title'] . '" style="color:inherit;" href="' . Ayoola_Application::getUrlPrefix() . $data['article_url'] . '"';
		$realPost = true;
		if( stripos( $data['article_url'], '/tools/classplayer') === 0 )
		{
			$link .= ' onclick="this.href=\'javascript:\';ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . $data['article_url'] . '\', \'page_refresh\' );"';
			$realPost = false;
		}
		$header = 'h2';
		if( $data['article_url'] === Ayoola_Application::getPresentUri() )
		{
			$link = 'span';
			$header = 'h1';

		}
		$html = null;
		$html .= '<div style="-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777; margin-bottom:3em;">';
		$html .= '<' . $link . '>';
		$html .= '<div  class="pc_theme_parallax_background" style="background-image: linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)  ),    url(\'' . Ayoola_Application::getUrlPrefix() . $image . '\'); ">';
		$html .= '<p style="text-align:right;">' . $data['article_type'] . '</p>';
		$html .= '<' . $header . '>' . $data['article_title'] . '</' . $header . '>';
		$html .= $data['article_description'] ? '<br><br><p>' . $data['article_description'] . '</p>' : null;
		$html .= $realPost && $data['button_value'] ? '<br><br><p><button class="pc-btn"> ' . $data['button_value'] . ' </button></p>' : null;
		$html .= '</div>';
		$html .= '</' . $link . '>';
		$html .= '<div class="pc_theme_parallax_background" style="font-size:x-small;text-transform:uppercase;background-image: linear-gradient(      rgba(0, 0, 0, 0.5),      rgba(0, 0, 0, 0.5)  ),    url(\'' . Ayoola_Application::getUrlPrefix() . $image . '\'); ">';

		$html .= $data['item_price'] ? '
		<span style="font-size:small;">
		<span class="pc_posts_option_items" style="text-decoration:line-through;" >' . $data['item_old_price'] . '</span> 
		<span class="pc_posts_option_items" >' . $data['item_price_with_currency'] . '</span> 
		</span>
		' : null;
		$html .= '<span class="pc_posts_option_items">' . self::filterTime( $data ) . '</span>';
		$html .= '<span class="pc_posts_option_items"> by ' . $data['username'] . '</span>';
		if( isset( $data['views_count'] ) )
		{
			$html .= '<span class="pc_posts_option_items">' . $data['views_count'] . ' <span >views</span></span>';
		}
		if( isset( $data['download_count'] ) )
		{
			$html .= '<span class="pc_posts_option_items">' . $data['download_count'] . ' <span >downloads</span></span>';
		}
		if( isset( $data['audio_play_count'] ) )
		{
			$html .= '<span class="pc_posts_option_items">' . $data['audio_play_count'] . ' <span >plays</span></span>';
		}
		$html .= $data['category_text'] ? '<span class="pc_posts_option_items"> in ' . $data['category_text'] . ' </span>' : null;  
	//	var_export( self::isAllowedToEdit( $data ) ); 

		$html .= self::isAllowedToEdit( $data ) && $realPost ? '  
		<a  class="pc_posts_option_items" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Editor/?article_url=' . $data['article_url'] . '&\', \'page_refresh\' );" href="javascript:">edit</a> 
		<a  class="pc_posts_option_items" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '&\', \'page_refresh\' );" href="javascript:"> delete </a>
		' : null;
		
		$shareLink = '
		<a class="a2a_dd" href="#">Share</a>
		<script>
		var a2a_config = a2a_config || {};
		a2a_config.linkurl = "' . Ayoola_Page::getCanonicalUrl( $data['article_url'] ) . '";
		</script>
		<script async src="https://static.addtoany.com/menu/page.js"></script>
		<!-- AddToAny END -->
		';
		$html .= '<span style="display: inline-block; color:inherit; margin-right:2em;">' . $shareLink . ' </span>';  

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
	
    /**
     * Returns an HTML to display footer for messages
     * 
     */
	public static function isDownloadable( &$data )
    {
		if( @$data['download_url'] )
		{
			return true;
		}
		elseif( @$data['download_path'] )
		{
			return true;
		}
		elseif( @$data['download_base64'] )
		{
			return true;
		}
		return false;
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
		if( @$data['article_creation_date'] )
		{
		//	$html .= '<strong> Modified: </strong> ';
			$html .= $filter->filter( $data['article_creation_date'] );
		}
		else
		{
		//	$html .= '<strong> Posted: </strong> ';
			$html .= $filter->filter( @$data['article_modified_date'] ? : ( time() - 3 ) ); 
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
     * Returns an HTML to display footer for messages
     * 
     */
	public static function initHTMLEditor()
    {
		if( self::$editorInitialized )
		{
			return false;
		}
		self::$editorInitialized = true;
		
		Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
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
						CKEDITOR.config.extraPlugins = "confighelper,uploadimage,autogrow,tableresize,codesnippet";
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
									{ name: "insert", items: [ "Image", "Table", "SpecialChar", "CodeSnippet" ] },
									{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
									{ name: "paragraph", groups: [ "list", "indent", "blocks", "align" ], items: [ "NumberedList", "BulletedList", "-", "Blockquote", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-" ] },
									{ name: "links", items: [ "Link", "Unlink" ] },
									{ name: "styles", items: [ "Format", "Font", "FontSize" ] },
									{ name: "colors", items: [ "TextColor", "BGColor" ] },
								],
								autoGrow_minHeight : 50,
								autoGrow_maxHeight : 400,
							}
						);
						CKEDITOR.config.codeSnippet_theme = "pojoaque";
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
								if( ! a[b].getAttribute( "data-html" ) && a[b].getAttribute( "data-document_type" ) != "html" )
								{
									break;
								}
							//	ayoola.events.add( a[b], "click", f );
								ayoola.events.add( a[b], "dblclick", f );
							break;
						}
					}
				}
			)' 
		);
		return true;
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
		
		//	Do we want to just edit some particular fields?
		$fieldsToEdit = array();
		if( ! empty( $_REQUEST['pc_post_info_to_edit'] ) && is_string( $_REQUEST['pc_post_info_to_edit'] ) )
		{
			$fieldsToEdit = array_map( 'trim', explode( ',', @$_REQUEST['pc_post_info_to_edit'] ) );
			$fieldsToEdit[] = 'article_type';
			$fieldsToEdit[] = 'true_post_type';
		}
		
		$form->oneFieldSetAtATime = $this->hashFormElementName;   
		$form->oneFieldSetAtATime = false;   
		$form->submitValue = 'Save' ;
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
	//		$fieldset->addElement( array( 'name' => 'form_name', 'type' => 'InputText', 'autocomplete' => 'off', 'value' => @$formToUse ) );
		}
		
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
	//	if( ! self::hasPriviledge( @$articleSettings['allowed_writers'] ) )
		{ 
	//		return false; 
		}
	//	$fieldset->placeholderInPlaceOfLabel = true;
		//	Let's know the kind of post that we are working on.
	//	var_export( $this->getParameter( 'article_type' ) ); 
	//	self::v( $articleSettings ); 
	//	self::v( $values['article_type'] ); 
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
//		$values['article_type'] = $values['article_type'] ? : 'post';
		
		//	Set Article Type
		$options = Application_Article_Type::getInstance();
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
		$options = $filter->filter( $options );
		$options = $options ? : Application_Article_Type_TypeAbstract::$presetTypes;

		if( ! empty( $_REQUEST['article_type'] ) )
		{
			if( empty( $options[$_REQUEST['article_type']] ) )
			{
				$options[$_REQUEST['article_type']] = ucwords( str_replace( '-', ' ', $_REQUEST['article_type'] ) );
			}
			
		}

		$tempOptions = array_keys( $options );

		$articleTypeWeUsing = $values['article_type'] ? : array_shift( $tempOptions );
		
		//	Check if post type is registered in the post db
//		$values['true_post_type'] = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $values['article_type'] );
		if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $articleTypeWeUsing ) )
		{
		//	var_export( $postTypeInfo );
			$values['true_post_type'] = $postTypeInfo['article_type'];
			$values['post_type'] = $postTypeInfo['post_type'];
		}
		else
		{
			$values['true_post_type'] = $values['article_type'];
			$values['post_type'] = $options[$values['article_type']] ? : $values['article_type'];
		}
//		var_export( $values['true_post_type'] );
		
	//	var_export( $options[key( $options )] );
//		var_export( $articleTypeWeUsing );
//		var_export( $values['post_type'] );
		$values['post_type'] = $values['post_type'] ? : $articleTypeWeUsing;
		$values['true_post_type'] = $values['true_post_type'] ? : $articleTypeWeUsing;

		$fieldset->addElement( array( 'name' => 'article_type', 'label' => 'Post Type', 'onchange'=> 'window.location.search += \'&article_type=\' + this.value + \'\';', 'type' => 'Select', 'value' => $articleTypeWeUsing ), $options );
		$fieldset->addElement( array( 'name' => 'true_post_type', 'type' => 'Hidden', 'value' => @$values['true_post_type'] ? : @$values['article_type'] ) );
		   
		
	//	if( is_null( $values ) )
		{
/* 			//	Category allows one to enter a category that will be discretely entered.
			$values['category'] = $this->getParameter( 'category' ) ? : @$_REQUEST['category'];
			$fieldset->addElement( array( 'name' => 'category', 'type' => 'Hidden', 'value' => $values['category'] ) );
 */		}
		$postTypeLabel = $this->getParameter( 'post_type_label' ) ? : ucwords( $values['post_type'] );
		
		//	Title
		$fieldset->addElement( array( 'name' => 'article_title', 'label' => $postTypeLabel . ' Title', 'placeholder' => 'Enter a title for the ' . $postTypeLabel . ' here...', 'type' => 'InputText', 'value' => @$values['article_title'] ) );
	//	$fieldset->addElement( array( 'name' => 'cs', 'type' => 'Html' ), array( 'html' => '<div style="display:block;min-width:100%;"></div><br />' ) );
	
		//	Description
		$fieldset->addElement( array( 'name' => 'article_description', 'label' => '' . $postTypeLabel . ' Description', 'placeholder' => 'Describe this ' . $postTypeLabel . ' in a few words...', 'type' => 'TextArea', 'value' => @$values['article_description'] ) );

		$fieldset->addRequirement( 'article_title', array( 'WordCount' => array( 3,200 ) ) );
		$fieldset->addRequirement( 'article_description', array( 'WordCount' => array( 0, 5000 ) ) );
	
		//	addRequirements
		//	Cover photo
	//	$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldName64 = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url_base64' ) : 'document_url_base64' );
	//	var_export( $link );
	//	var_export( @$values['article_url'] );
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => 'Cover Photo', 'placeholder' => 'Cover Photo for this ' . $postTypeLabel . '', 'type' => 'Document', 'value' => @$values['document_url'] ) );
//		$fieldset->addElement( array( 'name' => 'document_url', 'label' => 'Cover Photo', 'placeholder' => 'Cover Photo for this ' . $postTypeLabel . '', 'type' => 'Document', 'data-previous-url' => @$values['article_url'] ?'/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$values['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ) : null, 'autocomplete' => 'off', 'value' => null ) );
	//	if( @$values['document_url_base64'] )
		{ 
			
		//	$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
	//		$size = ( @$articleSettings['cover_photo_width'] ? : '900' ) . 'x' . ( @$articleSettings['cover_photo_height'] ? : '300' );
	//		$imgHtml = '<img title="Cover Photo for this ' . $postTypeLabel . '" alt="" style="" name="' . $fieldName64 . '_preview_zone_image' . '" src="' . ( ( @$values['document_url_base64'] ) ? ( '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$values['article_url'] ) : ( 'http://placehold.it/' . $size . '&text=' . ( @$element['label'] ? : ( 'Photo (' . $size . ')' ) ) . '' ) ) . '"  class="" onClick=""  >';
	//		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html', 'value' => '' ), array( 'html' => $imgHtml, 'fields' => '' ) );
		}
//		$fieldset->addElement( array( 'name' => 'document_url_base64', 'data-previous-url' => '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$values['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ), 'label' => 'Cover Photo', 'data-allow_base64' => true, 'type' => 'Document', 'autocomplete' => 'off', 'value' => null ) );
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
//		$fieldset->addElement( array( 'name' => 'article_options', 'label' => '' . $postTypeLabel . ' Options', 'type' => 'Checkbox', 'value' => $values['article_options'] ), $options );
	
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset ); 


		//	supplementary form
	//	var_export( $postTypeInfo );
		if( ! empty( $postTypeInfo['supplementary_form'] ) )
		{
			$supplementaryForm = $postTypeInfo['supplementary_form'];
			$parameters = array( 'form_name' => $supplementaryForm, 'default_values' => $values );  
	//		var_export( $parameters );
			$orderFormClass = new Ayoola_Form_View( $parameters );
			
			if( $orderFormClass->getForm() )
			{
				foreach( $orderFormClass->getForm()->getFieldsets() as $each )  
				{
				//	$each->addLegend( "" );
					
					$form->addFieldset( $each );
				}
				$form->submitValue = 'Continue...';
			}
		}
		//	internal forms to use
		$internalForms = array();
		$internalForms[] = $values['true_post_type'];
		$internalForms = array_merge( is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : array(), $internalForms );

		//	Next Level

		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = $this->hashFormElementName;
	//	var_export( $internalForms );
	//	var_export( array_unique( $internalForms ) );
		foreach( array_unique( $internalForms ) as $eachPostType )
		{	
			switch( $eachPostType )
			{
				case 'book':
					$fieldset->addElement( array( 'name' => 'isbn', 'label' => 'ISBN', 'type' => 'InputText', 'value' => @$values['isbn']  ) );
			//		$fieldset->addElement( array( 'name' => 'authors', 'label' => 'Authors', 'placeholder' => 'Enter an author here... Click + to add more...', 'type' => 'MultipleInputText', 'value' => @$values['authors']  ) );   
				break;  
				case 'gallery':
					$fieldset->addElement( array( 'name' => 'images', 'label' => $postTypeLabel . ' Images', 'type' => 'Document', 'data-document_type' => 'image', 'multiple' => 'multiple', 'data-multiple' => 'multiple', 'value' => @$values['images']  ) );
				break;  
				case 'examination':
				case 'test':
				case 'quiz':
				//	self::v( $values['quiz_options'] );
					$quizOptions = array(
											'quiz_subgroups' => 'This quiz has subgroups',
											'random' => 'Randomize Questions',
											'save_results' => 'Save Test Results',
											'edit_questions' => 'Load or edit questions bank now', 
											'no_correction' => 'Hide correction from the user when test is concluded',
											'hide_result' => 'Hide the test results from user after submission',
										);
					$fieldset->addElement( array( 'name' => 'quiz_options', 'label' => 'Quiz Options', 'type' => 'Checkbox', 'value' => @$values['quiz_options'] ? : array( 'save_results', 'random', 'edit_questions' ) ), $quizOptions );
					
					
					$form->addFieldset( $fieldset );
					
					//	New fieldset for categories
					$fieldset = new Ayoola_Form_Element;
					$fieldset->hashElementName = $this->hashFormElementName;
					
				//	self::v( $fieldsToEdit );
					//	
					$groupIds = $this->getGlobalValue( 'quiz_subgroup_id' ) ? : @$values['quiz_subgroup_id'];
				//	var_export( $values['quiz_subgroup_id'] );
					$groupQuestions = $this->getGlobalValue( 'quiz_subgroup_question' ) ? : @$values['quiz_subgroup_question'];
					if( in_array( 'group_questions', $fieldsToEdit ) || ( is_array( $this->getGlobalValue( 'quiz_options' ) ) && in_array( 'quiz_subgroups', $this->getGlobalValue( 'quiz_options' ) ) ) )
					{
					//	self::v( $fieldsToEdit );
					
						$i = 0;
						//	Build a separate demo form for the previous group
						$questionForm = new Ayoola_Form( array( 'name' => 'categories...' )  );
						$questionForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
						$questionForm->wrapForm = false;
						
						do
						{
							
							//	Put the questions in a separate fieldset
							$categoryFieldset = new Ayoola_Form_Element; 
							$categoryFieldset->allowDuplication = true;
							$categoryFieldset->duplicationData = array( 'add' => '+ Add New Category Below', 'remove' => '- Remove Above Category', 'counter' => 'subgroup_counter', );
							$categoryFieldset->container = 'span';
							
							$categoryFieldset->addElement( array( 'name' => 'quiz_subgroup_id', 'label' => 'Unique Subgroup ID', 'placeholder' => 'e.g. Subgroup 1', 'type' => 'Hidden', 'multiple' => 'multiple', 'value' => @$values['quiz_subgroup_id'][$i] ? : $groupIds[$i] ) );
							$categoryFieldset->addElement( array( 'name' => 'quiz_subgroup_question', 'label' => 'Subgroup Question or Instructions', 'placeholder' => 'e.g. Use this information to answer the following questions', 'type' => 'TextArea', 'data-html' => '1', 'multiple' => 'multiple', 'value' => @$values['quiz_subgroup_question'][$i] ? : $groupQuestions[$i] ) );
													
							$i++;
							$categoryFieldset->addLegend( 'Sub-group  <span name="subgroup_counter">' . $i . '</span> of <span name="subgroup_counter_total">' . ( ( count( @$values['quiz_subgroup_id'] ) ? : count( $groupQuestions ) ) ? : 1 ) . '</span>' );			   			
							$questionForm->addFieldset( $categoryFieldset );
						//	self::v( $i );  
						}
						while( isset( $values['quiz_subgroup_id'][$i] ) || isset( $groupQuestions[$i] ) );
						
						
						//	Put the questions in a separate fieldset
						$categoryFieldset = new Ayoola_Form_Element; 
						$categoryFieldset->allowDuplication = false;
					//	$categoryFieldset->placeholderInPlaceOfLabel = true;
						$categoryFieldset->container = 'span';
						
						//	add previous categories if available
						$categoryFieldset->addElement( array( 'name' => 'group_questions', 'type' => 'Html', 'value' => '' ), array( 'html' => $questionForm->view(), 'fields' => 'quiz_subgroup_id,quiz_subgroup_question' ) );
						
						//	Autogenerate group ids
						if( $groupIds )
						{
							foreach( $groupIds as $eachKey => $eachGroup )  
							{
								if( ! $eachGroup )
								{
									$groupIds[$eachKey] = md5( $groupQuestions[$eachKey] );
								}
							}
							$categoryFieldset->addFilter( 'quiz_subgroup_id', array( 'DefiniteValue' => array( $groupIds ) ) );						
						}
					//	var_export( Ayoola_Form::getStorage( 'global_values' )->retrieve() );
				//		var_export( $groupIds );
					//	var_export( $groupQuestions );
				//		var_export( $this->getGlobalValue( 'quiz_subgroup_id' ) );

						//	Add only the last one into the main form
						$form->addFieldset( $categoryFieldset );
					}
					
					//	New fieldset for questions
					$fieldset = new Ayoola_Form_Element;
					$fieldset->hashElementName = $this->hashFormElementName;
					
					
					if( in_array( 'questions_and_answers', $fieldsToEdit ) || ( is_array( $this->getGlobalValue( 'quiz_options' ) ) && in_array( 'edit_questions', $this->getGlobalValue( 'quiz_options' ) ) ) )
				//	if( ! $values || $this->getGlobalValue( 'edit_questions' ) )
					{
						$j = 0; // group count
						
						
				//	var_export( $groupIds );  
					//	do
						//	Separate form for category confirmation
						//	Do this later after questions have been set so the max questions could equal total questions
						$questionConfForm = new Ayoola_Form( array( 'name' => 'categories-conf...' )  );
						$questionConfForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
						$questionConfForm->wrapForm = false;
						while( $j <= count( @$groupIds ) && $j < 9 )//	Do this for all each categories and don't forget the "uncategorized"
						{ 
					//	var_export( $j );
					//	var_export( $groupIds );
							
							//	autogenerate group ids
							if( isset( $groupIds[$j] ) )
							{
								//	Randomly generate IDs for group questions
								@$groupIds[$j] = $groupIds[$j] ? : md5( $groupQuestions[$j] );
							//	@$groupIds[$j] = $groupIds[$j] ? : intval( md5( $groupQuestions[$j] ), 36 );
							}
							else
							{
								//	This is causing infinite loop
							//	$groupIds[$j] = null;
							//	$groupQuestions[$j] = 'Uncategorized Questions';
							}
				//			var_export( $groupIds[$j] );
							
							$i = 0; // question count
							//	Build a separate demo form for the previous group
							$questionForm = new Ayoola_Form( array( 'name' => 'questions...' )  );
							$questionForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true ) );
					//		$form->oneFieldSetAtATime = false;
							$questionForm->wrapForm = false;  
					//		self::v( $groupIds );
							do
							{
								
								//	Put the questions in a separate fieldset
								$questionFieldset = new Ayoola_Form_Element; 
								$questionFieldset->allowDuplication = true;
								$questionFieldset->duplicationData = array( 'add' => '+ Add New Question Below', 'remove' => '- Remove Above Question', 'counter' => 'question_counter' . $j . '', );
								$questionFieldset->container = 'span';
							//	$questionFieldset->wrapper = 'white-content-theme-border';   
								$questionFieldset->wrapper = 'white-background';
						//		self::v( 'quiz_question' . @$groupIds[$j] );
							//	$questionFieldset->addElement( array( 'name' => 'questions_and_answers_break', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '<hr>' ) ) );
								

								$questionFieldset->addElement( array( 'name' => 'quiz_question' . @$groupIds[$j], 'data-html' => '1', 'data-pc-element-whitelist-group' => 'questions_and_answers', 'multiple' => 'multiple', 'rows' => '1', 'label' => ( 'Question <span name="question_counter' . $j . '">' . ( $i + 1 ) . '</span> of <span name="question_counter' . $j . '_total">' . ( count( @$values['quiz_question' . @$groupIds[$j]] ) ? : 1 ) . '</span>' ), 'placeholder' => 'Enter question here...', 'title' => 'Double-Click here to launch the advanced editor', 'type' => 'TextArea', 'value' => @$values['quiz_question' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_question' . @$groupIds[$j], null , $i ) ) );  
							//	$questionFieldset->addFilter( 'quiz_question', array( 'HtmlSpecialChars' => null, ) );
							//	var_export( $this->getGlobalValue( 'quiz_question' . $groupIds[$j], null , $i ) );
												
								//	Option 1
								$questionFieldset->addElement( array( 'name' => 'quiz_option1' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'First Option', 'placeholder' => 'Enter option 1', 'type' => 'TextArea', 'value' => @$values['quiz_option1' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_option1' . @$groupIds[$j], null , $i ) ) );
							//	$questionFieldset->addFilter( 'quiz_option1', array( 'HtmlSpecialChars' => null, ) );
								
								//	Option 2
								$questionFieldset->addElement( array( 'name' => 'quiz_option2' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Second Option', 'placeholder' => 'Enter option 2', 'type' => 'TextArea', 'value' => @$values['quiz_option2' . @$groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_option2' . @$groupIds[$j], null , $i ) ) );
							//	$questionFieldset->addFilter( 'quiz_option2', array( 'HtmlSpecialChars' => null, ) );
								
								//	Option 3
								$questionFieldset->addElement( array( 'name' => 'quiz_option3' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Third Option', 'placeholder' => 'Enter option 3', 'type' => 'TextArea', 'value' => @$values['quiz_option3' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_option3' . @$groupIds[$j], null , $i ) ) );
							//	$questionFieldset->addFilter( 'quiz_option3', array( 'HtmlSpecialChars' => null, ) );
								
								//	Option 4
						//		var_export( 'quiz_option4' . @$groupIds[$j] );  
								$questionFieldset->addElement( array( 'name' => 'quiz_option4' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Fourth Option', 'placeholder' => 'Enter option 4', 'type' => 'TextArea', 'value' => @$values['quiz_option4' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_option4' . @$groupIds[$j], null , $i ) ) );
							//	$questionFieldset->addFilter( 'quiz_option4', array( 'HtmlSpecialChars' => null, ) );
								
								//	Solution
						//		var_export( $values['quiz_answer_notes' . @$groupIds[$j]][$i] );
								$questionFieldset->addElement( array( 'name' => 'quiz_answer_notes' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'data-html' => '1', 'multiple' => 'multiple', 'rows' => '1', 'label' => 'Answer Notes and Workings', 'placeholder' => 'Enter the information that will be displayed to user as the answer workings...', 'type' => 'TextArea', 'value' => @$values['quiz_answer_notes' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_answer_notes' . @$groupIds[$j], null , $i ) ) );
							//	$questionFieldset->addFilter( 'quiz_answer_notes', array( 'HtmlSpecialChars' => null, ) );
								
								//	Correct Answer
								$questionFieldset->addElement( array( 'name' => 'quiz_correct_option' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'multiple' => 'multiple', 'label' => 'Correct Option', 'placeholder' => '', 'type' => 'Select', 'value' => @$values['quiz_correct_option' . $groupIds[$j]][$i] ? : $this->getGlobalValue( 'quiz_correct_option' . @$groupIds[$j], null , $i ) ), array_combine( range( 1, 4 ), range( 1, 4 ) ) );
								
							//	var_export( $_SESSION );
								

								//	We need to save the keys to use later so this information may save in the real fieldset
								
								$i++;
								
						//		$questionFieldset->addLegend( 'Question <span name="question_counter">' . $i . '</span> of <span name="question_counter_total">' . ( count( @$values['quiz_question' . @$groupIds[$j]] ) ? : 1 ) . '</span>' );						  
								$questionForm->addFieldset( $questionFieldset );
							//	self::v( $i );  
						//		var_export( '' . $groupQuestions[$j] . '<br>' );
							}
							while( isset( $values['quiz_question' . @$groupIds[$j]][$i] ) );
						//		var_export( $values['quiz_question' . @$groupIds[$j]] );  
							
							
							//	Put the questions in a separate fieldset
							$questionFieldset = new Ayoola_Form_Element; 
							$questionFieldset->allowDuplication = false;
						//	$questionFieldset->placeholderInPlaceOfLabel = true;
							$questionFieldset->container = 'span';
							
							//	add previous questions if available
							$subGroupHeading = '<p style="font-size:large;">Question Sub Group - #' . ( $j + 1 ) . '</p><p>Sub-group Question/Instruction:</p><p><blockquote>' . ( @$groupQuestions[$j] ? : 'Uncategorized Questions' ) . ' </blockquote> </p>';
							$subGroupHeading = Ayoola_Object_Wrapper_Abstract::wrap( $subGroupHeading, 'white-content-theme-border' );
					//		self::v( $subGroupHeading );
					//		exit();
							$totalQuestionCount = @$totalQuestionCount ? : 0;
							$questionCount = count( $this->getGlobalValue( 'quiz_question' . @$groupIds[$j] ) ? : array() );
							$totalQuestionCount += $questionCount;
						//	self::v( strval( $questionCount ) );
						//	self::v( $totalQuestionCount );
						//	self::v( 'question_count' . @$groupIds[$j] );
							
							$questionFieldset->addElement( array( 'name' => 'question_count' . @$groupIds[$j], 'data-pc-element-whitelist-group' => 'questions_and_answers', 'label' => '', 'type' => 'Hidden', 'value' => null ) );
							$questionFieldset->addFilter( 'question_count' . @$groupIds[$j], array( 'DefiniteValue' => $questionCount ) );

							$questionElementList = 'quiz_question' . @$groupIds[$j] . ',quiz_option1' . @$groupIds[$j] . ',quiz_option2' . @$groupIds[$j] . ',quiz_option3' . @$groupIds[$j] . ',quiz_option4' . @$groupIds[$j] . ',quiz_correct_option' . @$groupIds[$j] . ',quiz_answer_notes' . @$groupIds[$j];
							$questionFieldset->addElement( array( 'name' => 'questions_and_answers', 'data-pc-element-whitelist-group' => 'questions_and_answers', 'type' => 'Html', 'value' => '' ), array( 'html' => ( $subGroupHeading . $questionForm->view() ), 'parameters' => array( 'data-pc-element-whitelist-group' => 'questions_and_answers' ), 'fields' => $questionElementList ) );
							
	/* 						if( in_array( 'questions_and_answers', $fieldsToEdit ) )
							{
								//	Add the following to the elements whitelist
								$questionElementList = array_map( 'trim', explode( ',', $questionElementList . ',question_count' . @$groupIds[$j] ) );
								$fieldsToEdit = array_merge( $fieldsToEdit, $questionElementList );
							//	self::v( $fieldsToEdit );
							}
	*/						
	/* 						$questionFieldset->addFilter( 'quiz_question' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_option1' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_option2' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_option3' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_option4' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_answer_notes' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
							$questionFieldset->addFilter( 'quiz_correct_option' . @$groupIds[$j], array( 'HtmlSpecialChars' => null, ) );
	*/						
						//	var_export( $_SESSION );
							
							//	Add only the last one into the main form
							$form->addFieldset( $questionFieldset );
					//		$categoryName = array_shift( $groupIds );
					

							//	Do this later after questions have been set so the max questions could equal total questions
							$categoryFieldset2 = new Ayoola_Form_Element; 
							$categoryFieldset2->container = 'span';
					//		$categoryFieldset2->addLegend( 'Review of Questions and Categories' );			   			
							$categoryFieldset2->addElement( array( 'name' => 'quiz_subgroup_question_demo', 'label' => 'Number of questions to pick from this category (' . count( $this->getGlobalValue( 'quiz_question' . @$groupIds[$j] ) ) . ' total questions set)', 'placeholder' => 'e.g. Use this information to answer the following questions', 'type' => 'TextArea', 'data-html' => '1', 'style' => 'display:block;', 'multiple' => 'multiple', 'disabled' => 'disabled', 'value' => ( @$values['quiz_subgroup_question'][$j] ? : @$groupQuestions[$j] ) ? : 'Uncategorized Questions' ) );
							$categoryFieldset2->addElement( array( 'name' => 'quiz_subgroup_question_max', 'label' => ' ', 'placeholder' => '', 'style' => '', 'type' => 'Select', 'multiple' => 'multiple', 'value' => @$values['quiz_subgroup_question_max'][$j] ? : count( $this->getGlobalValue( 'quiz_question' . @$groupIds[$j] ) ) ), array_combine( range( 0, count( $this->getGlobalValue( 'quiz_question' . @$groupIds[$j] ) ) ),  range( 0, count( $this->getGlobalValue( 'quiz_question' . @$groupIds[$j] ) ) ) ) );
							$questionConfForm->addFieldset( $categoryFieldset2 );    
					
							$j++;
						}
						
						//	Put the questions in a separate fieldset
						$questionConfFieldset = new Ayoola_Form_Element; 
						$questionConfFieldset->allowDuplication = false;
						$questionConfFieldset->container = 'span';
						
						//	time
						$questionConfFieldset->addElement( array( 'name' => 'quiz_time', 'placeholder' => 'e.g. 900', 'label' => 'Maximum Test Time (in secs)', 'type' => 'InputText', 'value' => @$values['quiz_time'] ) );
						$questionConfFieldset->addFilter( 'quiz_time', array( 'Int' => null ) );
						
						//	Now lets review category questions
						$questionConfFieldset->addElement( array( 'name' => 'previous_forms', 'type' => 'Html', 'value' => '' ), array( 'html' => ( '<h3>How many questions per test per subgroup?</h3>' . $questionConfForm->view() ), 'fields' => 'quiz_subgroup_question_max' ) );
						
						$questionConfFieldset->addElement( array( 'name' => 'total_question_count', 'data-pc-element-whitelist-group' => 'questions_and_answers', 'type' => 'Hidden', 'value' => null ) );
						$questionConfFieldset->addFilter( 'total_question_count', array( 'DefiniteValue' => $totalQuestionCount ) );
						
						$questionConfFieldset->addElement( array( 'name' => 'total_question_displayed', 'data-pc-element-whitelist-group' => '', 'type' => 'Hidden', 'value' => null ) );
						$questionConfFieldset->addFilter( 'total_question_displayed', array( 'DefiniteValue' => array_sum( $this->getGlobalValue( 'quiz_subgroup_question_max' ) ) ) );
	/* 					if( in_array( 'questions_and_answers', $fieldsToEdit ) )
						{
							//	Add the following to the elements whitelist
							$fieldsToEdit[] = 'total_question_count';
						//	self::v( $fieldsToEdit );
						}
	*/					
					//	self::v( $totalQuestionCount );
						//	Review Questions and Set  
						$form->addFieldset( $questionConfFieldset );  
					//	while( $j <= count( $groupIds ) );
					}
				break;
				case 'product':
				case 'service':
				case 'subscription':
					$fieldset->addElement( array( 'name' => 'item_old_price', 'label' => 'Old price', 'placeholder' => '0.00', 'type' => 'InputText', 'value' => @$values['item_old_price'] ) );
					$fieldset->addElement( array( 'name' => 'item_price', 'label' => 'Current price', 'placeholder' => '0.00', 'type' => 'InputText', 'value' => @$values['item_price'] ) );
					$fieldset->addElement( array( 'name' => 'no_of_items_in_stock', 'type' => 'InputText', 'value' => @$values['no_of_items_in_stock'] ) );   
			//		$fieldset->addElement( array( 'name' => 'call_to_action', 'placeholder' => 'e.g. Add to Cart', 'type' => 'InputText', 'value' => @$values['call_to_action'] ) );
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
	/*					$i = 0;
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
							$product->addElement( array( 'name' => 'option_name', 'multiple' => 'multiple', 'placeholder' => 'Enter option name here...', 'type' => 'InputText', 'value' => @$values['option_name' . $groupIds[$j]][$i] ) );
							$product->addElement( array( 'name' => 'option_price', 'multiple' => 'multiple', 'placeholder' => 'Enter option price here...', 'type' => 'InputText', 'value' => @$values['option_price' . $groupIds[$j]][$i] ) );
											
							$product->addLegend( 'Product options (if available)' );						
							$productForm->addFieldset( $product );
							$i++;
						//	self::v( $i );  
						}
						while( ! empty( $values['option_name' . @$groupIds[$j]][$i] ) );
						
				//		if( @$values['quiz_question'] )
						{
							//	add previous questions if available
							$fieldset->addElement( array( 'name' => 'previous_forms', 'type' => 'Html', 'value' => '' ), array( 'html' => $productForm->view(), 'fields' => 'option_name,option_price' ) );
							
						}
	*/					//	Add only the last one into the main form
					//	$form->addFieldset( $product );   
					
				break;
				case 'multi-price':
				//	var_export( $postTypeInfo );
					$i = 0;
					do
					{
						$fieldsetX = new Ayoola_Form_Element; 
						$fieldsetX->hashElementName = false;
						$fieldsetX->duplicationData = array( 'add' => 'New Option', 'remove' => 'Remove Option', 'counter' => 'pricing_option_counter', );

						$fieldsetX->container = 'div';
						$form->wrapForm = false;
						$fieldsetX->addElement( array( 'name' => 'price_option_title', 'style' => 'max-width: 40%;', 'label' => '', 'placeholder' => 'Option Name', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @htmlspecialchars( $values['price_option_title'][$i] ) ) );
						$fieldsetX->addElement( array( 'name' => 'price_option_price', 'style' => 'max-width: 40%;', 'label' => '', 'placeholder' => 'Separate Option Price', 'type' => 'InputText', 'multiple' => 'multiple', 'value' => @$values['price_option_price'][$i] ) );
						$fieldsetX->allowDuplication = true;  
						$fieldsetX->placeholderInPlaceOfLabel = true;
						$fieldsetX->wrapper = 'white-content-theme-border';  
						$i++;
						$fieldsetX->addLegend( 'Pricing Option <span name="pricing_option_counter">' . $i .  '</span>' );
						$form->oneFieldSetAtATime = false;   
						$form->addFieldset( $fieldsetX );
					}
					while( ! empty( $values['price_option_title'][$i] ) || ! empty( $values['price_option_price'][$i] ) );
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
					//	video
					$fieldset->addElement( array( 'name' => 'video_url', 'type' => 'InputText', 'value' => @$values['video_url'] ) );
				break;
				case 'link':
					//	link
					$fieldset->addElement( array( 'name' => 'link_url', 'type' => 'InputText', 'value' => @$values['link_url'] ) );
				break;
				case 'date':
				case 'datetime':
				case 'event':
				case 'events':  
					//	event
					//	retrieve birthday
					if( @$values['date'] )
					{
						switch( strlen( $values['date'] ) )
						{
							case 8:
								$values['year'] = $values['date'][0] . $values['date'][1] . $values['date'][2] . $values['date'][3];
								$values['month'] = $values['date'][4] . $values['date'][5];
								$values['day'] = $values['date'][6] . $values['date'][7];
							break;
							default:
								@list( $values['year'], $values['month'], $values['day'] ) = explode( '-', $values['date'] );
							break; 
						}
					}
				//	self::v( $values['date'] );       
					
					//	Month
					$options = array_combine( range( 1, 12 ), array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ) );
					$birthMonthValue = intval( @strlen( $values['month'] ) === 1 ? ( '0' . @$values['month'] ) : @$values['month'] );
					$birthMonthValue = intval( $birthMonthValue ?  : $this->getGlobalValue( 'month' ) );
				//	var_export( $birthMonthValue );
				//	var_export( $this->getGlobalValue( 'month' ) );
					$fieldset->addElement( array( 'name' => 'month', 'label' => $postTypeLabel . ' Date', 'style' => 'min-width:0px;width:100px;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthMonthValue ), array( 'Month' ) + $options ); 
					$fieldset->addRequirement( 'month', array( 'InArray' => array_keys( $options ) ) );
					if( strlen( $this->getGlobalValue( 'month' ) ) === 1 )
					{
						$fieldset->addFilter( 'month', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'month' ) ) );
					}
					
					//	Day
					$options = range( 1, 31 );
					$options = array_combine( $options, $options );
					$birthDayValue = intval( @strlen( $values['day'] ) === 1 ? ( '0' . @$values['day'] ) : @$values['day'] );
					$birthDayValue = intval( $birthDayValue ?  : $this->getGlobalValue( 'day' ) );
					$fieldset->addElement( array( 'name' => 'day', 'label' => '', 'style' => 'min-width:0px;width:100px;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthDayValue ), array( 'Day' ) +$options );
					$fieldset->addRequirement( 'day', array( 'InArray' => array_keys( $options ) ) );
					if( strlen( $this->getGlobalValue( 'day' ) ) === 1 )
					{
						$fieldset->addFilter( 'day', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'day' ) ) );
					}
					
					//	Year
					//	10 years and 10 years after todays date
					$options = range( date( 'Y' ) + 10, date( 'Y' ) - 10 );
					$options = array_combine( $options, $options );
					$fieldset->addElement( array( 'name' => 'year', 'label' => '', 'style' => 'min-width:0px;width:100px;display:inline-block;margin-right:0;', 'type' => 'Select', 'value' => @$values['year'] ? : date( 'Y' ) ), array( 'Year' ) + $options );
					$fieldset->addRequirement( 'year', array( 'InArray' => array_keys( $options ) ) );
					$options = range( 0, 23 );
					foreach( $options as $key => $each )
					{
						if( strlen( $options[$key] ) < 2 )  
						{
							$options[$key] = '0' . $options[$key];
						}
					}
					$fieldset->addElement( array( 'name' => 'time_hour', 'label' => ' ', 'style' => 'min-width:0px;width:100px;', 'type' => 'Select', 'value' => @$values['time_hour'] ), array( 'Hour' ) +  array_combine( $options, $options ) );
					$fieldset->addRequirement( 'time_hour', array( 'InArray' => array_keys( $options ) ) );
					$options = range( 0, 59 );
					foreach( $options as $key => $each )
					{
						if( strlen( $options[$key] ) < 2 )    
						{
							$options[$key] = '0' . $options[$key];
						}
					}
					$fieldset->addElement( array( 'name' => 'time_minutes', 'label' => ' ', 'style' => 'min-width:0px;width:100px;', 'type' => 'Select', 'value' => @$values['time_minutes'] ), array( 'Minute' ) + array_combine( $options, $options ) );
					$fieldset->addRequirement( 'time_minutes', array( 'InArray' => array_keys( $options ) ) );

			//		$fieldset->addElement( array( 'name' => 'host', 'label' => $postTypeLabel . ' Host', 'type' => 'InputText', 'value' => @$values['host'] ) );
					
					//	datetime combined
					$fieldset->addElement( array( 'name' => 'datetime', 'label' => 'Timestamp', 'placeholder' => 'YYYY-MM-DD HH:MM', 'type' => 'Hidden', 'value' => @$values['datetime'] ) );
					$datetime = $this->getGlobalValue( 'year' );
					$datetime .= '-';
					$datetime .= strlen( $this->getGlobalValue( 'month' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'month' ) ) : $this->getGlobalValue( 'month' );
					$datetime .= '-';
					$datetime .= strlen( $this->getGlobalValue( 'day' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'day' ) ) : $this->getGlobalValue( 'day' );
					$datetime .= ' ';
					$datetime .= strlen( $this->getGlobalValue( 'time_hour' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'time_hour' ) ) : $this->getGlobalValue( 'time_hour' );
					$datetime .= ':';
					$datetime .= strlen( $this->getGlobalValue( 'time_minutes' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'time_minutes' ) ) : $this->getGlobalValue( 'time_minutes' );
					$fieldset->addFilter( 'datetime', array( 'DefiniteValue' => $datetime ) );
	//			break;
				case 'location':

					$fieldset->addElement( array( 'name' => 'address', 'label' => $postTypeLabel . ' Address', 'placeholder' => 'e.g. Jogor Centre', 'type' => 'InputText', 'value' => @$values['address'] ) );  
					$fieldset->addElement( array( 'name' => 'city', 'label' => $postTypeLabel . ' City', 'placeholder' => 'e.g. Ibadan', 'type' => 'InputText', 'value' => @$values['city'] ) );
					$fieldset->addElement( array( 'name' => 'province', 'label' => $postTypeLabel . ' - State, Province or Region', 'placeholder' => '', 'type' => 'InputText', 'value' => @$values['province'] ) );
		//			$fieldset->addElement( array( 'name' => 'ante', 'type' => 'InputText', 'value' => @$values['ante'] ) );
			//		$form->setParameter( array( 'requirements' => 'address,' . $form->getParameter( 'requirements' ) ) );
			//		$form->setParameter( array( 'address_fieldset_legend' => 'Event Location'  ) );   
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
												'require_user_info' => 'Require log-in before download', 
												'download_notification' => 'Notify me on every download', 
										//		'version' => 'This document has a version information', 
								//				'password' => 'Require a password to download ' . $postTypeLabel . '', 
										//		'private' => 'This document is private. Do not allow the public to have access to it.', 
										//		'premium' => 'This is a premium file. Users are charged before they can download.', 
											);
					$fieldset->addElement( array( 'name' => 'download_options', 'label' => '' . $postTypeLabel . ' Options', 'type' => 'Checkbox', 'value' => @$values['download_options'] ), $downloadOptions  );
					
			//		if( strlen( @$values['download_base64'] ) < 1999999 )
					{
			//			$fieldset->addElement( array( 'name' => 'download_base64', 'type' => 'Hidden', 'value' => @$values['download_base64'] )  );
					}
			//		$name = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'download_url' ) : 'download_url' );
				//	$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . $name;
				//	$fieldset->addElement( array( 'name' => 'html_xx', 'type' => 'Html', 'value' => '' ), array( 'html' => '<br><input onClick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' ); return true;" type=\'button\' value="Browse Site..." /> <input onClick="ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.fieldName = \'' . $name . '\'; ayoola.image.clickBrowseButton( { accept: \'\' } );" type=\'button\' value="Browse Device..." />', 'fields' => '' ) );
				//	$fieldset->addElement( array( 'name' => 'xxxx_html', 'value' => '' ) );
					$fieldset->addElement( array( 'name' => 'download_url', 'label' => 'Download File', 'placeholder' => 'e.g. http://example.com/path/to/file.mp3', 'type' => 'Document', 'optional' => 'optional', 'value' => @$values['download_url'] ) );
				//	$fieldset->addRequirement( 'download_url', array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => $this->getParameter( 'allowed_extensions' ) ? explode( ',', $this->getParameter( 'allowed_extensions' ) ) : null ) ) );
	//				$fieldset->addElement( array( 'name' => 'download_base64', 'label' => ' ', 'data-allow_base64' => true, 'type' => 'Document', 'data-previous-url' => '' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/document-8x.png', 'autocomplete' => 'off', 'value' => null ) );
					

					//	For security reasons, only admins can do this.
					if( Ayoola_Abstract_Table::hasPriviledge() )
					{ 
			//			$fieldset->addElement( array( 'name' => 'download_path', 'type' => 'InputText', 'value' => @$values['download_path'] ) );
					}
	/* 				if( @$values['download_version'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'version', Ayoola_Form::getGlobalValue( 'download_options' ) ) )  
					{
						$fieldset->addElement( array( 'name' => 'download_version', 'type' => 'InputText', 'value' => @$values['download_version'] ) );
					}		
	*/				if( @$values['download_password'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'password', Ayoola_Form::getGlobalValue( 'download_options' ) ) )
					{
						$fieldset->addElement( array( 'name' => 'download_password', 'label' => 'Download Password (Optional)', 'type' => 'InputPassword', 'value' => @$values['download_password'] ) );
					}		
				//	if( @$values['premium'] || is_array( Ayoola_Form::getGlobalValue( 'download_options' ) ) && in_array( 'premium', Ayoola_Form::getGlobalValue( 'download_options' ) ) )
					{
					//	$fieldset->addElement( array( 'name' => 'item_price', 'label' => 'How much should the ' . $postTypeLabel . ' cost? Leave blank for free downloads', 'placeholder' => '0.00', 'type' => 'InputText', 'value' => @$values['item_price'] ) );
				//		$fieldset->addElement( array( 'name' => 'item_old_price', 'label' => 'Compare price', 'type' => 'InputText', 'value' => @$values['item_old_price'] ) );
				//		$fieldset->addFilter( 'item_price', array( 'Currency' => null ) );
				//		$fieldset->addFilter( 'item_old_price', array( 'Currency' => null ) );
				///		$fieldset->addRequirement( 'item_price', array( 'WordCount' => array( 1,20 ) ) );
				//		$fieldset->addRequirement( 'item_old_price', array( 'WordCount' => array( 1,20 ) ) );
					}		
				break;
				default:
					
				break;
			}
		}
		if( ! empty( $postTypeInfo['post_type_options'] ) && in_array( 'multi-price', $postTypeInfo['post_type_options'] ) )
		{
			
		}

		//	Inject other form fields
		foreach( static::$_otherFormFields as $eachField )
		{
			$fieldset->addElement( array( 'name' => $eachField, 'type' => 'Hidden', ) );
		}
		
//		$fieldset->addRequirement( 'article_title', array( 'WordCount' => array( 6,200 ) ) );
//		$fieldset->addRequirement( 'article_description', array( 'WordCount' => array( 0, 500 ) ) );
		$fieldset->addLegend( 'Other information' );
		$form->addFieldset( $fieldset ); 
		
		//	Next Level
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = $this->hashFormElementName;
		//	Use tiny editor

		static::initHTMLEditor();


	//	var_export( $values['true_post_type'] );
		if( $values['true_post_type'] == 'article' || $values['true_post_type'] == 'post' || @$values['article_content'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'article', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) || ( is_array( $values['article_options'] ) && in_array( 'article', $values['article_options'] ) ) || $values['article_type'] == 'article' || $values['article_type'] == 'post' || in_array( 'article', $internalForms )  )       
		{
			$fieldset->addElement( array( 'name' => 'article_content', 'data-html' => '1', 'label' => '' . $postTypeLabel . ' write up (Article)', 'rows' => '10', 'placeholder' => 'Enter content here...', 'type' => 'TextArea', 'value' => @$values['article_content'] ) );
	//		$fieldset->addRequirement( 'article_content', array( 'WordCount' => array( 0,10000000 ) ) );
	
		}
		if( @$values['article_tags'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'keywords', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) )
		{
			$fieldset->addElement( array( 'name' => 'article_tags', 'label' => '' . $postTypeLabel . ' Tags', 'placeholder' => 'Enter tags for this ' . $postTypeLabel . ' separated by comma', 'type' => 'InputText', 'value' => @$values['article_tags'] ) );
	
		}
		//	Categories
		$table = Application_Category::getInstance();
		
		//	Now allowing users to create their own personal categories
		
		//	Get information about the user access information
		$userInfo = Ayoola_Access::getAccessInformation();
		$categories = array();
		if( ! empty( $userInfo['post_categories_id'] ) && ! empty( $userInfo['post_categories'] ) )
		{
			$categories['My Categories'] = array_combine( $userInfo['post_categories_id'], $userInfo['post_categories'] );  
		}
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		if( ! empty( $articleSettings['allowed_categories'] ) )
		{
			if( $categories['General Site Categories'] = $table->select( null, array( 'category_name' => $articleSettings['allowed_categories'] ) ) )
			{
				foreach( $categories['General Site Categories'] as $key => $value )
				{
					if( ! $categories['General Site Categories'][$key]['category_label'] )
					{
						$categories['General Site Categories'][$key]['category_label'] = $categories['General Site Categories'][$key]['category_name'];        
					}
				//	var_export( array( 'parent_category' => $categories['General Site Categories'][$key]['category_name'] ) );
					if( $inner = $table->select( null, array( 'parent_category' => $categories['General Site Categories'][$key]['category_name'] ) ) )
					{
						$categories[$categories['General Site Categories'][$key]['category_label']] = $filter->filter( $inner );
					}
				//	var_export( $inner );
				}
				$categories['General Site Categories'] = $filter->filter( $categories['General Site Categories'] );
			}
			else
			{
				unset( $categories['General Site Categories'] );
			}
		}
		elseif( $categories['General Site Categories'] = $table->select() )
		{
			$categories['General Site Categories'] = $filter->filter( $categories['General Site Categories'] );
		}
	//	var_export( $articleSettings );
 		$addCategoryLink = ( '<a rel="spotlight;changeElementId=' . get_class( $this ) . '" title="Add new Category" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Access_AccessInformation_Editor?pc_profile_info_to_edit=post_categories">My categories</a>' );     
		if( self::hasPriviledge() )
		{
			$addCategoryLink .= ( ' |  <a rel="spotlight;changeElementId=' . get_class( $this ) . '" title="Add new Category" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Articles/">Site categories</a>' );
		}
		if( isset( $_GET['category_name'] ) )
		{
		//	$presetCategory = $_GET['category_name'];
			if( is_string( $_GET['category_name'] ) )
			{
				$_GET['category_name'] = array_map( 'trim', explode( ',', $_GET['category_name'] ) );
				$_REQUEST['category_name'] = $_GET['category_name'];
			}
		}
		$fieldset->addElement( array( 'name' => 'category_name', 'label' => 'Categories ' . $addCategoryLink, 'type' => 'SelectMultiple', 'value' => @$values['category_name'] ? : @$_GET['category_name']  ), $categories ? : array() );
		{
			$options = array( 0 => 'Public', 97 => 'Private (Invited viewers only)', 98 => 'Only Me' );
		}
		$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Privacy', 'type' => 'Select', 'value' => @$values['auth_level'] ? : array_keys( $options ) ), $options );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $options ) ) );
	//	$fieldset->addRequirement( 'article_title', array( 'UserRestrictions' => null ) );
		if( @$values['requirement_name'] || ( is_array( Ayoola_Form::getGlobalValue( 'article_options' ) ) && in_array( 'requirement', Ayoola_Form::getGlobalValue( 'article_options' ) ) ) )
		{
			$options = Ayoola_Form_Requirement::getInstance();
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
				$fieldset->addElement( array( 'name' => 'article_requirements', 'label' => 'Select information required from viewers of this ' . $postTypeLabel . ' (advanced)', 'type' => 'Checkbox', 'value' => @$values['article_requirements'] ), $options );
				
			//	$fieldset->addRequirement( 'article_requirements', array( 'InArray' => array_keys( $options )  ) );
			}
	
		}
		
		
		//	Publish
//		$options = array( 'No', 'Yes' );
		//	retain this because we need to bring this up in custom forms
	//	$fieldset->addElement( array( 'name' => 'publish', 'type' => 'submit', 'value' => 'Continue...' ) );
	//	$fieldset->addElement( array( 'name' => 'publish', 'type' => 'Hidden', 'value' => null ) );
		$fieldset->addElement( array( 'name' => 'user_restrictions', 'type' => 'Hidden', 'value' => null ) );
		$fieldset->addRequirement( 'user_restrictions', array( 'UserRestrictions' => null ) );
	

		$fieldset->addFilters( array( 'trim' => null, 'FormatArticle' => null ) );
		$form->addFieldset( $fieldset ); 
	//	self::v( $fieldsToEdit );
		$form->setParameter( array( 'element_whitelist' => $fieldsToEdit ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
