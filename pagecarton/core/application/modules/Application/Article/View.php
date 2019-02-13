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
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View a Post'; 
	
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
				if( Ayoola_Application::$GLOBAL['post']['article_url'] === $data['article_url'] )
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
	//	self::v( $data );
	//	$this->_xml = '<span class="' . __CLASS__ . '_UL" style="list-style:none;">';
		$url = $data['article_url'];
	//	var_export( $data['article_content'] );
		if( $this->getParameter( 'use_datetime' ) )
		{
			if( ! empty( $data['datetime'] ) )
			{
				$data['datetime'] = strtotime( $data['datetime'] );

				$data['article_modified_date'] = $data['datetime'];
				$data['article_creation_date'] = $data['datetime'];
		//		var_export( $data['article_modified_date'] );
			}
			
		}
		if( ! empty( $data['profile_url'] ) )
		{
		//	self::v( $data );
			if( $profileInfo = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] ) )
			{
			//	self::v( $profileInfo );
				$data += $profileInfo ? : array();
			}
		}
			
		//	Get user info
		if( @$data['username'] && $this->getParameter( 'get_access_information' ) )
		{
			//	Causes things to run slow
			if( $userInfo = Ayoola_Access::getAccessInformation( $data['username'] ) )
			{
				$data += $userInfo;
			}
		}

		$data['article_description'] = trim( $data['article_description'] );
		if( empty( $data['article_description'] ) && ! empty( $data['article_content'] ) )
		{
			$data['article_description'] = substr( strip_tags( $data['article_content'] ), 0, 200 );
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
			@$data['article_date_M'] = date( 'M', $data['article_modified_date'] );
			@$data['article_date_Y'] = date( 'Y', $data['article_modified_date'] );
			@$data['article_date_d'] = date( 'd', $data['article_modified_date'] );
		}
	//	elseif( $this->getParameter( 'filter_date' ) )  
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
		$data['filtered_time'] = self::filterTime( $data );
		$data['document_url'] = ( $data['document_url'] ? : $this->getParameter( 'default_cover_photo' ) ) ? : '/img/placeholder-image.jpg'; 
		$data['post_full_url'] = Ayoola_Page::getHomePageUrl() . $data['article_url'];
		$data['document_url_plain'] = $data['document_url']; 
		$data['document_url_uri'] = $data['document_url']; 
		
		if( @$data['document_url_base64'] && ! @$data['document_url'] && @$data['article_url'] )
		{
		//	$data['document_url'] = $data['document_url_base64'];
			$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$data['article_url'] . '&document_time=' . filemtime( self::getFolder() . @$data['article_url'] );
		}
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		
		//	default at 1800 so it can always cover the screen
		$maxWith = $this->getParameter( 'cover_photo_width' );
		$maxHeight = $this->getParameter( 'cover_photo_height' ); 
		$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ); 
		$data['document_url_cropped'] = Ayoola_Application::getUrlPrefix() . $data['document_url']; 
		$data['document_url_no_resize'] = Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] );     
		if( $image = Ayoola_Doc::uriToDedicatedUrl( @$data['document_url'] ) )  
		{
		//	if( $this->getParameter( 'thumbnail' ) )    
			{
				Ayoola_Page::$thumbnail = Ayoola_Page::getHomePageUrl() . $data['document_url_uri'] . '?width=500&height=500';  
				Ayoola_Page::$favicon = Ayoola_Page::getHomePageUrl() . $data['document_url_uri'] . '?width=500&height=500';  
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
		$data['css_class_of_inner_content'] = $this->getParameter( 'css_class_of_inner_content' );
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
		$data['post_link'] = $data['article_url'];
		$data['post_full_url'] = Ayoola_Page::getHomePageUrl() . $data['article_url'];
		if( @$data['article_url'] && strpos( @$data['article_url'], ':' ) === false && $data['article_url'][0] !== '?'  )
		{
			$data['post_link'] = Ayoola_Application::getUrlPrefix() . $data['article_url'];
	//		var_export( $data['post_link'] );
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

		//	just use this
		$data['document_url'] = '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=' . $maxWith . '&max_height=' . $maxHeight . '&article_url=' . @$data['article_url'] . '&document_time=' . @filemtime( self::getFolder() . @$data['article_url'] ); 
		@$categoryToUse = is_array( $data['category_name'] ) ? $data['category_name'] : array();
		$categoryTextRaw = self::getCategories( $categoryToUse, array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
		$categoryText = $categoryTextRaw ? ' ' . $categoryTextRaw : null;
		$data['category_text'] = $categoryText;
		//	
	 //	self::v( $data );

		//	get number of views
		if( $this->getParameter( 'get_views_count' ) )
		{
			self::getViewsCount( $data );
		}

		//	get number of downloads
		if( $this->getParameter( 'get_download_count' ) && self::isDownloadable( $data ) )
		{
			self::getDownloadCount( $data );
		}
	//	self::v( $data );
		//	get number of downloads
		if( $this->getParameter( 'get_audio_play_count' ) && $data['true_post_type'] == 'audio' )
		{   
			self::getAudioPlayCount( $data );
		}
		if( $this->getParameter( 'get_comment_count' ) )
		{   
			self::getCommentsCount( $data );
		}

		$this->_xml = self::getDefaultPostView( $data );

		//	internal forms to use
		$features = is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : array();
		$featuresPrefix = is_array( @$postTypeInfo['post_type_options_name'] ) ? $postTypeInfo['post_type_options_name'] : array();
		$features[] = $data['true_post_type'];
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
				case 'subscription-options':
			//		var_export( $featureSuffix );
			//		var_export( $data['subscription_selections' . $featureSuffix] );
					if( $this->getParameter( 'subscription_selections_template' ) && $data['subscription_selections' . $featureSuffix] )
					{
						$data['subscription_selections_html' . $featureSuffix] = null;
						foreach( $data['subscription_selections' . $featureSuffix] as $eachSelection )
						{
							if( $eachSelection == '' )
							{
								continue;
							}
							$data['subscription_selections_html' . $featureSuffix] .= str_ireplace( array( '{{{subscription_selections}}}', '{{{suffix}}}', ), array( $eachSelection, $featureSuffix ), $this->getParameter( 'subscription_selections_template' ) );
						}
					//	var_export( $data['subscription_selections_html' . $featurePrefix] );
					}			
				break;
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
					// $this->_xml .= @$data['article_content'];
				break;
				case 'multi-price':
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
					$parameterX = array( 'data' => $baseData + $data, 'button_value' => $this->getParameter( 'button_value' ), 'multi-price' => true, 'min_quantity' => $this->getParameter( 'min_quantity' ), 'max_quantity' => $this->getParameter( 'max_quantity' ) );
					$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( $parameterX );
					$this->_xml .= $data['button_add_to_cart'];
					// $this->_xml .= @$data['article_content'];
				break;
				case 'video':
					$data['video_content'] = Application_Article_Type_Video::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['video_content'];
					// $this->_xml .= @$data['article_content'];  
				break;
				case 'audio':
					$data['audio_content'] = Application_Article_Type_Audio::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['audio_content'];
					// $this->_xml .= @$data['article_content'];  
				break;
				case 'link':
					$this->_xml .= '<a target="_blank" href="' . $data['link_url'] . '" class="pc-btn pc-bg-color">Visit Link</a>';
					// $this->_xml .= @$data['article_content'];
				break;
				case 'poll':
					// $this->_xml .= @$data['article_content'];
					@$data['poll'] = Application_Article_Type_Poll::viewInLine( array( 'data' => $data ) );
					$this->_xml .= @$data['poll'];
				break;
				case 'quiz':
					// $this->_xml .= @$data['article_content'];
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
							$data['file_size'] =  filesize( Ayoola_Loader::checkFile(  'documents/' . $data['download_url'] ) );
						}
						else
						{
								#	we don't want to use get_headers again. Can make site slow
						//	$head = array_change_key_case(get_headers( $data['download_url'], TRUE));
						//	$data['file_size'] = $head['content-length'];							
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
					// $this->_xml .= @$data['article_content'];
					$data['download_button'] = Application_Article_Type_Download::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['download_button'];
				break;
				case 'article':
				case 'post':
					$this->_xml .= @$data['article_content' . $featureSuffix];
				break;
			}
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
/* 		if( $this->getParameter( 'download_count' ) )
		{
			//	Log into the database 
			$table = Application_Article_Type_Download_Table::getInstance();
			$count = $table->select( null, array( 'article_url' => $data['article_url'] ) );
			$data['download_count'] = count( $count );
		//	self::v( array( 'article_url' => $data['article_url'] ) );
		//	self::v( $count );
		}
 */		//	destroy float
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
