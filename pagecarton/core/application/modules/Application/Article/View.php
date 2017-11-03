<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';    


/**
 * @category   PageCarton CMS
 * @package    Application_Article_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_View extends Application_Article_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'article_name',  );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			//	var_export( $this->getIdentifierData() ); 
			if( ! $data = $this->getIdentifierData() )
			{
				return false;				
			}
			//	self::v( $data ); 
			if( ! $data  
				|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) && ! @in_array( 'publish', @$data['article_options'] ) && Ayoola_Application::getUserInfo( 'username' ) !== $data['username'] )   
				|| ( ! self::hasPriviledge( @$data['auth_level'] ) && ! self::isOwner( @$data['user_id'] ) )
			)
			{
		//		var_export( @$data['user_id'] );
		//		exit();
			//	var_export( self::hasPriviledge( @$data['auth_level'] ) );
				if( Ayoola_Application::$GLOBAL['article_url'] === $data['article_url'] )
				{
					
					//	IF WE ARE HERE, WE ARE NOT AUTHORIZED
					$access = Ayoola_Access::getInstance();          
					$access->logout();
					$login = new Ayoola_Access_Login();  
					$login->getObjectStorage( 'pc_coded_login_message' )->store( 'You are not authorized to view this post. Please log in with an authorized account to continue' );
					
					header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/accounts/signin/?pc_coded_login_message=1&previous_url=' . $data['article_url'] );
					exit();
				}
				
				return $this->setViewContent( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again. ' . self::getQuickLink() . '</p>', true );
			//	self::setIdentifierData( $data );
			}
		//	var_export( self::hasPriviledge( @$data['auth_level'] ) );
			$this->setViewContent( self::getXml(), true );

		//	if( $this->getParameter( 'pagination' ) )
			{
				$pagination = null;

				//	Prepare post viewing for next posts
				$storageForSinglePosts = self::getObjectStorage( array( 'id' => 'post_list_id' ) );
				
				$postListId = $storageForSinglePosts->retrieve();

				$postList = Application_Article_ShowAll::getObjectStorage( array( 'id' => $postListId, 'device' => 'File' ) );
				$postList = $postList->retrieve();
				if( ! empty( $postList['single_post_pagination'] ) )
				{
					$postList = $postList['single_post_pagination'][$data['article_url']];
					if( ! empty( $postList['pc_next_post'] ) )
					{
						if( $nextPost = self::loadPostData( $postList['pc_next_post'] ) )
						{
							$this->_objectTemplateValues['paginator_next_page'] = Ayoola_Application::getUrlPrefix() . $postList['pc_next_post'];
							$this->_objectTemplateValues['paginator_next_page_button'] = '<a class="pc-btn" href="' . $this->_objectTemplateValues['paginator_next_page'] . '">Next  &rarr; "' . $nextPost['article_title'] . '"</a>';       
						}
			//			var_export( $nextPost );

					}
					if( ! empty( $postList['pc_previous_post'] ) )
					{
						if( $previousPost = self::loadPostData( $postList['pc_previous_post'] ) )
						{
							$this->_objectTemplateValues['paginator_previous_page'] = Ayoola_Application::getUrlPrefix() . $postList['pc_previous_post'];
							$this->_objectTemplateValues['paginator_previous_page_button'] = '<a class="pc-btn" href="' . $this->_objectTemplateValues['paginator_previous_page'] . '"> "' . $previousPost['article_title'] . '" &larr; Previous</a>';
						}
					}
					$pagination .= @$this->_objectTemplateValues['paginator_previous_page_button'];
					$pagination .= @$this->_objectTemplateValues['paginator_next_page_button'];			
					$pagination = '<div class="pc_posts_distinguish_sets" id="' . $postListId . '">' . $pagination . '</div>';

				}
			//	var_export( $postList );
				$this->setViewContent( $pagination );
			}

		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewContent( '<p class="badnews">Error with article package.</p>' ); 
		}
	//	var_export( $this->_xml );
    } 
	
    /**
     * Returns the Xml
     * 
     * @return Ayoola_Xml
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' ); 		
		$data = $this->getIdentifierData();
	//	var_export( $data );
	//	$this->_xml = '<span class="' . __CLASS__ . '_UL" style="list-style:none;">';
		$url = $data['article_url'];
	//	var_export( $data['article_content'] );
	
		//	Get user info
		if( @$data['username'] && $this->getParameter( 'get_access_information' ) )
		{
			//	Causes things to run slow
			if( $userInfo = Ayoola_Access::getAccessInformation( $data['username'] ) )
			{
				$data += $userInfo;
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
			if( ! empty( $data['datetime'] ) )
			{
				$data['datetime'] = strtotime( $data['datetime'] );
				if( $this->getParameter( 'use_datetime' ) )
				{

					$data['article_modified_date'] = $data['datetime'];
			//		var_export( $data['article_modified_date'] );
				}
				
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
			@$data['article_date_M'] = date( 'M', $data['article_modified_date'] );
			@$data['article_date_Y'] = date( 'Y', $data['article_modified_date'] );
			@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );
		}
		elseif( $this->getParameter( 'filter_date' ) )
		{
			$filter = new Ayoola_Filter_Time();
		//	if( @$data['article_modified_date'] )
			{
				$data['article_modified_date_filtered'] = $filter->filter( $data['article_modified_date'] );
			}
		//	else
			{
				$data['article_creation_date_filtered'] = $filter->filter( @$data['article_creation_date'] ? : ( time() - 3 ) ); 
			}
		}
		
			if( @$data['document_url_base64'] && ! @$data['document_url'] && @$data['article_url'] )
			{
			//	$data['document_url'] = $data['document_url_base64'];
				$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$data['article_url'] . '&document_time=' . filemtime( self::getFolder() . @$data['article_url'] );
			}
		if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )  
		{
			if( $this->getParameter( 'thumbnail' ) )   
			{
				Ayoola_Page::$thumbnail = $data['document_url'];
			}
			if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )  
			{
				$imageLink = '<a href="' . $url . '" onClick=""><img class="' . __CLASS__ . '_IMG" style="max-width:100%;" src="' . $image . '" alt="' . $data['article_title'] . "'s cover photo 2" . '" title="' . $data['article_title'] . "'s cover photo" . '"/></a>';   
				
				//	Create this template placeholder value so we can have solve the problem of blank image tags in template markups
				$data['cover_photo_with_link'] = $imageLink;
		//		$this->_xml .= '<img class="' . __CLASS__ . '_IMG" style="max-width:100%;" src="' . $image . '" alt="" title="' . $data['article_title'] . "'s cover photo" . '"/>';
			}
		}
		//	CATEGORIES
		@$data['category_name'] = is_array( $data['category_name'] ) ? $data['category_name'] : array();
		@$data['category_id'] = is_array( $data['category_id'] ) ? $data['category_id'] : array(); 

		$data['category_name'] = @$data['category_name'] ? : array();
		$data['category_id'] = @$data['category_id'] ? : array();
		$data['category_name'] = array_merge( $data['category_name'], $data['category_id'] );
		$categoryText = self::getCategories( $data['category_name'], array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
		$this->_objectData['category_text'] = $categoryText;
		$this->_objectTemplateValues['category_text'] = $categoryText;
		$categoryText = $categoryText ? 'Category:  ' . $categoryText : null;		
		$categoryText = '<div style="font-size:small; margin: 1em 0 1em 0;">' . $categoryText . '</div>';
			
		//	Social Media
		$parameter = array( 'url' => Ayoola_Application::getUrlPrefix() . $url, 'title' => $data['article_title'] );
	//	$this->_xml .= Application_GooglePlus_Share::viewInLine( $parameter );
	//	$this->_xml .= Application_Facebook_Like::viewInLine( $parameter );
	//	$this->_xml .= '<hr />';
//		$this->_xml .= '<div>';
	//	$this->_xml .= '<button style="" onClick="this.nextSibling.style.display=\'\';">Share...</button>';
	///	$this->_xml .= '<div style="display:none;">' . Application_SocialMedia_Share::viewInLine( $parameter ) . '</div>';  
		$shareLinks = '<div style="font-size:x-small; margin: 2em 0 2em 0;">' . self::getShareLinks( Ayoola_Page::getCanonicalUrl( $url ) ) . '</div>';  
	//	$this->_xml .= $shareLinks;  
		$this->_objectData['share_link'] = $shareLinks;
		$this->_objectTemplateValues['share_link'] = $shareLinks;
		if( self::isOwner( $data['user_id'] ) || self::hasPriviledge( array( 98 ) ) || self::hasPriviledge( $articleSettings['allowed_editors'] ) )
		{
		//	var_export( __LINE__ );
			$editLink = '' . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Article_Editor/?article_url=' . $data['article_url'];
			$editLinkHTML = null;
		//	$editLinkHTML .= '<a href="' . $editLink . '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button></a>';
			$editLinkHTML .= '<a href="' . $editLink . '"><button style="">Edit Post...</button></a>';
			$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">Delete Post...</button>';
			$this->_objectData['edit_link'] = $editLinkHTML;
			$this->_objectTemplateValues['edit_link'] = $editLinkHTML;
//			$this->_xml .= $editLinkHTML;
		}
	//	var_export( $data['article_content'] );
//		$this->_xml .= '</div>'; 
		$leastPrice = false;
		if( isset( $data['item_price'] ) )
		{
			if( empty( $data['item_price'] ) )
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
					$data['item_price'] = $leastPrice;
				}

			}
		}
		if( @$data['item_price'] )
		{
			//	Filter the price to display unit
			$filter = 'Ayoola_Filter_Currency';
			$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
			$data['currency'] = $filter::$symbol;
			$filter = new $filter();
			@$data['item_old_price'] = $data['item_old_price'] ? $filter->filter( $data['item_old_price'] ) : null;
			$data['item_price_without_currency'] = $data['item_price'];
		//	$data['item_price'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
			
			//	Split to naira / kobo
			$filter = new $filter();
	//		$filter::$symbol = '';
			$data['item_price_with_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
			$data['item_price_before_decimal'] = array_shift( explode( '.', $data['item_price_without_currency'] ) );
			$data['item_price_after_decimal'] = array_pop( explode( '.', $data['item_price_without_currency'] ) );
		
		}
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
		
	//	var_export( $postTypeInfo );
		if( $postTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $data['article_type'] ) )
		{
			$postType = $postTypeInfo['article_type'];
		}
		else
		{
			$postType = $data['article_type'];
		}
		$data['filtered_time'] = self::filterTime( $data );
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
			if( ! empty( $data['datetime'] ) )
			{
				$data['datetime'] = strtotime( $data['datetime'] );
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
			@$data['article_date_M'] = date( 'M', $data['article_modified_date'] );
			@$data['article_date_Y'] = date( 'Y', $data['article_modified_date'] );
			@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );
			@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );
		}
		elseif( $this->getParameter( 'filter_date' ) )
		{
			$filter = new Ayoola_Filter_Time();
		//	if( @$data['article_modified_date'] )
			{
				$data['article_modified_date_filtered'] = $filter->filter( $data['article_modified_date'] );
			}
		//	else
			{
				$data['article_creation_date_filtered'] = $filter->filter( @$data['article_creation_date'] ? : ( time() - 3 ) ); 
			}
		}

		//	just use this
		$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ); 
		@$categoryToUse = is_array( $data['category_name'] ) ? $data['category_name'] : array();
		$categoryTextRaw = self::getCategories( $categoryToUse, array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
		$categoryText = $categoryTextRaw ? ' ' . $categoryTextRaw : null;
		$data['category_text'] = $categoryText;
		$this->_xml = self::getDefaultPostView( $data );
		switch( $postType )   
		{
			case 'product':
			case 'service':
			case 'subscription':
			case 'event':
			case 'events':
				//	By
				
				//	title
				$baseData = array();
				if( ! empty( $leastPrice ) )
				{
					//	don't let least price get into the cart'
					$baseData['item_price'] = '';
				//	unset( $data['item_price'] );
				}
		//		var_export( $baseData );
		//		var_export( $baseData + $data );
				$parameterX = array( 'data' => $baseData + $data, 'button_value' => $this->getParameter( 'button_value' ), 'min_quantity' => $this->getParameter( 'min_quantity' ), 'max_quantity' => $this->getParameter( 'max_quantity' ) );
				$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( $parameterX );
				$this->_xml .= $data['button_add_to_cart'];
				$this->_xml .= @$data['article_content'];
			break;
			case 'profile':
				
				//	title
				$this->_xml .= '<p style=""><strong>Full Name:</strong> ' . $data['full_legal_name'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Phone Number:</strong> +' . $data['dial_code'] . '-' . $data['phonenumber'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Blackberry PIN:</strong> ' . $data['bbm_pin'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Blackberry Channel:</strong> ' . $data['bbm_channel'] . '</p> ';
			//	$this->_xml .= '<p style=""><strong>Twitter Handle:</strong> ' . $data['twitter_handle'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Website:</strong> ' . $data['website'] . '</p> ';
				$this->_xml .= @$data['article_content'];
			break;
			case 'video':
				
				//	title
		//		$this->_xml = '<span style=""><h1>' . $data['article_title'] . '</h1></span>';  
				//	By
			//	$this->_xml .= '<span><strong>Video by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
		//		$this->_xml .= '  ' . self::filterTime( $data );
		//		$this->_xml .= '' . $categoryText;
		//		$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$data['video_content'] = Application_Article_Type_Video::viewInLine( array( 'data' => $data ) );
				$this->_xml .= $data['video_content'];
				$this->_xml .= @$data['article_content'];  
			break;
			case 'link':
				
				//	title
	//			$this->_xml .= '<span style=""><h1>' . $data['article_title'] . '</h1></span>';
				//	By
			//	$this->_xml .= '<span><strong>Video by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
	//			$this->_xml .= '  ' . self::filterTime( $data );
	//			$this->_xml .= '' . $categoryText;
		//		$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$this->_xml .= '<a target="_blank" href="' . $data['link_url'] . '" class="pc-btn pc-bg-color">Visit Link</a>';
				$this->_xml .= @$data['article_content'];
			break;
			case 'poll':
				
				//	title
			//	$this->_xml .= '<span style=""><h1>' . $data['article_title'] . '</h1></span>';
				//	By
			//	$this->_xml .= '<span><strong>Poll by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
		//		$this->_xml .= '  ' . self::filterTime( $data );
		//		$this->_xml .= '' . $categoryText;
		//		$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['article_content'];
				@$data['poll'] = Application_Article_Type_Poll::viewInLine( array( 'data' => $data ) );
				$this->_xml .= @$data['poll'];
			break;
			case 'quiz':
				
				//	title
		//		$this->_xml .= '<span style=""><h1>' . $data['article_title'] . '</h1></span>';
					//	By
		//		$this->_xml .= '<span><strong>Quiz by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
	//			$this->_xml .= '  ' . self::filterTime( $data );
	//			$this->_xml .= '' . $categoryText;
	//			$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['article_content'];
				$this->_xml .= Application_Article_Type_Quiz::viewInLine( array( 'data' => $data ) );
			break;
			case 'audio':
			case 'music':
			case 'message':
			case 'e-book':
			case 'document':
			case 'file':
			case 'download':
			//	self::v( $data );
				//	title
				if( @$data['download_url'] )
				{
					if( $data['download_url'][0] === '/' )
					{
						//	this is still a local file we can load with Ayoola_Doc
				//		var_export($data['download_url'] );
				//		var_export( Ayoola_Loader::checkFile( 'documents/' . $data['download_url'] ) );  
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
		//		$this->_xml .= '<span style=""><h1>' . $data['article_title'] . '</h1></span>';
					
				//	Version
	//			$this->_xml .= '<span style="display:inline;"><strong>Version:</strong> ' . ( @$data['download_version'] ? $data['download_version'] : 'None' ) . '</span> ';
				
				//	By
	//			$this->_xml .= '  ' . self::filterTime( $data );
	//			$this->_xml .= '' . $categoryText;
			//	$this->_xml .= '<span style="display:inline;"><strong>Uploaded by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
	//			$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['article_content'];
				$data['download_button'] = Application_Article_Type_Download::viewInLine( array( 'data' => $data ) );
				$this->_xml .= $data['download_button'];
			break;
			default:
				
				//	title
	//			$this->_xml .= '<span style=""><h1>' . $data['article_title'] . ' </h1></span>';
				//	By
			//	$this->_xml .= '<span style=""><strong>by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span>';
	//			$this->_xml .= '  ' . self::filterTime( $data );
	//			$this->_xml .= '' . $categoryText;
		//		$this->_xml .= '<p>' . $data['article_description'] . '</p>';
	//			$this->_xml .= '<span style="">';
	//			$this->_xml .= $data['article_description'] ? '<blockquote>' . $data['article_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['article_content'];
	//			$this->_xml .=	'</span>';
			break;
		}
		if( $this->getParameter( 'file_size' ) )
		{
			$filesizeLevel = array( 'bytes', 'KB', 'MB', 'GB' );
			$denomenator = 1; 
			$newFilesize = $data['file_size'];
	//		var_export( $data['file_size'] );
			while( $denomenator < $newFilesize && $filesizeLevel )
			{
				$suffixFilesize = array_shift( $filesizeLevel );
				$newFilesize = intval( $newFilesize ) / $denomenator;
				$newFilesizeString = floatval( round( $newFilesize, 1 ) ) . $suffixFilesize;
		//		var_export( $newFilesize . '<br>' );
		//		var_export( $newFilesizeString . '<br>' );
				
				$denomenator = 1024;
			}
			$data['file_size'] = $newFilesizeString;
		}
		if( $this->getParameter( 'download_count' ) )
		{
			//	Log into the database 
			$table = new Application_Article_Type_Download_Table();
			$count = $table->select( null, array( 'article_url' => $data['article_url'] ) );
			$data['download_count'] = count( $count );
		//	self::v( array( 'article_url' => $data['article_url'] ) );
		//	self::v( $count );
		}
		//	destroy float
		$this->_xml .= '<div style="clear:both;"></div>';
		
		
		$this->_objectTemplateValues = array_merge( $data ? : array(), $this->_objectTemplateValues ? : array() );
		

		//	Social Media
//		$parameter = array( 'url' => $url );
/* 		$this->_xml .= Application_GooglePlus_Share::viewInLine( $parameter );
		$this->_xml .= Application_Facebook_Like::viewInLine( $parameter );
		$this->_xml .= Application_Twitter_Tweet::viewInLine( $parameter );
 *///		$this->_xml .= Application_SocialMedia_Share::viewInLine( $parameter );
	//	$this->_xml .= self::getQuickLink( $data );
				
/* 		//	CATEGORIES
		@$data['category_name'] = $data['category_name'] ? : array();
		@$data['category_id'] = $data['category_id'] ? : array(); 
		$data['category_name'] += $data['category_id'];
		$this->_xml .= self::getCategories( $data['category_name'] );
				
		//	hastags
		$tags = array_map( 'trim', explode( ',', $data['article_tags'] ) );
	//	var_export( $tags );
		$this->_xml .= self::getHashTags( $tags );
		
		//	footer
		$this->_xml .= self::getFooter( $data );
		
		$this->_xml .= '</span>';
 */    } 
	// END OF CLASS
}
