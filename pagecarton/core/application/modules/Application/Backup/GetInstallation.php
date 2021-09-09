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

            $version = explode( '.', PageCarton::VERSION );
            $minor = array_pop( $version );
            $version = implode( '.', $version ) . '.x';


            $config = PageCarton::getDomainSettings( 'site_configuraton' );
            if( empty( $config['repository'] ) )
            {
                $config['repository'] = 'https://github.com/pagecarton/pagecarton/archive/' . $version . '.zip'; 
            }

            // create installer
            $createInstaller = function() use ( $config )
            {

                set_time_limit( 0 );

                //  download main core

                if( ! $content = self::fetchLink( $config['repository'], array( 'rand' => time(), 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) ) )
                {
                    die( 'NOT ABLE TO CONNECT TO REPOSITORY - ' . $config['repository'] . ' ' );
                }
                $filename = tempnam( CACHE_DIR, __CLASS__ ) . '';  
    
                $filename .= '.zip';
                Ayoola_File::putContents( $filename, $content['response'] );  

                //  extract to temp
                $tempDir = CACHE_DIR . DS . __CLASS__ . PageCarton::VERSION;
                Ayoola_Doc::createDirectory( $tempDir );

                $zip2 = new ZipArchive;
                if ($zip2->open( $filename ) === TRUE) 
                {
                    $zip2->extractTo( $tempDir );
                    $zip2->close();
                } 

                
                $dirPcBase = Ayoola_Doc::getDirectories( $tempDir ); 
                $dirPcBase = array_pop( $dirPcBase );

                $from = $dirPcBase . '/pagecarton/core';
                $to = APPLICATION_DIR . '';

                $toto = $to . '-' . PageCarton::VERSION . DS . time();
                Ayoola_Doc::createDirectory( $toto );
                rename( $to, $toto );    

                Ayoola_Doc::createDirectory( $to );
                rename( $from, $to );
                var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) );
                var_export( APPLICATION_PATH );

                if( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) === APPLICATION_PATH )
                {
                    //  save tar
                    $parameters = array( 'backup_type' => 'installer', 'no_init' => true );
                    require_once 'Application/Backup/Creator.php';
                    $class = new Application_Backup_Creator( $parameters );
                    $class->fakeValues = $parameters;
                    $class->init();
                    var_export( $class->view() . "<br>" );

                }

            };

            if( ! file_exists( $file1 ) || ! empty( $_REQUEST['pc_recreate_installer'] ) )   
            {
                @unlink( $coreZip ); 
                var_export( __LINE__ . "<br>" );
                $createInstaller();

                if( ! empty( $_REQUEST['pc_recreate_installer'] ) )   
                {
                    exit( $config['repository'] . ' pc_recreate_installer done!' );  
                }
            }

            if( @$_GET['pc_core_only'] )
            {
                if( @$_GET['archive_type'] === 'zip'  )
                {
                    if( ! file_exists( $coreZip )  )
                    {
                        //  download main core
                        $coreLink = 'https://github.com/pagecarton/pagecarton/archive/' . $version . '/pagecarton/core.zip';
                        $content = self::fetchLink( $coreLink, array( 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) );
            
                        Ayoola_Doc::createDirectory( dirname( $coreZip ) );  
                        Ayoola_File::putContents( $coreZip, $content['response'] );
                    }
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
            readfile($file);    
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
