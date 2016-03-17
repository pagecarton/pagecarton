<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abtract.php 1.18.2012 7.46 ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Adapter_Abstract implements Ayoola_Doc_Adapter_Interface
{

    /**
     * Default Content Type Description
     *
     * @var string
     */
	protected $_defaultContentTypeDesc = null;
	
    /**
     * The Default Content Type to be used for the Documents
     *
     * @var string
     */
	protected $_defaultContentType = null;
	
    /**
     * Content type for this document
     *
     * @var string
     */
	protected $_contentType;

    /**
     * The Content of the Document
     *
     * @var string
     */
	protected $_content = null;

    /**
     * The Path of the Document
     *
     * @var mixed
     */
	protected $_paths = array();

    /**
     * Constructor
     *
     * @param string Filename
     * 
     */
    public function __construct( $paths = null )
    {
	//	var_export( $paths );
		$this->setPaths( $paths );		
    }

    /**
     * Detects the Content Type
     *
     * @param void
     * @return string
     */
    public function getExtention( $path )
    {
		require_once 'Ayoola/Filter/FileExtention.php';
		$filter = new Ayoola_Filter_FileExtention();
		$extention = strtolower( $filter->filter( $path ) );

		require_once 'Ayoola/Filter/Alpha.php';
		$filter = new Ayoola_Filter_Alpha();
		$extention = $filter->filter( $extention );
		
		return (string) $extention;
    } 

    /**
     * Detects the Content Type
     *
     * @param void
     * @return string
     */
    public function getContentType( $path = null )
    {
		if( $this->_contentType )
		{
			return $this->_contentType;
		}
		$extention = $this->getDefaultContentTypeDesc() ? : $this->getExtention( $path );
		$contentType = $this->getDefaultContentType() ? $this->getDefaultContentType() . $extention : null; 

		return (string) $contentType;
    } 

    /**
     * return the default content type
     *
     * @param void
     * @return string
     */
    public function getDefaultContentType()
    {
		return (string) $this->_defaultContentType;
    } 

    /**
     * return the default content type description e.g. css
     *
     * @param void
     * @return string
     */
    public function getDefaultContentTypeDesc()
    {
		return (string) $this->_defaultContentTypeDesc;
    } 

    /**
     * return content property
     *
     * @param void
     * @return string
     */
    public function getContent()
    {
		return (string) $this->_content;
    } 
	
    /**
     * Sets the content property to a value
     *
     * @param void
     * @return void
     */
    public function setContent( $content )
    {
		$this->_content .= (string) $content;
    } 
	
    /**
     * Sets the Path property to a value
     *
     * @param string Filename
     * @return void
     */
    public function setPaths( $path )
    {
		if( is_array( $path ) )
		{
			$this->_paths = array_merge( $this->getPaths(), $path );
		}
		elseif( is_string( $path ) )
		{
			$this->_paths[] = $path;
		}
    } 
	
    /**
     * This method outputs the document
     *
     * @param void
     * @return void
     */
    public function getPaths()
    {
        return (array) $this->_paths;
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
		foreach( $this->getPaths() as $path )
		{
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			exit();
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
		$paths = array_unique( $this->getPaths() );
		foreach( $paths as $path )
		{	
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: ' . $this->getContentType( $path ) );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . filesize( $path ) );
/* 			setlocale( LC_TIME, "C" );
			$ft = filemtime ( $path );
			$localt = mktime();
			$gmtt = gmmktime();
			$ft = $ft - $gmtt + $localt;
			$modified = strftime( "%a, %d %b %Y %T GMT", $ft );
			header( 'Last-Modified: ' . $modified );
 */			//header('Expires: 0');
			//header('Cache-Control: must-revalidate, post-check = 0, pre-check=0');
			//header('Pragma: public');

		//	ob_clean();
		//	flush();  
		//	exit( $path );
			readfile( $path );
		}
    } 
	// END OF CLASS
}
