<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Storage_File  
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: File.php 5-7-2012 9.45AM ayoola $
 */

/**
 * @see Ayoola_Storage_Interface
 */
 
require_once 'Ayoola/Storage/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Storage_File
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Storage_File implements Ayoola_Storage_Interface
{

    /**
     * File Namespace
     *
     * @var string
     */
	protected $_namespace;

    /**
     * File Object
     *
     * @var Ayoola_File_Storage
     */
	protected $_fileStorage;
		
    /**
     * Store data in File
     *
     * @param mixed Data to be Stored
     * @return boolean
     */
    public function store( $data )
	{

		$this->getFileStorage()->write( $data );
	}
	
    /**
     * Retrieve data from the Storage
     *
     * @return mixed Stored Data
     */
    public function retrieve()
	{
		return $this->getFileStorage()->read();
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
		$this->getFileStorage()->purgeNamespace( $this->getNamespace() );
	}
	
    /**
     * Switch if there is a record of a user in storage
     *
     * @param void
     * @return boolean
     */
    public function isLoaded()
	{
		if( $this->getFileStorage()->read() )
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
     * @param Ayoola_File
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
	 * Returns the _File property
	 * 
     * @param void
     * @return Ayoola_File
     */
    public function getFileStorage()
	{
		if( is_null( $this->_fileStorage ) ){ $this->setFileStorage(); }
		return $this->_fileStorage;
	}
	
    /**
	 * Set the _fileStorage property
     *
     * @param Ayoola_File
     */
    public function setFileStorage( Ayoola_File_Storage $file = null )  
	{
		if( is_null( $file ) )
		{ 
			require_once 'Ayoola/File/Storage.php';
			$file = new Ayoola_File_Storage(); 
		}
   //     PageCarton_Widget::v( $file );
		$file->timeOut = $this->timeOut;
		$file->setNamespace( $this->getNamespace() );
		$this->_fileStorage = $file;
	}

	// END OF CLASS
}
