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
     * Returns the storage for the object
     * 
     * @param string Namespace for storage
     */
/* 	public function getObjectStorage( $namespace = null )
    {		
		$storage = new Ayoola_Storage();
		$storage->storageNamespace = $namespace . get_class( $this );
		return $storage;
	}
 */	
    /** 
     * Replace placeholders in notification Info
     * 
     */
	public static function replacePlaceholders( $template, array $values )
    {
		$search = array();
		$replace = array();
		$values['placeholder_prefix'] = @$values['placeholder_prefix'] ? : '@@@';
		$values['placeholder_suffix'] = @$values['placeholder_suffix'] ? : '@@@';
		foreach( $values as $key => $value )
		{
			$search[] = $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
			$replace[] = $value;
		}
//		var_export( $search );
//		var_export( $replace );
//		var_export( $template );
		$template = @str_ireplace( $search, $replace, $template );
		$search = '/' . $values['placeholder_prefix'] . '([\w+]+)' . $values['placeholder_suffix'] . '/';
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
