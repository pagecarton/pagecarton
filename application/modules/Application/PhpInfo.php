<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_PhpInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhpInfo.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_PhpInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_PhpInfo extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
			  
/* 				//	Rip uploaded content
				$phar = 'Ayoola_Phar_Data';
				$filename = 'A:\Downloads\Web Templates\New Templates\brushed.zip';
				$filename = 'A:\Downloads\Web Templates\New Templates\1139studiofrancesca.zip';
	//			$filename = 'A:\Downloads\PageCarton\Templates\BisLite.tar.gz';
				$filename =  str_replace( '\\', '/', $filename );
				$export = new $phar( $filename  );
			//	var_export( $export['layout_information'] );
			
				$tempDestination = ( @constant( 'PC_TEMP_DIR' ) ? : sys_get_temp_dir() ) . '/layout/';
				
				//	Copy this file here for temp manipulation
				$export->extractTo( $tempDestination, null, true );
			
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
						//		print( $fullPathToFile . '<br>' );
						//		print( $fullPathToFile . '<br>' );
								$baseDirs[] = $fullPathToFile;
							//	var_Export( explode( $filename, $fullPathToFile ) );
								$dirDestination = dirname( $filename ) . '/test';
							break;
						} 
					}		
				}
			//	print( dirname( array_pop( $baseDirs['template'] ) ) );
			//	var_export( $baseDirs );
				foreach( $baseDirs as $baseName => $baseNameDir )
				{
					$source = dirname( $baseNameDir );
					$destination = dirname( $filename ) . '/test/' . basename( $source );
					Ayoola_Doc::createDirectory( $destination );
					Ayoola_Doc::recursiveCopy( $source, $destination );
					
					//	Do only the first one
					break;
				}
 */				
/* 				if( ! empty( $baseDirs['template'] ) )
				{
				}
 */			//	elseif
			//	var_export( $baseDirs );
		//		var_export( $export->getChildren() );
		//		var_export( $export->hasChildren() );
				//	We must have the template file in it.
			phpinfo();
		//	copy( $installerFilenamePhp, Ayoola_Application::$upgrader );
			
		//	header( 'Location: /' . Ayoola_Application::$upgrader );
		//	exit();
			
	//		file_get_contents( Ayoola_Application::$upgrader );
		}  
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
