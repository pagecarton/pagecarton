<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Adapter.php 1.17.2012 11.01 ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter
{
    /**
     * The Document Loader
     *
     * @var array Containing Ayoola_Doc_Adapter_Interface
     */
	protected $_loaders = array();

    /**
     * File paths
     *
     * @var array 
     */
	protected $_paths = array();

    /**
     * Whether to view inline or as attachment
     *
     * @var boolean 
     */
	protected $_inlineViewMode = false;

    /**
     * The Title of the Document
     *
     * @var string 
     */
	public $title;

    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct( $paths = array() )
    {
	//	var_export( $paths );
		$this->setPaths( $paths );
    }
	
    /**
     * Sets _inlineViewMode to a value
     *
     * @param boolean
     */
    public function setInlineViewMode( $flag )
    {
        $this->_inlineViewMode = $flag;
    } 
	
    /**
     * Return _inlineViewMode
     *
     * @return boolean
     */
    public function getInlineViewMode()
    {
        return $this->_inlineViewMode;
    } 
	
    /**
     * Return the Adapter property to a value
     *
     * @return Ayoola_Doc_Adapter_Interface
     */
     public function getLoaders()
    {
		if( ! $this->_loaders )
		{
			$this->setLoader( $this->getPaths() );
		}

        return (array) $this->_loaders;
    } 
	
    /**
     * This method assigns a loader per path
     *
     * @param string
     * @return boolean
     */
    public function setLoader( $paths = null )
    {
		$paths = $paths ? : $this->getPaths();
		//var_export( $this->getPaths() );
		foreach( $paths as $path )
		{ 
			require_once 'Ayoola/Filter/FileExtention.php';
			$filter = new Ayoola_Filter_FileExtention();
			$extention = $filter->filter( $path );
			
			//	Switching off because of Mp3 in Ayoola_Doc
/* 			require_once 'Ayoola/Filter/Alpha.php';
			$filter = new Ayoola_Filter_Alpha();
			$extention = $filter->filter( $extention );
 */
			$default = 'Ayoola_Doc_Adapter_';
			$class = $default . ucfirst( strtolower( $extention ) );
			$default = $default . 'Default';
		//	$class = 'Ayoola_Doc_Adapter';

			require_once 'Ayoola/Loader.php';
			
			if( ! Ayoola_Loader::loadClass( $class ) )
			{
				if( ! array_key_exists( strtolower( $extention ), $default::getAllowedExtentions() ) )
				{
				//	echo( $extention );
				//	echo( $path );
				//	var_export( $default::getAllowedExtentions() );
				
					require_once 'Ayoola/Doc/Exception.php';
					throw new Ayoola_Doc_Exception( 'Adapter not found for "' . $extention . '" file extentions.' );
				}
				$class = $default;
			}
			$this->_loaders[$class][] = $path;
		}
	} 
	
    /**
     * This method returns the file path
     *
     * @return string File Path
     */
    public function getPaths()
    {
        return (array) $this->_paths;
    } 
	
    /**
     * This method sets the path parameter to a value
     *
     * @param string File Path
     * @return void
     */
    public function setPaths( $paths )
    {
	//	var_export( $paths );
		if( is_array( $paths ) )
		{
			$this->_paths = array_merge( $this->getPaths(), $paths );
		}
		elseif( is_string( $paths ) )
		{
			$this->_paths[] = $paths;
		}
    } 

    /**
     * Force the download of the document
     *
     * @param void
     * @return void
     */
    public function download()
    {
		//	As written in the PHP Manual
		//	On How to use the functuon readfile()
		//	var_export( $this->getLoaders() );
		foreach( $this->getLoaders() as $class => $paths )
		{	
			require_once 'Ayoola/Filter/ClassToFilename.php';
			$filter = new Ayoola_Filter_ClassToFilename();
			$filename = $filter->filter( $class );
			require_once $filename;
			$loader = new $class( $paths );
			$loader->download();
		}
    } 
	
    /**
     * This method outputs the document
     *
     * @param void
     * @return mixed
     */
    public function view()
    {
		$viewValue = null;
		//var_export( $this->getLoaders() );
		foreach( $this->getLoaders() as $class => $paths )
		{	
			require_once 'Ayoola/Filter/ClassToFilename.php';
			$filter = new Ayoola_Filter_ClassToFilename();
			$filename = $filter->filter( $class );
			require_once $filename;
			$loader = new $class( $paths );
			$viewValue = $this->getInlineViewMode() ? $loader->viewInline( $this->title ) : $loader->view();
			//var_export( $viewValue );
		}
		return $viewValue;
    } 
	
	// END OF CLASS
}
