<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Download.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Download extends Ayoola_Extension_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 

			if( ! $values = self::getIdentifierData() ){ return false; }
		
			$files = array();
		//	$appPath = Ayoola_Application::getDomainSettings( APPLICATION_PATH );
			if( @$values['modules'] )
			{
				$directory =   '/modules';
				foreach( $values['modules'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
			if( @$values['databases'] )
			{
				$directory =  '/databases';
				foreach( $values['databases'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
			if( @$values['documents'] )
			{
				$directory =  '/documents';
				foreach( $values['documents'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
			if( @$values['plugins'] )
			{
				$directory =  '/plugins';
				foreach( $values['plugins'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
			if( @$values['pages'] )
			{
				$directory =  '/';
				foreach( $values['pages'] as $uri )
				{
					if( $pagePaths = Ayoola_Page::getPagePaths( $uri ) )
					{
						foreach( $pagePaths as $each )
						{
							$files[] = $directory . $each;
						}
					}
				}
			}
			if( @$values['templates'] )
			{
				$directory =  '/documents/layout/';
				foreach( $values['templates'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
			
			//		var_export( $files );
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '_';
			$values['extension_name'] = strtolower( $filter->filter( $values['extension_title'] ) );
			$filename = sys_get_temp_dir() . DS . $values['extension_name'] . '.tar';
			
			//	remove previous files
			@unlink( $filename );
			@unlink( $filename . '.gz' );
	//	var_export( $path );
	//		var_export( $data['document_url_base64'] );
			
		//	file_put_contents( $path, $data['document_url_base64'] );
			
			$values['files'] = $files;
			$phar = 'Ayoola_Phar_Data';
			$export = new $phar( $filename  );
			$export->startBuffering();
			$regex = null;
			foreach( $files as $each )
			{
				$regex .= '(' . Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . $each . ')|';			
			}
			$regex = trim( $regex, '|' );
			$regex = str_replace( DS, '/', "#{$regex}#" );
		//	var_export( $files );
		//	var_export( $regex );
		//	return false;
			$export->buildFromDirectory( Ayoola_Application::getDomainSettings( APPLICATION_DIR ), $regex );
			$export['extension_information'] = json_encode( $values );
			$export->stopBuffering();
			
			$export->compress( Ayoola_Phar::GZ ); 
			unset( $export );
			$phar::unlinkArchive( $filename );
			
			//	download
			header( 'Content-Type: application/x-gzip' . '' );
			$document = new Ayoola_Doc( array( 'option' => $filename . '.gz' ) ); 
			$document->download();
			
	//		var_export( $data['download_base64'] ); 
	//	var_export( $path ); 
			
			//	remove previous files
			@unlink( $filename );
			@unlink( $filename . '.gz' );
			exit();
			
		//	$this->setViewContent( '<p class="boxednews goodnews">Extension has been saved successfuly.</p>, true' );
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getTraceAsString());
		//	$this->getForm()->setBadnews( $e->getMessage() );
		//	$this->setViewContent( $this->getForm()->view(), true );
		//	return false; 
		}
    } 

}
