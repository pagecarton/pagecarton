<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_File
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: File.php 3.5.2010 8.11PM Ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_File
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_File
{
	
    /**
     * Full Path to the file
     *
     * @var string
     */
	protected $_path = null;	
	
    /**
     * Base Directory for the file.
     *
     * @var string
     */
	protected $_directory;	
	
    /**
	 * Sets the _path property
	 * 
     * @param string Path
     * @return null
     */
    public function setPath( $path = null )
	{
		return $this->_path = $path;
	}
	
    /**
	 * Returns the _path property
	 * 
     * @param void
     * @return string The Path to the file
     */
    public function getPath()
	{
		if( is_null( $this->_path ) )
		{
			throw new Ayoola_File_Exception( 'PATH NOT SET' ); 
		}
		return $this->getDirectory() . DS . $this->_path; 
	}
	
    /**
	 * Returns the _namespace property
	 * 
     * @param string Path to Directory
     * @return null
     */
    public function setDirectory( $dir = null )
	{
		$this->_directory = $dir;
		if( ! is_dir( $dir ) )
		{
			$this->_directory = CACHE_DIR; 
		}
	}
	
    /**
	 * Returns the _directory property
	 * 
     * @param void
     * @return string The Directory
     */
    public function getDirectory()
	{
		if( is_null( $this->_directory ) )
		{
			$this->setDirectory(); 
		}
		return $this->_directory;
	}
	
}
