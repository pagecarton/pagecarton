<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Abstract_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Storage.php 1.22.2012 10.11PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Abstract_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Storage
{
    /**
     * The Default device to use if no device is set
     *
     * @var string
     */
	const DEFAULT_DEVICE = 'Session';

    /**
     * The Data in Storage
     *
     * @var array
     */
	protected $_data = array();

    /**
     * Storage Device 
     *
     * @see Ayoola_Abstract_Storage_Interface 
     * @var Ayoola_Abstract_Storage_Interface 
     */
	protected $_device;

    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct( $device = null )
    {
		$this->setDevice( $device );
    }

    /**
     * Clears the storage of any record of users
     *
     * @return boolean
     */
    public function clear()
    {
		return $this->getDevice()->clear();
    } 

    /**
     * Switch if there is a record of a user in storage
     *
     * @return boolean
     */
    public function isLoaded()
    {
		return $this->getDevice()->isLoaded();
    } 

    /**
     * Returns the Access Priviledge of the current User
     *
     * @return array
     */
    public function getPriviledges()
    {
		return array();
    } 

    /**
     * Stores Data in the Storage
     *
     * @param mixed Data to be Stored
     * @return boolean
     */
    public function store( $data )
    {
        return $this->getDevice()->store( $data );
    } 

    /**
     * Stores Data in the Storage
     *
     * @return mixed Data Stored
     */
    public function retrieve()
    {
        return $this->getDevice()->retrieve();
    } 
		
    /**
     * This method returns the Storage Device in use
     *
     * @see Ayoola_Abstract_Storage_Interface 
     * @return Ayoola_Abstract_Storage_Interface 
     */
    public function getDevice()
    {
 		if( is_null( $this->_device ) ){ $this->setDevice( self::DEFAULT_DEVICE ); }
		return $this->_device;
    } 
	
    /**
     * This method sets the _device parameter to a value
     * 
     * @param mixed Storage Device Name or Object 
     * @return void
     */
    public function setDevice( $device )
    {
		$device = is_null( $device ) ? static::DEFAULT_DEVICE : $device;
		if( is_string( $device ) )
		{
			$class = 'Ayoola_Abstract_Storage_' . ucfirst( $device );
			require_once 'Ayoola/Loader.php';	
			if( ! Ayoola_Loader::loadClass( $class ) )
			{
				require_once 'Ayoola/Abstract/Storage/Exception.php';
				throw new Ayoola_Abstract_Storage_Exception( 'Unable to load Storage Device - ' . $device );
			}
			$device = new $class;
		}
		if( ! $device instanceof Ayoola_Abstract_Storage_Interface )
		{
			require_once 'Ayoola/Abstract/Storage/Exception.php';
			throw new Ayoola_Abstract_Storage_Exception( get_class( $device ) . ' is an invalid device for ' . __CLASS__ );
		}
		//	Set Namespace for storage
		$device->setNamespace( get_class( $this ) );
		$this->_device = new $device;
    } 
	
    /**
     * This method returns the Storage Data
     * 
     * @return array 
     */
    public function getData()
    {
        $this->_data ? : $this->setData( $this->getDevice()->getData() );
        return (array) $this->_data;
    } 
	
    /**
     * This method sets the _data parameter to a value
     * 
     * @param array Storage Data 
     * @return void
     */
    public function setData( Array $data )
    {
		$this->_data = $data;
    } 
	// END OF CLASS
}
