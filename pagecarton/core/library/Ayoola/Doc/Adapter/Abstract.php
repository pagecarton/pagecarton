<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Adapter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abtract.php 1.18.2012 7.46 ayoola $
 */

/**
 * @see Ayoola_
 */

/**
 * @category   PageCarton
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
     * Whether to embed PageCarton Widget in Doc
     *
     * @var boolean
     */
	protected $_embedWidget = false;

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
     * 
     */
    public static function getEmbedFilterPath( $path )
    {
        $fakePath = false;
        
        $fakePath = CACHE_DIR . DS .  __CLASS__ . DS . md5( $path );
        $content = file_get_contents( $path );
        $newContent = Ayoola_Page_Editor_Text::embedWidget( $content, array( 'file_path' => $path ) );  

        if( $content === $newContent )
        {
            //  redundancy
            return false;
        }
        Ayoola_Doc::createDirectory( dirname( $fakePath ) );
        if( Ayoola_File::putContents( $fakePath, $newContent ) )       
        {
            return $fakePath;
        }
    }

    /**
     * 
     */
    public static function linkToWebRoot( $pathToGo, $link )
    {
        if( $docOptions = Ayoola_Doc_Settings::retrieve( 'options' ) AND is_array( $docOptions ) AND in_array( 'link_doc_to_web_root', $docOptions )  )
        {
            if( ! Ayoola_Loader::checkFile( 'documents' . $link ) )
            {
                return false;
            }
            
            $link = trim( $link, '/ ' );
            Ayoola_Doc::createDirectory( dirname( $link ) );

            symlink( $pathToGo, $link );
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
      foreach( $this->getPaths() as $path )
      {
        header( 'Content-Description: File Transfer' );

        header( 'Content-Type: ' . $this->getContentType( $path ) );

        //  embed widget
        $fakePath = null;
        if( $this->_embedWidget )
        {
            $fakePath = self::getEmbedFilterPath( $path );       
        }  

        //  Showing livestreaming in iOS?
        header( 'Content-Disposition: attachment; filename=' . ( basename( $path ) ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $path ) );
        ob_clean();
        flush();

        if( isset($_SERVER['HTTP_RANGE'] ) ) 
        { 
            // do it for any device that supports byte-ranges not only iPhone
            self::smartReadFile( $fakePath ? : $path, basename( $path ), $this->getContentType( $path ) );
        } 
        else 
        {
            header( "Content-Length: " . filesize( $fakePath ? : $path ) );
            readfile( $fakePath ? : $path );
        }

        exit();
      }
    } 

	#	https://mobiforge.com/design-development/content-delivery-mobile-devices
    public function rangeDownload( $file ) 
    {
        $fp = @fopen($file, 'rb');
    
        $size   = filesize($file); // File size
        $length = $size;           // Content length
        $start  = 0;               // Start byte
        $end    = $size - 1;       // End byte

        // Now that we've gotten so far without errors we send the accept range header
        /* At the moment we only support single ranges.
         * Multiple ranges requires some more work to ensure it works correctly
         * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
         *
         * Multirange support annouces itself with:
         * header('Accept-Ranges: bytes');
         *
         * Multirange content must be sent with multipart/byteranges mediatype,
         * (mediatype = mimetype)
         * as well as a boundry header to indicate the various chunks of data.
         */
        header("Accept-Ranges: 0-$length");

        // multipart/byteranges
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
        if (isset($_SERVER['HTTP_RANGE'])) {
    
            $c_start = $start;
            $c_end   = $end;
            // Extract the range string
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            // Make sure the client hasn't sent us a multibyte range
            if (strpos($range, ',') !== false) {
    
                // (?) Shoud this be issued here, or should the first
                // range be used? Or should the header be ignored and
                // we output the whole content?
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                return false;
            }
            // If the range starts with an '-' we start from the beginning
            // If not, we forward the file pointer
            // And make sure to get the end byte if spesified
            if ($range == '-') {
                // The n-number of the last bytes is requested
                $c_start = $size - substr($range, 1);
            }
            else {
                $range  = explode('-', $range);
                $c_start = $range[0];
                $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }
            /* Check the range and make sure it's treated according to the specs.
             * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
             */
            // End bytes can not be larger than $end.
            $c_end = ($c_end > $end) ? $end : $c_end;
            // Validate the requested range and return an error if it's not correct.
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
    
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                return false;
            }
            $start  = $c_start;
            $end    = $c_end;
            $length = $end - $start + 1; // Calculate new content length
            fseek($fp, $start);
            header('HTTP/1.1 206 Partial Content');
        }
        // Notify the client the byte range we'll be outputting
        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: $length");
    
        // Start buffered download
        $buffer = 1024 * 8;
        while(!feof($fp) && ($p = ftell($fp)) <= $end) {
            if ($p + $buffer > $end) {
                // In case we're only outputtin a chunk, make sure we don't
                // read past the length
                $buffer = $end - $p + 1;
            }
            set_time_limit(0); // Reset time limit for big files
            echo fread($fp, $buffer);
            flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
        }
        fclose($fp);
	}
	
	#	https://www.php.net/manual/en/function.readfile.php#86244
    public function smartReadFile( $location, $filename, $mimeType='application/octet-stream' )
    { if(!file_exists($location))
      { header ("HTTP/1.0 404 Not Found");
        return;
      }
      
      $size=filesize($location);
      $time=date('r',filemtime($location));
      
      $fm=@fopen($location,'rb');
      if(!$fm)
      { header ("HTTP/1.0 505 Internal server error");
        return;
      }
      
      $begin=0;
      $end=$size;
      
      if(isset($_SERVER['HTTP_RANGE']))
      { if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches))
        { $begin=intval($matches[0]);
          if(!empty($matches[1]))
            $end=intval($matches[1]);
        }
      }
      
      if($begin>0||$end<$size)
        header('HTTP/1.0 206 Partial Content');
      else
        header('HTTP/1.0 200 OK');  
      
      header("Content-Type: $mimeType"); 
      header('Cache-Control: public, must-revalidate, max-age=0');
      header('Pragma: no-cache');  
      header('Accept-Ranges: bytes');
      header('Content-Length:'.($end-$begin));
      header("Content-Range: bytes $begin-$end/$size");
      header("Content-Disposition: inline; filename=$filename");
      header("Content-Transfer-Encoding: binary\n");
      header("Last-Modified: $time");
      header('Connection: close');  
      
      $cur=$begin;
      fseek($fm,$begin,0);
    
      while(!feof($fm)&&$cur<$end&&(connection_status()==0))
      { print fread($fm,min(1024*16,$end-$cur));
        $cur+=1024*16;
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
            //  embed widget
            $fakePath = null;
            if( $this->_embedWidget )
            {
                $fakePath = self::getEmbedFilterPath( $path );       
            }  

			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: ' . $this->getContentType( $path ) );
			header( 'Content-Transfer-Encoding: binary' );
            $pathToGo = $fakePath ? : $path;

            self::linkToWebRoot( $pathToGo, Ayoola_Application::getRequestedUri() );

            if( isset($_SERVER['HTTP_RANGE'] ) ) 
            { 
                // do it for any device that supports byte-ranges not only iPhone
                self::rangeDownload(  $pathToGo );
            } 
            else 
            {
                header( "Content-Length: " . filesize( $fakePath ? : $path ) );
                readfile( $pathToGo );
            }

    
		}
    } 
	// END OF CLASS
}
