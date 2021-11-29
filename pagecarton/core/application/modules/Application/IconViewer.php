<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_PhotoViewer
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhotoViewer.php Monday 11th of September 2017 04:23PM  $
 */

/**
 * @see PageCarton_Widget
 */

class Application_IconViewer extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Icon Viewer'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
                @$maxWith = ( $this->getParameter( 'max_width' ) ? : @intval( $_REQUEST['max_width'] ) );
                @$maxHeight = ( $this->getParameter( 'max_height' ) ? : @intval( $_REQUEST['max_height'] ) ); 

            if( ! $path = $this->getParameter( 'path' ) OR ! is_file( $path ) )
            {

                $url = $this->getParameter( 'url' ) ? : @$_REQUEST['url'];

                if( empty( $url ) || ! Ayoola_Loader::checkFile( '/documents' . $url ) )
                {
                    
                }
                elseif( Ayoola_Loader::checkFile( '/documents/__' . $url ) )
                {
                    $url = '/__' . $url;
                } 
                //  Code that runs the widget goes here...
                $realExt = explode( '.', $url );
                $realExt = array_pop( $realExt );
                $realExt = strtolower( $realExt );
                $ext = @$_REQUEST['extension'] ? : $realExt;  
                if( $realExt == $url )
                {

                }
                $defaultWidth = 600;
                $defaultHeight = 600;
                switch( $ext )
                {
                    case 'jpg':
                    case 'jpeg':
                    case 'gif':
                    case 'png':
                        //  The url is same
                        $url = $url ? : '/img/placeholder-image.jpg';
                        if( empty( $_GET['crop'] ) )
                        {
                            $defaultWidth = 0;
                            $defaultHeight = 0;
                        }
                    break;
                    case 'ico':
                        //  The url is same
                        $url = $url ? : '/img/placeholder-image.jpg';
                        $noImageManipulation = true;
                    break;
                    case 'tar':
                    case 'gz':
                    case 'zip':
                        $url = '/img/file-zip-icon.png';
                    break;
                    case 'pdf':
                        $url = '/img/pdf-icon.png';
                    break;
                    case 'txt':
                    case 'css':
                    case 'js':
                        $url = '/img/file-text-icon.png';
                    break;
                    case 'doc':
                    case 'docx':
                    case 'pptx':
                    case 'ppt':
                    case 'xls':
                    case 'xlsx':
                        $url = '/img/document-icon.png';
                    break;
                    case 'mp3':
                    case 'wma':
                    case 'm4a':
                    case 'aac':
                        $url = '/img/audio-icon.png';
                    break;
                    case 'mp4':
                    case 'flv':
                    case 'avi':
                        $url = '/img/video-icon.png';
                    break;
                    default:
                        $url = '/img/file-icon.png';
                    break;
                }
                $url = $url ? : '/img/file-icon.png';
                if( ! $path = Ayoola_Loader::checkFile( 'documents' . $url ) )
                {  
                    $url = '/img/error-icon.png';
                    if( ! $path = Ayoola_Loader::checkFile( 'documents' . $url ) )
                    {  
                        $errorMessage = '<p class="badnews">Document does not  exist</p>';
                        echo $errorMessage;
                    }
                }
            }
            $maxWith = $maxWith ? : $defaultHeight;
            $maxHeight = $maxHeight ? : $defaultHeight; 
			
            //  cache me
            if( $_REQUEST['document_time'] )
            {
                //	Enable Cache for Documents
                // seconds, minutes, hours, days
                $expires = 60 * 60 * 24 * 140; // 140 days
                
                header( "Pragma: public" );
                header( "Cache-Control: maxage=" . $expires );
                header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
                Ayoola_Application::$accessLogging = false;
            }
            else
            {
                if( $path )
                {
                    header('Cache-Control: private');

                    $docTime = filemtime( $path );


                    // Checking if the client is validating his cache and if it is current.
                    if( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && ( strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) == $docTime ) ) 
                    {
                        // Client's cache IS current, so we just respond '304 Not Modified'.
                        header( 'Last-Modified: '.  gmdate( 'D, d M Y H:i:s', $docTime ) . ' GMT', true, 304 );
                        exit(); 
                    } 
                    else 
                    {
                        // Image not cached or cache outdated, we respond '200 OK' and output the image.
                        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $docTime ) . ' GMT', true, 200 );
                    }
                }
                //  browser keep expecting more when we resize and download has different size
                //      header( 'Content-Length: ' . filesize( $path ) );
                
            }

            if( $path AND ( $maxHeight || $maxWith ) AND empty( $noImageManipulation ) AND function_exists( 'imagecreatetruecolor' ) )
            {
                ImageManipulator::makeThumbnail( $path, $maxWith, $maxHeight );
                exit();
                //	default
            }
            elseif( $url )
            {
                header( 'Location: ' . Ayoola_Application::getUrlPrefix() . $url . '?' . http_build_query( $_GET ), '301' );
                exit();
            }
            elseif( $path )
            {
                readfile( $path );
                exit();
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
