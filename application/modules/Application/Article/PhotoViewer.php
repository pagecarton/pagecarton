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
			if( ! $data = self::getIdentifierData() )
			{ 
			//	$userInfo = Ayoola_Application::getUserInfo();
				//	Get information about the user access information
				if( ! $data = Ayoola_Access::getAccessInformation( Ayoola_Application::getUserInfo( 'username' ) ) )
				{
					return false; 
				}
			//	var_export( $data );
			}
			if( @$data['document_url_base64'] )
			{
/* 				$baseArray = explode( ',', $data['document_url_base64'] );
				$baseExt = null;
				if( count( $baseArray ) > 1 )
				{
					$data['document_url_base64'] = base64_decode( array_pop( $baseArray ) );
					$baseExt = array_pop( $baseArray );
		//	var_export( array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) ) ); 
					$baseExt = array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) );
			//		var_export( $baseExt ); 
				}
 */		
				$result = self::splitBase64Data( $data['document_url_base64'] );
				$filter = new Ayoola_Filter_Name(); 
				$filter->replace = '-';
				$customName = substr( trim( $filter->filter( @$data['display_name'] . '_' . @$data['article_title'] . '_' . microtime() ) , '-' ), 0, 70 );
				$path = sys_get_temp_dir() . DS . $customName . '.' . $result['extension'];
		//	var_export( $path );
	//		var_export( $data['document_url_base64'] );   
				
				file_put_contents( $path, $result['data'] );
				do
				{
					$manipulator = new ImageManipulator( $path );
					$width  = $manipulator->getWidth();
					$height = $manipulator->getHeight();
					$centreX = round( $width / 2 );
					$centreY = round( $height / 2 );
					
					//	Setting the default to my screensize
					$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
					$maxWith = @intval( $_REQUEST['max_width'] ) ? : ( $articleSettings['cover_photo_width'] ? : 1500 );
					$maxHeight = @intval( $_REQUEST['max_height'] ) ? : ( $articleSettings['cover_photo_height'] ? : 1500 ); 
					
			//		var_export( $maxWith );
			//		var_export( $maxHeight );
			//		exit();
					
					if( ( $width <= $maxWith && $height <= $maxHeight ) || ! $maxWith || ! $maxHeight )
					{
						//	No need for manipulation
						break;
					}
					
 					$x1 = $centreX - ( $maxWith / 2 ); 
					$y1 = $centreY - ( $maxHeight / 2 ); 
			 
					$x2 = $centreX + ( $maxWith / 2 ); 
					$y2 = $centreY + ( $maxHeight / 2 ); 
			//		var_export( $articleSettings );
			//		var_export( $articleSettings );
			//		var_export( $x1 );
			//		var_export( $x2 );
			 
					// center cropping to 200x130
					$newImage = $manipulator->crop($x1, $y1, $x2, $y2);
					$manipulator->save( $path );
 				}
				while( false );  
				
			//		var_export( $_REQUEST['document_time'] );
				
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
				else
				{
			//		var_export( $_REQUEST['document_time'] );
			//		exit( $_REQUEST['document_time'] );
				}
			//	header( 'Content-Type: image/jpeg' . '' );
				$document = new Ayoola_Doc( array( 'option' => $path ) ); 
				$document->view();
	//		var_export( $data['download_base64'] ); 
		//	var_export( $path ); 
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
