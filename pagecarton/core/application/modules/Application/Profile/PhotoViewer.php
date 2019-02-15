<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhotoViewer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_PhotoViewer extends Application_Profile_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		#  https://stackoverflow.com/questions/7324242/headers-for-png-image-output-to-make-sure-it-gets-cached-at-browser
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		try
		{ 
			if( ! $data = self::getIdentifierData() )
			{ 
	//		var_export( $data );
	//		exit();
			//	$userInfo = Ayoola_Application::getUserInfo();
				//	Get information about the user access information
				if( ! $data = Ayoola_Access::getAccessInformation( Ayoola_Application::getUserInfo( 'username' ) ) )
				{
				Application_IconViewer::viewInLine( array( 'url' => $data['document_url'] ) );
                exit();
				//	exit()
				//	header( 'Location: https://placeholdit.imgix.net/~text?txtsize=75&txt=No Photo&w=300&h=300' );
				//	exit( 'die' );
				}
			//	var_export( $data );
			}
			//	var_export( $data['display_picture'] );
          //      exit();
			if( $data['display_picture'] )
			{ 
			//	var_export( $data['display_picture'] );
				Application_IconViewer::viewInLine( array( 'url' => $data['display_picture'] ) );
                exit();
			}
	//		var_export( $data );
	//		exit();
			$result = self::splitBase64Data( @$data['display_picture_base64'] );
			
			//	https://chrisjean.com/generating-mime-type-in-php-is-not-magic/
			if ( function_exists( 'finfo_open' ) && function_exists( 'finfo_file' ) && function_exists( 'finfo_close' ) ) 
			{
				$f = finfo_open();
				$type = finfo_buffer( $f, $result['data'], FILEINFO_MIME_TYPE );
			}
			elseif ( function_exists( 'getimagesizefromstring' ) ) 
			{
				$fileInfo = getimagesizefromstring( $result['data'] );
				$type = $fileInfo['mime'];
			}
			if( ! in_array( $type, array( 'image/gif', 'image/jpeg', 'image/png', ) ) )
			{
			//	exit()
				Application_IconViewer::viewInLine( array( 'url' => $data['document_url'] ) );
                exit();
			//	header( 'Location: https://placeholdit.imgix.net/~text?txtsize=75&txt=' . @$data['display_name'] . '&w=300&h=300' );
				exit( 'die' );
			}
			$result['formatted_image'] = false;
		//	if( $data['display_picture_base64'] )
			{
				do
				{
				
					$manipulator = new ImageManipulator();
					$manipulator->setImageString( $result['data'] );
					$width  = $manipulator->getWidth();
					$height = $manipulator->getHeight();
					$centreX = round( $width / 2 );
					$centreY = round( $height / 2 );
					
					//	Setting the default to my screensize
					$maxWith = @intval( $_REQUEST['max_width'] ) ? : 300;
					$maxHeight = @intval( $_REQUEST['max_height'] ) ? : 300; 
					
			//		var_export( $maxWith );
			//		var_export( $maxHeight );
			//		exit();
					
					if( $width <= $maxWith && $height <= $maxHeight )
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
				
				if( $_REQUEST['time'] )
				{
					//	Enable Cache for Documents
					// seconds, minutes, hours, days
					$expires = 60 * 60 * 24 * 14; // 14 days
					
					header( "Pragma: public" );
					header( "Cache-Control: maxage=" . $expires );
					header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
				}
				$result['formatted_image'] = $result['formatted_image'] ? : imagecreatefromstring( $result['data'] );
				

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
		}
		catch( Application_Profile_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
		//	$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
