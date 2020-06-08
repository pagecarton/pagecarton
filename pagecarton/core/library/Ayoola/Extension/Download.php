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

            $files = array();

			if( @$values['settings_class'] )
			{
                $filter = new Ayoola_Filter_ClassToFilename();
                $path = $filter->filter( $values['settings_class'] );
                $base = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';
                $file = Ayoola_Doc::getRelativePath( $path, $base );
                $values['modules'] = $file;
            }
            
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
                    
                    //  Supplementary files
                    $supDir = $directory . dirname( $each ) . DS . '__' . DS . array_shift( explode( '.', basename( $each ) ) );
                    $files[] = $supDir;
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

			$filter = new Ayoola_Filter_Name();
			$filter->replace = '_';
			$values['extension_name'] = strtolower( $filter->filter( $values['extension_title'] ) );
			$filename = CACHE_DIR . DS . $values['extension_name'] . '.tar';
			
			//	remove previous files
			@unlink( $filename );
			@unlink( $filename . '.gz' );
			
			$values['files'] = $files;
			$phar = 'Ayoola_Phar_Data';
			$export = new $phar( $filename  );
			$export->startBuffering();
			$regex = null;
            $fullFiles = array();

			foreach( $files as $each )
			{
				$each = str_replace( array( '/', '\\' ), DS, $each );
				$fullFiles[$each] = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . $each;	
                $fullFiles[$each] = str_replace( array( '/', '\\' ), DS, $fullFiles[$each] );

				if( is_dir( $fullFiles[$each] ) && is_readable( $fullFiles[$each] ) )
				{
                    $innerFiles = Ayoola_Doc::getFilesRecursive( $fullFiles[$each] );
                    $fullFiles += $innerFiles;
                    unset( $fullFiles[$each] );
				}
				elseif( ! is_file( $fullFiles[$each] ) || ! is_readable( $fullFiles[$each] ) )
				{
					unset( $fullFiles[$each] );
				}
			}

			$regex = trim( $regex, '|' );
			$regex = str_replace( DS, '/', "#({$regex})#" );

			$export->buildFromIterator( new ArrayIterator( $fullFiles ), Ayoola_Application::getDomainSettings( APPLICATION_DIR ) );			

			$export['extension_information'] = json_encode( $values );
			$export->stopBuffering();
			
			$export->compress( Ayoola_Phar::GZ ); 
			unset( $export );
			$phar::unlinkArchive( $filename );
			
			//	download
			header( 'Content-Type: application/x-gzip' . '' );
			$document = new Ayoola_Doc( array( 'option' => $filename . '.gz' ) ); 
			$document->download();

			
			//	remove previous files
			@unlink( $filename );
			@unlink( $filename . '.gz' );
			exit();

		}
		catch( Exception $e )
		{ 

			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $e->getMessage(), true );

		}
    } 

}
