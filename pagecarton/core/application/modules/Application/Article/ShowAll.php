<?php
/**
 * PageCarton Content Management System
 * 
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $  
 */

/** 
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';   


/**
 * @category   PageCarton CMS
 * @package    Application_Article_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_ShowAll extends Application_Article_Abstract  
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );	
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Posts';      

    /**
     * The Options Available as a Viewable Object
     * This property makes it possible to use this same class
     * To serve all menu available on the site
     * 
     * @var array
     */
	protected $_classOptions;

    /**
     * The xml string
     * 
     * @var string
     */
	protected $_xml;

    /**
     * Use this to detect the instance of this class for unique pagination
     * 
     * @var int
     */
	protected static $_listCounter = 0;
	
    /**
     * Module files directory namespace
     * 
     * @var string
     */
	protected static $_newPostUrl;	
	
    /**
     * 
     * 
     */
	public static function getItemName()
    {
		return static::$_itemName;
	}
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( $this->getParameter( 'template_name' ) )
			{
			//	self::v( $this->getParameter( 'template_name' ) );
				$options = Application_Article_Template::getInstance();
				if( $options = $options->selectOne( null, array( 'template_name' => $this->getParameter( 'template_name' ) ) ) )
				{
					
				}
			//	markup_template_namespace
				if( $this->getParameter( 'max_group_no' ) )
				{
					//	allow us to override max_group_no
					unset( $options['max_group_no'] );
				}
				$this->setParameter( ( $options ? : array() ) + array(  'markup_template_no_cache' => true, 'markup_template_namespace' => $this->getParameter( 'template_name' ) . $this->getParameter( 'markup_template_namespace' ) ) );
				if( @$options['javascript_files'] )
				{
					foreach( $options['javascript_files'] as $each )
					{
						Application_Javascript::addFile( $each );
					}
				}
				if( @$options['javascript_code'] )
				{
				//	$options['javascript_code'] = self::replacePlaceholders( $options['javascript_code'], $data + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
					Application_Javascript::addCode( $options['javascript_code'] );
				}
				if( @$options['css_files'] )
				{
					foreach( @$options['css_files'] as $each )
					{
						Application_Style::addFile( $each );
					}
				}
				if( @$options['css_code'] )
				{
					Application_Javascript::addCode( $options['css_code'] );
				}
			}
			@$this->_parameter['content_to_clear_internal'] .= '
			<p></p>
			<span class="reducedfrom"></span>
			<span class="pc_posts_option_items" style="text-decoration:line-through;" ></span>
			<div class="sale-box1"><span style="border-bottom: 0;" class="on_sale title_shop pc-bg-color "></span></div>
			<div class="price-number"><p><span class="rupees"></span></p></div>
			';
		//	var_export( 'ewe' );
			try
			{
			//	var_export( $this->getParameter() );
				switch( @$_GET['post'] )
				{
					default:
						$this->show();
				//		var_export( $this->getMarkupTemplate() );
				//		var_export( $this->_parameter['markup_template'] );
					break;
				}
		//		echo $this->getViewContent();
			}
			catch( Application_Article_Exception $e )
			{ 
				$this->_parameter['markup_template'] = null;
				$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
			//	return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
			}
			catch( Exception $e )
			{ 
				$this->_parameter['markup_template'] = null;
				$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
			//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
			}
		}
		catch( Exception $e )
		{
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
		}
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Returns markup for link for new post buildQueryForRequestedPosts
     * 
     * @return string
     */
//	public function getLinkForNewPost()
	public function buildQueryForRequestedPosts( $link )
    {
	//	$link = self::getPostUrl() . '/post/creator/?';
		$categoryId = @$_GET['category'] ? : $this->getParameter( 'category' );  
		$categoryId = $this->getParameter( 'category_name' ) ? : $categoryId;
		$articleInfo = array();
		if( is_string( $this->getParameter( 'article_types' ) ) )
		{
		//	var_export( $this->getParameter( 'article_types' ) );
			$articleInfo += array( 'article_type' => $this->getParameter( 'article_types' ) );
		}
		if( $categoryId )
		{
			$articleInfo += array( 'category' => $categoryId );
		}
		$link .= http_build_query( $articleInfo );  
	//	var_export( $this->getParameter() );
	//	var_export( $link );
		return $link;
	}
	
    /**
     * Display the real list
     * 
     * @return
     */
	public function showMessage()
    {
	//	$this->_parameter['markup_template'] = null;
		if( empty( $_GET['pc_post_list_id'] ) )
		{
			$message = array_pop( $this->_badnews ) ? : 'Posts will be displayed here when they become available.';
			$message = $this->getParameter( 'badnews' ) ? : $message;
		}
		else
		{
			$this->_parameter['markup_template'] = null; 
			$this->_parameter['markup_template_no_data'] = null; 
			$message = 'No more items.';
		}
		
	//	$message = $this->getParameter( 'badnews' ) ? : null;
	//	if( ! $values )
		{
			//	switch templates off
	//		$this->_parameter['markup_template'] = null; 
		}
		
		$this->setViewContent( '<p style="clear: both;" class="pc-notify-info pc_no_post_to_show"> ' . $message . ' ' . self::getQuickLink() . '</p>', true );
	//	$message ? $this->setViewContent( ' ' . $message . ' ', true ) : null;
		
		//	Check settings
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' ); 

		//	Only allowed users can write
		if( self::hasPriviledge( @$articleSettings['allowed_writers'] ? : 98 ) && $this->getParameter( 'add_a_new_post' ) )
		{
			$addPostMessage = is_numeric( $this->getParameter( 'add_a_new_post' ) ) ? 'Create a new post' : $this->getParameter( 'add_a_new_post' );
		}
		else
	//	if( ! $values )
		{
			//	switch templates off
			$this->_parameter['markup_template'] = null; 
		}
	}
	
    /**
     * Display the real list
     * 
     * @return
     */
	public function show()
    {
 		if( ! $this->getDbData() )
		{ 
			$this->showMessage();
	//		return;
		}
 	//	var_export( $this->getDbData() );
 	//	var_export( self::getXml() );
		$this->setViewContent( self::getXml() );   
    } 
	
    /**
     * Returns the Xml
     * 
     * @return string
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * 
     * 
     * @return array
     */
	public function retrieveArticleData( $data )
    {
		//	self::v( $data );
			
		//	Allow injection of data
		switch( @$data['article_url'] )
		{
/* 			case $this->buildQueryForRequestedPosts( self::getPostUrl() . '/post/creator/?' ):
				
			break;
 */			default:
				if( is_array( $data ) )
				{
					$data = array_merge( $data ? : array(),  $this->getParameter( 'data_to_merge' ) ? : array() );
				}
				if( is_array( $data ) && empty( $data['allow_raw_data'] ) )
				{ 
			//		var_export( $data );
					$data = $data['article_url']; 
				}
				
				//	Module can now send full path
				//	$path = $data;
				if( ! is_array( $data ) )
				{
					
					$data = self::loadPostData( $data );
				}
			break;
		}
		return $data;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		self::$_listCounter++;
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' ); 		
		
		$this->_xml = '';
		$allTemplate = '';
	//	cache_timeout
		if( ! empty( $_GET['pc_post_list_id'] ) )
		{
			$postListId = $_GET['pc_post_list_id'];
		}
		else
		{
			$postListId = 'pc_post_list_' . md5( serialize( $_GET ) . 'x-----==-.---' . serialize( $this->getParameter() ) );
		}
	//	var_export( $_GET );
//		var_export( $postListId );
//		var_export( $postListId );
		//			self::v( $postListId  );   

		$storage = self::getObjectStorage( array( 'id' => $postListId, 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 44600, ) );
	//	$storage = new Ayoola_Storage( array( 'id' => $postListId, 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 44600, ) );
		$storedValues = $storage->retrieve();
//		self::v( $storage  );   

//		var_export( $storedValues );
		if( ! empty( $storedValues['parameter'] ) && ! empty( $_GET['pc_post_list_autoload'] ) )
		{	
		//	var_export( $storedValues['parameter'] );

			//	Bring out stored parameters
			$this->setParameter( $storedValues['parameter'] );
		}
		//	Prepare post viewing for next posts
		
		//	Using menu template?
		//	autoload new posts
//		self::v( $postListId );
//		self::v( $storedValues );
		$values = array();

		if( ! empty( $storedValues['values'] )  )
		{
			$values = $storedValues['values'];
		}

	//	if( ! $storedValues || ! $this->getParameter( 'cache_post_list' ) )
		if( ! $this->getParameter( 'cache_post_list' ) )
		{   
		//	var_export( $values );    
	//		self::v( $this->getParameter() );       
	//		self::v( $this->getParameter() );       
		//	self::v( $postListId );

			//	This ensures that data altered by query strings is uploaded when autoloaded
			if( empty( $_REQUEST['pc_post_list_autoload'] ) || empty( $_REQUEST['pc_post_list_id'] )  )
			{
				$values = $this->getDbData();
			}
		//	self::v( $values );
			
		//	var_export( $this->getParameter( 'sort_column' ) );
			if( $this->getParameter( 'sort_column' ) )
			{   
				
		//	var_export( $values );
		//	var_export( $this->getParameter( 'sort_column' ) );
				$values = self::sortMultiDimensionalArray( $values, $this->getParameter( 'sort_column' ) );
		//	self::v( $this->getParameter( 'sort_column' ) );
		//	self::v( $values );
			}
			else
			{   
				
		//	var_export( $values );
		//	var_export( $this->getParameter( 'sort_column' ) );
				if( $values )
				{
					$sortColumn = @$values[0]['profile_creation_date'] ? 'profile_creation_date' : 'article_creation_date';
			//		var_export( $sortColumn );
					$values = self::sortMultiDimensionalArray( $values, $sortColumn );
					$values = array_reverse( $values );
				}
			}			
	//
	
	//		var_export( $values );
	//		self::v( $values );
			
			//	sort
	//		if( $this->getParameter( 'sort_column' ) )
			{ 
				$previousKey = null;
			//	$singlePostPaginationInfo = array();
				foreach( $values as $key => $data )
				{
				//	var_export( $data );
			//		var_export( $data );
			//		self::v( $data );
					unset( $values[$key] );

					//	quick fix for older posts that the dates were not set in the table
					if( is_array( $data ) && empty( $data['article_creation_date'] ) && empty( $data['profile_modified_date'] ) && ! empty( $data['article_url'] ) )
					{
						if( $data = $this->retrieveArticleData( $data ) )
						{
							$class = Application_Article_Table::getInstance();
							$class->update( $data, array( 'article_url' => $data['article_url'] ) );
						}			
					}
					static::sanitizeData( $data );
					if( ! $data )
					{
						continue;
					}
			//		var_export( $data );
		//			$oldData = $data;
			//		$dataX = $this->retrieveArticleData( $data );
			//		self::v( $data );
					if( ! empty( $data['true_post_type'] ) || empty( $data['not_real_post'] ) )
					{
						if( ! $dataX = $this->retrieveArticleData( $data ) )
						{
							continue;
						}

						//	Some old posts does have titles in the table
						if( empty( $data['article_title'] ) && ! empty( $dataX['article_title'] ) )
						{
							$class = Application_Article_Table::getInstance();
							$class->update( $dataX, array( 'article_url' => $data['article_url'] ) );
							$data = $dataX;
						}
						$data += $dataX;
					}
					
		//			self::v( $data );
//					var_export( $data['article_type'] );
					

					if( ! is_array( $data ) || ! self::isAllowedToView( $data ) )    
					{
					//	self::v( @$data['auth_level'] );
					//	self::v( self::hasPriviledge( @$data['auth_level'] ) );
					//	self::v( $data['publish'] );
					//	self::v( self::isAllowedToView( $data ) );
				//	self::v( $data );
						continue;
					//	self::setIdentifierData( $data );
					}
					$data['post_list_id'] = $postListId;
			//		self::v( $data );
				//	var_export( $data );
					//	Switch
					if( $this->getParameter( 'post_switch' ) )
					{
						$switches = array_map( 'trim', explode( ',', $this->getParameter( 'post_switch' ) ) );
				//		var_export( $switches );
						foreach( $switches as $switch )
						{
					//		var_export( $data[$switch] );
							if( empty( $data[$switch] ) )
							{
								continue 2;
							}
							$data[$switch] = $switch;
							$data['post_switch'] = $switch;
						}
						
					}
					if( $this->getParameter( 'skip_ariticles_without_cover_photo' ) && ! @$data['document_url_base64'] && ( ! Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ? : @$data['display_picture'] ) ) )
					{
						//	Post without image is not allowed 
						continue;
					}
					if( $this->getParameter( 'skip_ariticles_without_this_key' ) )
					{
						$keys = $this->getParameter( 'skip_ariticles_without_this_key' );
						if( is_string( $keys ) )
						{
							$keys = array_map( 'trim', explode( ',', $keys ) );
						}
					//	var_export( $keys );
						foreach( $keys as $eachKey )
						{
							if( ! @$data[$eachKey] )
							{
					//		var_export( $data['article_title'] );
					//		var_export( $data[$eachKey] );
								//	Post without this is not allowed 
								continue 2;
							}
						//	var_export( $data[$eachKey] );
						}
					}
			//		var_export( $data['article_creation_date'] ); //2592000 //604800
					if( ( time() - $data['article_creation_date'] ) < ( $this->getParameter( 'time_span_for_new_badge' ) ? : 2592000 ) )
					{
						$data['new_badge'] = $this->getParameter( 'new_badge' ) ? : 'New';

					}
					if( ! empty( $data['true_post_type'] ) || empty( $data['not_real_post'] ) )
					{

						
						if( $this->getParameter( 'price_lower_limit' ) && floatval( @$data['item_price'] ) <= floatval( $this->getParameter( 'price_lower_limit' ) ) )
						{
							//	freebies 
							continue;
						}
						if( $this->getParameter( 'price_upper_limit' ) && floatval( @$data['item_price'] ) >= floatval( $this->getParameter( 'price_upper_limit' ) ) )
						{
							//	freebies 
							continue;
						}
					//	self::v( $data['new_badge'] );

					//	get number of views
						self::getViewsCount( $data );
						if( $this->getParameter( 'get_views_count' ) )
						{
							if( ! $this->viewsTable )
							{
								$this->viewsTable =  new Application_Article_Views();
							}
							$data['views_count'] = count( $this->viewsTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ddddddddddddd', 'limit' => $this->getParameter( 'limit_for_views_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_views_count_record_search' ) ? : '10' ) ) );
						//	set_time_limit( 0 );
						}

						self::getDownloadCount( $data );
						//	get number of downloads
						if( $this->getParameter( 'get_download_count' ) && self::isDownloadable( $data ) )
						{
							if( ! $this->downloadTable )
							{
								$this->downloadTable =  new Application_Article_Type_Download_Table();
							}
							$data['download_count'] = count( $this->downloadTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'sssdefwefefs', 'limit' => $this->getParameter( 'limit_for_download_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_download_count_record_search' ) ? : '10' ) ) );
						//	set_time_limit( 0 );
						}
					//	var_export( $data );
						//	get number of downloads
						self::getAudioPlayCount( $data );
						if( $this->getParameter( 'get_audio_play_count' ) && $data['true_post_type'] == 'audio' )
						{   
							if( ! $this->audioTable )
							{
								$this->audioTable =  new Application_Article_Type_Audio_Table();
							}
							$data['audio_play_count'] = count( $this->audioTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ssss', 'limit' => $this->getParameter( 'limit_for_audio_play_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_audio_play_count_record_search' ) ? : '10' ) ) );
						//	set_time_limit( 0 );
						}
						self::getCommentsCount( $data );
						if( $this->getParameter( 'get_comment_count' ) )
						{   
							if( ! $this->commentTable )
							{
								$this->commentTable =  new Application_CommentBox_Table();
							}
							$data['comments_count'] = count( $this->commentTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ssss', 'limit' => $this->getParameter( 'limit_for_audio_play_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_audio_play_count_record_search' ) ? : '10' ) ) );
						//	set_time_limit( 0 );
						}
					//	exit();
						$data['engagement_count'] = intval( $data['download_count'] ) + intval( $data['views_count'] ) + intval( $data['comments_count'] ) + intval( $data['audio_play_count'] );

						$data['engagement_count_total'] = intval( $data['download_count_total'] ) + intval( $data['views_count_total'] ) + intval( $data['comments_count_total'] ) + intval( $data['audio_play_count_total'] );



						//	don't cache base64 strings of images and download data
						unset( $data['document_url_base64'] );
						unset( $data['download_base64'] );  

						if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $data['article_type'] ) )
						{
							$data['true_post_types'] = $postTypeInfo['article_type'];
							$data['post_type'] = $postTypeInfo['post_type'];
						}
						else
						{
							$data['true_post_types'] = $data['article_type'];
							$data['post_type'] = $data['article_type'];
						}
					}
					$values[$key] = $data;
				}
			}
	//		var_export( $singlePostPaginationInfo );
			if( $this->getParameter( 'order_by' ) )
			{   
				$values = self::sortMultiDimensionalArray( $values, $this->getParameter( 'order_by' ) );
	//		self::v( $this->getParameter( 'order_by' ) );
		//	self::v( array_pop( $values ) );
			}
			if( $this->getParameter( 'inverse_order' ) )
			{   
				krsort( $values );
			}
		

			//	Cache results
		//	var_export( $this->getParameter( 'markup_template' ) );  
			$valuesToStore = array( 'values' => $values, 'parameter' => $this->getParameter() );

			// store if it's an independent request
			if( empty( $_GET['pc_post_list_autoload'] ) )
			{
			//	self::v( $valuesToStore );
				$storage->store( $valuesToStore );
			//	self::v( $storage->retrieve() );
			}
		}
		$this->_objectTemplateValues['total_no_of_posts'] = count( $values );
		 
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		
		//	default at 1800 so it can always cover the screen
		$maxWith = $this->getParameter( 'cover_photo_width_for_list' ) ? : ( $this->getParameter( 'cover_photo_width' ) ? : ( @$articleSettings['cover_photo_width'] ? : 600 ) );
		$maxHeight = $this->getParameter( 'cover_photo_height_for_list' ) ? : ( $this->getParameter( 'cover_photo_height' ) ? : ( @$articleSettings['cover_photo_height'] ? : 300 ) );    

	//	var_export( $values );

		//	calculate  the creator link here because of Application_Article_Publisher
		//	So it can see $this->_parameter['add_a_new_post_full_url']
		$where =  $this->_dbWhereClause;
		$truePostType = @array_pop( $where['true_post_type'] ) ? : $this->getParameter( 'true_post_type' );
		$newArticleType = ( @array_pop( $where['article_type'] ) ? : ( $this->getParameter( 'article_types' ) ? : $truePostType ) );
		$postTypeInfo = Application_Article_Type::getInstance()->selectOne( null, array( 'post_type_id' => $newArticleType ) );
		@$newArticleTypeToShow = self::getItemName() ? : ( ucfirst( $postTypeInfo['post_type'] ) ? : 'Item' );
	//		self::v( $newArticleType );
	//	self::v( $this->_dbWhereClause );
	//	self::v( $where );
		$categoryForNewPost = @array_pop( $where['category_name'] );
		$addNewPostUrl = ( static::$_newPostUrl ? : 
							( $this->getParameter( 'add_a_new_post_link' ) ? : 
							( ( $this->getParameter( 'add_a_new_post_classplayer' ) ? :  
							'/widgets' ) . '/Application_Article_Creator/' )
							) 
							) . '?';
		if( $newArticleType )
		{
			$addNewPostUrl .= '&article_type=' . $newArticleType . '';
		}
		if( $categoryForNewPost )
		{
			$addNewPostUrl .= '&category_name=' . $categoryForNewPost . '';
		}
		if( $truePostType )
		{
			$addNewPostUrl .= '&true_post_type=' . $truePostType . '';
		}
		if( $this->getParameter( 'post_type_custom_fields' ) )  
		{
			$addNewPostUrl .= '&post_type_custom_fields=' . $this->getParameter( 'post_type_custom_fields' ) . '';
		}
		if( $this->getParameter( 'post_type_options' ) )
		{
			$addNewPostUrl .= '&post_type_options=' . $this->getParameter( 'post_type_options' ) . '';
		}
		if( $this->getParameter( 'post_type_options_name' ) )
		{
			$addNewPostUrl .= '&post_type_options_name=' . $this->getParameter( 'post_type_options_name' ) . '';
		}
		$howManyPostsToAdd = intval( $this->getParameter( 'add_a_new_post' ) );
		$addNewPostUrl .= '&' . Ayoola_Page::setPreviousUrl() . '&counter=' . $howManyPostsToAdd;

		$this->_parameter['add_a_new_post_full_url'] = $addNewPostUrl;

		if( self::hasPriviledge( @$articleSettings['allowed_writers'] ? : 98 ) && $this->getParameter( 'add_a_new_post' ) ) 
		{ 
			$myProfileInfo = Application_Profile_Abstract::getMyDefaultProfile();
			do
			{
				$tempItem = array_pop( $values );

			//	$tempItem = array_shift( $values );
				//	make the first item a link to add a new post
			//	$newArticleType = is_string( $this->getParameter( 'article_types' ) ) && $this->getParameter( 'article_types' ) ? $this->getParameter( 'article_types' ) : 'post';
			//	self::v( $tempItem );
			//	self::v( $howManyPostsToAdd );
			//	$tempItem2 = $tempItem;
			//	if( is_string( $tempItem2 ) )
				{
				//	$tempItem2 = include( $tempItem );
				}
			//	if( property_exists( $this, '_itemName' ) )
				
				//			$urlToGo = Ayoola_Page::setPreviousUrl( $urlToGo ); 

				$item = array( 
								'article_url' => $addNewPostUrl, 
								'allow_raw_data' => true, 
								'not_real_post' => true, 
							//	'article_type' => $newArticleType, 
								'always_allow_article' => $this->getParameter( 'article_types' ), 
								'category_name' => $this->getParameter( 'category_name' ), 
								'document_url' => $this->getParameter( 'default_cover_photo' ) ? : '/img/placeholder-image.jpg', 
								'user_id' => Ayoola_Application::getUserInfo( 'user_id' ),
								'publish' => true, 
								'auth_level' => $articleSettings['allowed_writers'], 
					//			'article_tags' => '', 
								'username' => Ayoola_Application::getUserInfo( 'username' ), 
								'article_title' => 'Post new ' . $newArticleTypeToShow . '', 
								'article_description' => 'The short description for the new ' . $newArticleTypeToShow . ' will appear here. The short description should be between 100 and 300 characters.', 
							)  + ( $myProfileInfo ? : array() );  
			//	$item ? array_unshift( $values, $item ) : null;
				$tempItem ? array_push( $values, $tempItem ) : null;
				$item ? array_push( $values, $item ) : null;
			//	$tempItem ? array_unshift( $values, $tempItem ) : null;
			}
			while( --$howManyPostsToAdd );

		}
		
		$i = 0; //	counter
		$j = 5; //	5 is our max articles to show
	//	var_export( $this->_viewOption );  
		$this->_viewOption = intval( $this->_viewOption ) ? : $this->getParameter( 'no_of_post_to_show' );
		$j = $this->_viewOption ? : $j;
		$j = is_numeric( @$_GET['no_of_articles_to_show'] ) ? intval( $_GET['no_of_articles_to_show'] ) : $j; 
		$j = is_numeric( @$_GET['no_of_post_to_show'] ) ? intval( $_GET['no_of_post_to_show'] ) : $j; 
		$j = is_numeric( $this->getParameter( 'no_of_post_to_show' ) ) ? intval( $this->getParameter( 'no_of_post_to_show' ) ) : $j;
	//	var_export( $i );
	//	var_export( $j );
		$done = array();
		$template = null;  
	//	self::v( $values );   
 		
		//	Split to chunk\
	//	$_REQUEST['list_page_number'] = 0;
	//	self::v( self::$_listCounter );
	//	self::v( $_REQUEST['list_page_number'] );
		$offset = 0;
		$offsetDefined = false;
		if( is_numeric( @$_REQUEST['list_page_number'] ) )
		{
			if( self::$_listCounter != intval( @$_REQUEST['list_counter'] ) && empty( $_GET['pc_post_list_id'] ) )
			{
				$offset = 0;
			}
			else
			{
				$offset = intval( @$_REQUEST['list_page_number'] );
				$offsetDefined = true;
			}
		}
		if( $this->getParameter( 'list_page_number_offset' ) )
		{
			$offset = $this->getParameter( 'list_page_number_offset' );  
			$offsetDefined = true;
		}
	//	self::v( $_REQUEST['list_page_number'] );
		
	//	var_export( $offset );
		$chunk = array_chunk( $values , $j );
	//	self::v( $values );  
		if( @$chunk[$offset] )
		{
			$values = $chunk[$offset];
			++$offset;
		}
		elseif( intval( $offset ) > 0 && $offsetDefined  )
		{
		//	var_export( $offset );
			//	Seeking a chunk that isn't available'
			$values = array();
		}
		else
		{
			$values = $chunk;
			$values = array_pop( $values );
		}
		$this->autoLoadNewPosts( $postListId, $offset );
		if( @$chunk[$offset] )
		{
	//		var_export( $offset );
			$nextPageLink = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . @$offset;
			$this->_objectTemplateValues['paginator_next_page'] = $nextPageLink;
			$this->_objectTemplateValues['paginator_next_page_button'] = '<a class="pc-btn" href="' . $nextPageLink . '"> Next &rarr;</a>';       
			if( empty( $_GET['pc_post_list_autoload'] ) && $this->getParameter( 'pagination' ) && ! $this->getParameter( 'hide_pagination_buttons' ) )
			{
				$this->_objectTemplateValues['click_to_load_more'] = $linkToLoadMore = '<div style="text-align:center;" class="pc_posts_distinguish_sets" id="' . $postListId . '_pagination"><a class="pc-btn pc-btn-small" href="javascript:" onclick="pc_autoloadFunc_' . $postListId . '();"> Load more</a></div>';     
			}  
		}
		if( @$chunk[( @$offset - 2 )] )
		{
			$this->_objectTemplateValues['paginator_previous_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . ( @$offset - 2 );
			$this->_objectTemplateValues['paginator_previous_page_button'] = '<a class="pc-btn" href="' . $this->_objectTemplateValues['paginator_previous_page'] . '">&larr; Previous</a>';
		}
//		var_export( $offset );
		if( $offset != 1 )
		{
			$this->_objectTemplateValues['paginator_first_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=0';
		}
		if( $offset != ( @count( $chunk ) ) )
		{
			$this->_objectTemplateValues['paginator_last_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . ( @count( $chunk ) - 1 );
		}
		$this->_objectTemplateValues['paginator_last_page_number'] = ( @count( $chunk ) );
		$this->_objectTemplateValues = $this->_objectTemplateValues ? : array();

		$pagination = null;
		if( ! $this->getParameter( 'no_pagination' ) )
		{
			$pagination .= @$this->_objectTemplateValues['paginator_previous_page_button'];
			$pagination .= @$this->_objectTemplateValues['paginator_next_page_button'];
		}
		if( empty( $_GET['pc_post_list_autoload'] ) && ! $this->getParameter( 'hide_pagination_buttons' ) )
		{
			$pagination = '<div class="pc_posts_distinguish_sets" id="' . $postListId . '_pagination">' . $pagination . '</div>';
			$this->_objectTemplateValues['pagination'] = $data['pagination'] = $pagination;	
		}	
		$this->_objectTemplateValues['post_list_id'] = $postListId;
	//	self::v( $pagination );  
		$values = $values ? array_unique( $values, SORT_REGULAR ) : array(); 
	//	self::v( $values[''] );
	//	var_export( $values );
		$singlePostPaginationInfo = array();
		while( $values )
		{
			if( $i >= $j )
			{ 
				break; 
			}
			$data = array_shift( $values );

			
			switch( @$data['article_url'] )
			{
				case '':
				case null:
					$data['article_url'] = @$data['category_url'] ? : $data['article_url'];
				break;
			}
			//	Get user info
			if( @$data['username'] && $this->getParameter( 'get_access_information' ) )
			{
				//	Causes things to run slow
				if( $userInfo = Ayoola_Access::getAccessInformation( $data, array( 'skip_user_check' => true ) ) )
				{
					$data += $userInfo;
					@$data['article_title'] ? : ( @$data['display_name'] = $data['article_title'] = ( trim( $data['display_name'] ) ? trim( $data['display_name'] ) : ( $data['firstname'] . ' ' . $data['lastname'] ) ) );
					@$data['article_description'] ? : ( @$data['article_description'] = $data['profile_description'] );
					@$data['article_url'] ? : ( @$data['article_url'] = '/' . $data['username'] );
					@$data['document_url'] ? : ( $data['document_url'] = $data['display_picture'] );
				}
			}
			if( ! empty( $data['profile_url'] ) )
			{
				if( $profileInfo = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] ) )
				{
					$data += $profileInfo ? : array();
				}
			}
			$data['css_class_of_inner_content'] = $this->getParameter( 'css_class_of_inner_content' );
		//	if( @$data['document_url_base64'] && ! @$data['document_url'] && @$data['article_url'] )
			$data['post_link'] = $data['article_url'];
			$data['post_full_url'] = Ayoola_Page::getHomePageUrl() . $data['article_url'];
			if( @$data['article_url'] && strpos( @$data['article_url'], ':' ) === false && $data['article_url'][0] !== '?'  )
			{
				$data['post_link'] = Ayoola_Application::getUrlPrefix() . $data['article_url'];
		//		var_export( $data['post_link'] );
			}
			if( @$data['article_url'] )
			$data['document_url'] = ( $data['document_url'] ? : $this->getParameter( 'default_cover_photo' ) ) ? : '/img/placeholder-image.jpg'; 
			$data['document_url_plain'] = Ayoola_Application::getUrlPrefix() . $data['document_url']; 
			$data['document_url_uri'] = $data['document_url']; 
			$data['document_url_cropped'] = $data['document_url']; 
			$data['document_url_no_resize'] = $data['document_url']; 
	//		var_export( $data['document_url'] );
		//	var_export( Ayoola_Doc::uriToPath( $data['document_url'] ) );
			if( $fileP = Ayoola_Doc::uriToPath( $data['document_url'] ) )
			{
				$data['document_url_no_resize'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_IconViewer/?&url=' . @$data['document_url'] . '&document_time=' . @filemtime( $fileP ) . ''; 
				$data['document_url_cropped'] = $data['document_url_no_resize'] . '&max_width=' . $maxWith . '&max_height=' . $maxHeight . ''; 
		///		$data['document_url_no_resize'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] );     
			}
			elseif( strpos( @$data['document_url'], '//' ) === false && empty( $data['not_real_post'] ) )
			{
				//	This is the default now if they don't have picture, create a placeholder
			//	$data['document_url'] = $data['document_url_base64'];
				$data['document_url'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ); 
				$data['document_url_cropped'] = $data['document_url']; 
				$data['document_url_no_resize'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] );     
				
			}
			else
			{
				//	set back to original because it wasnt making repo images to work.
				$data['document_url'] = $data['document_url']; 
				$data['document_url_no_resize'] = $data['document_url']; 
				$data['document_url_cropped'] = $data['document_url']; 
			}
		//	self::v( $data['article_url'] );
		//	self::v( '<br>'); 
		//	self::v( $data['views_count'] );
	//		self::v( $data['document_url_cropped'] );
			
			//	Can't be lowercase because of auto create link
			$url = $data['article_url'];

			//	error making last post to be part of present one
	//		$data += is_array( $this->_objectTemplateValues ) ? $this->_objectTemplateValues : array();
			if( $this->getParameter( 'length_of_description' ) )
			{
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_description'] = strlen( $data['article_description'] ) < $this->getParameter( 'length_of_description' ) ? $data['article_description'] : ( trim( substr( $data['article_description'], 0, $this->getParameter( 'length_of_description' ) ) ) . '...' );
				}
				else
				{
					@$data['article_description'] = mb_strimwidth( $data['article_description'], 0, $this->getParameter( 'length_of_description' ), "..." );
				}
			}
	
			
			if( $this->getParameter( 'length_of_title' ) )
			{
				$titleToUse = trim( $data['article_title'] . ' - ' .  $data['article_description'], '- ' );
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_title'] = strlen( $titleToUse ) < $this->getParameter( 'length_of_title' ) ? $titleToUse : ( trim( substr( $titleToUse, 0, $this->getParameter( 'length_of_title' ) ) ) . '...' );  
				}
				else
				{
					@$data['article_title'] = mb_strimwidth( $titleToUse, 0, $this->getParameter( 'length_of_title' ), "..." );
				}
			}
			//		self::v( $data );
			if( $this->getParameter( 'use_datetime' ) )
			{

				if( ! empty( $data['datetime'] ) )
				{
					$data['datetime'] = strtotime( $data['datetime'] );
					$data['article_creation_date'] = $data['datetime'];		
					$data['article_modified_date'] = $data['datetime'];		
				}
		//		var_export( $data['article_modified_date'] );
			}
			if( $this->getParameter( 'modified_time_representation' ) )
			{
				if( is_string( $this->getParameter( 'modified_time_representation' ) ) )
				{
					$timeToShow = array_map( 'trim', explode( ',', $this->getParameter( 'modified_time_representation' ) ) );
					$timeToShow = array_combine( $timeToShow, $timeToShow );
				}
				else
				{
					$timeToShow = (array) $this->getParameter( 'modified_time_representation' );
				}
				foreach( $timeToShow as $key => $each )
				{
				//	var_export( date( $each, $data['article_modified_date'] ) );
				//	var_export( $key );
					@$data['modified_time_representation_' . $key] = date( $each, $data['article_modified_date'] ? : ( time() - 1 ) );
					@$data['article_modified_date_' . $key] = date( $each, $data['article_modified_date'] ? : ( time() - 1 ) );
					@$data['article_creation_date_' . $key] = date( $each, $data['article_creation_date'] ? : ( time() - 1 ) );
					if( ! empty( $data['datetime'] ) )
					{
						@$data['datetime_' . $key] = date( $each, $data['datetime'] );
					//	var_export( $data['datetime_' . $key] );
					}
				}
			}
		//	elseif( $this->getParameter( 'filter_date' ) )
			{
				$filter = new Ayoola_Filter_Time();
			//	if( @$data['article_modified_date'] )
				{
					@$data['article_modified_date_filtered'] = $filter->filter( $data['article_modified_date'] );
				}
			//	else
				{
					$data['article_creation_date_filtered'] = $filter->filter( @$data['article_creation_date'] ? : ( time() - 3 ) ); 
				}
			}
			@$data['article_date_M'] = date( 'M', $data['article_modified_date'] );
			@$data['article_date_m'] = date( 'm', $data['article_modified_date'] );   
			@$data['article_date_Y'] = date( 'Y', $data['article_modified_date'] );
			@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );   
			//	var_export( $data['article_modified_date'] );
				//		var_export( time() );
			switch( $this->getParameter( 'post_expiry_time' ) )
			{
				case 'future':
				//	var_export( $data['article_modified_date'] > time() );
				//	var_export( $data['article_modified_date'] );
			//		var_export( $data['article_title'] );
				//	var_export( time() );
					if( $data['article_modified_date'] < time() )
					{
						continue 2;
					}
				break;
				case 'past':
					if( $data['article_modified_date'] > time() )
					{
						continue 2;
					}
				break;
				default:

				break;
			}
		//	var_export( $data );

			// build a list
			if( @$data['true_post_type'] && empty( $data['not_real_post'] ) )
			{
				$firstPost = empty( $firstPost ) ? $data['article_url'] : $firstPost;

				//	by default, next is first post
				if( $data['article_url'] !==  $firstPost ) 
				{
					$data['pc_next_post'] = $firstPost;
					$singlePostPaginationInfo[$data['article_url']]['pc_next_post'] = $firstPost;
				}


				if( ! is_null( $previousKey ) )
				{
			//		$data['pc_previous_post'] = $previousKey;
					$singlePostPaginationInfo[$data['article_url']]['pc_previous_post'] = $previousKey;
					$singlePostPaginationInfo[$data['article_url']]['article_url'] = $data['article_url'];
					$singlePostPaginationInfo[$previousKey]['pc_next_post'] = $data['article_url'];
				}
				
			//	var_export( $values );
				$previousKey = $data['article_url'];
				}
			
			
			//	content
		//	var_export( $data );
			if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )
			{
				$imageLink = '<div style=""><a href="' . Ayoola_Application::getUrlPrefix() . $url . '" onClick=""><img class="' . __CLASS__ . '_IMG" style="filter: brightness(50%);-webkit-filter: brightness(50%);-moz-filter: brightness(50%);" src="' . Ayoola_Application::getUrlPrefix() . $image . '" alt="' . $data['article_title'] . "'s cover photo" . '" title="' . $data['article_title'] . "'s cover photo" . '"/></a></div>';  
				
				//	Create this template placeholder value so we can have solve the problem of blank image tags in template markups
				$data['cover_photo_with_link'] = $imageLink;
			}
			$data['button_value'] = $this->getParameter( 'button_value' ) ? : 'View';
			
			
			//	CATEGORIES
 			@$categoryToUse = is_array( $data['category_name'] ) ? $data['category_name'] : array();
			$categoryTextRaw = self::getCategories( $categoryToUse, array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
			$categoryText = $categoryTextRaw ? ' ' . $categoryTextRaw : null;
			$data['category_text'] = $categoryText;
			
			//	Social Media
			$parameter = array( 'url' =>  Ayoola_Application::getUrlPrefix() . $url, 'title' => $data['article_title'] );
			if( self::isOwner( @$data['user_id'] ) || self::hasPriviledge( @$articleSettings['allowed_editors'] ? : 98 ) )
			{
				$editLink = self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'];
				$editLinkHTML = null;
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button>';
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">Delete...</button>';
			//	$this->_objectData['edit_link'] = $editLinkHTML;
				$data['edit_link'] = $editLinkHTML;
			}
		//	var_export(  $data['item_price'] );
			if( isset( $data['item_price'] ) ) 
			{
				//	Filter the price to display unit
			//	@$data['item_price'] = $data['item_price'] ? : '0.00';
				if( empty( $data['item_price'] ) && $this->getParameter( 'use_price_option_price' ) )
				{
					if( ! empty( $data['price_option_price'] ) )
					{
						$allOptionPrices = $data['price_option_price'];
						asort( $allOptionPrices );
						do
						{
							$leastPrice = array_shift( $allOptionPrices );
						}
						while( ! $leastPrice && $allOptionPrices );
						$data['item_price'] = '' . $leastPrice;
					}

				}
				$filter = 'Ayoola_Filter_Currency';
				$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
				$data['currency'] = $filter::$symbol;
				$filter = new $filter();
				
				if( $data['item_old_price'] )
				{
					@$data['price_percentage_savings'] =  intval( ( ( $data['item_old_price'] - $data['item_price'] ) / $data['item_old_price'] ) * 100 ) . '';
					@$data['item_old_price'] = $data['item_old_price'] ? $filter->filter( $data['item_old_price'] ) : null;
				}
				$data['item_price_with_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
				
				//	Split to naira / kobo
				$filter = new $filter();
				$filter::$symbol = '';
				$data['item_price_without_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
	//			var_export( $data['item_price'] );
	//			var_export( $data['item_price_with_currency'] );
				$data['item_price_before_decimal'] = array_shift( explode( '.', $data['item_price_without_currency'] ) );
				$data['item_price_after_decimal'] = array_pop( explode( '.', $data['item_price_without_currency'] ) );
		//		var_export( $data['item_price'] );
				$data['item_price'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
		//		var_export( $data['item_price'] );
			
			}
		//	var_export( $data['article_type'] );
			if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( @$data['article_type'] ) )
			{
				$postType = @$postTypeInfo['article_type'];
			}
			else
			{
				$postType = @$data['article_type'];
			}
		//	self::v( $postType );
			$data['filtered_time'] = self::filterTime( $data );

			//	internal forms to use
			$features = is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : array();
			$featuresPrefix = is_array( @$postTypeInfo['post_type_options_name'] ) ? $postTypeInfo['post_type_options_name'] : array();
			$features[] = @$data['true_post_type'];
			$featuresPrefix[] = '';
			$featureCount = array();
			$featureDone = array();
			//		var_export( $features );
			foreach( $features as $key => $eachPostType )
			{	
				$featureSuffix = $featuresPrefix[$key];
				if( empty( $featureCount[$eachPostType] ) )
				{
					$featureCount[$eachPostType] = 1;
				}
				else
				{
					if( empty( $featureSuffix ) )
					{
						$featureSuffix = $featureCount[$eachPostType];
					}
					$featureCount[$eachPostType]++;
				}
				$featureCountKey = $eachPostType . $featureSuffix;
				if( ! empty( $featureDone[$featureCountKey] ) )
				{
					continue;
				}
			//	self::v( $eachPostType );
				$featureDone[$featureCountKey] = true;
				switch( $eachPostType )
				{
					case 'gallery':
						$imagesKey = 'images' . $featurePrefix;
						$images = $data[$imagesKey];
						foreach( $images as $imageCounter => $eachImage )
						{
							if( ! trim( $eachImage ) )
							{
								continue;
							}
							$eachImageKey = $imagesKey . '_' . $imageCounter;
							$data[$eachImageKey] = $eachImage;
							$data[$eachImageKey . '_cropped'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_IconViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&url=' . $eachImage; 
						}
						unset( $data[$imagesKey] );
					break;  
					case 'subscription':
					case 'product':
					case 'service':
						$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( array( 'data' => $data, 'button_value' => $this->getParameter( 'button_value' ) ? : $data['button_value'] ) );
					break; 
					case 'category_information':
					//	self::v( $data );
				//		var_export( $data['display_name'] );
						@$data['article_title'] = $data['category_label'];
						@$data['article_description'] = $data['category_description'];
						@$data['document_url'] = $data['cover_photo'] ? : $data['document_url'];
						@$data['article_url'] = $data['category_url'] ? : ( '' . self::getPostUrl() . '/category/' . $data['category_name'] );
						@$data['item_price'] = null;					
					break;
					case 'profile':
					break;
					case 'audio':
					case 'music':
					case 'message':
					case 'e-book':
					case 'document':
					case 'file':
					case 'download':
						//	title
						if( @$data['download_url'] )
						{
							if( $data['download_url'][0] === '/' )
							{
								//	this is still a local file we can load with Ayoola_Doc
								$data['file_size'] =  filesize( Ayoola_Loader::checkFile(  'documents/' . $data['download_url'] ) );
							}
							else
							{
								$head = array_change_key_case(get_headers( $data['download_url'], TRUE));
								$data['file_size'] = $head['content-length'];							
							}
						}
						elseif( @$data['download_path'] )
						{
							$path = APPLICATION_DIR . $data['download_path'];
							$data['file_size'] =  filesize( $path );
						}
						elseif( @$data['download_base64'] )
						{
							$result = self::splitBase64Data( $data['download_base64'] );
							$data['file_size'] =  strlen( $result['data'] );
						}
				//		var_export( $data['download_base64'] );
						$filter = new Ayoola_Filter_FileSize();
						$data['file_size'] = $filter->filter( $data['file_size'] );
						
						$data['download_button'] = '<a title="Go to the download page." href="' . Ayoola_Application::getUrlPrefix() . $url . '"><input type="button" value="Download Now" /></a>';
					break;
					case 'video':
					break;
					case 'poll':
					break;
					default:
					break;
				}
			}
		//	
			$this->_xml .= '' . self::getDefaultPostView( $data ) . '';
		//	var_export( self::getDefaultPostView( $data )  );
			
			//	useful in the templates
			$data['article_quick_links'] = self::getQuickLink( $data );
			$data['comments_count'] = '0';
			$data['category_html'] = $categoryTextRaw;
			$data['record_count'] = $i + 1; 
			
			//	compatibility
			$data['category_id'] = $categoryTextRaw;
			if( $this->getParameter( 'markup_template' ) )
			{
			//	foreach( $data['slideshow_images'] as $key => $each )
				{
					//	Calculate the max_group_no
					
				//	do 
					{
						$templateToUse = null;
						if( ! $this->getParameter( 'max_group_no' ) )
						{
					//		break;
						}
						if( ! @$maxGroupNo || $maxGroupNo == 1 )
						{
						//	self::v( $maxGroupNo ); 
						//	self::v( $data['article_title'] ); 
							$maxGroupNo = 1;
							$templateToUse .= $this->getParameter( 'markup_template_prefix' );
							
							//	never forget to include the last suffix
							$lastSuffix = true;
						}
						$templateToUse .= $this->getParameter( 'markup_template' );
						if( ( $this->getParameter( 'max_group_no' ) && $maxGroupNo == $this->getParameter( 'max_group_no' ) ) || empty( $values ) )
						{		
					//		self::v( $maxGroupNo ); 
								$maxGroupNo = 0;
								$templateToUse .= $this->getParameter( 'markup_template_suffix' );
								$lastSuffix = false;
						
						}
						$maxGroupNo++;
					}
			//		while( false );
				//	var_export( $data['article_title'] );
					$template .= self::replacePlaceholders( $templateToUse, $data + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
				}
			}
			if( @$_POST['PAGECARTON_RESPONSE_WHITELIST'] )
			{
			
				//	Limit the values that is being sent
				$whitelist = @$_POST['HTTP_PAGECARTON_RESPONSE_WHITELIST'];
				$whitelist = is_array( $whitelist ) ? $whitelist : array_map( 'trim', explode( ',', $whitelist ) );
				$whitelist = array_combine( $whitelist, $whitelist );
				$data = array_intersect_key( $data, $whitelist );
				
			//		var_export( $data );
			}
			//	Save
			$allTemplate .= $this->_xml;
			$this->_xml = null; //	reset
			$i++;
			$this->_objectData[] = $data;
			$this->_objectTemplateValues[] = $data;
			
		}

		// store playlist
		if( $this->getParameter( 'single_post_pagination' ) )
		{
			$storage = self::getObjectStorage( array( 'id' => $postListId . '_single_post_pagination', 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 44600, ) );
		//	self::v( $postListId );  
		//	self::v( $singlePostPaginationInfo );    
			//	add it to previous because of autoload clearing this settings
			$prevSinglePostPagination = $storage->retrieve();
			$singlePostPaginationInfo = $singlePostPaginationInfo + ( is_array( $prevSinglePostPagination ) ? $prevSinglePostPagination : array() );
			$storage->store( $singlePostPaginationInfo );

			
			$class = new Application_Article_ViewPagination( array( 'no_init' => true ) );
			$storageForSinglePosts = $class::getObjectStorage( array( 'id' => 'post_list_id' ) );
		//	self::v( $postListId );  
			$storageForSinglePosts->store( $postListId );
		//	self::v( $storageForSinglePosts->retrieve() ); 
		}
	//	self::v( $prevSinglePostPagination );
	//	self::v( count( $singlePostPaginationInfo ) );
	//	self::v( $postListId );
	//	$this->_xml = $allTemplate; //	reset
		$this->_xml = '' . $allTemplate . '';
//		var_export( $this->_xml );
		//	delete so we dont do this twice
		unset( $_POST['PAGECARTON_RESPONSE_WHITELIST'] );


	//	if( $this->getParameter( 'pagination' ) )
		
		//	$this->_objectTemplateValues['paginator_next_page_button']
		if( empty( $_GET['pc_post_list_autoload'] ) )
		{
			$this->_xml .= $pagination;
			if( $template )
			{
				$template = '' . $template . $pagination;
	//			self::v( $pagination );
			}
		}
		else
		{
			$this->_parameter['markup_template_append'] = null;
			$this->_parameter['markup_template_prepend'] = null;  
		}
			//	self::v( $this->_objectTemplateValues['pagination'] );
			//	self::v( strpos( $this->_parameter['markup_template'], '}}}{{{0}}}' ) );
	//	var_export( $this->_parameter['markup_template'] );
		
		if( strpos( $this->_parameter['markup_template'], '}}}{{{0}}}' ) === false )  
//		if( ! $this->_parameter['array_key_placeholders'] )  
		{
			//	update the markup template
			@$this->_parameter['markup_template'] = null;
			
			//	allows me to add pagination on post listing with predefined suffix
		//	@$this->_parameter['markup_template'] .= @$this->_parameter['markup_template_prepend'];
			@$this->_parameter['markup_template'] .= $template; 
			@$this->_parameter['markup_template'] .= @$lastSuffix ? $this->_parameter['markup_template_suffix'] : null;
			
			//	Allows me to put the pagination
		//	@$this->_parameter['markup_template'] .= @$this->_parameter['markup_template_append'];
			$this->_parameter['markup_template_prefix'] = null;
			$this->_parameter['markup_template_suffix'] = null;  
		}
		else
		{
			@$this->_parameter['markup_template'] .= $linkToLoadMore;   
		}
		//? '<a href="' . $nextPageLink . '"><input type="button" value="Next ' . ( @count( $chunk[( @$offset )] )) . '..." /></a>' : null;
 		if( ! $i ) 
		{
//			var_export( count( $value ) );   
			//	No post is eligible to be displayed. 
			$this->showMessage();
		}
 	//	var_export( $this->_parameter['markup_template'] );    
	//	var_export( count( $values ) );
//		var_export( $this->_xml );
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
	//	if( $this->getParameter( 'allow_dynamic_category_selection' ) )
		{
	///		self::v( $_REQUEST['pc_module_url_values'] );      
	//		self::v( intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) ) );      
	//		self::v( $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) )] );      

			if( is_numeric( $this->getParameter( 'pc_module_url_values_post_type_offset' ) ) && @array_key_exists( $this->getParameter( 'pc_module_url_values_post_type_offset' ), $_REQUEST['pc_module_url_values'] ) )
			{
				$postType = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) )];
			//	var_export( $category );
			}
			elseif( $this->getParameter( 'allow_dynamic_category_selection' ) )
			{
				@$postType = $_REQUEST['article_type'] ? : $_REQUEST['post_type'];  
			}
			if( is_numeric( $this->getParameter( 'pc_module_url_values_category_offset' ) ) && @array_key_exists( $this->getParameter( 'pc_module_url_values_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
			{
				$categoryId = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_category_offset' ) )];
			//	var_export( $categoryId );
				if( $categoryId == 'category' )
				{
					$categoryId = @$_REQUEST['category'];
				}
			//	var_export( $category );
			}
			elseif( @$_REQUEST['category'] &&  $this->getParameter( 'allow_dynamic_category_selection' ) )
			{
				$categoryId = $_REQUEST['category'];  
			}
		}
	//	var_export( $this->getParameter() );
	//	@$categoryId = $_GET['category']; 
		if( $this->getParameter( 'ignore_category_query_string' ) )
		{
			// switch $_GET['category'] off for this instance 
			@$categoryId = null; 
		}
		@$categoryId = $this->getParameter( 'category' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_id' ) ? : $categoryId;
		@$categoryId = $this->getParameter( 'category_name' ) ? : $categoryId;
		if( $this->getParameter( 'post_with_same_category' ) && @Ayoola_Application::$GLOBAL['post']['category_name'] )
		{
			$categoryId = @Ayoola_Application::$GLOBAL['post']['category_name'];
		}
//		var_export( $this->getParameter( 'category_name' ) );
	//	self::v( $categoryId );
		$categoryName = null;
		$table = Application_Category::getInstance();
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
	//			$this->_dbData = array();
	//			return false;   
			}
			else
			{
				$categoryId = @$category['category_id'];
				$categoryName = @$category['category_name'] ? : $category['category_id'];
				$categoryName = '' . $categoryName . '';
			}
			@$category['category_description'] = $category['category_description'] ? : ' Latest Posts in the "' . $category['category_label'] . '" category on ' . ( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'company_name' ) ? : Ayoola_Page::getDefaultDomain() );
			
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
		}
		elseif( $categoryId && is_array( $categoryId ) )
		{
			//	
		//	var_export( $categoryId );
		//	$categoryName = count( $categoryId ) === 1 ? ( '' . $categoryId[key( $categoryId )] . '' ) : ( '(' . implode( ')|(', $categoryId ) . ')' );
		//	$categoryId = null;
		}
	//	self::v( $categoryId );
	//	self::v( $categoryName );
	//	var_export( $this->getParameter() );
		$path = self::getFolder();
		$pathToSearch = $path;
		$output = array();
		$whereClause = array();
	//	var_export( Ayoola_Application::$GLOBAL );
		if( $this->getParameter( 'show_post_by_me' ) )
		{
			if( ! Ayoola_Application::getUserInfo( 'username' ) )
			{
				//	show_post_by_me exclusive to signed-inn user. No username means this should be empty
				$this->_dbData = array();
				return false;
			}
		//	var_export( Ayoola_Application::getUserInfo( 'username' ) );
			$this->setParameter( array( 'username_to_show' => Ayoola_Application::getUserInfo( 'username' ) ) );
		}
		elseif( $this->getParameter( 'show_profile_posts' ) && @Ayoola_Application::$GLOBAL['profile']['profile_url'] )
		{
		//	var_export( Ayoola_Application::$GLOBAL['post']['username'] );  
			$this->setParameter( array( 'profile_to_show' => strtolower( Ayoola_Application::$GLOBAL['profile']['profile_url'] ) ) );
		}
		elseif( $this->getParameter( 'search_mode' ) && @$_REQUEST['q'] )
		{
			switch( $this->getParameter( 'search_mode' ) )
			{
				case 'keyword':
					$keywords = array_map( 'trim', explode( ' ', $_REQUEST['q'] ) );
					$keywordPaths = null;
					while( $keywords )
					{
						$keyword = array_shift( $keywords );
				//		$command = "find $path -type f -print0 | xargs -0 egrep -l \"*" . $keyword . "*\"";
				//		$pattern = implode('\|', $contents_list) ;
				//		@exec( $command, $output );
				//		$keywordPaths .= implode( ' ', $output ); 
						$whereClause['*'][] = $keyword;
					}
					$path = $keywordPaths ? : $path; 
				break;
				case 'phrase':
				default:      
				//	$command = "find $path -type f -print0 | xargs -0 egrep -l \"*" . $_REQUEST['q'] . "*\"";
			//		$pattern = implode('\|', $contents_list) ;
				//	exec( $command, $output );
				//	$path = implode( ' ', $output ); 
					$whereClause['*'] = $_REQUEST['q'];

				break;
			}
		//	var_export( $path ); 
		//	var_export( $command );
		}
//		var_export( $this->getParameter( 'username_to_show' ) ); 
		if( $this->getParameter( 'username_to_show' ) )
		{
			$whereClause['username'][] = $this->getParameter( 'username_to_show' );
		} 
		if( $this->getParameter( 'profile_to_show' ) )
		{
			$whereClause['profile_url'][] = $this->getParameter( 'profile_to_show' );
		} 
		if( $this->getParameter( 'true_post_type' ) )
		{
			$whereClause['true_post_type'][] = $this->getParameter( 'true_post_type' );
		}
		if( $this->getParameter( 'trending' ) )
		{
			$table = Application_Article_Views::getInstance();

			switch( $this->getParameter( 'trending_key' ) )
			{
				case 'views_count':

				break;
				case 'audio_play_count':
					$table = Application_Article_Type_Audio_Table::getInstance();
				break;
				case 'download_count':
					$table = Application_Article_Type_Download_Table::getInstance();
				break;
				case 'comments_count':
					$table = Application_CommentBox_Table::getInstance();
				break;
			}
			$noOfTrends = intval( $this->getParameter( 'trending' ) ) > 9 ? $this->getParameter( 'trending' ) : 100;
			$trendingData = $table->select( null, null, array( 'limit' => $noOfTrends, 'record_search_limit' => $noOfTrends ) );
		//	self::v( $trendingData );   
			$trendingPost = array();
		//	self::v( empty( $trendingData[0][$this->getIdColumn()] ) );   
		//	self::v( empty( $trendingData[0]['article_url'] ) );   
			if( empty( $trendingData[0][$this->getIdColumn()] ) && ! empty( $trendingData[0]['article_url'] ) )
			{
		//	self::v( empty( $trendingData[0]['article_url'] ) );   
				foreach( $trendingData as $key => $each )
				{
					$trendingData[$key] = Application_Article_Abstract::loadPostData( $each['article_url'] );
					$trendingData[$key][$this->getIdColumn()] = strtolower( $trendingData[$key][$this->getIdColumn()] );
					if( empty( $trendingData[$key][$this->getIdColumn()] ) )
					{
						unset( $trendingData[$key] );
					}

		//	self::v( $each['article_url'] );   
				}
	//	self::v( $trendingData );   
			}
			$trendingPost = array_unique( array_column( $trendingData, $this->getIdColumn() ) );

		//	self::v( $trendingPost );   
		//	self::v( $this->getIdColumn() );   
			$whereClause[$this->getIdColumn()] = $trendingPost;
			@$this->_parameter['order_by'] = $this->_parameter['order_by'] ? : 'engagement_count';
			@$this->_parameter['inverse_order'] = isset( $this->_parameter['inverse_order'] ) ? $this->_parameter['inverse_order'] : true;
		}
		@$postType = $this->getParameter( 'article_types' ) ? : $postType;
	//	var_export( $postType );
		if( $postType )
		{
		//	var_export( $postType );
		//	var_export( $postTypeInfo );
			//	//	Show this here to avoid looping in Article_ShowAll
		//	$path = self::getFolder();
			$whereClause['article_type'][] = $postType;
//			$command = "find $path -type f -print0 | xargs -0 egrep -l \"'article_type' => '" . $postType . "'\"";
	//		$pattern = implode('\|', $contents_list) ;
//			@exec( $command, $output );
	//		$realPostTypePath = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts';
		//	var_export( $path );
		//	$allOriginalPostTypes = array();
			//	var_export( $postType );
			if( @$this->_parameter['article_types_plus_original'] ) 
			{
				if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $postType ) )
				{
					$postType = $postTypeInfo['article_type'];
				}
				$whereClause['article_type'][] = $postType;
			}
	//		var_export( $realPostTypePath ); 
	//		var_export( $allOriginalPostTypes );
			
			@$path = $realPostTypePath . ' ' . $allOriginalPostTypes;
		
		} //	For profiles
		elseif( @$this->_parameter['access_level'] )
		{
			$whereClause['access_level'][] = $this->_parameter['access_level'];

		}
		elseif( @$_REQUEST['type'] )
		{
			$typeInfo = Ayoola_Access_AuthLevel::getInstance();
			if( $typeInfo = $typeInfo->selectOne( null, array( 'auth_name' => $_REQUEST['type'] ) ) )
			{
	//			$command = "find $path -type f -print0 | xargs -0 egrep -l \"'access_level' => '" . $typeInfo['auth_level'] . "'\"";
	//			@exec( $command, $output );
	//			$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts';
			}
		//	var_export( $typeInfo );
		//	var_export( $path );
		}
	//	var_export( $path );
	//	self::v( $path );
		//		self::v( $categoryName );      
		if( $this->getParameter( 'post_with_same_true_post_type' ) && @Ayoola_Application::$GLOBAL['post']['true_post_type'] )
		{
			$whereClause['true_post_type'][] = @Ayoola_Application::$GLOBAL['post']['true_post_type'];
		}
		if( $this->getParameter( 'post_with_same_article_type' ) && @Ayoola_Application::$GLOBAL['post']['article_type'] )
		{
			$whereClause['article_type'][] = @Ayoola_Application::$GLOBAL['post']['article_type']; 
		}
	
		if( $categoryId || $categoryName )
		{
			$category = $categoryName ? : $categoryId;
			$whereClause['category_name'] = @$whereClause['category_name'] ? : array();
			if( ! is_array( $category ) )
			{
				$whereClause['category_name'][] = $category;
			}
			else
			{
				$whereClause['category_name'] += $category;
			}
			if( $children = Application_Category_ShowAll::getChildren( $category ) )
			{
				$whereClause['category_name'] = array_merge( $whereClause['category_name'], $children );
				;
			}
		//	var_export( $children );
	//		$whereClause['category_name'][] = $categoryId ? : 'workaround_avoid_error_in_search';
		//	$this->_dbWhereClause['category_id'] = $categoryId;
		//	$this->setViewContent( '<p>Showing articles from ', true );
		//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 ) 
			{
		//		var_export( count( $files ) );
				//	Removing dependence on Ayoola_Api for showing posts
			//
			//	$categoryId = $categoryId ? : 'workaround_avoid_error_in_search';
			//	$this->_dbData = $output;   
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
				//		$command = "find $path -type f -print0 | xargs -0 egrep -l \"'username' => '{$this->_dbWhereClause['username']}'\"";
				//		$pattern = implode('\|', $contents_list) ;
				//		@exec( $command, $output );
				//		$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts';
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
			$whereClause['username'][] = $this->_dbWhereClause['username'];
	//		if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
		//		var_export( count( $files ) );
				//	Removing dependence on Ayoola_Api for showing posts
			//	$path = self::getFolder();
		//		$command = "find $path -type f -print0 | xargs -0 egrep -l \"'username' => '{$this->_dbWhereClause['username']}'\"";
				
	//			$pattern = implode('\|', $contents_list) ;
		//		@exec( $command, $output );
		//		$path = implode( ' ', $output ) ? : 'work_around_to_avoid_it_showing_all_posts';
		//		var_export( '<br />' );
		//		var_export( $this->_dbData ); 
		//		var_export( $command );
		//		var_export( $output );
		//		var_export( '<br />' );
		//		$this->_dbData = $output;
			} 
			
			//	Reset canonical url
			//	Reset canonical url
			Ayoola_Page::getCanonicalUri( self::getPostUrl() );
			Ayoola_Page::getCanonicalUri( self::getPostUrl() . '/by/' . $_GET['by'] . '/' );
		}
//		else
		{
	//	var_export( $path );
		//	Removing dependence on Ayoola_Api for showing posts
			$keyFunction = create_function
			( 
				'& $value, & $otherData, & $searchTerm', 
				'
				//	$otherData = Application_Article_ShowAll::loadPostData( $value );
					$searchTerm = json_encode( Application_Article_ShowAll::loadPostData( $value ) );
				//	var_export( $otherData );
				//	return $otherData;
				'
			); 
			try
			{
				//	var_export( $path . " 1 \r\n" );
				//	var_export(  );
				//	var_export( self::getFolder() . " 2 \r\n" );
			
		//		if( $path === self::getFolder() )
				$table = $this->_postTable;
	////			var_export( $table );
				if( empty( $whereClause ) )
				{
				//	var_export( $path );
					$sortFunction = create_function
					( 
						'$filePath', 
						'
						$values = Application_Article_Abstract::loadPostData( $filePath );
			//			var_export( $values[\'article_title\'] );
						if( ! $values )
						{
				//			var_export( $values[\'article_title\'] );
							return false;
						}
						return $values[\'article_creation_date\'] ? : $values[\'article_modified_date\'];
				//		if( filesize( $filePath ) > 300000 )
						{
					//		$result = filectime( $filePath );
							
			//				var_export( $values[\'article_title\'] );
						}
					//	var_export( $result  . "<br>");
						return $result;
						'
					); 
				//	$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder(), array( 'key_function' => 'filectime' ) );
		//			$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder(), array( 'key_function' => $sortFunction ) );
		//			krsort( $this->_dbData );
				//	self::v( $this->_dbData );
				//	self::v( $_REQUEST );
					if( empty( $_REQUEST['pc_load_old_posts']))
					{
						$table = $table::getInstance( $table::SCOPE_PRIVATE );
						$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
						$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
						$this->_dbData = $table->select( null, null, array( 'x' => 'workaround-to-avoid-cache', 'key_filter_function' => array( 'article_url' => $keyFunction ) ) );
				//		var_export( $this->_dbData );    
					}  
					else
					{
						$this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder() );
						krsort( $this->_dbData ); 
					}
	//					var_export( $this->_dbData );        
				//	self::v( Ayoola_Doc::getFilesRecursive( self::getFolder() ) );
				//	var_export( count( $files ) );  
					//	Removing dependence on Ayoola_Api for showing posts
				//	$path = self::getFolder();   
				}
				else
				{
/*					$command = "find $path -type f -print0 | xargs -0 egrep -l \"'article_url' => '\"";
			//		$pattern = implode('\|', $contents_list) ;
					@exec( $command, $output );
					$this->_dbData = array_unique( $output ); 
*/
					$table = $table::getInstance();
					$this->_dbData = $table->select( null, $whereClause, array( 'key_filter_function' => array( 'article_url' => $keyFunction ) ) );
					$this->_dbWhereClause = $whereClause;
			//		var_export( $this->_postTable );
		//			var_export( $this->_dbData );
		//			var_export( $whereClause );
				}
//self::v( $table->select() );
			//	self::v( $whereClause );
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
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
		@$object['option'] = $object['option'] ? : 5;
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		$options = get_called_class();

		if( ! Ayoola_Loader::loadClass( $options ) )
		{
			return false;
		}
	//	var_export( $options );
//		var_export( get_called_class() );
		$options = new $options( array( 'no_init' => true ) );
//		$options = array();
		$options = (array) $options->getClassOptions();
//		$options = (array) $options->getClassOptions();
		$html .= '<span style=""> Show a List of </span>';
		$html .= '<select data-parameter_name="option">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( $object['option'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> posts </span>';
		$html .= '<span style=""> in </span>';
		
		$options = Application_Category_ShowAll::getPostCategories();
	//	var_export( $options );
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$options = array( '' => 'All' ) + $filter->filter( $options );
		
		$html .= '<select data-parameter_name="category_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['category_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> Category; Of </span>';
		
		//	Article Types
		$options = Application_Article_Type::getInstance();
		$options = $options->select();
	//	$options = $options ? : Application_Article_Type_TypeAbstract::$presetTypes;
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
		$options = $filter->filter( $options );
		$options = $options ? : Application_Article_Type_TypeAbstract::$presetTypes;
		$options = array( '' => 'All' ) + $options;
		  
		$html .= '<select data-parameter_name="article_types">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['article_types'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> type. </span>';
		
		$html .= '<span style=""> In </span>';
		
		$options = Application_Article_Template::getInstance();
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'template_name', 'template_label');
		$options = array( '' => 'Default' ) + $filter->filter( $options ); 
		
		$html .= '<select data-parameter_name="template_name">'; 
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['template_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> style. </span>';  
		if( static::$_editableTitle )
		{
			$html .= '<a href="javascript:;" title="' . static::$_editableTitle . '" onclick="ayoola.div.makeEditable( this.nextSibling ); this.nextSibling.style.display=\'block\';"> edit </a>';
				//	var_export( $object );
			$html .= '<span data-parameter_name="editable" style="padding:1em;display:none;" onclick="this.nextSibling.style.display=\'block\';">' . @$object['editable'] . '</span>';
			$html .= '<a href="javascript:;" style="display:none;" title="' . static::$_editableTitle . '" onclick="this.previousSibling.style.display=\'none\';this.style.display=\'none\';"> hide </a>';
		}
		return $html;
	}

    /**
     * Returns an array of other classes to get parameter keys from
     *
     * @param void
     * @return array
     */
    protected static function getParameterKeysFromTheseOtherClasses( & $parameters )
    {
	//	var_export( $parameters['editable'] );
		return array( __CLASS__, 'Application_Article_Abstract' );
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
			$this->_classOptions = range( 1, 200 );
		}
		return array_combine( $this->_classOptions, $this->_classOptions );;
    } 	
// END OF CLASS
}
