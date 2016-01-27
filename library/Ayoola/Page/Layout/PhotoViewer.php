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
			$newFilename = dirname( $this->getMyFilename() ) . DS . 'screenshot';
		//	var_export( $filename );
		//	array( 'option' => $path )
			try
			{
				
			//	$doc = new Ayoola_Doc( array( 'option' => $filename ) );
				//	var_export( Ayoola_Loader::checkFile( $filename ) );
				if( $path = Ayoola_Loader::checkFile( '/documents' . $filename ) )
				{
					header( 'Location: ' . Ayoola_Application::getUrlPrefix() . $filename );
					exit();
/* 					$filter = new Ayoola_Filter_Name();      
					$filter->replace = '-';
					$customName = substr( trim( $filter->filter( @$data['layout_name'] ) , '-' ), 0, 70 );
				//	$newFilename = sys_get_temp_dir() . DS . $customName;
					file_put_contents( $newFilename, 'data:image/jpeg;base64,' . base64_encode( file_get_contents( $path ) ) );
 */				//	var_export( $newFilename );
				//	var_export( $path );
				//	exit(); 
					
				//	var_export( $path );
				//	exit();
				}
			}
			catch( Exception $e )
			{
			//	var_export( $e );
				null;
			}
		//	var_export( $data );
		//	if( is_object( $doc ) && $doc->view() )
			if( is_file( $newFilename ) )
			{
				// save screenshot
				$data = file_get_contents( $newFilename );
			//	var_export( $newFilename );
			//	var_export( $data );

				$result = self::splitBase64Data( $data );
				$filter = new Ayoola_Filter_Name(); 
				$filter->replace = '-';
				$path = sys_get_temp_dir() . DS . $data['layout_name'] . '.' . $result['extension'];
				
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
					$maxWith = @intval( $_REQUEST['max_width'] ) ? : $articleSettings['cover_photo_width'];
					$maxHeight = @intval( $_REQUEST['max_height'] ) ? : $articleSettings['cover_photo_height']; 
					
				//	var_export( $maxWith );
				//	var_export( $maxHeight );
				//	exit();
					
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
			else
			{
				header( 'Location: https://placeholdit.imgix.net/~text?txtsize=75&txt=No%20Screenshot&w=900&h=300' );
				exit();
			}
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
