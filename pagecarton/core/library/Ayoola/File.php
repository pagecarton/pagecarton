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
 
require_once 'Ayoola/File.php';


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
     * Singleton instance
     *
     * @var self
     */
	protected static $_instance;
	
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
	 * Writes content to a file
	 * 
     * @param string $path
     * @param string $data
     * @return boolean
     */
    public static function putContents( $path, $data )
	{
        $x = array( 'path' => $path, 'data' => $data, 'response' => true );
        try
        {        
            $lockDiskFile = CACHE_DIR . DS .  __CLASS__ . DS . 'lockdisk';

            if( is_file( $lockDiskFile ) )
            {
                return false;
            }

            //  hook file writing so we can write plugin to manipulate file writing result;
            //  setting hooks causes infinite loop
            //  probably because it involves also writing files
            if( ! $response = file_put_contents( $x['path'], $x['data'] ) )
            {

                //  can't write
                //  is disk full? clear cache
                Application_Cache_Clear::viewInLine( array( 'clear_all' => true ) );


                //	Notify Admin
                $mailInfo = array();
                $mailInfo['subject'] = "Write to disk failed";
                $mailInfo['body'] = 'Some content could not be written to disk on the server. We have locked the disk against further writing to prevent data loss.

Path: ' . $path . ',
Content Size: ' . strlen( $data ) . '
                
                ';
                $log = array( 'error_message' => $mailInfo['subject'] . ' - ' . $mailInfo['body'], 'error_time' => time() );
                Application_Log_View_Error_Log::getInstance()->insert(  );

                try
                {
                    @Ayoola_Application_Notification::mail( $mailInfo );
                }
                catch( Ayoola_Exception $e ){ null; }

            }
            file_put_contents( $lockDiskFile, $path );

            return $response;
        }
        catch( Ayoola_Abstract_Exception $e  )
        {
            //  now hooks can avoid execution of a class method by throwing an exception
            return $x['response'];
        }
    }
	
    /**
	 * Sends a file to trash instead of deleting it
	 * 
     * @param string $path
     * @return boolean
     */
    public static function trash( $path )
	{
        try
        {    
            if( ! $path || ! is_file( $path ) )
            {
                return false;
            }
            $x = explode( Ayoola_Application::getDomainSettings( APPLICATION_DIR ), $path );
            $trashDir = Ayoola_Application::getDomainSettings( APPLICATION_DIR ) . DS . '.trash';
            if( empty( $x[1] ) )
            {
                //    unlink( $path );
                $trashDir = CACHE_DIR . DS . '.trash';
            }
            $newDir  = $x[1] ? : $x[0];  
            $newPath = $trashDir . $newDir . DS . time() . basename( $newDir );
            Ayoola_Doc::createDirectory( dirname( $newPath ) );
            if( rename( $path, $newPath  ))
            {
                return true;
            }
        }
        catch( Ayoola_Abstract_Exception $e  )
        {

        }
        return false;
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

    /**
     * Returns a singleton Instance
     *
     * @param void
     * @return self
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if( empty( self::$_instance[$class] ) )
        { 
        //    var_export( __LINE__ );
            self::$_instance[$class] = new $class(); 
        }
    //    var_export( $class );
		return self::$_instance[$class];
    } 	
	
}
