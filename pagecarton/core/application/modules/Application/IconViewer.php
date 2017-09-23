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
            if( empty( $_REQUEST['url'] ) || ! Ayoola_Loader::checkFile( '/documents' . $_REQUEST['url'] ) )
            {
                
       //         Ayoola_Application::view();
      //          exit();
      //          return false;
            }
            $url = @$_REQUEST['url'];
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
            if( $path = Ayoola_Loader::checkFile( 'documents' . $url ) )
            {
                ImageManipulator::makeThumbnail( $path );
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
