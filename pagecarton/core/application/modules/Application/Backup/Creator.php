<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
	//	var_export( getcwd() );
	//	var_export( basename( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
	//	var_export( sys_get_temp_dir() );
	//	var_export( $_SERVER['SCRIPT_FILENAME'] );
		//	$dir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
	//	var_export( APPLICATION_DIR );

		$this->createForm( 'Create', 'Create a Backup' );
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

		//		var_export( $installerFilenamePhp );


				Ayoola_Doc::createDirectory( dirname( $installerFilenameGz ) );
				Ayoola_Doc::createDirectory( dirname( $installerFilenamePhp ) );
				@unlink( $installerFilenameGz );
				
				//	dont remove this again so it can be useful for upgrade.
			//	@unlink( $installerFilenamePhp );
			
				$year = date( 'Y' );
				$date = date( "m:d:Y g:ia" );
				$userInfo = Ayoola_Application::getUserInfo();
				$domain = Ayoola_Page::getDefaultDomain();
				$filename = self::getInstallerLink(); 
				$version = PageCarton::VERSION; 
	//	each
				//	Build installer into the zipped document so that it always have the fresh content
	//			file_put_contents( $installerFilenamePhp, $installerText );
	//			dont do this here again since we switching to upgrades.pagecarton.org
				
				if( ! $info  = $this->createFile() ){ return false; }
				rename( $info['backup_filename'], $installerFilenameGz );
				
				//	Save a draft in local_html to use for upgrade
		//		copy( $installerFilenamePhp, APPLICATION_DIR . DS . 'local_html' );
				
				$this->setViewContent( '<p class="goodnews">Archive for installation has been created successfully. It is now accessible publicly for download at <a href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Backup_GetInstallation/">' . 'http://' . Ayoola_Page::getDefaultDomain() . Ayoola_Application::getUrlPrefix() . '/object/name/Application_Backup_GetInstallation/</a></p>', true );
//				$this->setViewContent( '<p class="goodnews">Archive for installation has been created successfully. It is now accessible publicly for download at <a href="' . self::getInstallerLink() . '?r=' . time() . '">' . 'http://' . Ayoola_Page::getDefaultDomain() .  self::getInstallerLink() . '</a></p>', true );
				$this->setViewContent( '<p class="">The script to install the archive on a new server has been auto-generated and could be found on this link <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/' . $simpleFilename . '?r=' . time() . '">' . 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $simpleFilename . '</a></p>' );
			break;
			case 'export':
				if( ! $values  = $this->createFile() ){ return false; }
				$values['export_information']['export_expiry'] = $values['export_expiry'];
				$values['export_information']['time'] = time();
				if( ! $data = $this->insertDb( $values ) ){ return false; }
				$this->setViewContent( '<p class="boxednews goodnews">Backup created successfully.</p>', true );
				$this->setViewContent( '<p class="">Export URL is "http://' . DOMAIN . '' . Ayoola_Application::getUrlPrefix() .'/tools/classplayer/get/object_name/Application_Backup_Export/?backup_id=' . $data['backup_id'] . '"</p>' );
			break;
			default:
				if( ! $values  = $this->createFile() ){ return false; }
		//		var_export( $values );
				if( ! $this->insertDb( $values ) ){ return false; }
				$this->setViewContent( '<p class="boxednews goodnews">Backup created successfully.</p>', true );  
			break;
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
	//	if( ! $this->insertDb( $values ) ){ return null; }
		
		//	remove duplicate names
		$phar = 'Ayoola_Phar_Data';
		$backup = new $phar( $values['filename'] );
		$backup->startBuffering();
		
		//	remove "cache dir" this is causing issues.
		//	Clear cache
		$stupidCache = dirname( $_SERVER['SCRIPT_FILENAME'] ) . '/cache';
	//	var_export( $stupidCache );
		if( is_dir( $stupidCache ) )
		{
	//	var_export( $stupidCache );
			Ayoola_Doc::deleteDirectoryPlusContent( $stupidCache );
		}
		
		
		
		
		//	Cwd is also required
		$requiredList = array( '/' . basename( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) . '/', '/local_html/', '/license.txt', '/changelog.txt', '/readme.txt', '/application/configs/', '/application/functions/', '/pagecarton/local_html/', '/pagecarton/license.txt', '/pagecarton/changelog.txt', '/pagecarton/copyright.txt', '/pagecarton/readme.txt', '/pagecarton/application/configs/', '/pagecarton/application/functions/' );  
		$userList = array_intersect( array_keys( self::$_exportList ), $values['backup_export_list'] );
		$totalList = array_merge( $requiredList, $userList );
	//	var_export( self::$_exportList );
	//	var_export( $values['backup_export_list'] );
	//	var_export( $totalList );
		$dir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
//		var_export( $dir );
//		$f = '/home/server4101/www/avalanchenigeria.com/public_html/ayoola_cmf_controller.php';
//		$f = '/home/server4101/www/avalanchenigeria.com/public_html/.htaccess';
		$f = $dir . '/public_html/ayoola_cmf_controller.php';
	//	var_export( $dir );
	//	var_export( $f );
	
		//	Fixes a bug where a symlink breaks the backup process.
		if( is_link( $f ) )
		{
		//	var_export( file_get_contents( $f ) );
			$c = file_get_contents( $f );
			unlink( $f );
			$f = file_put_contents( $f, $c );
		//	var_export( $f );
		}
		
		//	for heavy files
		set_time_limit( 0 );
		ignore_user_abort( true );
//		ini_set( 'memory_limit', "256M" );
 		switch( $values['backup_type'] )
		{
			case 'export':
		//		$dir = APPLICATION_DIR;  
			break;
			case 'installer':
				$dir = APPLICATION_DIR;
			break;
			case 'simple':
			default:
		//		$dir = APPLICATION_PATH;
			break;
		}

		//	Build a regex to remove the excluded from the export
		$regex = null;
		foreach( $totalList as $each )
		{
		//	$each = str_replace( DS, '/', $dir . $each );
			$each = basename( $dir ) . $each;
			$regex .= "({$each})|";			
		}
		$regex = trim( $regex, '|' );
		$regex = "#{$regex}#";
		$backup->buildFromDirectory( $dir, $regex );
	//	foreach( $excluded as $key => $each )
		{
		//	unset( $backup[ltrim( $key, '/' )] );

		//	$backup->delete( ltrim( $key, '/' ) );			
		}
		$backup['backup_information'] = serialize( $values );
	//	var_export( $regex );
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
/* 				try
				{
					$backup->delete( 'application/databases/Application/domain.xml' );
					$backup->delete( 'application/databases/Application/settings.xml' );
					$backup->delete( 'application/databases/Ayoola/Api/api.xml' );
					$backup->delete( 'pagecarton/application/databases/Application/domain.xml' );
					$backup->delete( 'pagecarton/application/databases/Application/settings.xml' );
					$backup->delete( 'pagecarton/application/databases/Ayoola/Api/api.xml' );
				}
				catch( Exception $e ){ null; }
 */			break;
			case 'simple':
			default:

			break;
		}
		  
		$backup->stopBuffering();
		
		$backup->compress( Ayoola_Phar::GZ ); 
		unset( $backup );
		$phar::unlinkArchive( $values['filename'] );
//		set_time_limit( 30 );
		return $values;
    } 
	// END OF CLASS
}
