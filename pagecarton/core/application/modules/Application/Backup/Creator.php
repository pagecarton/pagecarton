<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Backup_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';   


/**
 * @category   PageCarton
 * @package    Application_Backup_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Creator extends Application_Backup_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{


			$this->createForm( 'Continue', 'Create a Backup' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

			set_time_limit( 0 );
			ignore_user_abort( true ); 
			
			switch( $values['backup_type'] )
			{
				case 'installer':
					
					//	copy the file to my document directory so it can be downloadable.
					$coreDocumentsDir = APPLICATION_PATH . DS . DOCUMENTS_DIR;
					$documentsDir = Ayoola_Doc::getDocumentsDirectory();
					$installerFilenameGz = $documentsDir . self::getInstallerLink();
					$simpleFilename = Ayoola_Application::$installer;
					$urlPrefix = Ayoola_Application::getUrlPrefix();

					//	This one needs to always be in the core
					$installerFilenamePhp = $coreDocumentsDir . DS . $simpleFilename;



					Ayoola_Doc::createDirectory( dirname( $installerFilenameGz ) );
					Ayoola_Doc::createDirectory( dirname( $installerFilenamePhp ) );
					@unlink( $installerFilenameGz );
					
					//	dont remove this again so it can be useful for upgrade.
				
					$year = date( 'Y' );
					$date = date( "m:d:Y g:ia" );
					$userInfo = Ayoola_Application::getUserInfo();
					$domain = Ayoola_Page::getDefaultDomain();
					$filename = self::getInstallerLink(); 
					$version = PageCarton::VERSION; 
                    //	each
                                //	Build installer into the zipped document so that it always have the fresh content
                    //			Ayoola_File::putContents( $installerFilenamePhp, $installerText );
                    //			dont do this here again since we switching to upgrades.pagecarton.org
					
					if( ! $info  = $this->createFile() ){ return false; }
					rename( $info['backup_filename'], $installerFilenameGz );
										
					$this->setViewContent(  '' . self::__( '<p class="goodnews">Archive for installation has been created successfully. It is now accessible publicly for download at <a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Backup_GetInstallation/">' . 'http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() .  '/widgets/Application_Backup_GetInstallation/</a></p>' ) . '', true  );
					$this->setViewContent( self::__( '<p class="">The script to install the archive on a new server has been auto-generated and could be found on this link <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/' . $simpleFilename . '?r=' . time() . '">' . 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $simpleFilename . '</a></p>' ) );
				break;
				case 'export':
					if( ! $values  = $this->createFile() ){ return false; }
					$values['export_information']['export_expiry'] = $values['export_expiry'];
					$values['export_information']['time'] = time();
					if( ! $data = $this->insertDb( $values ) ){ return false; }
					$this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">Backup created successfully.</p>' ) . '', true  );
					$this->setViewContent( self::__( '<p class="">Export URL is "http://' . DOMAIN . '' . Ayoola_Application::getUrlPrefix() .'/tools/classplayer/get/object_name/Application_Backup_Export/?backup_id=' . $data['backup_id'] . '"</p>' ) );
				break;
				default:
					if( ! $values  = $this->createFile() ){ return false; }
					if( ! $this->insertDb( $values ) ){ return false; }
					$this->setViewContent(  '' . self::__( '<p class="boxednews goodnews">Backup created successfully.</p>' ) . '', true  );  
				break;
            }
            ignore_user_abort( false ); 
           
		}
		catch( Exception $e )  
		{
			$this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view() );
			return false; 
		}
    } 
	
    /**
     * Create the backup file
     * 
     * @return void
     */
	protected function createFile()
    {
		if( ! $values = $this->getForm()->getValues() ){ return null; }
		
		$values['filename'] = $values['backup_filename'] = self::getFilename( $values['backup_name'] );
		$values['backup_creation_date'] = time();
		$values['backup_name'] = $values['backup_name'] ? : date( 'r' );
		@unlink( $values['backup_filename'] );
		$values['backup_filename'] .= '.gz';
		@unlink( $values['backup_filename'] );
		
		//	use basename. That's what we want to use to locate files henceforth
		//	The internal filename may change e.g via export
		$values['basename'] = basename( $values['backup_filename'] );
		
		//	remove duplicate names
		$phar = 'Ayoola_Phar_Data';
		$backup = new $phar( $values['filename'] );
		$backup->startBuffering();
		
		//	remove "cache dir" this is causing issues.
		//	Clear cache
		$stupidCache = dirname( $_SERVER['SCRIPT_FILENAME'] ) . '/cache';
		if( is_dir( $stupidCache ) )
		{
			Ayoola_Doc::deleteDirectoryPlusContent( $stupidCache );
		}
		
		//	Cwd is also required
		$requiredList = array( '/' . basename( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . '/', '/local_html/', '/license.txt', '/changelog.txt', '/readme.txt', '/application/configs/', '/application/functions/', '/pagecarton/local_html/', '/pagecarton/license.txt', '/pagecarton/changelog.txt', '/pagecarton/copyright.txt', '/pagecarton/readme.txt', '/pagecarton/application/configs/', '/pagecarton/application/functions/' );
		if( empty( $values['backup_export_list'] ) )
		{
			$values['backup_export_list'] = array_keys( self::$_exportList );
		}

		$userList = array_intersect( array_keys( self::$_exportList ), $values['backup_export_list'] );

		//	multisites
		$totalList = array_merge( $requiredList, $userList, $values['backup_export_multisites'] ? : array() );
		$dir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
		$f = $dir . '/public_html/ayoola_cmf_controller.php';
	
		//	Fixes a bug where a symlink breaks the backup process.
		if( is_link( $f ) )
		{
			$c = file_get_contents( $f );
			unlink( $f );
			$f = Ayoola_File::putContents( $f, $c );
		}		
 		switch( $values['backup_type'] )
		{
			case 'export':

            break;
			case 'installer':
				$dir = APPLICATION_DIR;
			break;
			case 'simple':
			default:

            break;
		}

		//	Build a regex to remove the excluded from the export
		$regex = null;
		foreach( $totalList as $each )
		{
			$each = basename( $dir ) . $each;
			$regex .= "({$each})|";			
		}
		$regex = trim( $regex, '|' ); 
		$regex = "#{$regex}#";
		try
		{

		}
		catch( Exception $e )
		{

		}

        // briefly turn off plugins
        //  plugins leave behind orphan symlinks
        $installedPlugins = Ayoola_Extension_Import_Table::getInstance()->select( null, array( 'status' => 'Enabled' ) );
        foreach( $installedPlugins as $each )
        {
            $result = Ayoola_Extension_Import_Status::viewInLine( array(
                'fake_values' => array( 'true' => 1 ),
                'extension_name' => $each['extension_name']
            ) );
        }

		$backup->buildFromDirectory( $dir, $regex );  
		$backup['backup_information'] = serialize( $values );
 		switch( $values['backup_type'] )
		{
			case 'export':

			break;
			case 'installer':

			    //	Remove this sensitive files for install type
				$files = array(
					'application/databases/Application/domain.xml',
					'application/databases/Application/settings.xml',
					'application/databases/Ayoola/Api/api.xml',
					'application/databases/Ayoola/Access/localuser.xml',
				);
				foreach( $files as $each )
				{
					try
					{
						$backup->delete( $each );
					}
					catch( Exception $e ){ null; }
					try
					{
						$backup->delete( 'pagecarton/' . $each );  
					}
					catch( Exception $e ){ null; }
				}
			break;
			case 'simple':
			default:

			break;
		}
		  
		$backup->stopBuffering();

        // turn plugins back on
        foreach( $installedPlugins as $each )
        {
            $result = Ayoola_Extension_Import_Status::viewInLine( array(
                'fake_values' => array( 'true' => 1 ),
                'extension_name' => $each['extension_name']
            ) );
        }

		$backup->compress( Ayoola_Phar::GZ ); 
		unset( $backup );
		$phar::unlinkArchive( $values['filename'] );
		return $values;
    } 
	// END OF CLASS
}
