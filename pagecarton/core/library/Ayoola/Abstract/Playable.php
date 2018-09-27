<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com) 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Playable.php 4.26.2012 10.08am ayoola $
 */

/**
 * @see Ayoola_Exception 
 * @see Ayoola_Object_Interface_Viewable 
 * @see Ayoola_Abstract_Viewable 
 */
 
require_once 'Ayoola/Exception.php';
require_once 'Ayoola/Object/Interface/Playable.php';
require_once 'Ayoola/Abstract/Viewable.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Playable extends Ayoola_Abstract_Viewable implements Ayoola_Object_Interface_Playable
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = false;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_instance;
	
    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_properties;
	
    /**
     * Array of data response to send as JSON or PHP Serial or other standard form Response
     *
     * @var array
     */
	protected $_objectData = array();
	
    /**
     * Array of data response to send as JSON or PHP Serial or other standard form Response
     *
     * @var array
     */
	protected $_playMode = self::PLAY_MODE_DEFAULT;
		
    /**
     * 
     *
     */
	const PLAY_MODE_DEFAULT = self::PLAY_MODE_HTML;
		
    /**
     * 
     *
     */
	const PLAY_MODE_HTML = 'HTML';
		
    /**
     * 
     *
     */
	const PLAY_MODE_MUTE = 'MUTE';
		
    /**
     * 
     *
     */
	const PLAY_MODE_JSON = 'JSON';
		
    /**
     * 
     *
     */
	const PLAY_MODE_JSONP = 'JSONP';
		
    /**
     * 
     *
     */
	const PLAY_MODE_PHP = 'PHP';

    /**
     * Returns a singleton Instance
     *
     * @param void
     * @return self
     */
    public static function getInstance()
    {
	//	if( is_null( self::$_instance ) ){ self::$_instance = new static; }
		return new static;
    } 	
	
    /** 
     * Replace placeholders in notification Info
     * 
     */
	public static function replacePlaceholders( $template, array $values )
    {
//		var_export( $values );
//		var_export( $values );
//		self::v( $values );
//		self::v( $template );
		$search = array();
		$replace = array();
		$values['placeholder_prefix'] = @$values['placeholder_prefix'] ? : '@@@';      
		$values['placeholder_suffix'] = @$values['placeholder_suffix'] ? : '@@@';
		$defaultSearch = array();
		$defaultSearch['pc_domain'] = $defaultSearch['pc_domain'] = Ayoola_Page::getDefaultDomain();
		$defaultSearch['pc_url_prefix'] = Ayoola_Application::getUrlPrefix();
	//	$search += array_keys( $defaultSearch );
	//	$replace += array_values( $defaultSearch );
		$values = $values + $defaultSearch;
				$replaceInternally = false;
				$iTemplate = null;
				$postTheme = null;
//		$search[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
		foreach( $values as $key => $value )
		{
			if( ! is_array( $value ) )
			{
				$search[] = $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
				$replace[] = $value;	
			}
			elseif( is_array( $value ) && array_values( $value ) != $value )
			{
	//	self::v( $value );
		//		$replaceInternally = false;
		//		if( stripos( $template, $values['placeholder_prefix'] . 'array_key_count' . $values['placeholder_suffix'] ) !== false )
				{
		//			$replaceInternally = true;
				}
			//	var_export( ( stripos( $template, $values['placeholder_prefix'] . 'pc_other_posts_goes_here' . $values['placeholder_suffix'] ) !== false ) );
				if( ( stripos( $template, $values['placeholder_prefix'] . 'pc_other_posts_goes_here' . $values['placeholder_suffix'] ) !== false || stripos( $template, $values['placeholder_prefix'] . 'pc_post_item_' ) !== false ) && stripos( $template, '<!--{{{0}}}' ) !== false )
				{
					$start = strpos( $template, '<!--{{{0}}}' ) + strlen( '<!--{{{0}}}' );
				//	var_export( $postTheme );
					$length = strpos( $template, '{{{0}}}-->' ) - $start;
					$postTheme = substr( $template, $start, $length );
				//	var_export( $start );
				//	var_export( $length );
				//	var_export( $postTheme );
					$replaceInternally = true;
				}
				$iSearch = array();
				$iReplace = array();
				$jSearch = array();
				$jReplace = array();
					//			var_export( $value );  
				//				var_export( $values['article_title'] );  

				foreach( $value as $eachKey => $eachValue )
				{
					if( is_array( $eachValue ) )
					{
//var_export( $eachValue );
						foreach( $eachValue as $vKey => $eachValueV )
						{
							if( $replaceInternally & is_numeric( $key ) )
							{
								$iSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'];
								$iReplace[] = $eachValueV; 
							}
							else
							{
								$jSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
								$jReplace[] = $eachValue;  	
							}    
						}
					}
					else
					{
						//	placeholder now {{{key}}}{{{0}}}
					//	if( $replaceInternally & $key > 0 )
						//	skipping index 0 makes the first on the list be info about current post on the page
						if( $replaceInternally & is_numeric( $key ) )
						{
						//	var_export( $eachValue );
							$iSearch[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'];
							$iReplace[] = $eachValue;  
					//		var_export( $iSearch );
						}  
						else
						{
						//	var_export( $eachKey );
						//	var_export( $eachValue );
							$jSearch[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
							$jReplace[] = $eachValue;  
							
							//	CLEAR HTML comments like <!--{{{0}}} {{{0}}}-->
							$jSearch[] = '<!--' . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'] . '';
							$jReplace[] = '';  
							$jSearch[] = '' . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'] . '-->';
							$jReplace[] = ''; 
							
						}
					}
 				}
		//		var_export( $search );
		//		var_export( $replace );
				if( @$replaceInternally && $iSearch )
				{
					foreach( $defaultSearch as $ckey => $cccc )
					{
						$iSearch[] = $values['placeholder_prefix'] . $ckey . $values['placeholder_suffix'];
						$iReplace[] = $cccc;
					}
					$iTemplate .= @str_ireplace( $iSearch, $iReplace, $postTheme );  

					//	deal with {{{pc_post_item_1}}}
					$search[] = '' . $values['placeholder_prefix'] . 'pc_post_item_' . $key . $values['placeholder_suffix'] . '';
					$replace[] = $iTemplate;  
				}
				elseif( $jSearch )
				{
					$template = str_ireplace( $jSearch, $jReplace, $template );    
				}
				
			}
		}
	//	self::v( $search );    
//		self::v( $replace );
//		self::v( $template );
		foreach( $defaultSearch as $ckey => $cccc )
		{
			$search[] = $values['placeholder_prefix'] . $ckey . $values['placeholder_suffix'];
			$replace[] = $cccc;
		}
		$search[] = $values['placeholder_prefix'] . 'pc_other_posts_goes_here' . $values['placeholder_suffix'];
		$replace[] = @$iTemplate;  
		$template = @str_ireplace( $search, $replace, $template );
		$search = array();
		$search[] = '/' . $values['placeholder_prefix'] . '([\w+]+)' . $values['placeholder_suffix'] . '/';
		$search[] = '/<!--([.]+)-->/';   
	//	var_export( $search );
		@$template = preg_replace( $search, '', $template );
		
		return $template;
    } 
	

    /**
     * Returns $_playable 
     *
     * @return boolean
     */
    public static function isPlayable()
    {
		return static::$_playable;
    } 	

    /**
     * Returns $_accessLevel 
     *
     * @return int
     */
    public static function getAccessLevel()
    {
		return static::$_accessLevel;
    } 	
	
    /**
     * Check if I own a resource (they must be a registered user to own a resource)
     * 
     * param int Owner User ID
     * return boolean
     */
	public static function isOwner( $userId  )
	{
		if( $userId && Ayoola_Application::getUserInfo( 'access_level' ) && ( intval( Ayoola_Application::getUserInfo( 'access_level' ) ) === 99 || Ayoola_Application::getUserInfo( 'user_id') === $userId || Ayoola_Application::getUserInfo( 'username') === $userId ) )
		{
			return true;
		}
		return false;
	}
	
    /**
     * View for ayoola class player
     *
     * @param string The View Parameter
     * @param string The View Option
     */
    public static function viewInLine( $viewParameter = null, $viewOption = null )
    {
		$parameter = $viewParameter;
		if( ! is_array( $viewParameter ) )
		{
			$parameter = array( 'view' => $viewParameter, 'option' => $viewOption, );
		}
	//	$view = new static( $parameter + array( 'no_init' => true ) );
		$view = new static( $parameter );
	//	$view->setViewParameter( $viewParameter );
	//	$view->setViewOption( $viewOption );
	//	var_export( __LINE__ );
		$view->initOnce();
//		var_export( get_class( $view ) );

//		var_export( get_class( $view ) );
	//	var_export( @$view->getForm()->getValues() );
		return isset( $viewParameter['return_as_object'] ) ? $view : $view->view();
    } 	
	// END OF CLASS
}
