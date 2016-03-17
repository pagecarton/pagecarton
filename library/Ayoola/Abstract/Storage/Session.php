<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Storage_Session
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Session.php date time username $
 */

/**
 * @see Ayoola_Abstract_Storage_Interface
 */
 
require_once 'Ayoola/Abstract/Storage/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Abstract_Storage_Session
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Abstract_Storage_Session implements Ayoola_Abstract_Storage_Interface
{

    /**
     * Session Namespace
     *
     * @var string
     */
	protected $_namespace;

    /**
     * Session Object
     *
     * @var Ayoola_Session
     */
	protected $_session;
		
    /**
     * Store data in Session
     *
     * @param mixed Data to be Stored
     * @return boolean
     */
    public function store( $data )
	{

		$this->getSession()->write( $data );
		//session_regenerate_id();
	}
	
    /**
     * Retrieve data from the Storage
     *
     * @return mixed Stored Data
     */
    public function retrieve()
	{
		return $this->getSession()->read();
	}
	
    /**
     * Put data in Storage
     *
     * @param void
     * @return boolean
     */
    public function setData( $data )
	{
	
	}
	
    /**
     * Retrieve Data from Storage
     *
     * @param void
     * @return boolean
     */
    public function getData()
	{
	
	}
	
    /**
     * Empties the Storage
     *
     * @param void
     * @return boolean
     */
    public function clear()
	{
		$this->getSession()->purgeNamespace( $this->getNamespace() );
	}
	
    /**
     * Switch if there is a record of a user in storage
     *
     * @param void
     * @return boolean
     */
    public function isLoaded()
	{
		if( $this->getSession()->read() )
		{
			return true;
		}
		return false;
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
     * @param Ayoola_Session
     */
    public function setNamespace( $namespace = null )
	{
		if( is_null( $namespace ) )
		{ 
			$namespace = __CLASS__; 
		}
		$this->_namespace = $namespace;
	}
	
    /**
	 * Returns the _session property
	 * 
     * @param void
     * @return Ayoola_Session
     */
    public function getSession()
	{
		if( is_null( $this->_session ) ){ $this->setSession(); }
		return $this->_session;
	}
	
    /**
	 * Set the _session property
     *
     * @param Ayoola_Session
     */
    public function setSession( Ayoola_Session $session = null )
	{
		if( is_null( $session ) )
		{ 
			require_once 'Ayoola/Session.php';
			$session = new Ayoola_Session(); 
		}
		$session->setNamespace( $this->getNamespace() );
		$this->_session = $session;
	}

	// END OF CLASS
}
