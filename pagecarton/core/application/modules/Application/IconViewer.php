<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
            $url = $this->getParameter( 'url' ) ? : @$_REQUEST['url'];

            if( empty( $url ) || ! Ayoola_Loader::checkFile( '/documents' . $url ) )
            {
                
       //         Ayoola_Application::view();
      //          exit();
      //          return false;
            }
            //  Code that runs the widget goes here...
            $ext = strtolower( array_pop( explode( '.', $url ) ) );
            switch( $ext )
            {
                case 'jpg':
                case 'jpeg':
                case 'gif':
        //        case 'ico':
       //         case 'bmp':
                case 'png':
                    //  The url is same
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
     //       var_export( $url );
    //        header( 'Location: ' . $url );
            @$maxWith = $this->getParameter( 'max_width' ) ? : @intval( $_REQUEST['max_width'] );
            @$maxHeight = $this->getParameter( 'max_height' ) ? : @intval( $_REQUEST['max_height'] ); 

            if( $path = Ayoola_Loader::checkFile( 'documents' . $url ) )
            {


            }
			
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
        //		var_export( $_REQUEST['document_time'] );
        //		exit( $_REQUEST['document_time'] );
            }
            else
            {
			//	$fn = DOCUMENTS_DIR . DS . $url;
                if( $path )
                {
                    header('Cache-Control: private');

                    $docTime = filemtime( $path );

            //        var_export( $docTime );
     //               var_export( $path );
        //            exit();

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
                header( 'Content-Length: ' . filesize( $path ) );
                
            }
            if( $path AND ( $maxHeight || $maxWith ) )
            {
         //      var_export( $maxWith );
          //     var_export( $maxHeight );
         //      var_export( $path );
         //       exit();
                ImageManipulator::makeThumbnail( $path, $maxWith, $maxHeight );
                exit();
                //	default
            }
            $doc = new Ayoola_Doc( array( 'option' => $url ) );
            $doc->view();
            exit();
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
