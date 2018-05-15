<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Creator extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
		//	var_export( Ayoola_Page::getCurrentPageInfo( 'upload' ) );
			$this->createForm( 'Save', 'Add a new layout theme' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
		//	var_export( $values );
	//		var_export( $this->getParameter( 'path' ) );
	//		var_export( Ayoola_Loader::checkFile( $this->getParameter( 'path' ) ) );
			
			//	Import mode
	//		if( @$values['upload'] )
			if( @$values['theme_url'] || Ayoola_Loader::checkFile( $this->getParameter( 'path' ) ) )
			{ 				
				//	let's use url so that it will be possible to put the theme on the server via filemanager
					if( ! $filename = Ayoola_Loader::checkFile( Ayoola_Doc_Browser::getDocumentsDirectory() . $values['theme_url'] ) AND ! $filename = Ayoola_Loader::checkFile( $this->getParameter( 'path' ) ) )
					{
						return false;
					}
					//	Deal with zip files first. Phar isn't handling ZIP compression well.
					//	http://php.net/manual/en/ziparchive.extractto.php
					$zip = new ZipArchive;
					$isZip = $zip->open( $filename );
			//		var_export( $isZip );
					if ( $isZip === TRUE ) 
					{
					//	$zip->extractTo( $tempDestination );
					//	$zip->close();
					//	echo 'ok';
					} 
					else   
					{
						//	Rip uploaded content
						$export = new Ayoola_Phar_Data( $filename );
 					}			
			
				//	We must have the template file in it. // or all zip files will be handled here.
				if( $isZip || ( ! isset( $export['template'] ) && ! isset( $export['template.html'] ) && ! isset( $export['index.html'] ) && ! isset( $export['home.html'] ) ) )
				{
					
					$tempDestination = ( @constant( 'PC_TEMP_DIR' ) ? : sys_get_temp_dir() ) . '/layout/';
					
					//	Clean up temp dir
					if( is_dir( $tempDestination ) )
					{
						Ayoola_Doc::deleteDirectoryPlusContent( $tempDestination );
					}
					else
					{
						Ayoola_Doc::createDirectory( $tempDestination );
					}
					//	Copy this file here for temp manipulation
					//	Deal with zip files first. Phar isn't handling ZIP compression well.
					//	http://php.net/manual/en/ziparchive.extractto.php
					if ( $isZip === TRUE ) 
					{
						$zip->extractTo( $tempDestination );
						$zip->close();
					//	echo 'ok';
					} 
					else 
					{
						$export->extractTo( $tempDestination, null, true );
					}			
				
					//	 Trying to see if its possible to upload random templates.
					//	Before now, users need to have all the template files in the root dir of the archive. 
					$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $tempDestination, RecursiveDirectoryIterator::SKIP_DOTS ), RecursiveIteratorIterator::SELF_FIRST );
					$baseDirs = array();
					foreach( $iterator as $path ) 
					{
						if($path->isDir()) 
						{
						//   print($path->__toString() . PHP_EOL);
						} 
						else 
						{
							$fullPathToFile =  str_replace( '\\', '/', $path->__toString() );
							$baseName = basename( $fullPathToFile );
							switch( $baseName ) 
							{
								//	Try to detect which folder has the index file
								case 'index.html':
								case 'home.html':
							//	case 'template':
									$baseDirs[strlen( $fullPathToFile )] = $fullPathToFile;
								break;
							} 
						}		
					}
				//	print( dirname( array_pop( $baseDirs['template'] ) ) );
			//		var_export( $baseDirs );  
					ksort( $baseDirs );
		//			var_export( $baseDirs );
					if( $baseDirs )
					{
						foreach( $baseDirs as $baseName => $baseNameDir )
						{
 							$source = dirname( $baseNameDir );
/*						//	$destination = dirname( $filename ) . '/test/' . basename( $source );
							$destination = dirname( $this->getMyFilename() );
							Ayoola_Doc::createDirectory( $destination );
							Ayoola_Doc::recursiveCopy( $source, $destination );
							
 */							  
							$export = new Ayoola_Phar_Data( $tempDestination . 'temp.tar' );
							$export->startBuffering();
							$export->buildFromDirectory( $source );
							$export->stopBuffering();
						//	$backup->compress( Ayoola_Phar::GZ ); 
						
							try
							{
								//	I don't know how this got in, so i got to remove it somehow
								$export->delete( 'template' );
							}
							catch( Exception $e )
							{
								null;
							}
					//		unset( $export['template'] );
							
							//	Do only the first one
							break;
						}
					}
					elseif( isset( $export['template'] ) )
					{
						//	We Welcome already prepared file
					}
					else
					{
						throw new Ayoola_Page_Layout_Exception( 'TEMPLATE FILE WAS NOT FOUND IN THE ARCHIVE' );
					}
				}

 				//	retain the same layoutname of imported theme to preserve data links like images
 				if( isset( $export['layout_information'] ) )
				{
					if( $previousData = unserialize( file_get_contents( $export['layout_information'] ) ) )
					{
						if( $previousData['layout_name'] )
						{
							$values['layout_name'] = $previousData['layout_name'];
						}
						
					}
				}
				if( empty( $values['layout_name'] ) &&  $values['layout_label'] )
				{
					$filter = new Ayoola_Filter_Name();
					$values['layout_name'] = strtolower( $filter->filter( 'pc_layout_' . $values['layout_label'] ) );
				} 
				if( $this->getDbTable()->selectOne( null, array( 'layout_name' => $values['layout_name'] ) ) )
				{
					$this->getForm()->setBadnews( 'Please enter a different name for this layout template (theme). There is a layout with the same name: ' . $values['layout_name'] );
					$this->setViewContent( $this->getForm()->view(), true );
					return false; 
				}

				if( ! $this->insertDb( $values ) )
				{ 
				//	var_export( $values );
					$this->setViewContent( '<p class="badnews boxednews">ERROR - COULD NOT INSERT TEMPLATE DATA INTO DATABASES.</p>.' ); 
					return false; 
				}
			//		var_export( $values );
			//	$export['template'] = preg_replace('#(href|src)="([^:"]*)(?:")#','$1="http://wintermute.com.au/$2"',$str);
				  
				//	Automate the creation of template files
				if( ! isset( $export['template'] ) )  
				{
					//	We now allow for other names
					//	Automagically convert all relative links to absolute links
					$path = isset( $export['template.html'] ) ? $export['template.html'] : ( isset( $export['index.html'] ) ? $export['index.html'] : ( isset( $export['home.html'] ) ? $export['home.html'] : null ) ); 
					
					$content = file_get_contents( $path );
			//		var_export( $path );
				//	$content = self::sanitizeTemplateFile( $content, $values );

					$export['template'] = $content;
				}
				else
				{
				//		var_export( $export );

				}
/* 				//	Update Screenshot
				$screenshot = Ayoola_Doc::getDocumentsDirectory() . $values['screenshot'];
				if( is_file( $screenshot ) )
				{
					$export['screenshot.jpg'] = file_get_contents( $screenshot );
				}
 */
				//	This normally would only work with an identifier data
				$this->setFilename( $values );
				
				$layoutDir = dirname( $this->getMyFilename() );
				$export->extractTo( $layoutDir, null, true );    

				//	look for screenshot
				$screenshot = $layoutDir . '/screenshot.jpg';
				if( ! file_exists( $screenshot )  )
				{
					$files = Ayoola_Doc::getFilesRecursive( $layoutDir );
					foreach( $files as $each )
					{
						$ext = strtolower( array_pop( explode( '.', $each ) ) );
						switch( $ext )
						{
							case 'jpg':
							case 'jpeg':
								copy( $each, $screenshot );
								break 2;
							break;
						}
					}
				}

				//	Use the traditional file saving mechanism so as to sanitize template file
				$values['plain_text'] = file_get_contents( $export['template'] );  
				$this->updateFile( $values );
				unset( $export );

				//	don't delete again
			//	unlink( $filename );
				
				$this->setViewContent( '<p class="goodnews">New theme saved successfully.</p>', true );
				$this->setViewContent( '<p class="">
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/layout/' . $values['layout_name'] . '/template" class="pc-btn pc-btn-small">Edit Theme</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Set as Default Theme</a>

				</p>' );
			//	$this->setViewContent( '<p class=""></p>' );
				
				
				//	Clean up temp dir
				Ayoola_Doc::deleteDirectoryPlusContent( $tempDestination );
				return true;  
			}
	//		$result = self::splitBase64Data( $values['screenshot'] );
	//		$filter = new Ayoola_Filter_Name();
	//		$filter->replace = '-';
	//		$customName = substr( trim( $filter->filter( $values['layout_name'] ) , '-' ), 0, 70 );
	
			// save screenshot
			if( $values['screenshot'] )
			{
				$filename = dirname( $this->getMyFilename() ) . DS . 'screenshot';   
				file_put_contents( $filename, $values['screenshot']);
			}
			
		//	var_export( $values );
		//	var_export( $values['screenshot'] );
			
			
		//	dirname( $this->getMyFilename() );			
			if( ! $this->insertDb( $values ) )  
			{ 
			//	$this->setViewContent( '<p class="badnews">Error: could not create layout template.</p>.' ); 
				return false;
			}
			//	This normally would only work with an identifier data
			$this->setFilename( $values );
			
			if( $this->updateFile( $values ) )
			{ 
				$this->setViewContent( '<p class="boxednews goodnews">New theme saved successfully.</p>', true );
				$this->setViewContent( '<p class="">
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Edit Codes Again</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/edit/layout/?url=/layout/' . $values['layout_name'] . '/template" class="pc-btn pc-btn-small">Launch Theme Editor</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Change Default Theme</a>
				
				</p>' );
			}
			
		//	$this->setViewContent( '<p class="goodnews">Layout created successfully.</p>' );
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getTraceAsString());
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
