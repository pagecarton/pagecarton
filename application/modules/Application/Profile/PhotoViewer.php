<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Profile_PhotoViewer
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: PhotoViewer.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Profile_PhotoViewer
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
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
			if( @$data['display_picture_base64'] )
			{
				$baseArray = explode( ',', $data['display_picture_base64'] );
				$baseExt = null;
				if( count( $baseArray ) > 1 )
				{
					$data['display_picture_base64'] = base64_decode( array_pop( $baseArray ) );
					$baseExt = array_pop( $baseArray );
		//	var_export( array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) ) ); 
					$baseExt = array_shift( explode( ';', array_pop( explode( '/', $baseExt ) ) ) );
			//		var_export( $baseExt ); 
				}
				else
				{
				//	exit()
					header( 'Location: /404' );
					exit( 'die' );
				}
				$filter = new Ayoola_Filter_Name();
				$filter->replace = '-';
				$customName = substr( trim( $filter->filter( @$data['display_name'] . '_' . microtime() ) , '-' ), 0, 70 );
				$path = sys_get_temp_dir() . DS . $customName . '.' . $baseExt;
				
				file_put_contents( $path, $data['display_picture_base64'] );
				do
				{
					$manipulator = new ImageManipulator( $path );
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
					$manipulator->save( $path );
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
				$document = new Ayoola_Doc( array( 'option' => $path ) ); 
				$document->view();
	//		var_export( $data['download_base64'] ); 
		//	var_export( $path ); 
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
