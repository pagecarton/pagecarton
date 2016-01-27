<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Adapter.php 1.22.2012 10.11PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Adapter
{
    /**
     * The Default Adapter to use if no adapter is set
     *
     * @var string
     */
	const DEFAULT_DEVICE = 'DbaseTable';

    /**
     * Adapter Device 
     *
     * @see Ayoola_Access_Adapter_Interface 
     * @var Ayoola_Access_Adapter_Interface 
     */
	protected $_device;

    /**
     * Constructor
     *
     * @see Ayoola_Access_Adapter_Interface
     * @param Ayoola_Access_Adapter_Interface
     * 
     */
    public function __construct( $device = null )
    {
		if( ! is_null( $device ) ){	$this->setDevice( $device ); }
    }
		
    /**
     * Authenticate the User Against Records in the Storage Device
     *
     * @param array The Credentials Used In Authentication
     * @return boolean
     */
    public function authenticate( $credentials )
    {
		//	The Device Gets the Credentials Through This Method
		return $this->getDevice()->authenticate( $credentials );
    }

    /**
     * This method returns the row from authentication
     *
     * @param void
     * @return array
     */
    public function getResultRow()
    {
		return (array) $this->getDevice()->getResultRow();
    } 
		
    /**
     * This method returns the Storage Device in use
     *
     * @see Ayoola_Access_Adapter_Interface 
     * @return Ayoola_Access_Adapter_Interface 
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
		if( is_string( $device ) )
		{
			$class = 'Ayoola_Access_Adapter_' . ucfirst( $device );
			require_once 'Ayoola/Loader.php';	
			if( ! Ayoola_Loader::loadClass( $class ) )
			{
				require_once 'Ayoola/Access/Adapter/Exception.php';
				throw new Ayoola_Access_Adapter_Exception( 'Unable to load adapter - "' . $device );
			}
			$device = new $class();
		}
		if( ! $device instanceof Ayoola_Access_Adapter_Interface )
		{
			require_once 'Ayoola/Access/Adapter/Exception.php';
			throw new Ayoola_Access_Adapter_Exception( get_class( $device ) . ' is an invalid device for ' . __CLASS__ );
		}
		$this->_device = $device;
    } 
	// END OF CLASS
}
