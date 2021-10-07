<?php
/**
 * PageCarton
 * 
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * @var array
     */
	public static $_parameterDefinition = array(
        'add_a_new_post' => array( 
            'type' => 'int',
            'desc' => 'Defines number of placeholder datasets to put in the widget template',
        ),
        'allow_dynamic_category_selection' => array( 
            'type' => 'bool',
            'desc' => 'Set whether widget will respond category set via query strings',
        ),
        'article_types' => array( 
            'type' => 'string',
            'desc' => 'Defines post type to display',
        ),
        'true_post_type' => array( 
            'type' => 'string',
            'desc' => 'Defines PageCarton original post type to display e.g. article, product, video, download, link etc ',
        ),
        'cache_post_list' => array( 
            'type' => 'bool',
            'desc' => 'Set whether to cache the post display result',
        ),
        'cache_timeout' => array( 
            'type' => 'int',
            'desc' => 'Set time in seconds when post cache will expire',
        ),
        'category' => array( 
            'type' => 'string',
            'desc' => 'Defines category of post to display',
        ),
        'cover_photo_height' => array( 
            'type' => 'int',
            'desc' => 'Defines height for cover photo auto-cropping',
        ),
        'cover_photo_width' => array( 
            'type' => 'int',
            'desc' => 'Defines width for cover photo auto-cropping',
        ),
        'default_cover_photo' => array( 
            'type' => 'string',
            'desc' => 'Defines URL to use for default cover photo if no one is set for the post',
        ),
        'inverse_order' => array( 
            'type' => 'bool',
            'desc' => 'Inverse the order of display of the post',
        ),
        'length_of_description' => array( 
            'type' => 'int',
            'desc' => 'Truncates the post description to this length in display',
        ),
        'length_of_title' => array( 
            'type' => 'int',
            'desc' => 'Truncates the post title to this length in display',
        ),
        'list_page_number_offset' => array( 
            'type' => 'int',
            'desc' => 'Set the batch of data to show in pagination - default is 0, could set to any integer value',
        ),
        'no_of_post_to_show' => array( 
            'type' => 'int',
            'desc' => 'How many posts to show per batch',
        ),
        'order_by' => array( 
            'type' => 'string',
            'desc' => 'Defines the key/variable to use to sort the post data. Default is "article_creation_date"',
        ),
        'post_type_custom_fields' => array( 
            'type' => 'array',
            'desc' => 'Set the custom form fields to use when creating and editing post',
        ),
        'show_post_without_this_key' => array( 
            'type' => 'string',
            'desc' => 'Set a key or variable which when present in post info means the post should be ignored when displaying posts',
        ),
        'skip_articles_without_this_key' => array( 
            'type' => 'string',
            'desc' => 'Set a key or variable which when absent means the post should be ignored when displaying posts',
        ),
        'trending' => array( 
            'type' => 'int',
            'desc' => 'Display only post which has had most engagements recently. Sets the number of recent engagements to consider',
        ),
    );

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
			try
			{
				switch( @$_GET['post'] )
				{
					default:
						$this->show();
					break;
				}
			}
			catch( Application_Article_Exception $e )
			{ 
				$this->_parameter['markup_template'] = null;
				$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
			}
			catch( Exception $e )
			{ 
				$this->_parameter['markup_template'] = null;
				$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
			}
		}
		catch( Exception $e )
		{
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
		}
    } 
	
    /**
     * Returns markup for link for new post buildQueryForRequestedPosts
     * 
     * @return string
     */
	public function buildQueryForRequestedPosts( $link )
    {
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
		return $link;
	}
	
    /**
     * Display the real list
     * 
     * @return
     */
	public function showMessage()
    {

		if( empty( $_GET['pc_post_list_id'] ) && ! $this->getParameter( 'add_a_new_post' ) )
		{
			$message = array_pop( $this->_badnews ) ? : 'Posts will be displayed here when they become available.';
			$message = $this->getParameter( 'badnews' ) ? : $message;
		}
		else
		{
			$this->_parameter['markup_template'] = null; 
			$this->_parameter['markup_template_no_data'] = null; 
			$message = '...';
		}

	//	if( ! $values )
		{
			//	switch templates off

		}
		
		$this->setViewContent(  '' . self::__( '<p style="clear: both;" class="pc-notify-normal pc_no_post_to_show pc_give_space_top_bottom"> ' . $message . '</p>' ) . '', true  );

		
		//	Check settings
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' ); 

		//	Only allowed users can write
		if( $this->getParameter( 'add_a_new_post' ) )
		{
			$addPostMessage = is_numeric( $this->getParameter( 'add_a_new_post' ) ) ? self::__( 'Create a new post' ) : $this->getParameter( 'add_a_new_post' );
		}
		else
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

		}

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

			
		//	Allow injection of data
		switch( @$data['article_url'] )
		{
			default:
				if( is_array( $data ) )
				{
					$data = array_merge( $data ? : array(),  $this->getParameter( 'data_to_merge' ) ? : array() );
				}
				if( is_array( $data ) && empty( $data['allow_raw_data'] ) )
				{ 

					$data = $data['article_url']; 
				}
				
				//	Module can now send full path

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
		if( ! empty( $_GET['pc_post_list_id'] ) )
		{
			$postListId = $_GET['pc_post_list_id'];
		}
		else
		{

            $idC = '';
            if( $this->getParameter() )
            {
                $idC .= serialize( $this->getParameter() );
            }
            //  pc_module_url_values_post_type_offset
            if( null !== $this->getParameter( 'allow_dynamic_category_selection' ) || null !== $this->getParameter( 'pc_module_url_values_post_type_offset' ) || null !== $this->getParameter( 'pc_module_url_values_category_offset' )  )
            {
                $idC .= serialize( $_GET );
            }
			$postListId = 'post-id-' . md5( $idC );
		}

		$storage = self::getObjectStorage( array( 'id' => $postListId, 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 44600, ) );
		$storedValues = $storage->retrieve();
		if( ! empty( $storedValues['parameter'] ) && ! empty( $_GET['pc_post_list_autoload'] ) )
		{	

			//	Bring out stored parameters
			$this->setParameter( $storedValues['parameter'] );
		}
		//	Prepare post viewing for next posts
		
		//	Using menu template?
		//	autoload new posts
		$values = array();

		if( ! empty( $storedValues['values'] )  )
		{
			$values = $storedValues['values'];
		}

		if( ! $values || ! $this->getParameter( 'cache_post_list' ) )
		{   

			//	This ensures that data altered by query strings is uploaded when autoloaded
			if( empty( $_REQUEST['pc_post_list_autoload'] ) || empty( $_REQUEST['pc_post_list_id'] )  )
			{
				$values = $this->getDbData();
			}
			
			if( $this->getParameter( 'sort_column' ) )
			{   
				$values = self::sortMultiDimensionalArray( $values, $this->getParameter( 'sort_column' ) );
			}
			else
			{   
				
				if( $values && $this->getParameter( $this->getIdColumn() ) !== $values )
				{
					$sortColumn = @$values[0]['profile_creation_date'] ? 'profile_creation_date' : 'article_creation_date';
					$values = self::sortMultiDimensionalArray( $values, $sortColumn );
					$values = array_reverse( $values );
				}
			}			
			//	sort
            $previousKey = null;
            $recorded = array();
            foreach( $values as $key => $data )
            {
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
                $this->filterData( $data );
                if( ! $data )
                {
                    continue;
                }
                if( ! empty( $data['true_post_type'] ) || empty( $data['not_real_post'] ) )
                {
                    if( ! $dataX = $this->retrieveArticleData( $data ) )
                    {
                        continue;
                    }

                    //	Some old posts does have titles in the table
                    if( empty( $data['article_title'] ) && ! empty( $dataX['article_title'] )&& ! empty( $data['article_url'] ) )
                    {
                        $class = Application_Article_Table::getInstance();
                        $class->update( $dataX, array( 'article_url' => $data['article_url'] ) );
                        $data = $dataX;
                    }
                    $data = is_array( $data ) ? $data : array();
                    $dataX = is_array( $dataX ) ? $dataX : array();
                    $data += $dataX;
                }

                if( ! is_array( $data ) || ! self::isAllowedToView( $data ) )    
                {
                    continue;
                }
                $data['post_list_id'] = $postListId;

                if( ! empty( $recorded[$data['article_url']] ) )
                {
                    continue;
                }
                
                $recorded[$data['article_url']] = true;

                //	Switch
                if( $this->getParameter( 'post_switch' ) )
                {
                    $switches = array_map( 'trim', explode( ',', $this->getParameter( 'post_switch' ) ) );
                    foreach( $switches as $switch )
                    {
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
                elseif( $this->getParameter( 'skip_ariticles_without_this_key' ) )
                {
                    $keys = $this->getParameter( 'skip_ariticles_without_this_key' );
                    if( is_string( $keys ) )
                    {
                        $keys = array_map( 'trim', explode( ',', $keys ) );
                    }
                    foreach( $keys as $eachKey )
                    {
                        if( ! @$data[$eachKey] )
                        {
                            //	Post without this is not allowed 
                            continue 2;
                        }
                    }
                }
                elseif( $this->getParameter( 'show_posts_without_this_key' ) )
                {
                    $keys = $this->getParameter( 'show_posts_without_this_key' );
                    if( is_string( $keys ) )
                    {
                        $keys = array_map( 'trim', explode( ',', $keys ) );
                    }
                    foreach( $keys as $eachKey )
                    {
                        if( @$data[$eachKey] )
                        {
                            //	Post with this is not allowed 
                            continue 2;
                        }
                    }
                }
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
                    if( $this->getParameter( 'use_datetime' ) || $data['true_post_type'] == 'event' )
                    {
                        if( ! empty( $data['datetime'] ) )
                        {
                            $data['datetime'] = strtotime( $data['datetime'] );
                            $data['article_creation_date'] = $data['datetime'];		
                            $data['article_modified_date'] = $data['datetime'];		
                        }
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
                            @$data['modified_time_representation_' . $key] = date( $each, $data['article_modified_date'] ? : ( time() - 1 ) );
                            @$data['article_modified_date_' . $key] = date( $each, $data['article_modified_date'] ? : ( time() - 1 ) );
                            @$data['article_creation_date_' . $key] = date( $each, $data['article_creation_date'] ? : ( time() - 1 ) );
                            if( ! empty( $data['datetime'] ) )
                            {
                                @$data['datetime_' . $key] = date( $each, $data['datetime'] );
                            }
                        }
                    }
                    switch( $this->getParameter( 'post_expiry_time' ) )
                    {
                        case 'future':
                            if( $data['article_modified_date'] < time() && empty( $data['not_real_post'] ) )
                            {
                                continue 2;
                            }
                        break;
                        case 'past':
                            if( $data['article_modified_date'] > time() && empty( $data['not_real_post'] ) )
                            {
                                continue 2;
                            }
                        break;
                        default:
        
                        break;
                    }
    
                    //	get number of views
                    self::getViewsCount( $data );
                    if( $this->getParameter( 'get_views_count' ) )
                    {
                        if( ! $this->viewsTable )
                        {
                            $this->viewsTable =  new Application_Article_Views();
                        }
                        $data['views_count'] = count( $this->viewsTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ddddddddddddd', 'limit' => $this->getParameter( 'limit_for_views_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_views_count_record_search' ) ? : '10' ) ) );
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
                    }
                    //	get number of downloads
                    self::getAudioPlayCount( $data );
                    if( $this->getParameter( 'get_audio_play_count' ) && $data['true_post_type'] == 'audio' )
                    {   
                        if( ! $this->audioTable )
                        {
                            $this->audioTable =  new Application_Article_Type_Audio_Table();
                        }
                        $data['audio_play_count'] = count( $this->audioTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ssss', 'limit' => $this->getParameter( 'limit_for_audio_play_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_audio_play_count_record_search' ) ? : '10' ) ) );
                    }
                    self::getCommentsCount( $data );
                    if( $this->getParameter( 'get_comment_count' ) )
                    {   
                        if( ! $this->commentTable )
                        {
                            $this->commentTable =  new Application_CommentBox_Table();
                        }
                        $data['comments_count'] = count( $this->commentTable->select( null, array( 'article_url' => $data['article_url'] ), array( 'ssss' => 'ssss', 'limit' => $this->getParameter( 'limit_for_audio_play_count' ) ? : '99', 'record_search_limit' => $this->getParameter( 'limit_for_audio_play_count_record_search' ) ? : '10' ) ) );
                    }
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
			
			if( $this->getParameter( 'order_by' ) )
			{   
				$values = self::sortMultiDimensionalArray( $values, $this->getParameter( 'order_by' ) );
			}
			if( $this->getParameter( 'inverse_order' ) )
			{   
				krsort( $values );
			}

			//	Cache results
			$valuesToStore = array( 'values' => $values, 'parameter' => $this->getParameter() );

			// store if it's an independent request
			if( empty( $_GET['pc_post_list_autoload'] ) )
			{
				$storage->store( $valuesToStore );
			}
		}
		$this->_objectTemplateValues['total_no_of_posts'] = count( $values );
		 
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		
		//	default at 1800 so it can always cover the screen
		$maxWith = $this->getParameter( 'cover_photo_width_for_list' ) ? : ( $this->getParameter( 'cover_photo_width' ) ? : ( @$articleSettings['cover_photo_width'] ? : 600 ) );
		$maxHeight = $this->getParameter( 'cover_photo_height_for_list' ) ? : ( $this->getParameter( 'cover_photo_height' ) ? : ( @$articleSettings['cover_photo_height'] ? : 300 ) );    

		//	calculate  the creator link here because of Application_Article_Publisher
		//	So it can see $this->_parameter['add_a_new_post_full_url']
		$where =  $this->_dbWhereClause;
		$truePostType = @array_pop( $where['true_post_type'] ) ? : $this->getParameter( 'true_post_type' );
		$newArticleType = ( @array_pop( $where['article_type'] ) ? : ( $this->getParameter( 'article_types' ) ? : $truePostType ) );
		$postTypeInfo = Application_Article_Type::getInstance()->selectOne( null, array( 'post_type_id' => $newArticleType ) );
		@$newArticleTypeToShow = self::getItemName() ? : ucwords( ( $postTypeInfo['post_type'] ) ? : str_replace( '-', ' ', $newArticleType ) );
		@$newArticleTypeToShow = $newArticleTypeToShow ? : 'Item';
		$categoryForNewPost = @array_pop( $where['category_name'] );
		$addNewPostUrl = ( static::$_newPostUrl ? : 
							( $this->getParameter( 'add_a_new_post_link' ) ? : 
							( ( $this->getParameter( 'add_a_new_post_classplayer' ) ? :  
							'/tools/classplayer/get/name' ) . '/Application_Article_Creator/' )
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

		if( $howManyPostsToAdd > 20 ) 
		{
			$howManyPostsToAdd = 20;
		}

        $noOfPosts = count( $values );
        $howManyPostsToAdd = $howManyPostsToAdd - $noOfPosts;
        if(  $howManyPostsToAdd < 1 )
        {
            $howManyPostsToAdd = 0;
        }
		if( $howManyPostsToAdd && empty( $_GET['pc_post_list_id'] ) ) 
		{ 
			$myProfileInfo = Application_Profile_Abstract::getMyDefaultProfile();
			do
			{
                $tempItem = array_pop( $values );

				if( self::hasPriviledge( @$articleSettings['allowed_writers'] ? : 98 ) ) 
				{
					$item = array( 
						'article_url' => $addNewPostUrl . '&pcx=' . rand( 100, 8900 ), 
						'allow_raw_data' => true, 
						'not_real_post' => true, 
						'always_allow_article' => $this->getParameter( 'article_types' ), 
						'category_name' => $this->getParameter( 'category_name' ), 
						'document_url' => $this->getParameter( 'default_cover_photo' ) ? : '/img/placeholder-image.jpg', 
						'user_id' => Ayoola_Application::getUserInfo( 'user_id' ),
						'publish' => true, 
						'auth_level' => $articleSettings['allowed_writers'], 
						'display_name' => Ayoola_Application::getUserInfo( 'username' ), 
						'username' => Ayoola_Application::getUserInfo( 'username' ), 
						'article_title' => sprintf( PageCarton_Widget::__( 'Add new "%s" here' ), $newArticleTypeToShow ), 
						'article_description' => sprintf( PageCarton_Widget::__( 'The short description for the new "%s" you add will appear here. The short description should be between 100 and 300 characters.' ), $newArticleTypeToShow ), 
					)  + ( $myProfileInfo ? : array() );  
				}
				else
				{
					$item = array( 
						'article_url' => '#' . rand( 100, 8900 ) . ';',  
						'allow_raw_data' => true, 
						'not_real_post' => true, 
						'always_allow_article' => $this->getParameter( 'article_types' ), 
						'category_name' => $this->getParameter( 'category_name' ), 
						'document_url' => $this->getParameter( 'default_cover_photo' ) ? : '/img/placeholder-image.jpg', 
						'publish' => true, 
						'auth_level' => $articleSettings['allowed_writers'], 
						'article_title' => '...', 
						'article_description' => sprintf( PageCarton_Widget::__( 'The short description for the new %s  will appear here. The short description should be between 100 and 300 characters.' ), $newArticleTypeToShow ), 
					);  
				}

				$tempItem ? array_push( $values, $tempItem ) : null;
				$item ? array_push( $values, $item ) : null;
			}
			while( --$howManyPostsToAdd );
		}
		$i = 0; //	counter
		$j = 6; //	6 is our max articles to show
		if( intval( $this->getParameter( 'add_a_new_post' ) ) > 1 )
		{
			$j = $this->getParameter( 'add_a_new_post' );
		}
		$this->_viewOption = intval( $this->_viewOption ) ? : $this->getParameter( 'no_of_post_to_show' );
		$j = $this->_viewOption ? : $j;
		$j = is_numeric( @$_GET['no_of_articles_to_show'] ) ? intval( $_GET['no_of_articles_to_show'] ) : $j; 
		$j = is_numeric( @$_GET['no_of_post_to_show'] ) ? intval( $_GET['no_of_post_to_show'] ) : $j; 
		$j = is_numeric( $this->getParameter( 'no_of_post_to_show' ) ) ? intval( $this->getParameter( 'no_of_post_to_show' ) ) : $j;
		if( $j < intval( $this->getParameter( 'add_a_new_post' ) ) )
		{
			$j = $this->getParameter( 'add_a_new_post' );
		}
		$done = array();
		$template = null;  
 		
		//	Split to chunk\
		$offset = 0;
		$realOffset = 0;
		$offsetDefined = false;
		if( $this->getParameter( 'list_page_number_offset' ) )
		{
			$offset = $this->getParameter( 'list_page_number_offset' );  
			$offsetDefined = true;
		}
		elseif( is_numeric( @$_REQUEST['list_page_number'] ) )
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
		$realOffset = $offset;
		$chunk = array_chunk( $values , $j );
		if( @$chunk[$offset] )
		{
			$values = $chunk[$offset];
			++$offset;
		}
		elseif( intval( $offset ) > 0 && $offsetDefined  )
		{
			//	Seeking a chunk that isn't available'
			$values = array();
		}
		else
		{
			$values = $chunk;
			$values = array_pop( $values );
        }
        $q = '?';
        if( $_GET )
        {
            $q .= http_build_query( $_GET ) . '&';
        }
        $dd = count( $chunk );
        $maxPbefore = intval( $this->getParameter( 'max_paginator_before' ) ? : 5 );
        $maxPAfter = intval( $this->getParameter( 'max_paginator_after' ) ? : 5 );
        $this->_objectTemplateValues['paginator_before'] = array();
        $this->_objectTemplateValues['paginator_after'] = array();

        $start = $realOffset - $maxPbefore;
        if( $start < 0 )
        {
            $start = 0;
        } 
        $end = $realOffset + $maxPAfter + 1;
        for( $d = $start; $d < $end; $d++ )
        {
            if( $d < $realOffset && isset( $chunk[$d] ) )
            {
                if( count( $this->_objectTemplateValues['paginator_before'] ) >= $maxPbefore )
                {
                    $d = $realOffset;
                    continue;
                }
                $this->_objectTemplateValues['paginator_before'][] = array( 'pagination_url' => '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=' . $d, 'pagination_number' => $d + 1 );
            }
            elseif( $d > $realOffset && isset( $chunk[$d] ) )
            {
                if( count( $this->_objectTemplateValues['paginator_after'] ) >= $maxPAfter )
                {
                    break;
                }
               $this->_objectTemplateValues['paginator_after'][] = array( 'pagination_url' => '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=' . $d, 'pagination_number' => $d + 1 );
            }
        }
        $this->autoLoadNewPosts( $postListId, $offset );
		if( @$chunk[$offset] )
		{
			$nextPageLink = '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=' . @$offset;
			$this->_objectTemplateValues['paginator_next_page'] = $nextPageLink;
			$this->_objectTemplateValues['paginator_next_page_button'] = '<a class="pc-btn" href="' . $nextPageLink . '"> ' . self::__( 'Next' ) . ' &rarr;</a>';       
			if( empty( $_GET['pc_post_list_autoload'] ) && $this->getParameter( 'pagination' ) && ! $this->getParameter( 'hide_pagination_buttons' ) )
			{
				$this->_objectTemplateValues['click_to_load_more'] = $linkToLoadMore = '<div style="text-align:center;" class="pc_posts_distinguish_sets" id="' . $postListId . '_pagination"><a class="pc-btn pc-btn-small" href="javascript:" onclick="pc_autoloadFunc_' . $postListId . '();"> Load more</a></div>';     
			}  
		}
		if( @$chunk[( @$offset - 2 )] )
		{
            $this->_objectTemplateValues['paginator_previous_page'] = '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=' . ( @$offset - 2 );
			$this->_objectTemplateValues['paginator_previous_page_button'] = '<a class="pc-btn" href="' . $this->_objectTemplateValues['paginator_previous_page'] . '">&larr; ' . self::__( 'Previous' ) . '</a>';
		}
		if( $offset != 1 )
		{
			$this->_objectTemplateValues['paginator_first_page'] = '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=0';
		}
		if( $offset != ( @count( $chunk ) ) )
		{
			$this->_objectTemplateValues['paginator_last_page'] = '' . $q . 'list_counter=' . self::$_listCounter . '&list_page_number=' . ( @count( $chunk ) - 1 );
		}
		$this->_objectTemplateValues['paginator_current_page_number'] = $realOffset + 1;
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
		$values = $values ? array_unique( $values, SORT_REGULAR ) : array(); 
        $singlePostPaginationInfo = array();
        $timeFilter = new Ayoola_Filter_Time();

        $wellDefinedPostTheme = false;
        if( strpos( $this->_parameter['markup_template'], '}}}{{{0}}}' ) === false && strpos( $this->_parameter['markup_template'], '<!--{{{0}}}' ) === false )  
        {

        }
        else
        {
            $wellDefinedPostTheme = true;
        }
        if( $this->_parameter['markup_template'] && empty( $wellDefinedPostTheme ) )  
        {
            $postThemeInfo = Ayoola_Abstract_Playable::getPostTheme( $this->_parameter['markup_template'] );
            if( stripos( $this->_parameter['markup_template'], $postThemeInfo['start'] ) === false )
            {
                
            }
            else
            {
                $wellDefinedPostTheme = true;
            }
        }

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
			if( @Ayoola_Application::$GLOBAL['post']['article_url'] == $data['article_url'] )
			{
				continue;
			}
			$data['css_class_of_inner_content'] = $this->getParameter( 'css_class_of_inner_content' );
			$data['post_link'] = $data['article_url'];
			$data['post_full_url'] = Ayoola_Page::getHomePageUrl() . $data['article_url'];
			if( @$data['article_url'] && strpos( @$data['article_url'], ':' ) === false && $data['article_url'][0] !== '?'  )
			{
				$data['post_link'] = Ayoola_Application::getUrlPrefix() . $data['article_url'];
			}
			if( @$data['article_url'] && empty( $data['document_url_base64'] )  )
			{
				$data['document_url'] = ( $data['document_url'] ? : $this->getParameter( 'default_cover_photo' ) ) ? : '/img/placeholder-image.jpg'; 
			}
			$data['document_url_plain'] = Ayoola_Application::getUrlPrefix() . $data['document_url']; 
			$data['document_url_uri'] = $data['document_url']; 
			$data['document_url_cropped'] = $data['document_url']; 
			$data['document_url_no_resize'] = $data['document_url']; 
            $data['document_url_photoviewer'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ); 
			if( @$data['document_url'][0] === '/' AND $fileP = Ayoola_Doc::uriToPath( $data['document_url'] ) )
			{
				$data['document_url_no_resize'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_IconViewer/?url=' . @$data['document_url'] . '&document_time=' . @filemtime( $fileP ) . ''; 
				$data['document_url_cropped'] = $data['document_url_no_resize'] . '&max_width=' . $maxWith . '&max_height=' . $maxHeight . ''; 
			}
			elseif( strpos( @$data['document_url'], '//' ) === false && empty( $data['not_real_post'] ) )
			{
				//	This is the default now if they don't have picture, create a placeholder
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
			
			//	Can't be lowercase because of auto create link
			$url = $data['article_url'];

			$data['article_description'] = trim( $data['article_description'] );
			if( empty( $data['article_description'] ) && ! empty( $data['article_content'] ) )
			{
				$data['article_description'] = substr( strip_tags( $data['article_content'] ), 0, 501 ) . '';
			}
			$lengthOfDescription = $this->getParameter( 'length_of_description' ) ? : 500;
			if( $lengthOfDescription )
			{
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_description'] = strlen( $data['article_description'] ) < $lengthOfDescription ? $data['article_description'] : ( trim( substr( $data['article_description'], 0, $lengthOfDescription ) ) . '...' );
				}
				else
				{
					@$data['article_description'] = mb_strimwidth( $data['article_description'], 0, $lengthOfDescription, "..." );
				}
			}
            
            @$data['article_modified_date_filtered'] = $timeFilter->filter( $data['article_modified_date'] );
            $data['article_creation_date_filtered'] = $timeFilter->filter( @$data['article_creation_date'] ? : ( time() - 3 ) ); 
            
            @$data['article_date_M'] = strftime( '%B', $data['article_modified_date'] );
            @$data['article_date_m'] = strftime( '%b', $data['article_modified_date'] );   
            @$data['article_date_Y'] = strftime( '%Y', $data['article_modified_date'] );
            @$data['article_date_y'] = strftime( '%y', $data['article_modified_date'] );
            @$data['article_date_d'] = strftime( '%d', $data['article_modified_date'] );   

			$lengthOfTitle = $this->getParameter( 'length_of_title' ) ? : 160;
			if( $lengthOfTitle )
			{
				$titleToUse = trim( $data['article_title'], '- ' );
				if( ! function_exists( 'mb_strimwidth' ) )
				{
					
					@$data['article_title'] = strlen( $titleToUse ) < $lengthOfTitle ? $titleToUse : ( trim( substr( $titleToUse, 0, $lengthOfTitle ) ) . '...' );  
				}
				else
				{
					@$data['article_title'] = mb_strimwidth( $titleToUse, 0, $lengthOfTitle, "..." );
				}
			}

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
					$singlePostPaginationInfo[$data['article_url']]['pc_previous_post'] = $previousKey;
					$singlePostPaginationInfo[$data['article_url']]['article_url'] = $data['article_url'];
					$singlePostPaginationInfo[$previousKey]['pc_next_post'] = $data['article_url'];
				}
				
				$previousKey = $data['article_url'];
			}
				
			
			//	content
			if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )
			{
                $alt = sprintf( PageCarton_Widget::__( "%s's cover photo" ), $data['article_title'] );
				$imageLink = '<div style=""><a href="' . Ayoola_Application::getUrlPrefix() . $url . '" onClick=""><img class="' . __CLASS__ . '_IMG" style="filter: brightness(50%);-webkit-filter: brightness(50%);-moz-filter: brightness(50%);" src="' . Ayoola_Application::getUrlPrefix() . $image . '" alt="' . $alt . "" . '" title="' . $alt . '"/></a></div>';  
				
				//	Create this template placeholder value so we can have solve the problem of blank image tags in template markups
				$data['cover_photo_with_link'] = $imageLink;
            }

			$data['button_value'] = self::__( $this->getParameter( 'button_value' ) ) ? : '' . self::__( 'Details' ) . '';
			
			
            //	CATEGORIES 
            //  Category not array in the case of category showall
			$categoryTextRaw = self::getCategories( $data['category_name'], array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
			$categoryText = $categoryTextRaw ? '' . $categoryTextRaw : null;
			$data['category_text'] = $categoryText;
			
			//	Social Media
			$parameter = array( 'url' =>  Ayoola_Application::getUrlPrefix() . $url, 'title' => $data['article_title'] );
			if( self::isOwner( @$data['user_id'] ) || self::hasPriviledge( @$articleSettings['allowed_editors'] ? : 98 ) )
			{
				$editLink = self::getPostUrl() . '/post/editor/?article_url=' . $data['article_url'];
				$editLinkHTML = null;
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button>';
				$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">Delete...</button>';
				$data['edit_link'] = $editLinkHTML;
			}
			if( isset( $data['item_price'] ) ) 
			{
				//	Filter the price to display unit
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
				$data['item_price_before_decimal'] = array_shift( explode( '.', $data['item_price_without_currency'] ) );
				$data['item_price_after_decimal'] = array_pop( explode( '.', $data['item_price_without_currency'] ) );
				$data['item_price'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
			
			}
            $data['article_type'] = strtolower( trim( $data['article_type'] ) );
			if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $data['article_type'] ) )
			{
				$postType = @$postTypeInfo['article_type'];
			}
			else
			{
				$postType = @$data['article_type'];
			}
			$data['filtered_time'] = self::filterTime( $data );

			//	internal forms to use
			if( empty( @$postTypeInfo['post_type_options'][0] )  )
			{
				unset( $postTypeInfo['post_type_options'] );
				unset( $postTypeInfo['post_type_options_name'] );
			}
			$features = is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : static::$_defaultPostElements;
			$featuresPrefix = is_array( @$postTypeInfo['post_type_options_name'] ) ? $postTypeInfo['post_type_options_name'] : array();

			if( ! in_array( @$data['true_post_type'], $features ) )
			{
				$features[] = @$data['true_post_type'];
				$featuresPrefix[] = '';
			}
			$featureCount = array();
            $featureDone = array();
            if( ! empty( $data['article_content'] ) )
            {
                $data['article_content'] = '<div class="pc-article-content">' . $data['article_content'] . '</div>';
            }
			foreach( $features as $key => $eachPostType )
			{	
                $eachPostType = strtolower( trim( $eachPostType ) );
				$featureSuffix = @$featuresPrefix[$key];
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
				$featureDone[$featureCountKey] = true;
				switch( $eachPostType )
				{
					case 'gallery':
						$imagesKey = 'images' . $featurePrefix;
						if( $images = $data[$imagesKey] )
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
						$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( array( 'data' => $data, 'button_value' => $this->getParameter( 'button_value' ) ? : $data['button_value'] ) );
					break; 
					case 'category_information':
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
								$data['file_size'] =  filesize( Ayoola_Loader::checkFile(  'documents' . $data['download_url'] ) );
							}
							else
							{
								#	we don't want to use get_headers again. Can make site slow

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
			$this->_xml .= '' . self::getDefaultPostView( $data ) . '';
			
			//	useful in the templates
			$data['comments_count'] = intval( $data['comments_count'] ) ? : '0';
			$data['category_html'] = $categoryTextRaw;
			$data['record_count'] = $i + 1; 
			
			//	compatibility
			$data['category_id'] = $categoryTextRaw;
			if( $this->getParameter( 'markup_template' ) && empty( $wellDefinedPostTheme ) )
			{
                $templateToUse = null;
                if( ! $this->getParameter( 'max_group_no' ) )
                {

                }
                if( ! @$maxGroupNo || $maxGroupNo == 1 )
                {
                    $maxGroupNo = 1;
                    $templateToUse .= $this->getParameter( 'markup_template_prefix' );
                    
                    //	never forget to include the last suffix
                    $lastSuffix = true;
                }
                $templateToUse .= $this->getParameter( 'markup_template' );
                if( ( $this->getParameter( 'max_group_no' ) && $maxGroupNo == $this->getParameter( 'max_group_no' ) ) || empty( $values ) )
                {		
                        $maxGroupNo = 0;
                        $templateToUse .= $this->getParameter( 'markup_template_suffix' );
                        $lastSuffix = false;
                
                }
                $maxGroupNo++;
                $templateD = self::replacePlaceholders( $templateToUse, $data + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
                
                //	fix case where ajax auto-loading didn't fix url prefix in posts
                $templateD = Ayoola_Page_Editor_Text::fixUrlPrefix( $templateD, $this->getParameter( 'url_prefix' ), Ayoola_Application::getUrlPrefix() );
                $template .= $templateD;
			}
			if( @$_POST['PAGECARTON_RESPONSE_WHITELIST'] )
			{			
				//	Limit the values that is being sent
				$whitelist = @$_POST['HTTP_PAGECARTON_RESPONSE_WHITELIST'];
				$whitelist = is_array( $whitelist ) ? $whitelist : array_map( 'trim', explode( ',', $whitelist ) );
				$whitelist = array_combine( $whitelist, $whitelist );
				$data = array_intersect_key( $data, $whitelist );
                
                //	delete so we dont do this twice
                unset( $_POST['PAGECARTON_RESPONSE_WHITELIST'] );
			}
			//	Save
			$allTemplate .= $this->_xml;
			$this->_xml = null; //	reset
            $i++;
            $data['post_type'] = self::__( $data['post_type'] );
            $data['article_type'] = self::__( $data['article_type'] );
            $data['true_post_type'] = self::__( $data['true_post_type'] );
			$this->_objectData[] = $data;
            $this->_objectTemplateValues[] = $data;	
		}

		// store playlist
		if( $this->getParameter( 'single_post_pagination' ) )
		{
			$storage = self::getObjectStorage( array( 'id' => $postListId . '_single_post_pagination', 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 44600, ) );

            //	add it to previous because of autoload clearing this settings
			$prevSinglePostPagination = $storage->retrieve();
            $singlePostPaginationInfo = $singlePostPaginationInfo + ( is_array( $prevSinglePostPagination ) ? $prevSinglePostPagination : array() );

			$storage->store( $singlePostPaginationInfo );

			
			$class = new Application_Article_ViewPagination( array( 'no_init' => true ) );
			$storageForSinglePosts = $class::getObjectStorage( array( 'id' => 'post_list_id' ) );
			$storageForSinglePosts->store( $postListId );
		}
        $this->_xml = '' . $allTemplate . '';
		
		if( empty( $_GET['pc_post_list_autoload'] ) )
		{
			$this->_xml .= $pagination;
			if( $template && $this->getParameter( 'pagination' ) )
			{
				$template = '' . $template . $pagination;
			}
		}
		else
		{
			$this->_parameter['markup_template_append'] = null;
			$this->_parameter['markup_template_prepend'] = null;  
        }	

       
		if( empty( $wellDefinedPostTheme ) )  
		{
			//	update the markup template
			@$this->_parameter['markup_template'] = null;
			
			//	allows me to add pagination on post listing with predefined suffix
			@$this->_parameter['markup_template'] .= $template; 
			@$this->_parameter['markup_template'] .= @$lastSuffix ? $this->_parameter['markup_template_suffix'] : null;
			
			//	Allows me to put the pagination
			$this->_parameter['markup_template_prefix'] = null;
			$this->_parameter['markup_template_suffix'] = null;  
		}
		else
		{
			//	this adds markup to some that we want clean like slideshows
			if( $this->getParameter( 'pagination' ) )
			{
				@$this->_parameter['markup_template'] .= $linkToLoadMore;   
			}
		}
 		if( ! $i ) 
		{
			//	No post is eligible to be displayed. 
			$this->showMessage();
		}
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
        if( $this->getParameter( $this->getIdColumn() ) && is_array( $this->getParameter( $this->getIdColumn() ) ) )
		{
            $this->_dbData = $this->getParameter( $this->getIdColumn() );
            return false;
		}

        if( is_numeric( $this->getParameter( 'pc_module_url_values_post_type_offset' ) ) && @array_key_exists( $this->getParameter( 'pc_module_url_values_post_type_offset' ), $_REQUEST['pc_module_url_values'] ) )
        {
            $postType = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_post_type_offset' ) )];
        }
        elseif( $this->getParameter( 'allow_dynamic_category_selection' ) )
        {
            @$postType = $_REQUEST['article_type'] ? : $_REQUEST['post_type'];  
        }
        if( is_numeric( $this->getParameter( 'pc_module_url_values_category_offset' ) ) && @array_key_exists( $this->getParameter( 'pc_module_url_values_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
        {
            $categoryId = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_category_offset' ) )];
            if( $categoryId == 'category' )
            {
                $categoryId = @$_REQUEST['category'];
            }
        }
        elseif( @$_REQUEST['category'] &&  $this->getParameter( 'allow_dynamic_category_selection' ) )
        {
            $categoryId = $_REQUEST['category'];  
        }

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
		$categoryName = null;
        $table = Application_Category::getInstance();
        $categoryError = sprintf( PageCarton_Widget::__( 'There are no recent posts in the %s category.' ), $categoryId );
		if( $categoryId && is_numeric( $categoryId ) ) 
		{
			$category = $table->selectOne( null, array( 'category_id' => $categoryId ) );
			$this->_badnews[] = $categoryError;
		}
		elseif( $categoryId && is_string( $categoryId ) )
		{
			//	Get the numeric category ID from the  DB
			$category = $table->selectOne( null, array( 'category_name' => $categoryId ) );
		
			$this->_badnews[] = $categoryError;
			
			if( ! $category )
			{

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
				Ayoola_Page::setCurrentPageInfo( $pageInfo );
			}
		}
		$path = self::getFolder();
		$pathToSearch = $path;
		$output = array();
		$whereClause = array();
		if( $this->getParameter( 'show_post_by_me' ) )
		{
			if( ! Ayoola_Application::getUserInfo( 'username' ) )
			{
				//	show_post_by_me exclusive to signed-inn user. No username means this should be empty
				$this->_dbData = array();
				return false;
			}
			$this->setParameter( array( 'username_to_show' => Ayoola_Application::getUserInfo( 'username' ) ) );
		}
		elseif( $this->getParameter( 'show_profile_posts' ) && @Ayoola_Application::$GLOBAL['profile']['profile_url'] )
		{
			$this->setParameter( array( 'profile_to_show' => strtolower( Ayoola_Application::$GLOBAL['profile']['profile_url'] ) ) );
		}
		elseif( $this->getParameter( 'search_mode' ) && ( $this->getParameter( 'q' ) || @$_REQUEST['q'] ) )
		{
            $q = $this->getParameter( 'q' ) ? : @$_REQUEST['q'];
			switch( $this->getParameter( 'search_mode' ) )
			{
				case 'keyword':
					$keywords = array_map( 'trim', explode( ' ', $q ) );
					$keywordPaths = null;
					while( $keywords )
					{
						$keyword = array_shift( $keywords );
						$whereClause['*'][] = $keyword;
					}
					$path = $keywordPaths ? : $path; 
				break;
				case 'phrase':
				default:      
					$whereClause['*'] = $q;
				break;
			}
		}
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
				case 'view':
				case 'views':
				case 'views_count':

				break;
				case 'play':
				case 'audio_play_count':
					$table = Application_Article_Type_Audio_Table::getInstance();
				break;
				case 'download':
				case 'downloads':
				case 'download_count':
					$table = Application_Article_Type_Download_Table::getInstance();
				break;
				case 'comments_count':
				case 'comment':
				case 'comments':
					$table = Application_CommentBox_Table::getInstance();
				break;
            }
			$noOfTrends = intval( $this->getParameter( 'trending' ) ) > 9 ? $this->getParameter( 'trending' ) : 100;
            //    var_export( $noOfTrends );
            $trendingPost = $table->select( 'article_url', null, array( 'row_id_column' => 'article_url', 'record_search_limit' => $noOfTrends ) );
			if( $trendingPost )
			{
                if( count( $trendingPost ) < intval( $this->getParameter( 'add_a_new_post' ) ) )
                {
                    $this->_parameter['add_a_new_post'] = count( $trendingPost );
                }    
				$whereClause[$this->getIdColumn()] = $trendingPost;
			}
			@$this->_parameter['order_by'] = $this->_parameter['order_by'] ? : 'engagement_count';
			@$this->_parameter['inverse_order'] = isset( $this->_parameter['inverse_order'] ) ? $this->_parameter['inverse_order'] : true;
        }

		@$postType = $this->getParameter( 'article_types' ) ? : $postType;
        $postType = strtolower( trim( $postType ) );
		if( $postType )
		{
			$whereClause['article_type'][] = $postType;
			if( @$this->_parameter['article_types_plus_original'] ) 
			{
				if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $postType ) )
				{
					$postType = $postTypeInfo['article_type'];
				}
				$whereClause['article_type'][] = $postType;
			}
			
			@$path = $realPostTypePath . ' ' . $allOriginalPostTypes;
		
		} //	For profiles
		elseif( @$this->_parameter['access_level'] )
		{
			$whereClause['access_level'][] = $this->_parameter['access_level'];
		}
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
		}
        $classKey = __CLASS__;
        $keyFunction = array( __CLASS__, 'filterSearch' );
        try
        {
            $table = $this->_postTable;
            if( empty( $whereClause ) )
            {
                if( empty( $_REQUEST['pc_load_old_posts']))
                {
                    $table = $table::getInstance( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
                    $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
                    $this->_dbData = $table->select( null, null, array( 'workaround-to-avoid-cache', 'key_filter_function' => array( 'article_url' => $keyFunction ) ) );
                }  
                else
                {
                    $this->_dbData = Ayoola_Doc::getFilesRecursive( self::getFolder() );
                    krsort( $this->_dbData ); 
                }
            }
            else
            {
                $table = $table::getInstance();
                $this->_dbWhereClause = $whereClause;
                $this->_dbData = $table->select( null, $whereClause, array( 'key_filter_function' => array( 'article_url' => $keyFunction ) ) );
            }

        }
        catch( Exception $e )
        { 
            //	Sometimes we have invalid dirs that causes an exception
            null;
        }
		//	Removing dependence on Ayoola_Api for showing posts		
 		if( ! is_null( $this->_dbData ) )
		{ 

			return true; 
		}
		else
		{
			$this->_dbData = array();
			return false; 
		}
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

		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object

		$options = get_called_class();

		if( ! Ayoola_Loader::loadClass( $options ) )
		{
			return false;
		}

		$options = new $options( array( 'no_init' => true ) );

		$options = (array) $options->getClassOptions();

        $html .= '<select data-parameter_name="option">';
        $html .=  '<option value="">' . self::__( 'No of Posts to Show' ) . '</option>';  

		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  

			if( $object['option'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		
		$options = Application_Category_ShowAll::getPostCategories();

		$filter = new Ayoola_Filter_SelectListArray( 'category_name', 'category_label');
		$options = array( '' => 'All' ) + $filter->filter( $options );
		
		$html .= '<select data-parameter_name="category_name">';
        $html .=  '<option value="">' . self::__( 'Category' ) . '</option>';  
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  

			if( @$object['category_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . self::__( '' . $value . '' ) . '</option>';  
		}
		$html .= '</select>';
		
		//	Article Types
		$options = Application_Article_Type::getInstance();
		$options = $options->select();

		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'post_type_id', 'post_type');
		$options = $filter->filter( $options );
		$options = $options ? : Application_Article_Type_TypeAbstract::$presetTypes;
		$options = array( '' => 'All' ) + $options;
		  
		$html .= '<select data-parameter_name="article_types">';
        $html .=  '<option value="">' . self::__( 'Post Type' ) . '</option>';  
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  

			if( @$object['article_types'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . self::__( '' . $value . '' ) . '</option>';  
		}
		$html .= '</select>';
		$options = Application_Article_Template::getInstance();
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'template_name', 'template_label');
		$options = array( '' => 'Default' ) + $filter->filter( $options ); 
		
		$html .= '<select data-parameter_name="template_name">'; 
        $html .=  '<option value="">' . self::__( 'Post Display Style' ) . '</option>';  
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  

			if( @$object['template_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . self::__( '' . $value . '' ) . '</option>';  
		}
		$html .= '</select>';
		return $html;
	}

    /**
     * Returns an array of other classes to get parameter keys from
     *
     * @param void
     * @return array
     */
    public static function filterSearch( & $value, & $otherData, & $searchTerm )
    {
		$searchTerm = json_encode( Application_Article_ShowAll::loadPostData( $value ) );
	}

    /**
     * Returns an array of other classes to get parameter keys from
     *
     * @param void
     * @return array
     */
    protected static function getParameterKeysFromTheseOtherClasses( & $parameters )
    {

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
