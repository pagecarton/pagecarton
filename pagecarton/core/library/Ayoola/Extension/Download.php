<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
		//	var_export( $values );
		
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
/*			if( @$values['plugins'] )
			{
				$directory =  '/plugins';
				foreach( $values['plugins'] as $each )
				{
					$files[] = $directory . $each;
				}
			}
*/			if( @$values['pages'] )
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
			//		var_export( $files );
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '_';
			$values['extension_name'] = strtolower( $filter->filter( $values['extension_title'] ) );
			$filename = CACHE_DIR . DS . $values['extension_name'] . '.tar';
			
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
			$fullFiles = array();
			foreach( $files as $each )
			{
			//	$regex .= '(' . $each . ')|';			
		//		$regex .= '(' . Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . $each . ')|';			
			//	$regex .= '(/application' . $each . ')|';	
				$each = str_replace( array( '/', '\\' ), DS, $each );
				$fullFiles[$each] = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . $each;	
				$fullFiles[$each] = str_replace( array( '/', '\\' ), DS, $fullFiles[$each] );
				if( ! is_file( $fullFiles[$each] ) || ! is_readable( $fullFiles[$each] ) )
				{
					unset( $fullFiles[$each] );
				}
			}
		//	var_export( $fullFiles );
		//	exit();
			$regex = trim( $regex, '|' );
			$regex = str_replace( DS, '/', "#({$regex})#" );
		//	$export->buildFromDirectory( Ayoola_Application::getDomainSettings( APPLICATION_DIR ), $regex );
			$export->buildFromIterator( new ArrayIterator( $fullFiles ), Ayoola_Application::getDomainSettings( APPLICATION_DIR ) );			
			
		//	$export->buildFromDirectory( Ayoola_Application::getDomainSettings( APPLICATION_DIR ) );
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
			
		//	$this->setViewContent( '<p class="boxednews goodnews">Plugin has been saved successfuly.</p>, true' );
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getTraceAsString());
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $e->getMessage(), true );
		//	$this->setViewContent( $this->getForm()->view(), true );
		//	return false; 
		}
    } 

}
