<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
			if( ! $data = $this->getIdentifierData() )
			{
				return false;				
			}
			if( $this->getParameter( 'show_to_editors_only' ) && ! self::isAllowedToEdit( $data ) )
			{
				$this->_parameter['markup_template'] = null; 
				return false;				
			}
			if( ! $data  
				|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) && ! @in_array( 'publish', @$data['article_options'] ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $data['username'] ) )   
				|| ( ! self::hasPriviledge( @$data['auth_level'] ) && ! self::isOwner( @$data['user_id'] ) )
				|| ! self::isAllowedToView( $data ) 
			)
			{
				if( Ayoola_Application::$GLOBAL['post']['article_url'] === $data['article_url'] )
				{
					
					//	IF WE ARE HERE, WE ARE NOT AUTHORIZED
					$access = Ayoola_Access::getInstance();          
					$access->logout();
					$login = new Ayoola_Access_Login();  
					$login->getObjectStorage( 'pc_coded_login_message' )->store( '' . self::__( 'You are not authorized to view this post. Please log in with an authorized account to continue' ) . '' . self::__( '' ) . '' );
					
					header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/accounts/signin/?pc_coded_login_message=1&previous_url=' . $data['article_url'] );
					exit();
				}
				
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again.</p>' ) . '', true  );

			}

			$this->setViewContent( $this->getXml(), true );

		}
		catch( Exception $e )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  );
			return $this->setViewContent( self::__( '<p class="badnews">' . self::__( 'Error With Post' ) . '</p>' ) ); 
		}

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

		$url = $data['article_url'];

		if( $this->getParameter( 'use_datetime' ) )
		{
			if( ! empty( $data['datetime'] ) )
			{
				$data['datetime'] = strtotime( $data['datetime'] );

				$data['article_modified_date'] = $data['datetime'];
				$data['article_creation_date'] = $data['datetime'];

			}
			
		}
		if( ! empty( $data['profile_url'] ) )
		{
			if( $profileInfo = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] ) )
			{

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
        @$data['article_date_M'] = strftime( '%B', $data['article_modified_date'] );
        @$data['article_date_m'] = strftime( '%m', $data['article_modified_date'] );   
        @$data['article_date_Y'] = strftime( '%Y', $data['article_modified_date'] );
        @$data['article_date_d'] = strftime( '%d', $data['article_modified_date'] );   
		{
			$filter = new Ayoola_Filter_Time();
			{
				$data['article_modified_date_filtered'] = $filter->filter( $data['article_modified_date'] );
			}
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
				$imageLink = '<a href="' . $url . '" onClick=""><img class="' . __CLASS__ . '_IMG" style="max-width:100%;" src="' . $image . '" alt="' . $data['article_title'] . "" . '" title="' . $data['article_title'] . "'s cover photo" . '"/></a>';    
				
				//	Create this template placeholder value so we can have solve the problem of blank image tags in template markups
				$data['cover_photo_with_link'] = $imageLink;

			}
		}
		//	CATEGORIES
        //  Category not array in the case of category showall

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

		$shareLinks = '<div style="font-size:x-small; margin: 2em 0 2em 0;">' . self::getShareLinks( Ayoola_Page::getCanonicalUrl( $url ) ) . '</div>';  

		$this->_objectData['share_link'] = $shareLinks;
		$this->_objectTemplateValues['share_link'] = $shareLinks;
		if( self::isOwner( $data['user_id'] ) || self::isAllowedToEdit( $data ) || self::hasPriviledge( array( 98 ) ) || self::hasPriviledge( $articleSettings['allowed_editors'] ) )
		{

			$editLink = '' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Editor/?article_url=' . $data['article_url'];
			$editLinkHTML = null;

			$editLinkHTML .= '<a href="' . $editLink . '"><button style="">' . self::__( 'Edit Post' ) . '</button></a>';
			$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Delete/?article_url=' . $data['article_url'] . '\' );">' . self::__( 'Delete Post' ) . '</button>';
			$this->_objectData['edit_link'] = $editLinkHTML;
			$this->_objectTemplateValues['edit_link'] = $editLinkHTML;

		}
        $data['css_class_of_inner_content'] = $this->getParameter( 'css_class_of_inner_content' );

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
            if( $data['item_old_price'] )
            {
                @$data['price_percentage_savings'] =  intval( ( ( $data['item_old_price'] - $data['item_price'] ) / $data['item_old_price'] ) * 100 ) . '';
                @$data['item_old_price'] = $data['item_old_price'] ? $filter->filter( $data['item_old_price'] ) : null;
            }            
			$data['item_price_without_currency'] = $data['item_price'];

			
			//	Split to naira / kobo
			$filter = new $filter();

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

		}

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

        //	get number of downloads
		if( $this->getParameter( 'get_audio_play_count' ) && $data['true_post_type'] == 'audio' )
		{   
			self::getAudioPlayCount( $data );
		}
		if( $this->getParameter( 'get_comment_count' ) )
		{   
			self::getCommentsCount( $data );
		}

		if( ! $this->getParameter( 'hide_default_post_view' ) )
		{
			$this->_xml = self::getDefaultPostView( $data );
		}

        if( ! empty( $data['article_content'] ) )
        {
            $data['article_content'] = '<div class="pc-article-content">' . $data['article_content'] . '</div>';
        }            

		//	internal forms to use

        if (empty(@$postTypeInfo['post_type_options'][0])) {
            unset($postTypeInfo['post_type_options']);
            unset($postTypeInfo['post_type_options_name']);
        }
		$features = is_array( @$postTypeInfo['post_type_options'] ) ? $postTypeInfo['post_type_options'] : static::$_defaultPostElements;
		$featuresPrefix = is_array( @$postTypeInfo['post_type_options_name'] ) ? $postTypeInfo['post_type_options_name'] : array();
		if( ! in_array( @$data['true_post_type'], $features ) )
		{
			$features[] = @$data['true_post_type'] ? : $data['article_type'];
			$featuresPrefix[] = '';
		}
		$featureCount = array();
		$featureDone = array();

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

            $featureDone[$featureCountKey] = true;       
			$this->_xml .= '<div class="pc_give_space_top_bottom">';

			switch( $eachPostType )
			{
				case 'gallery':
					$imagesKey = 'images' . $featureSuffix;
					$images = $data[$imagesKey];
					foreach( $images as $imageCounter => $eachImage )
					{
						if( ! trim( $eachImage ) )
						{
							continue;
						}
						$eachImageKey = $imagesKey . '_' . $imageCounter;
						$data[$eachImageKey] = $eachImage;
						$data[$eachImageKey . '_cropped'] = Ayoola_Application::getUrlPrefix() . '/__/' . $maxWith . 'x' . $maxHeight . '/__' . $eachImage;
					}
					unset( $data[$imagesKey] );
				break;  
                case 'subscription-options':
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
					}			
				break;
				case 'product':
				case 'subscription':
					//	By
					
					//	title
					$baseData = array();
					if( ! empty( $leastPrice ) )
					{
						//	don't let least price get into the cart'
						$baseData['item_price'] = '';

					}
					$parameterX = array( 'data' => $baseData + $data, 'button_value' => $this->getParameter( 'button_value' ), 'min_quantity' => $this->getParameter( 'min_quantity' ), 'max_quantity' => $this->getParameter( 'max_quantity' ) );
					$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( $parameterX );
					$this->_xml .= $data['button_add_to_cart'];

				break;
				case 'multi-price':
					//	By
					
					//	title
					$baseData = array();
					if( ! empty( $leastPrice ) )
					{
						//	don't let least price get into the cart'
						$baseData['item_price'] = '';

					}

					$parameterX = array( 'data' => $baseData + $data, 'button_value' => $this->getParameter( 'button_value' ), 'multi-price' => true, 'min_quantity' => $this->getParameter( 'min_quantity' ), 'max_quantity' => $this->getParameter( 'max_quantity' ) );
					$data['button_add_to_cart'] = Application_Article_Type_Subscription::viewInLine( $parameterX );
					$this->_xml .= $data['button_add_to_cart'];

				break;
				case 'video':
					$data['video_content'] = Application_Article_Type_Video::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['video_content'];

				break;
				case 'audio':
					$data['audio_content'] = Application_Article_Type_Audio::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['audio_content'];

				break;
				case 'link':
					$this->_xml .= '<a target="_blank" href="' . $data['link_url'] . '" class="pc-btn pc-bg-color">Visit Link</a>';

				break;
				case 'poll':

					@$data['poll'] = Application_Article_Type_Poll::viewInLine( array( 'data' => $data ) );
					$this->_xml .= @$data['poll'];
				break;
				case 'quiz':

					$this->_xml .= Application_Article_Type_Quiz::viewInLine( array( 'data' => $data ) );
				break;
				case 'audio':
				case 'music':
				case 'message':
				case 'e-book':
				case 'document':
				case 'file':
				case 'download':

					//	title
                    if( $data['download_url'][0] === '/' )
                    {
                        $data['file_size'] = intval( filesize( Ayoola_Doc::getDocumentsDirectory() . @$data['download_url'] ) );
                    }
                    elseif( stripos( ':', $data['download_url'][0] ) !== false )
                    {
                        $data['file_size'] = intval( filesize( $data['download_url'][0] ) );
                    }
                    elseif(  @$data['download_path'] )
                    {
                        $data['file_size'] = intval( filesize( @$data['download_path'] ) );
                    }
                    if( @$data['download_base64'] )
					{
						$result = self::splitBase64Data( $data['download_base64'] );
						$data['file_size'] =  strlen( $result['data'] );
					}

					$filter = new Ayoola_Filter_FileSize();
					$data['file_size'] = $filter->filter( $data['file_size'] );

					$data['download_button'] = Application_Article_Type_Download::viewInLine( array( 'data' => $data ) );
					$this->_xml .= $data['download_button'];
				break;
				case 'article':
                case 'post':
                    $data['article_content' . $featureSuffix] = self::cleanHTML( @$data['article_content' . $featureSuffix] );
                    if( strip_tags( $data['article_content' . $featureSuffix] ) === $data['article_content' . $featureSuffix] )
                    {
                        $data['article_content' . $featureSuffix] = nl2br( $data['article_content' . $featureSuffix] );
                    }
					$this->_xml .= $data['article_content' . $featureSuffix];
                break;
                
                default:
                $eachPostTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $eachPostType );
                if( ! empty( $eachPostTypeInfo['view_widget'] ) && Ayoola_Object_Embed::isWidget( $eachPostTypeInfo['view_widget'] ) )
                {
                    $class = new $eachPostTypeInfo['view_widget']( array( 'data' => $data ) );
                    $class->initOnce();
                    $this->_xml .= $class->view();
                }
                break;
			}
			$this->_xml .= '</div>';

		}
		if( $this->getParameter( 'file_size' ) )
		{
			$filesizeLevel = array( 'bytes', 'KB', 'MB', 'GB' );
			$denomenator = 1; 
			$newFilesize = $data['file_size'];

			while( $denomenator < $newFilesize && $filesizeLevel )
			{
				$suffixFilesize = array_shift( $filesizeLevel );
				$newFilesize = intval( $newFilesize ) / $denomenator;
				$newFilesizeString = floatval( round( $newFilesize, 1 ) ) . $suffixFilesize;

				
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

		}
 */		//	destroy float
		$this->_xml .= '<div style="clear:both;"></div>';
		
        $data['post_type'] = self::__( $data['post_type'] );
        $data['article_type'] = self::__( $data['article_type'] );
        $data['true_post_type'] = self::__( $data['true_post_type'] );
		$this->_objectData = array_merge( $data ? : array(), $this->_objectData ? : array() );
		$this->_objectTemplateValues = array_merge( $data ? : array(), $this->_objectTemplateValues ? : array() );
    } 
	// END OF CLASS
}
