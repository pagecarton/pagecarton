<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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

       //     $installer = Ayoola_Doc::getDocumentsDirectory() . '/ayoola/framework/installer.tar.gz';
         //   $zip->addFile( $installer, 'pc_installer.php.tar.gz');
			$file1 = Ayoola_Loader::getFullPath( 'documents/ayoola/framework/installer.tar.gz', array( 'prioritize_my_copy' => true ) );
      //      if( filectime( $file1 ) > time() + 86400 )
    //  var_export( $file1 );
 //     var_export( filemtime( $file1 ) );
         //       unlink( $file1 );
 //     var_export( filemtime( $file1 ) );
  //    var_export( filemtime( $file1 ) < ( time() + 0 ) );
            if( ! file_exists( $file1 ) || ! empty( $_REQUEST['pc_recreate_installer'] ) )   
//            if( ! file_exists( $file1 ) || filemtime( $file1 ) < ( time() + 86400 ) )   
            {
                $parameters = array( 'backup_type' => 'installer', 'no_init' => true );
                $class = new Application_Backup_Creator( $parameters );
                $class->fakeValues = $parameters;
                $class->init();
         //       self::v( $class->view() );  

            }
            if( ! empty( $_REQUEST['pc_recreate_installer'] ) )   
            {
                exit( 'pc_recreate_installer done!' );
            }
       //         exit();

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
            $zip->close();   
     //       var_export( $file1 );
    //        exit();


            //  Output demo content to screen
            header('Content-Type: application/zip');
            header('Content-Length: ' . filesize($file));
            header('Content-Disposition: attachment; filename="pagecarton-' . PageCarton::VERSION . '.zip"');
            readfile($file);
            unlink($file); 

            exit();
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
