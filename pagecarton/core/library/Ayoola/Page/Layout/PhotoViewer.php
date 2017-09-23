<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhotoViewer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_PhotoViewer extends Ayoola_Page_Layout_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /** 
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() )
			{ 
		//	var_export( $data );
	
			}
			$filename = '/layout/' . $data['layout_name'] . '/screenshot.jpg';
		//	$newFilename = dirname( $this->getMyFilename() ) . DS . 'screenshot';

			//	revert this to normal file
			if( $newFilename = Ayoola_Loader::checkFile( '/documents/layout/' . $data['layout_name'] . '/screenshot' ) )
			{
				if( is_file( $newFilename ) )
				{
					// save screenshot
					//	
					$myFile = Ayoola_Doc_Browser::getDocumentsDirectory() .  $filename;
					file_put_contents( $myFile, file_get_contents( $newFilename ) );
					unlink( $newFilename );
		//			var_export( $newFilename );
			//		var_export( $dataContent );

				//	$result = self::splitBase64Data( $dataContent );
				}
			}
		//	var_export( $filename );
		//	array( 'option' => $path )
			try
			{
				
			//	$doc = new Ayoola_Doc( array( 'option' => $filename ) );
				//	var_export( Ayoola_Loader::checkFile( $filename ) );
				if( empty( $data['screenshot_url']) OR ! $path = Ayoola_Loader::checkFile( '/documents' . $data['screenshot_url'] ) )
				{
					if( ! $path = Ayoola_Loader::checkFile( '/documents' . $filename ) )
					{
						if( $path = Ayoola_Loader::checkFile( '/documents/img/placeholder-image.jpg' ) )
						{
							//	default
						}
					}
				}
/*				#  https://stackoverflow.com/questions/7324242/headers-for-png-image-output-to-make-sure-it-gets-cached-at-browser
				header('Pragma: public');
				header('Cache-Control: max-age=86400');
				header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
*/                ImageManipulator::makeThumbnail( $path, 600, 600 );
                exit();
		//		var_export( $data );
		//		var_export( $path );
			//	exit();
/*				if( $path )
				{
					$result = array();
					$result['data'] = file_get_contents( $path );
				//	var_export();
				//	header( 'Location: ' . Ayoola_Application::getUrlPrefix() . $filename );
				//	exit();
				}
*/			}
			catch( Exception $e )
			{
			//	var_export( $e );
				null;
			}
		//	var_export( $data );
		//	if( is_object( $doc ) && $doc->view() )
		//	var_export( $newFilename );
/*			//	https://chrisjean.com/generating-mime-type-in-php-is-not-magic/
			$type = false;
			if( ! empty( $result['data'] ) )
			{
				if( function_exists( 'finfo_open' ) && function_exists( 'finfo_file' ) && function_exists( 'finfo_close' ) ) 
				{
					$f = finfo_open();
					$type = finfo_buffer( $f, $result['data'], FILEINFO_MIME_TYPE );
				}
				elseif ( function_exists( 'getimagesizefromstring' ) ) 
				{
					$fileInfo = getimagesizefromstring( $result['data'] );
					$type = $fileInfo['mime'];
				}
				else
				{
					$type = 'image/jpeg';
				}
			}
			
	//		var_export( function_exists( 'finfo_open' ) && function_exists( 'finfo_file' ) && function_exists( 'finfo_close' ) );
	//		var_export( function_exists( 'getimagesizefromstring' ) );
	//		var_export( $result );
	//		exit();
			$maxWith = 96;
			$maxHeight = 60;
			//	Setting the default to my screensize
			if( ! in_array( $type, array( 'image/gif', 'image/jpeg', 'image/png', ) ) )     
			{
			//	exit()
				header( 'Location: https://placeholdit.imgix.net/~text?txtsize=200&txt=' . $data['layout_label'] . '&w=' . $maxWith . '&h=' . $maxHeight . '' );
				exit( 'die' );
			}
			$result['formatted_image'] = false;
		//	if( @$data['document_url_base64'] )
			{
				do
				{
					$manipulator = new ImageManipulator();
					$manipulator->setImageString( $result['data'] );
					$width  = $manipulator->getWidth();
					$height = $manipulator->getHeight();
					$centreX = round( $width / 2 );
					$centreY = round( $height / 2 );
					
					
					
					if( ( $width <= $maxWith && $height <= $maxHeight ) || ! $maxWith || ! $maxHeight )
					{
						//	No need for manipulation
						break;
					}
					
 					$x1 = $centreX - ( $maxWith / 2 ); 
					$y1 = $centreY - ( $maxHeight / 2 ); 
			 
					$x2 = $centreX + ( $maxWith / 2 ); 
					$y2 = $centreY + ( $maxHeight / 2 ); 
			 
					// center cropping to 200x130
					$newImage = $manipulator->crop($x1, $y1, $x2, $y2);
					  
					$result['formatted_image'] = $manipulator->getResource();
 				}
				while( false );  
				
				if( @$_REQUEST['document_time'] )
				{
					//	Enable Cache for Documents
					// seconds, minutes, hours, days
					$expires = 60 * 60 * 24 * 14; // 14 days
					
					header( "Pragma: public" );
					header( "Cache-Control: maxage=" . $expires );
					header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
					Ayoola_Application::$accessLogging = false;
			//		var_export( $_REQUEST['document_time'] );
			//		exit( $_REQUEST['document_time'] );  
				}
				$result['formatted_image'] = $result['formatted_image'] ? : imagecreatefromstring( $result['data'] );
			//	var_export( $type );
			//	exit();
				header( 'Content-Type: ' . $type );
				switch( strtolower( $type ) ) 
				{
					case 'image/gif' :
						imagegif( $result['formatted_image'] );
					break;
					case 'image/jpeg':
						imagejpeg( $result['formatted_image'] );
					break;
					case 'image/png':
						imagepng( $result['formatted_image'] );
					break;
				}			//		var_export( $_REQUEST['document_time'] );
				imagedestroy( $result['formatted_image'] );
				exit();
			}
*/				
	//		}
		}
		catch( Application_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
		//	$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	
	// END OF CLASS
}
