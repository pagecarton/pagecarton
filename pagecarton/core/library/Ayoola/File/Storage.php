<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_File_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Storage.php 3.5.2010 8.11PM Ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/File.php';


/**
 * @category   PageCarton
 * @package    Ayoola_File_Storage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_File_Storage extends Ayoola_File
{
    /**
     * File NameSpace
     *
     * @var string
     */
	protected $_namespace;
	
    /**
     * File NameSpace
     *
     * @var string
     */
	protected static $_name = 'AyoolaCmfFileId';
	
    /**
     * 
     *
     * @var array
     */
	protected static $_memCache;
	
    /**
     * 
     *
     * @var array
     */
	protected static $_falseList;
	
    /**
     * File object
     *
     * @var Ayoola_File
     */
	protected $_file;
	
    /**
     * Time in seconds to render expired.
     *
     * @var int seconds
     */
	public $timeOut;

    /**
     * Constructor
     *
     * @param string Namespace
     * 
     */
    public function __construct( $namespace = null )
    {	
		$this->setNamespace( $namespace );
    }
	
    /**
     * This method unsets a namespace in the File
     *
     * @param 
     * @return 
     */
    public function purgeNamespace( $namespace = null )
    {
		$this->setNamespace( $namespace );
		$dir = dirname( $this->getFile()->getPath() );
		Ayoola_Doc::deleteDirectoryPlusContent( $dir );
    } 
	
    /**
     * This method clears the cache of a particular domain.
     *
     * @param 
     * @return 
     */
    public static function purgeDomain( $domain = null )
    {
		$class = new self();  
		$domain = $domain ? : Ayoola_Application::getDomainName( array( 'no_cache' => true ) );
		$filter = new Ayoola_Filter_DomainName();
		$domain = $filter->filter( $domain );
		
        $cacheDestination = dirname( CACHE_DIR ) . DS . $domain;  
        Ayoola_Doc::deleteDirectoryPlusContent( $cacheDestination);
        $cacheDestination = dirname( CACHE_DIR ) . DS . 'www.' . $domain;  
        Ayoola_Doc::deleteDirectoryPlusContent( $cacheDestination);
		return true;
    } 
	
    /**
     * Stores Data in the File
     * 
     * @param mixed Data to be stored
     * @return void
     */
    public function write( $data )
    {
		$path = $this->getFile()->getPath();
		Ayoola_Doc::createDirectory( dirname( $path ) );
        
        self::$_memCache[$path] = $data;

        if( ! $data )
        {
            return self::setToFalseList( $path, $data );
        }


        self::deleteFromFalseList( $path );

        Ayoola_File::putContents( $path, json_encode( $data ) );  
        return true;       
    } 
	
    /**
     * Reads Data that was previously stored in the File
     *
     * @param 
     * @return mixed Stored Data
     */
    public function read()
    {
		$path = $this->getFile()->getPath();
        $timeOut = intval( $this->timeOut );
        @$ctime = filectime( $path ) . filemtime( $path );
        $key = $path . $ctime;
        if( null !== self::$_memCache[$path] )
        {
            return self::$_memCache[$path];
        }

        if( ! $falseResult = self::getFromFalseList( $path ) )
        {
            return $falseResult; 
        }

		if( is_file( $path ) )
		{
            if( $timeOut )
            {
                //	Check the time out 
                if( $timeOut < time() - $ctime )
                {
                    unlink( $path );
                    self::$_memCache[$path] = false;
                    return false;
                }
            }

            $data = false;
            if( $content = file_get_contents( $path ) )   
            {
                $data = json_decode( $content, true );
            }
            self::$_memCache[$path] = $data;
            return $data;
			
		}
        self::$_memCache[$path] = false;
        return false;
    } 
	
    /**
     * 
     *
     * @param void
     * @return string
     */
    public static function getFalseListFile()
    {
        $flFile = CACHE_DIR . DS . Ayoola_Application::getUrlPrefix() . DS . 'a-false-list.json';
        Ayoola_Doc::createDirectory( dirname( $flFile ) );
        return $flFile;
    }
	
    /**
     * 
     *
     * @param void
     * @return string
     */
    public static function initFalseList()
    {
        if( empty( self::$_falseList ) )
        {
            self::$_falseList = array();
            if( is_file( self::getFalseListFile() ) )
            {
                self::$_falseList = json_decode( file_get_contents( self::getFalseListFile() ), true ) ? : array();
            }
        }

    }
	
    /**
     *  false list, to limit disk io for cache of false data
     *
     * @param string
     * @param mixed
     * @return bool
     */
    public static function setToFalseList( $path, $data )
    {
        if( ! $data )
        {
            //  false list, to limit disk io for cache of false data
            self::initFalseList();
            if( array_key_exists( $path, self::$_falseList ) )
            {
                return true;
            }
            self::$_falseList[$path] = $data;
            Ayoola_File::putContents( self::getFalseListFile(), json_encode( self::$_falseList ) );   
            return true;
        }
        return false;
    }
	
    /**
     *  false list, to limit disk io for cache of false data
     *
     * @param string
     * @return string
     */
    public static function getFromFalseList( $path )
    {
        self::initFalseList();
        if( array_key_exists( $path, self::$_falseList ) )
        {
            return self::$_falseList[$path]; 
        }
        return true;
    }
	
    /**
     *  false list, to limit disk io for cache of false data
     *
     * @param string
     * @return string
     */
    public static function deleteFromFalseList( $path )
    {
        self::initFalseList();
        if( array_key_exists( $path, self::$_falseList ) )
        {
            unset( self::$_falseList[$path] );
            Ayoola_File::putContents( self::getFalseListFile(), json_encode( self::$_falseList ) ); 
        }
        return true;
    }
	
    /**
     * Retrieves Ayoola_File
     *
     * @param 
     * @return Ayoola_File
     */
    public function getFile()
    {
		if( is_null( $this->_file ) )
		{ 
			$this->_file = new Ayoola_File();
			$filter = new Ayoola_Filter_Alnum();
			$filter->replace = DS;
			$name = $filter->filter( $this->getNamespace() );
			$domain = $filter->filter( Ayoola_Application::getDomainName( array( 'no_cache' => true ) ) );
         //   PageCarton_Widget::v( Ayoola_Application::getPathPrefix() );
			$this->_file->setPath( 'STORAGE' . DS . $domain . Ayoola_Application::getPathPrefix() .  DS . $name . md5( $name ) );     
		}
        return $this->_file;
    } 
	
    /**
     * Clears data from storage
     *
     * @param void
     */
    public static function destroy()
    {
		$class = new self();
	//	$dir = $class->getFile()->getDirectory() . DS . __CLASS__ . DS;
		$dir = $class->getFile()->getDirectory() . DS . 'STORAGE' . DS;
		Ayoola_Doc::deleteDirectoryPlusContent( $dir );
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
		if( ! $namespace )
		{ 
			$namespace = __CLASS__; 
		}
        require_once 'Ayoola/Filter/Alnum.php';
		$filter = new Ayoola_Filter_Alnum();
		$filter->replace = DS;
		$namespace = $filter->filter( $namespace );
		$this->_namespace = $namespace;
	}
}
