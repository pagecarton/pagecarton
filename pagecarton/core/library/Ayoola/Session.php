<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Session
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Session.php 3.5.2010 8.11PM Ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Session
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Session
{
    /**
     * Session NameSpace
     *
     * @var string
     */
	protected $_namespace;
	
    /**
     * Session NameSpace
     *
     * @var string
     */
	protected static $_name = 'AyoolaCmfSessionId';

    /**
     * Constructor
     *
     * @param string Namespace
     * 
     */
    public function __construct( $namespace = null )
    {
		if( ! isset( $_SESSION ) && Ayoola_Application::$mode != 'document' )
		{
		//	var_export( '234' );
	//		session_name( self::$_name );
		//	session_id( 'sexessset' );
			session_start();
			
		}
		
		$this->setNamespace( $namespace );
    }
	
    /**
     * This method unsets a namespace in the session
     *
     * @param 
     * @return 
     */
    public function purgeNamespace( $namespace = null )
    {
		if( is_null( $namespace ) ){ $namespace = $this->getNamespace(); }
        if( isset( $_SESSION[__CLASS__][$namespace] ) ){ unset( $_SESSION[__CLASS__][$namespace] ); }
    } 
	
    /**
     * Stores Data in the Session
     *
     * @param mixed Data to be stored
     * @return void
     */
    public function write( $data )
    {
		$_SESSION[__CLASS__][$this->getNamespace()] = $data;
    } 
	
    /**
     * Reads Data that was previously stored in the session
     *
     * @param 
     * @return mixed Stored Data
     */
    public function read()
    {
		if( ! isset( $_SESSION[__CLASS__][$this->getNamespace()] ) ){ return false; } 
        return $_SESSION[__CLASS__][$this->getNamespace()];
    } 
	
    /**
     * Clears data from storage
     *
     * @param void
     */
    public static function destroy()
    {
        unset( $_SESSION[__CLASS__] );
    } 
	
    /**
	 * Returns the _name property
	 * 
     * @param void
     * @return string The Name
     */
    public static function getName()
	{
		return self::$_name;
	}
	
    /**
	 * Returns the _namespace property
	 * 
     * @param void
     * @return string The Namespace
     */
    public function getNamespace()
	{
		if( is_null( $this->_namespace ) ){ $this->setNamespace(); }
		return $this->_namespace;
	}
	
    /**
	 * Set the _namespace property
     *
     * @param string
     */
    public function setNamespace( $namespace = null )
	{
		if( is_null( $namespace ) )
		{ 
			$namespace = __CLASS__; 
		}
		$this->_namespace = $namespace;
	}
}
