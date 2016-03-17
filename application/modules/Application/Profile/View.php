<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Profile_View extends Application_Profile_Abstract
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
	protected $_identifierKeys = array( 'profile_name',  );

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
			if( ! $data = $this->getIdentifierData() ){  }
			if( ! $data  
				|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) && ! @in_array( 'publish', @$data['profile_options'] ) )   
				|| ! self::hasPriviledge( @$data['auth_level'] )
			)
			{
			//	var_export( @$data ); 
			//	var_export( self::hasPriviledge( @$data['auth_level'] ) );
				return $this->setViewContent( '<p class="badnews">The requested profile was not found on the server. Please check the URL and try again. ' . self::getQuickLink() . '</p>', true );
			//	self::setIdentifierData( $data );
			}
			$pageInfo = array(
				'description' => @$data['profile_description'],
				'keywords' => @$data['profile_tags'],
				'title' => trim( $data['profile_title'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
			);
	//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			$this->setViewContent( self::getXml(), true );
/* 			if( self::hasPriviledge() )
			{ 
				$this->setViewContent( Application_Profile_ShowAll::viewInLine( array( 'username_to_show' => $data['username'] ) ) ); 
			}
 */		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			return $this->setViewContent( '<p class="badnews">Error with profile package.</p>' ); 
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
		$profileSettings = Application_Profile_Settings::getSettings( 'Profiles' ); 		
		$data = $this->getIdentifierData();
	//	var_export( $data );
		$this->_xml = '<span class="' . __CLASS__ . '_UL" style="list-style:none;">';
		$url = $data['profile_url'];
	
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
			$timeToShow = (array) $this->getParameter( 'modified_time_representation' );
			foreach( $timeToShow as $key => $each )
			{
			//	var_export( date( $each, $data['profile_modified_date'] ) );
			//	var_export( $key );
				@$data['modified_time_representation_' . $key] = date( $each, $data['profile_modified_date'] );
			}
			@$data['profile_date_M'] = date( 'M', $data['profile_modified_date'] );
			@$data['profile_date_Y'] = date( 'Y', $data['profile_modified_date'] );
			@$data['profile_date_d'] = date( 'd', $data['profile_modified_date'] );
		}
		
		
	//	$this->_xml .= '<caption style="">' . @$data['profile_description'] . '</caption>';
	
		//	Description
	//	$this->_xml .= '<li style=""><p style="">' . $data['profile_description'] .  '</p></li>';
		
		//	content
	//	var_export( $data );
		if( $image = Ayoola_Doc::uriToDedicatedUrl( $data['document_url'] ) )  
		{
			$this->_xml .= '<span style=""><a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $image . '\' )"><img class="' . __CLASS__ . '_IMG" style="vertical-align:top; margin:.5em;float:left;" src="' . $image . '" alt="' . $data['profile_title'] . "'s cover photo" . '" title="' . $data['profile_title'] . "'s cover photo" . '"/></a></span>';
			Ayoola_Page::$thumbnail = $data['document_url'];
		}
		
		//	CATEGORIES
		@$data['category_name'] = is_array( $data['category_name'] ) ? $data['category_name'] : array();
		@$data['category_id'] = is_array( $data['category_id'] ) ? $data['category_id'] : array(); 

		$data['category_name'] = @$data['category_name'] ? : array();
		$data['category_id'] = @$data['category_id'] ? : array();
		$data['category_name'] = array_merge( $data['category_name'], $data['category_id'] );
		$categoryText = self::getCategories( $data['category_name'], array( 'template' => $this->getParameter( 'category_template' ), 'glue' => ( $this->getParameter( 'category_template_glue' ) ? : ', ' ) ) );
		$categoryText = $categoryText ? ' in ' . $categoryText : null;
			
		//	Social Media
		$parameter = array( 'url' => $url, 'title' => $data['profile_title'] );
	//	$this->_xml .= Application_GooglePlus_Share::viewInLine( $parameter );
	//	$this->_xml .= Application_Facebook_Like::viewInLine( $parameter );
	//	$this->_xml .= '<hr />';
		$this->_xml .= '<div>';
		$this->_xml .= '<button style="" onClick="this.nextSibling.style.display=\'\';">Share...</button>';
		$this->_xml .= '<div style="display:none;">' . Application_SocialMedia_Share::viewInLine( $parameter ) . '</div>';  
		if( self::isOwner( $data['user_id'] ) || self::hasPriviledge( $profileSettings['allowed_editors'] ) )
		{
			$editLink = self::getPostUrl() . '/post/editor/?profile_url=' . $data['profile_url'];
			$editLinkHTML = null;
			$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $editLink . '\' );">Edit...</button>';
			$editLinkHTML .= '<button style="" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Profile_Delete/?profile_url=' . $data['profile_url'] . '\' );">Delete...</button>';
			$this->_objectData['edit_link'] = $editLinkHTML;
			$this->_objectTemplateValues['edit_link'] = $editLinkHTML;
			$this->_xml .= $editLinkHTML;
		}
		$this->_xml .= '</div>'; 
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
			$filter::$symbol = '';
			$data['item_price_with_currency'] = $data['item_price'] ? $filter->filter( $data['item_price'] ) : null;
			$data['item_price_before_decimal'] = array_shift( explode( '.', $data['item_price_without_currency'] ) );
			$data['item_price_after_decimal'] = array_pop( explode( '.', $data['item_price_without_currency'] ) );
		
		}
		switch( @$data['profile_type'] )
		{
			case 'product':
			case 'service':
			case 'subscription':
				//	By
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
				
				if( @$data['item_price'] )
				{
					$this->_xml .= '<p style="">
											<strong>Price:</strong> 
											' . ( @$data['item_old_price'] ? '
											 <span class="badnews" style="text-decoration:line-through;">' . @$data['item_old_price'] . '</span> ' : '' ) . ( @$data['item_price'] ) . '
									</p> ';
				}
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$data['button_add_to_cart'] = Application_Profile_Type_Subscription::viewInLine( array( 'data' => $data, 'button_value' => $this->getParameter( 'button_value' ) ) );
				$this->_xml .= $data['button_add_to_cart'];
				$this->_xml .= @$data['profile_content'];
			break;
			case 'profile':
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
				//	By
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= '<p style=""><strong>Full Name:</strong> ' . $data['full_legal_name'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Phone Number:</strong> +' . $data['dial_code'] . '-' . $data['phonenumber'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Blackberry PIN:</strong> ' . $data['bbm_pin'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Blackberry Channel:</strong> ' . $data['bbm_channel'] . '</p> ';
			//	$this->_xml .= '<p style=""><strong>Twitter Handle:</strong> ' . $data['twitter_handle'] . '</p> ';
				$this->_xml .= '<p style=""><strong>Website:</strong> ' . $data['website'] . '</p> ';
				$this->_xml .= @$data['profile_content'];
			break;
			case 'video':
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
				//	By
				$this->_xml .= '<span><strong>Video by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
				$this->_xml .= ' | ' . self::filterTime( $data );
				$this->_xml .= '' . $categoryText;
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= Application_Profile_Type_Video::viewInLine( array( 'data' => $data ) );
				$this->_xml .= @$data['profile_content'];
			break;
			case 'poll':
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
				//	By
				$this->_xml .= '<span><strong>Poll by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
				$this->_xml .= ' | ' . self::filterTime( $data );
				$this->_xml .= '' . $categoryText;
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['profile_content'];
				$this->_xml .= Application_Profile_Type_Poll::viewInLine( array( 'data' => $data ) );
			break;
			case 'quiz':
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
					//	By
				$this->_xml .= '<span><strong>Quiz by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
				$this->_xml .= ' | ' . self::filterTime( $data );
				$this->_xml .= '' . $categoryText;
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['profile_content'];
				$this->_xml .= Application_Profile_Type_Quiz::viewInLine( array( 'data' => $data ) );
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
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . '</h1></span>';
					
				//	Version
				$this->_xml .= '<span style="display:inline;"><strong>Version:</strong> ' . ( @$data['download_version'] ? $data['download_version'] : 'None' ) . '</span> ';
				
				//	By
				$this->_xml .= ' | ' . self::filterTime( $data );
				$this->_xml .= '' . $categoryText;
				$this->_xml .= '<span style="display:inline;"><strong>Uploaded by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span> ';
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['profile_content'];
				$data['download_button'] = Application_Profile_Type_Download::viewInLine( array( 'data' => $data ) );
				$this->_xml .= $data['download_button'];
			break;
			default:
				
				//	title
				$this->_xml .= '<span style=""><h1>' . $data['profile_title'] . ' </h1></span>';
				//	By
				$this->_xml .= '<span style=""><strong>by:</strong> ' . ( $data['username'] ? '<a  title=\'View other Posts by "' . $data['username'] . '"\' href="' . self::getPostUrl() . '/by/' . $data['username'] . '/">' . $data['username'] . '</a>' : 'Anonymous' ) . '</span>';
				$this->_xml .= ' | ' . self::filterTime( $data );
				$this->_xml .= '' . $categoryText;
		//		$this->_xml .= '<p>' . $data['profile_description'] . '</p>';
				$this->_xml .= '<span style="">';
				$this->_xml .= $data['profile_description'] ? '<blockquote>' . $data['profile_description'] . '</blockquote>' : null;
				$this->_xml .= @$data['profile_content'];
				$this->_xml .=	'</span>';
			break;
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
		$tags = array_map( 'trim', explode( ',', $data['profile_tags'] ) );
	//	var_export( $tags );
		$this->_xml .= self::getHashTags( $tags );
		
		//	footer
		$this->_xml .= self::getFooter( $data );
		
		$this->_xml .= '</span>';
 */    } 
	// END OF CLASS
}
