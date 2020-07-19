<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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

			$this->createForm( 'Save', 'Add a new layout theme' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 

			
			//	Import mode
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

					if( $isZip === TRUE && $zip->numFiles ) 
					{

					} 
					else   
					{
						//	Rip uploaded content
						$export = new Ayoola_Phar_Data( $filename );
 					}			
			
				//	We must have the template file in it. // or all zip files will be handled here.
				if( $isZip || ( ! isset( $export['template'] ) && ! isset( $export['template.html'] ) && ! isset( $export['index.html'] ) && ! isset( $export['home.html'] ) ) )
				{
					
					$tempDestination = ( @constant( 'PC_TEMP_DIR' ) ? : CACHE_DIR ) . '/layout/';
					
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

					ksort( $baseDirs );

					if( $baseDirs )
					{
						foreach( $baseDirs as $baseName => $baseNameDir )
						{
 							$source = dirname( $baseNameDir );							  
							$export = new Ayoola_Phar_Data( $tempDestination . 'temp.tar' );
							$export->startBuffering();
							$export->buildFromDirectory( $source );
							$export->stopBuffering();

						
							try
							{
								//	I don't know how this got in, so i got to remove it somehow
								$export->delete( 'template' );
							}
							catch( Exception $e )
							{
								null;
							}							
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
					if( $previousData = json_decode( file_get_contents( $export['layout_information'] ), true ) OR $previousData = unserialize( file_get_contents( $export['layout_information'] ) ) )
					{
						if( $previousData['layout_name'] )
						{
							//	send all content because of text update
                            foreach( $previousData['dummy_replace'] as $key => $each )
                            {
                                $previousData['dummy_replace'][$key] = trim( $previousData['dummy_search'][$key], '{-}' );
                            }
							$values += $previousData;
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

					$this->setViewContent( self::__( '<p class="badnews boxednews">ERROR - COULD NOT INSERT TEMPLATE DATA INTO DATABASES.</p>.' ) ); 
					return false; 
				}

				  
				//	Automate the creation of template files
				if( ! isset( $export['template'] ) )  
				{
					//	We now allow for other names
					//	Automagically convert all relative links to absolute links
					$path = isset( $export['template.html'] ) ? $export['template.html'] : ( isset( $export['index.html'] ) ? $export['index.html'] : ( isset( $export['home.html'] ) ? $export['home.html'] : null ) ); 
					
					$content = file_get_contents( $path );

					$export['template'] = $content;
				}
				else
				{

				}
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
                                //  automatically use any available image in theme as screenshot
                                //  if none is available
								copy( $each, $screenshot );
								break 2;
							break;
						}
					}
				}

				//	Use the traditional file saving mechanism so as to sanitize template file
				$values['plain_text'] = $this->getPreviousContent( $values['layout_name'] ) ? : file_get_contents( $export['template'] );  

				$this->updateFile( $values );
				unset( $export );

				//	don't delete again

				
				$this->setViewContent(  '' . self::__( '<p class="goodnews">New theme saved successfully. <a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_MakeDefault/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Set as Default Theme</a></p>' ) . '', true  );
				
				//	Clean up temp dir
				Ayoola_Doc::deleteDirectoryPlusContent( $tempDestination );
				return true;  
			}
			else
			{
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

			}

	
			// save screenshot
			if( $values['screenshot'] )
			{
				$filename = dirname( $this->getMyFilename() ) . DS . 'screenshot';   
				Ayoola_File::putContents( $filename, $values['screenshot']);
			}

		//	if(  )	
			if( ! empty( $values['plain_text'] ) && ! $this->insertDb( $values ) )  
			{ 
				return false;
			}
			//	This normally would only work with an identifier data
			$this->setFilename( $values );
			
			if( $this->updateFile( $values ) )
			{ 
				$indexFile = dirname( $this->getMyFilename() ) . '/index.html';
				if( ! is_file( $indexFile ) && ! empty( $values['plain_text'] ) )
				{
					//	Auto generate index
					Ayoola_File::putContents( $indexFile, $values['plain_text'] );

				}
				$this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">New theme saved successfully.</p>' ) . '', true  );
				$this->setViewContent(  self::__( '<p class="">
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_Editor/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Edit Codes Again</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout?url=/layout/' . $values['layout_name'] . '/template" class="pc-btn pc-btn-small">Launch Theme Editor</a>
				<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/?layout_name=' . $values['layout_name'] . '" class="pc-btn pc-btn-small">Change Default Theme</a>
				
				</p>' ) );  
			}

		}
		catch( Exception $e )
		{ 

			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
