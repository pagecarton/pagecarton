<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Import.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import extends Ayoola_Extension_Import_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 

			$this->createForm( 'Continue', 'Import new extension' );
		//	$this->setViewContent( $this->getForm()->view(), true );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
		//	var_export( $values );
			//	Import mode
			if( @$values['upload'] )
			{ 
				$result = self::splitBase64Data( $values['upload'] );
				$filter = new Ayoola_Filter_Name();
				$filter->replace = '-';
				$customName = time();   
				$filename = sys_get_temp_dir() . DS . $customName . '.' . $filter->filter( $result['extension'] );
				
				file_put_contents( $filename, $result['data'] );
				$newFilename = array_shift( explode( '.', $filename ) ) . '.tar.gz';
				rename( $filename, $newFilename );
				$filename = $newFilename;
			//	var_export( $filename );
				$export = new Ayoola_Phar_Data( $filename );
				
				$extensionInfo = json_decode( file_get_contents( $export['extension_information'] ), true );
	//		var_export( $extensionInfo );
				if( empty( $extensionInfo['extension_name'] ) )
				{
					return false;
				}
				$result = $this->insertDb( $extensionInfo );
				$dir = @constant( 'EXTENSIONS_PATH' ) ? Ayoola_Application::getDomainSettings( EXTENSIONS_PATH ) : ( APPLICATION_DIR . DS . 'extensions' );
				$dir = $dir . DS . $extensionInfo['extension_name'];
				if( $values['import_type'] === 'update' )
				{
					if( ! is_dir( $dir ) )
					{
						$this->setViewContent( '<p class="boxednews badnews">ERROR: DIRECTORY TO UPDATE IS NOT AVAILABLE.</p>.' ); 
						return false;
					}
					//	Disable extension
					$class = new Ayoola_Extension_Import_Status( array( 'switch' => 'off', 'extension_name' => $extensionInfo['extension_name'] ) );
					$class->init();
					
					//	remove files
					Ayoola_Doc::removeDirectory( $dir, true );
				}
				else
				{
					if( ! $result )
					{ 
						$this->setViewContent( '<p class="boxednews badnews">ERROR: COULD NOT SAVE EXTENSION DATA.</p>.' ); 
						return false;
					}
				}
				//	
				//	var_export( $dir );
				if( ! is_dir( $dir ) )
				{
					Ayoola_Doc::createDirectory( $dir );
				}
				$export->extractTo( $dir, null, true );
				unset( $export );
				unlink( $filename );
				
				$this->setViewContent( '<p class="goodnews boxednews">Extension file imported successfully.</p>', true );
				
				
				//	Clean up temp dir
		//		Ayoola_Doc::deleteDirectoryPlusContent( $tempDestination );
				return true;  
			}
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
