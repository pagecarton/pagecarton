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
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		   
		//	Using menu template?
		if( $this->getParameter( 'template_name' ) )
		{
		//	self::v( $this->getParameter( 'template_name' ) );
			$options = new Application_Article_Template;
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
			if( @$options['css_files'] )
			{
				foreach( @$options['css_files'] as $each )
				{
					Application_Style::addFile( $each );
				}
			}
		}
	//	var_export( 'ewe' );
		try
		{
		//	var_export( $this->getParameter() );
			switch( @$_GET['post'] )
			{
/* 				case 'creator';
					$this->setViewContent( Application_Article_Creator::viewInLine(), true );
					
				break;
				case 'editor';
					$this->setViewContent( Application_Article_Editor::viewInLine(), true );
				break;
 */				default:
					$this->show();
			//		var_export( $this->getMarkupTemplate() );
			//		var_export( $this->_parameter['markup_template'] );
				break;
			}
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
		return $link;
	}
	
    /**
     * Display the real list
     * 
     * @return
     */
	public function showMessage()
    {
		$this->_parameter['markup_template'] = null;
	//	$message = array_pop( $this->_badnews ) ? : 'There are no recent posts to display here. Please check back later.';
	//	$message = $this->getParameter( 'badnews' ) ? : $message;
		$message = $this->getParameter( 'badnews' ) ? : null;
	//	if( ! $values )
		{
			//	switch templates off
			$this->_parameter['markup_template'] = null; 
		}
		
	//	$this->setViewContent( '<p class="badnews"> ' . $message . ' ' . self::getQuickLink() . '</p>', true );
		$message ? $this->setViewContent( ' ' . $message . ' ', true ) : null;
		
		//	Check settings
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' ); 

		//	Only allowed users can write
		if( self::hasPriviledge( @$articleSettings['allowed_writers'] ) && $this->getParameter( 'add_a_new_post' ) )
		{
			$addPostMessage = is_numeric( $this->getParameter( 'add_a_new_post' ) ) ? 'Create a new post' : $this->getParameter( 'add_a_new_post' );
/* 			$link = self::getPostUrl() . '/post/creator/?';
			$categoryId = @$_GET['category'] ? : $this->getParameter( 'category' );  
			$categoryId = $this->getParameter( 'category_name' ) ? : $categoryId;
			$articleInfo = array();
			if( is_string( $this->getParameter( 'article_types' ) ) )
			{
				$articleInfo += array( 'article_type' => $this->getParameter( 'article_types' ) );
			}
			if( $categoryId )
			{
				$articleInfo += array( 'category' => $categoryId );
			}
			$link .= http_build_query( $articleInfo );  
 */
			$this->setViewContent( '<a href="' . $this->buildQueryForRequestedPosts( self::getPostUrl() . '/post/creator/?' ) . '">' . $addPostMessage . '</a>' );
		}
	//	if( self::hasPriviledge() )
	//	{
	//	}
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
			return;
		}
	//	var_export( $this->getDbData() );
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
					$data = $data['article_url']; 
				}
				
				//	Module can now send full path
				//	$path = $data;
				if( ! is_array( $data ) )
				{
					if( ! is_file( $data ) )
					{
						$data = self::getFolder() . $data;
						if( ! is_file( $data ) )
						{
							$data = false;
						//	self::setIdentifierData( $data );
						}
					}
					$data = @include $data;
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
		
		$this->_xml = '<span>';
  		$values = $this->getDbData();
	//	krsort( $values );
	//	var_export( $values );
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
		 
		
		if( self::hasPriviledge( @$articleSettings['allowed_writers'] ) && $this->getParameter( 'add_a_new_post' ) ) 
		{ 
			$tempItem = array_shift( $values );
			//	make the first item a link to add a new post
		//	$newArticleType = is_string( $this->getParameter( 'article_types' ) ) && $this->getParameter( 'article_types' ) ? $this->getParameter( 'article_types' ) : 'post';
		//	self::v( $tempItem );
			$tempItem2 = $tempItem;
			if( is_string( $tempItem2 ) )
			{
				$tempItem2 = include( $tempItem );
			}
			$newArticleType = @$tempItem2['article_type'];
			$newArticleTypeToShow = ucfirst( $newArticleType );
	//		self::v( $newArticleType );
	//		self::v( $newArticleTypeToShow );
			$item = array( 
							'article_url' => ( $this->buildQueryForRequestedPosts( self::getPostUrl() . '/post/creator/?' ) . '&article_type=' . $newArticleType ), 
							'allow_raw_data' => true, 
						//	'article_type' => $newArticleType, 
							'always_allow_article' => $this->getParameter( 'article_types' ), 
							'category_name' => $this->getParameter( 'category_name' ), 
							'document_url' => @$tempItem2['document_url'] ? ( 'http://placehold.it/' . ( @$articleSettings['cover_photo_width'] ? : '900' ) . 'x' . ( @$articleSettings['cover_photo_height'] ? : '300' ) . '&text=' . ( @$articleSettings['cover_photo_width'] ? : '900' ) . 'x' . ( @$articleSettings['cover_photo_height'] ? : '300' ) . '' ) : null, 
							'user_id' => Ayoola_Application::getUserInfo( 'user_id' ),
							'publish' => true, 
							'auth_level' => $articleSettings['allowed_writers'], 
							'article_tags' => '', 
							'username' => Ayoola_Application::getUserInfo( 'username' ), 
							'article_title' => 'Click here to add a new ' . $newArticleTypeToShow . '', 
							'article_description' => 'The short description for the new ' . $newArticleTypeToShow . ' will appear here. The short description should be between 100 and 300 characters.', 
							'article_content' => '<p> ' . $newArticleTypeToShow . ' article will be displayed here. You can <strong>format </strong>your <em>article </em>using any HTML <s>style</s> you want. You can use <span style="color:#FF0000">c</span>o<span style="color:#008000">l</span>o<span style="color:#0000FF">r</span>s. <span style="color:#008000">You </span>can <span style="background-color:#FFFF00">add</span> as many images as you want in this space.</p>
							<p>You can also use mathematical expressions in the article e.g. X<sup>2 -y</sup> + 2x = y.</p>
							', 
						);
		//	self::v( $item );
		//	if( $tempData = $this->retrieveArticleData( $tempItem ) )
			{
		//		$item = array_merge( $tempData, $item ); 
			}
			//	now a second row
		//	$item['article_type'] = $tempItem[]
		
			
			array_unshift( $values, $item );   
		//	array_push( $values, $item );   
			array_unshift( $values, $tempItem );
		}
		
		//	Split to chunk\
	//	$_REQUEST['list_page_number'] = 0;
		if( self::$_listCounter != @$_REQUEST['list_counter'] )
		{
			$_REQUEST['list_page_number'] = 0;
		}
		$offset = intval( $_REQUEST['list_page_number'] );
		
	//	self::v( self::$_listCounter );
	//	self::v( $_REQUEST['list_counter'] );
		$chunk = array_chunk( $values , $j );
		if( @$chunk[$offset] )
		{
			$values = $chunk[$offset];
			++$offset;
		}
		else
		{
			$values = $chunk;
			$values = array_pop( $values );
		}
	//	self::v( $values );
		
	//	$nextPageLink = $this->buildQueryForRequestedPosts( self::getPostUrl() . '/?' ) . '&list_counter=' . self::$_listCounter . '&list_page_number=' . @$offset;
		$nextPageLink = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . @$offset;
		$this->_objectTemplateValues['paginator_first_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=0';
		$this->_objectTemplateValues['paginator_last_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . ( @count( $chunk ) - 1 );
		$this->_objectTemplateValues['paginator_previous_page'] = '?&list_counter=' . self::$_listCounter . '&list_page_number=' . ( @$offset - 1 );
		$this->_objectTemplateValues = $this->_objectTemplateValues ? : array();
		$this->_objectTemplateValues['paginator_next_page'] = $nextPageLink;
		
			//		self::v( $values );
		$values = array_unique( $values );
		while( $values )
		{
			if( $i >= $j )
			{ 
			//	var_export( $i );
			//	var_export( $j );
				break; 
			}
			//	var_export( $i );
			//	var_export( $j );
		//	if( has )
			{
				
			}
		//	else
			{
			//	$data = array();
				$data = array_shift( $values );
			//	self::v( $data );
			}
		//	else
			{
		//		$data = array_map( 'strip_tags', array_pop( $values ) );
			}
			//	var_export( $data );
			//	Allow injection of data
	//		;
	
			if( ! $data = $this->retrieveArticleData( $data ) )
			{
				continue;
			}
			
			//	$url = $data;
		//	if( isset( $done[$data['article_id']] ) ){ continue; }
		//	$done[$data['article_id']] = true;
			static::sanitizeData( $data );
			$this->_xml .= '<span>';
/*   		if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
			{
		//		var_export( count( $files ) );
				var_export( '<br />' );
				var_export( $data );
				var_export( '<br />' );
			}
 */		//	var_export( $data );
		//	if( ! is_file( $data ) )
			{
		//		continue;
			//	self::setIdentifierData( $data );
			}
			
			//	CATEGORIES
			$data['category_name'] = @$data['category_name'] ? : array();
			
			//	show only requested type
			switch( gettype( $this->getParameter( 'article_types' ) ) )
			{
				case 'array':
			//		var_export( $data['article_type'] ); 
					if( ! in_array( @$data['article_type'], $this->getParameter( 'article_types' ) ) && ! @$data['always_allow_article'] )
					{
						continue 2;
					}
				break;
				case 'string':
			//		if(  )
					{
					
					}
					if( $this->getParameter( 'article_types' ) && ( @$data['article_type'] !== $this->getParameter( 'article_types' ) ) && ! @$data['always_allow_article'] )
					{
				//	self::v( $this->getParameter( 'article_types' ) ); 
				//	self::v( $data['article_type'] ); 
						continue 2;
					}
				break;
			}
			
			//	show only requested categories group
/* 			if( is_array( $this->getParameter( 'category_group' ) ) &&  count( array_intersect( $data['category_name'], $this->getParameter( 'category_group' ) ) ) !== count( $this->getParameter( 'category_group' ) ) )
			{
				continue;
			}
 */			
			
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
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			$maxWith = ( @$articleSettings['cover_photo_width'] ? : 900 );
			$maxHeight =( @$articleSettings['cover_photo_height'] ? : 300 ); 
			if( @$data['document_url_base64'] && ! @$data['document_url'] && @$data['article_url'] )
			{
			//	$data['document_url'] = $data['document_url_base64'];
				$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . filemtime( self::getFolder() . @$data['article_url'] ); 
			}
		
 			if( $this->getParameter( 'skip_ariticles_without_cover_photo' ) && ( ! Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ? : @$data['display_picture'] ) ) )
			{
				//	Post without image is not allowed 
				continue;
			}
		//	var_export( $data );
			
		//	var_export( array_intersect( $data['category_name'], $this->getParameter( 'category_group' ) ) );
			
			if( 
				( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) && ! self::isOwner( @$data['username'] ) && ! @in_array( 'publish', $data['article_options'] ) )  
				|| ! self::hasPriviledge( @$data['auth_level'] )
			)
			{
		//		var_export( $data );
				continue;
			//	self::setIdentifierData( $data );
			}
			//	self::v( $data );
			
			$url = strtolower( $data['article_url'] );
	//		var_export( $this->getParameter( 'article_types' ) );
			$data += is_array( $this->_objectTemplateValues ) ? $this->_objectTemplateValues : array();
			if( $this->getParameter( 'length_of_description' ) )
			{
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_description'] = strlen( $data['article_description'] ) < $this->getParameter( 'length_of_description' ) ? $data['article_description'] : substr( $data['article_description'], 0, $this->getParameter( 'length_of_description' ) ) . '...';
				}
				else
				{
					@$data['article_description'] = mb_strimwidth( $data['article_description'], 0, $this->getParameter( 'length_of_description' ), "..." );
				}
			}
	
			
			if( $this->getParameter( 'length_of_title' ) )
			{
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_title'] = strlen( $data['article_title'] ) < $this->getParameter( 'length_of_title' ) ? $data['article_title'] : substr( $data['article_title'], 0, $this->getParameter( 'length_of_title' ) ) . '...';  
				}
				else
				{
					@$data['article_title'] = mb_strimwidth( $data['article_title'], 0, $this->getParameter( 'length_of_title' ), "..." );
				}
			}
			if( $this->getParameter( 'modified_time_representation' ) )
			{
				$timeToShow = (array) $this->getParameter( 'modified_time_representation' );
				foreach( $timeToShow as $key => $each )
				{
				//	var_export( date( $each, $data['article_modified_date'] ) );
				//	var_export( $key );
					@$data['modified_time_representation_' . $key] = date( $each, $data['article_modified_date'] ? : ( time() - 1 ) );
				}
				@$data['article_date_M'] = date( 'M', $data['article_modified_date'] );
				@$data['article_date_Y'] = date( 'Y', $data['article_modified_date'] );
				@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );
			}
		//	var_export( $data );
			
			
			//	content
		//	var_export( $data );
	//		$this->_xml .= '<p style="">' . @$data['article_description'] .  ' <a title="Click to read more on this post" href="' . $url . '"> Read more... </a> ' . '</p>'; 
			if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )
			{
				$imageLink = '<a href="' . $url . '" onClick=""><img class="' . __CLASS__ . '_IMG" style="float:left; max-width:100%;" src="' . $image . '" alt="' . $data['article_title'] . "'s cover photo" . '" title="' . $data['article_title'] . "'s cover photo" . '"/></a>';
				
				//	Create this template placeholder value so we can have solve the problem of blank image tags in template markups
				$data['cover_photo_with_link'] = $imageLink;
				$this->_xml .= $imageLink;
			}
			$data['button_value'] = $this->getParameter( 'button_value' );
			
			
			//	CATEGORIES
 			@$categoryToUse = is_array( $data['category_name'] ) ? $data['category_name'] : array();
	//		@$data['category_id'] = is_array( $data['category_id'] ) ? $data['category_id'] : array(); 
 
		//	$data['category_name'] = @$data['category_name'] ? : array();
	//		$data['category_id'] = @$data['category_id'] ? : array();
		//	$data['category_name'] = array_merge( $data['category_name'], $data['category_id'] );
			$categoryTextRaw = self::getCategories( $categoryToUse, array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
		//	self::v( $categoryText );
			$categoryText = $categoryTextRaw ? ' in ' . $categoryTextRaw : null;
			
			//	Social Media
			$parameter = array( 'url' => $url, 'title' => $data['article_title'] );
		//	$this->_xml .= Application_GooglePlus_Share::viewInLine( $parameter );
		//	$this->_xml .= Application_Facebook_Like::viewInLine( $parameter );
		//	$this->_xml .= '<hr />';
			$this->_xml .= '<span style=""><h2><a href="' . $url . '">' . $data['article_title'] . '</a> </h2></span>';
			$this->_xml .= '<div>';
			$this->_xml .= '<button style="" onClick="this.nextSibling.style.display=\'\';">Share...</button>';
			$this->_xml .= '<div style="display:none;">' . Application_SocialMedia_Share::viewInLine( $parameter ) . '</div>';  
			if( self::isOwner( @$data['user_id'] ) || self::hasPriviledge( @$articleSettings['allowed_editors'] ) )
			{
	//			$editLink = self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'];
		//		$this->_xml .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button>';
		//		$this->_xml .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">Delete...</button>';
				$editLink = self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'];
				$editLinkHTML = null;
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button>';
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">Delete...</button>';
			//	$this->_objectData['edit_link'] = $editLinkHTML;
				$data['edit_link'] = $editLinkHTML;
				$this->_xml .= $editLinkHTML;
			}
			$this->_xml .= '</div>'; 
		//	var_export( $data['article_type'] );
		
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
			if( isset( $data['item_price'] ) ) 
			{
				//	Filter the price to display unit
				@$data['item_price'] = $data['item_price'] ? : '0.00';
				$filter = 'Ayoola_Filter_Currency';
				$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
				$data['currency'] = $filter::$symbol;
				$filter = new $filter();
				
				@$data['price_percentage_savings'] =  intval( ( ( $data['item_old_price'] - $data['item_price'] ) / $data['item_old_price'] ) * 100 ) . '%';
				@$data['item_old_price'] = $data['item_old_price'] ? $filter->filter( $data['item_old_price'] ) : null;
				$data['item_price'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
				
				//	Split to naira / kobo
				$filter = new $filter();
				$filter::$symbol = '';
				$data['item_price_with_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
				$data['item_price_without_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
				$data['item_price_before_decimal'] = array_shift( explode( '.', $data['item_price_without_currency'] ) );
				$data['item_price_after_decimal'] = array_pop( explode( '.', $data['item_price_without_currency'] ) );
			
			}
		//	self::v( $data['article_type'] );
			switch( @$data['article_type'] )
			{
				case 'subscription':
					
					
					$this->_xml .= '<p style="">
											<strong>Price:</strong> 
											' . ( $data['item_old_price'] ? '
											<span class="badnews" style="text-decoration:line-through;"> ' . $data['item_old_price'] . ' </span>' : '' ) . $data['item_price'] . '
									</p> ';
					$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
					$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( array( 'data' => $data, 'button_value' => $this->getParameter( 'button_value' ) ) );
					$this->_xml .= $data['button_add_to_cart'];
					$this->_xml .= @$data['article_content'];
				break; 
				case 'user_information':
				//	var_export( $data );
			//		var_export( $data['display_name'] );
					//	Lifted from http://stackoverflow.com/questions/3776682/php-calculate-age
					if( $this->getParameter( 'show_sex_of_user_in_full' ) )
					{
						switch( @$data['sex'] )
						{
							case 'F':
								$data['sex'] = 'Female';
							break;
							case 'M':
								$data['sex'] = 'Male';
							break;
						}
					}

					//	Lifted from http://stackoverflow.com/questions/3776682/php-calculate-age
					if( $this->getParameter( 'calculate_age' ) )
					{
						//date in mm/dd/yyyy format; or it can be in other formats as well
						$birthDate = @$data['birth_date'];
						//explode the date to get month, day and year
						$birthDate = explode("-", $birthDate);
						//get age from date or birthdate
						$age = (date("md", date("U", @mktime(0, 0, 0, @$birthDate[1], @$birthDate[2], @$birthDate[0]))) > date("md")
						? ((date("Y") - $birthDate[0]) - 1)
						: (date("Y") - $birthDate[0]));
						$data['age'] = $age;
					}
				//	echo "Age is:" . $age;					
				//	self::v( $data );
					
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
				//	$this->_xml .= '<span style="display:inline;"><strong>Name:</strong> ' . ( $data['full_legal_name'] ?  : 'None' ) . '</span> ';
				//	exit();
					@$this->_xml .= '<span style="display:inline;"><strong>From: </strong> ' . $data['_city'] . ', ' .  $data['_province'] . ', ' .  $data['_country'] . ' ' .  $data['_zip'] . '</span> ';
					$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;					
					$this->_xml .= '<span style="" class="boxednews goodnews"> <a href="' . $url . '">View Profile...</a> </span> ';
				break;
				case 'audio':
				case 'music':
				case 'message':
				case 'e-book':
				case 'document':
				case 'file':
				case 'download':
					//	title
		//			$this->_xml .= '<h2 style=""><a title="Click to read more and comment on this download" href="' . $url . '">' . $data['article_title'] . '</a></h2>';
					
					//	Version
					$this->_xml .= '<span style="display:inline;"><strong>Version:</strong> ' . ( @$data['download_version'] ? $data['download_version'] : 'None' ) . '</span> ';
					
					//	By
		//			$this->_xml .= '<span style="display:inline;"><strong>Uploaded by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
					$this->_xml .= ' ' . self::filterTime( $data );
					$this->_xml .= '' . $categoryText;
					
					$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
					
					$data['download_button'] = '<a title="Go to the download page." href="' . $url . '"><input type="button" value="Download Now" /></a>';
					$this->_xml .= $data['download_button'];
				//	$this->_xml .= '<a title="Go to the download page." href="' . $url . '"><input type="button" value="Download Now" /></a>';
				//	$this->_xml .= Application_Article_Type_Download::viewInLine( array( 'data' => $data ) );
				break;
				case 'video':
					
					//	By
				//	$this->_xml .= '<span style="["><strong>Video by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
					$this->_xml .= '  ' . self::filterTime( $data );
					$this->_xml .= '' . $categoryText;
					$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
					
					$this->_xml .= Application_Article_Type_Video::viewInLine( array( 'data' => $data ) );
				break;
				case 'poll':
					//	title
			//		$this->_xml .= '<h2 style=""><a title="Click to read more and comment on this poll" href="' . $url . '">' . $data['article_title'] . '</a></h2>';
					
					//	By
			//		$this->_xml .= '<span style="["><strong>Poll by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
					$this->_xml .= '  ' . self::filterTime( $data );
					$this->_xml .= '' . $categoryText;

					$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
					
				//	$this->_xml .= Application_Article_Type_Poll::viewInLine( array( 'data' => $data ) );
					$this->_xml .= '<a title="Go to the voting page." href="' . $url . '"><input type="button" value="Vote Now" /></a>';
				break;
				case 'quiz':
					//	title
			//		$this->_xml .= '<h2 style=""><a title="Click to take this online test" href="' . $url . '">' . $data['article_title'] . '</a></h2>';
					
					//	By
		//			$this->_xml .= '<span style=""><strong>Quiz by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
					$this->_xml .= '  ' . self::filterTime( $data );
					$this->_xml .= '' . $categoryText;

					
					$this->_xml .= '<a title="Click to start test." href="' . $url . '"><input  class="boxednews goodnews" type="button" value="Start Test (' . Ayoola_Filter_Time::splitSeconds( $data['quiz_time'] ? : 0, 2 ) . ')" /></a>';
//					$this->_xml .= '<h2><a title="Click to start test" href="' . $url . '">Click here to Start Test (' . Ayoola_Filter_Time::splitSeconds( $data['quiz_time'], 2 ) . ') </a></h2><p>' . $data['article_description'] . '</p>';
			//		$this->_xml .= Application_Article_Type_Quiz::viewInLine( array( 'data' => $data ) );
				break;
				default:
					//	title
				//	$this->_xml .= '<h2 style=""><a title="Click to read more and comment on this post" href="' . $url . '">' . $data['article_title'] . '</a></h2>';
					
					//	By
				//	$this->_xml .= '<span style=""><strong>by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
					//		$this->_xml .= '<p>' . $data['article_description'] . '</p>';
					$this->_xml .= '  ' . self::filterTime( $data );
					$this->_xml .= '' . $categoryText;
					
					$this->_xml .= '<span style="">';
					$this->_xml .= @$data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
					$this->_xml .= @$data['article_content'];
					$this->_xml .=	'</span>';
				break;
			}
			
			//	destroy float
			$this->_xml .= $data['clear_float'] = '<div style="clear:both;"></div>'; 
		//	$this->_xml .= '<hr />';
	//		$this->_xml .= self::getQuickLink( $data );
		//	$this->_xml .= $categoryText;
			
			//	useful in the templates
			$data['article_quick_links'] = self::getQuickLink( $data );
			$data['comment_count'] = '0';
			$data['category_html'] = $categoryTextRaw;
			$data['record_count'] = $i; 
			
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
			//	hashtags
			@$tags = array_map( 'trim', explode( ',', $data['article_tags'] ) );
		//	$this->_xml .= self::getHashTags( $tags );			
			
			//	footer
		//	$this->_xml .= self::getFooter( $data );

			$this->_xml .= '</span>';
			$i++;
			$this->_objectData[] = $data;
			
		}
		//	delete so we dont do this twice
		unset( $_POST['PAGECARTON_RESPONSE_WHITELIST'] );

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
		
		//	var_export( $_SERVER );
		$this->_xml .= '</span>';
	//	$this->_xml .= '<a href="' . $this->buildQueryForRequestedPosts( self::getPostUrl() . '/?' ) . '&no_of_articles_to_show=1000"><input type="button" value="Show more..." /></a>';
		$this->_xml .= '<a href="' . $nextPageLink . '"><input type="button" value="Show more..." /></a>';
 		if( ! $i ) 
		{
//			var_export( count( $value ) );   
			//	No post is eligible to be displayed. 
			$this->showMessage();
		}
 	//	var_export( $this->_parameter['markup_template'] );    
	//	var_export( count( $value ) );
    } 
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
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
		$options = $object['class_name'];
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
		
		$options = new Application_Category;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$options = array( 0 => 'All' ) + $filter->filter( $options );
		
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
		$options = array( 'article' => 'Article', 'video' => 'Video', 'post' => 'Post', 'profile' => 'Profile', 'subscription' => 'Product or service', 'download' => 'Download', 'poll' => 'Poll', 'quiz' => 'Online Test', );
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
		
		$options = new Application_Article_Template;
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
