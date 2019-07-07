<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Backup_GetInstallation
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: GetInstallation.php Saturday 16th of September 2017 11:18PM  $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Backup_GetInstallation extends Application_Backup_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Get Latest Installation'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            $file = tempnam( CACHE_DIR, __CLASS__ );
            $zip = new ZipArchive();

            //  Code that runs the widget goes here...
            $zip->open($file, ZipArchive::OVERWRITE);

            $loc = 'documents/ayoola/framework/installer.tar.gz';
            if( ! $file1 = Ayoola_Loader::getFullPath( $loc, array( 'prioritize_my_copy' => true ) ) )
            {
                $file1 = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $loc;
            }
            $coreZip = dirname( $file1 ) . DS . 'pagecarton.zip';
            if( ! file_exists( $coreZip ) || ! file_exists( $file1 ) || ! empty( $_REQUEST['pc_recreate_installer'] ) )   
            {
                set_time_limit( 0 );
                $version = explode( '.', PageCarton::VERSION );
                $minor = array_pop( $version );
                $version = implode( '.', $version ) . '.x';

                //  download main core

                $config = PageCarton::getDomainSettings( 'site_configuraton' );
            //    var_export( $config );
            //    exit();

                if( empty( $config['repository'] ) )
                {
                    $config['repository'] = 'https://github.com/pagecarton/pagecarton/archive/' . $version . '.zip'; 
                }

                if( ! $content = self::fetchLink( $config['repository'], array( 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) ) )
                {
                    die( 'NOT ABLE TO CONNECT TO REPOSITORY - ' . $config['repository'] . ' ' );
                }
                $filename = tempnam( CACHE_DIR, __CLASS__ ) . '';  
    
                $filename .= '.zip';
                file_put_contents( $filename, $content['response'] );  

                //  extract to temp
                $tempDir = CACHE_DIR . DS . __CLASS__ . PageCarton::VERSION;
                Ayoola_Doc::createDirectory( $tempDir );

                $zip2 = new ZipArchive;
                if ($zip2->open( $filename ) === TRUE) 
                {
                    $zip2->extractTo( $tempDir );
                    $zip2->close();
                } 

                //  save tar
                $phar = 'Ayoola_Phar_Data';
                $tFile = str_ireplace( '.tar.gz', '.tar', $file1 );
                Ayoola_Doc::createDirectory( dirname( $file1 ) );

                $backup = new $phar( $tFile );
                $backup->startBuffering(); 

           //     var_export( APPLICATION_DIR );

                $from = $tempDir . DS . 'pagecarton-' . $version . '/pagecarton/core';
                $to = APPLICATION_DIR . '';
           //     var_export( $from );
           //     var_export( $to );
                Ayoola_Doc::createDirectory( $to );
                Ayoola_Doc::recursiveCopy( $to, $to . '-' . PageCarton::VERSION );    
           //     $to = rename( $to, $to . '-' . PageCarton::VERSION );
                Ayoola_Doc::deleteDirectoryPlusContent( $to );
            //    $to = $to . 'x';
                Ayoola_Doc::createDirectory( $to );
                Ayoola_Doc::recursiveCopy( $from, $to );    
      
                $parameters = array( 'backup_type' => 'installer', 'no_init' => true );
                $class = new Application_Backup_Creator( $parameters );
                $class->fakeValues = $parameters;
                $class->init();

                //  save zip

                //  download main core
                $coreLink = 'https://github.com/pagecarton/pagecarton/archive/' . $version . '/pagecarton/core.zip';
                $content = self::fetchLink( $coreLink, array( 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) );
    
                @unlink( $coreZip );
            //    rmdir();
                Ayoola_Doc::createDirectory( dirname( $coreZip ) );  
                file_put_contents( $coreZip, $content['response'] );
                if( ! empty( $_REQUEST['pc_recreate_installer'] ) )   
                {
                    exit( 'pc_recreate_installer done!' );  
                }
    

            }
       //         exit();

            if( @$_GET['pc_core_only'] )
            {
                if( @$_GET['archive_type'] === 'zip' )
                {
                    $file = $coreZip;
                    header( 'Content-Disposition: attachment; filename="pagecarton-' . PageCarton::VERSION . '.zip"' );
                    header('Content-Type: application/zip');
                }
                else
                {
                    $file = $file1;
                    header( 'Content-Disposition: attachment; filename="pagecarton-' . PageCarton::VERSION . '.tar.gz"' );
                    header('Content-Type: application/x-gzip');
                }
            }
            else
            {
                $file2 = Ayoola_Loader::getFullPath( 'documents/pc_installer.php', array( 'prioritize_my_copy' => true ) );
                $file3 = Ayoola_Loader::getFullPath( 'documents/pc_redirect.php', array( 'prioritize_my_copy' => true ) );

                $zip->addFile( $file1, 'pc_installer.php.tar.gz');
                $zip->addFile( $file2, 'pc_installer.php');
                $zip->addFile( $file3, 'index.php');
                $zip->addFile( APPLICATION_DIR . DS . 'changelog.txt', 'changelog.txt');
                $zip->addFile( APPLICATION_DIR . DS . 'copyright.txt', 'copyright.txt');
                $zip->addFile( APPLICATION_DIR . DS . 'install.txt', 'install.txt');
                $zip->addFile( APPLICATION_DIR . DS . 'license.txt', 'license.txt');
                $zip->addFile( APPLICATION_DIR . DS . 'readme.txt', 'readme.txt');
                header('Content-Type: application/zip');
                header( 'Content-Disposition: attachment; filename="pagecarton-' . PageCarton::VERSION . '.zip"' );
            }
            $zip->close();   
     //       var_export( $file1 );
    //        exit();


            //  Output demo content to screen
        //    header('Content-Length: ' . filesize($file));

        //    header( 'Content-Length: ' . filesize( $file ) );
            readfile($file);    
        //    @unlink($file);     
            exit();
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( $e->getMessage() ); 
            $this->setViewContent( self::__( 'Theres an error in the code' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
