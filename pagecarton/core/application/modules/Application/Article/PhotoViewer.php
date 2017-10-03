<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhotoViewer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_PhotoViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_PhotoViewer extends Application_Article_Abstract
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
		try
		{ 
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			@$maxWith = @intval( $_REQUEST['max_width'] ) ? : ( $articleSettings['cover_photo_width'] ? : null );
			@$maxHeight = @intval( $_REQUEST['max_height'] ) ? : ( $articleSettings['cover_photo_height'] ? : null ); 
		//	var_export( $maxWith );
		//	var_export( $maxHeight );
		//		exit();
			
			if( ! $data = self::getIdentifierData() )
			{ 
/*				header( 'Location: https://placeholdit.imgix.net/~text?txtsize=200&txt=No Photo&w=' . $maxWith . '&h=' . $maxHeight . '' );
				exit( 'die' );
*/			}
			if( empty( $data['document_url_base64'] ) )
			{
				if( empty( $data['document_url'] ) )
				{
				//	header( 'Location: https://placeholdit.imgix.net/~text?txtsize=200&txt=No Photo&w=' . $maxWith . '&h=' . $maxHeight . '' );
			//		exit( 'die' );
					$data['document_url'] = '/img/placeholder-image.jpg';
				}
			//	var_export( $data['document_url'] );
				Application_IconViewer::viewInLine( array( 'url' => $data['document_url'] ) );
                exit();

	//			$data['document_url_base64'] = base64_encode( file_get_contents( Ayoola_loader::checkFile( 'documents' . $data['document_url'], array( 'prioritize_my_copy' => true, ) ) ) );
			//	var_export( Ayoola_loader::checkFile( 'documents' . $data['document_url'], array( 'prioritize_my_copy' => true, ) ) );
			}
			$result = self::splitBase64Data( $data['document_url_base64'] );
		//	var_export( $result );

		//	exit();
			
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
			//	No need to support < php 5.3
/* 			elseif ( function_exists( 'mime_content_type' ) ) 
			{
				
				tempnam()
				$type = mime_content_type( $result['data'] );
			}
 */			
		//	var_export( $type );
		//	var_export( $result );
			//	Setting the default to my screensize
			if( ! in_array( $type, array( 'image/gif', 'image/jpeg', 'image/png', ) ) )
			{
			//	exit()
				header( 'Location: https://placeholdit.imgix.net/~text?txtsize=200&txt=' . $data['article_title'] . '&w=' . $maxWith . '&h=' . $maxHeight . '' );
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
			//		$centreX = round( $width / 2 );
			//		$centreY = round( $height / 2 );
					
					
					
					if( $width < $maxWith || $height < $maxHeight )
					{
						//	No need for manipulation
						if( isset( $_REQUEST['max_width'], $_REQUEST['max_height'] ) )
						{
					//		header( 'Location: https://placeholdit.imgix.net/~text?txtsize=200&txt=Image too Small&w=' . $maxWith . '&h=' . $maxHeight . '' );
					//		exit( 'die' );
						}
					}
					
					if( ( $width <= $maxWith && $height <= $maxHeight ) || ! $maxWith || ! $maxHeight )
					{
						//	No need for manipulation
				//		break;
					}
					if( ! $maxWith || ! $maxHeight )
					{
						//	No need for manipulation
						break;
					}
				//	var_Export( $width );
				//	var_Export( $height );
					$maxWithToUse = $maxWith;
					$maxHeightToUse = $maxHeight;
				//	$width = 300;
				//	$min = 200;
					$ratio = 0;
					if($width > $height){
						$ratio = $width/$height;
					//	$height = $min;
						$maxWithToUse = round( $maxHeight * $ratio );
					} elseif( $width < $height )  {
						$ratio = $height/$width;
					//	$width = $min;
						$maxHeightToUse = round( $maxWith * $ratio);
					}					
				//	if( $ratio ) 
					{
						$manipulator->resample( $maxWithToUse, $maxHeightToUse, true );
					}
					$width  = $manipulator->getWidth();
					$height = $manipulator->getHeight();
					$centreX = round( $width / 2 );
					$centreY = round( $height / 2 );

					
 					$x1 = $centreX - ( $maxWith / 2 ); 
					$y1 = $centreY - ( $maxHeight / 2 ); 
			 
					$x2 = $centreX + ( $maxWith / 2 ); 
					$y2 = $centreY + ( $maxHeight / 2 ); 
			 
					// center cropping to 200x130
			//		var_Export( $maxWith );
			//		var_Export( $maxHeight );
			//		var_Export( $width );
			//		var_Export( $height );
					
			//		exit();
					
					$manipulator->crop($x1, $y1, $x2, $y2);
					  
					$result['formatted_image'] = $manipulator->getResource();
 				}
				while( false );  
				
				if( $_REQUEST['document_time'] )
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
		catch( Application_Article_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
		//	$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
