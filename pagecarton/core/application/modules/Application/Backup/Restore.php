<?php
/**
 * PageCarton Content Management System 
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Restore
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Restore.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Restore
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Restore extends Application_Backup_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = self::getIdentifierData() ){ return false; }
			$filter = new Ayoola_Filter_Time();
			$data['backup_creation_date'] = $filter->filter( $data['backup_creation_date'] );
			$this->createConfirmationForm( 'Restore', 'Restore application to the way it was ' . $data['backup_creation_date'] . ' using "' . $data['backup_name'] . '"' );
			$this->setViewContent( $this->getForm()->view(), true );		
			Application_Cache_Clear::viewInLine();
			if( $this->restore() )
			{ 
				$this->setViewContent( '<div class="goodnews">Back up restored successfully.</div>', true ); 
			}
		//	else
			//	do we have admin user
			//		if( $this->restore() ){ null; }
		}
		catch( Exception $e )
		{
		//	var_export( $e->getMessage() );
			$this->getForm()->setBadnews( 'Invalid Backup File' );
			$this->setViewContent( $this->getForm()->view(), true );		
			return false;
		}
    } 
		
    /**
     * Restore the backup
     * 
     */
	protected function restore()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }

		set_time_limit( 0 );
		ignore_user_abort( true ); 
		ini_set( "memory_limit","3000M" );	 

		$data = self::getIdentifierData();
		if( ! is_file( $data['backup_filename'] ) ){ throw new Application_Backup_Exception( 'File does not exist' ); } 
		$phar = 'Ayoola_Phar_Data';
		
		
/* 		$filter = new Ayoola_Filter_DomainName();
		$domain = $filter->filter( $domain );
		
		$filter = new Ayoola_Filter_Alnum();
		$filter->replace = DS;

 */		//	we cant use Ayoola_Application::getDomainName( array( 'no_cache' => true ) ) because it causes infinite loop
	//	$domain = $filter->filter( $domain );
		$tempDir = CACHE_DIR . DS . md5( Ayoola_Page::getDefaultDomain() ) . DS . __CLASS__ . DS . 'backups';
		
		//	USING DOMAIN NAME FIXES ERROR OF FILE PERMISSIONS
		$tempDirForPresentFile = CACHE_DIR . DS . md5( Ayoola_Page::getDefaultDomain() ) . DS . __CLASS__ . DS . 'present';
		Ayoola_Doc::createDirectory( $tempDir );
		Ayoola_Doc::createDirectory( $tempDirForPresentFile );
		
		//	copy the backup file to the temp dir so as to remain live through out the process
		$tempBackupFilename = $tempDirForPresentFile . DS . basename( $data['backup_filename'] );
		copy( $data['backup_filename'], $tempBackupFilename );
	//	var_export( $tempBackupFilename );
	//	var_export( filesize( $data['backup_filename'] ) );
	//	var_export( filesize( $tempBackupFilename ) );
	//	var_export( $dir );
	//	try
		{ 
			$backup = new $phar( $tempBackupFilename );
		}
	//	catch( Exception $e )
		{ 
/* 			#	https://stackoverflow.com/questions/38843938/phardata-extractto-method-failed-to-extract-tar-gz-on-linux-environment
			//	fix cases where error comes
		//	var_export( filesize( $tempBackupFilename ) );
			$p = new PharData( $tempBackupFilename, RecursiveDirectoryIterator::SKIP_DOTS );
		//	var_export( filesize( $tempBackupFilename ) );
			$p->convertToData(Phar::ZIP);

			$zip = new ZipArchive;
			$res = $zip->open($createdZipArchive);
			if ($res === TRUE) {
				$backup = $zip;
			//	$zip->extractTo($destinationPath);
			//	$zip->close();
			}
 */		}
		$dir = APPLICATION_DIR;
	//	var_export( $data );
		//	compatibility
		try
		{ 
			$backup['application']; 
		}
		catch( Exception $e )
		{ 
			//	The old backup style only copies from APPLICATION_PATH
			$dir = APPLICATION_PATH;
		}
/* 		switch( $data['backup_type'] )
		{
			case 'export':
				
			break;
			case '':
			case null:
			case false:
				//	Compatibility
				$dir = APPLICATION_PATH;
			break;
		}
 */		
	//	sys_get_temp_dir();
	
		//	save the previous backups to the temp dir
		$previousBackupFiles = Ayoola_Doc::getFilesRecursive( self::getBackupDirectory() );
		foreach( $previousBackupFiles as $file )
		{
			copy( $file, $tempDir . DS . basename( $file ) );
		}
		
		$dir = Ayoola_Application::getDomainSettings( $dir );
		$files = Ayoola_Doc::getFilesRecursive( $dir );
	//	var_export( $dir );
		foreach( $files as $key => $file )
		{
			$key = str_ireplace( $dir, '', $file );
			try{ $backup[$key]; }
			catch( Exception $e )
			{ 
				unlink( $file );
				@Ayoola_Doc::removeDirectory( dirname( $file ) );
			}
		}
		$backup->extractTo( $dir, null, true );
		
		//	Begin to add the backup done after the present backup
		$files = Ayoola_Doc::getFilesRecursive( $tempDir );
	//	var_export( $files );
		
		//	Destroy the previous table
		try
		{
			$this->getDbTable()->drop();
		}
		catch( Exception $e )
		{
			null;
		}
		
		foreach( $files as $file )
		{
			//	Attempt to 'Upload' the files
		//	$class = new Application_Backup_Upload( $file );
			try
			{ 
				
				$class = Application_Backup_Upload::viewInLine( array( 'local_file' => $file ) ); 
				
				//	delete file if it is not the file we are reading from
			//	$file === $tempBackupFilename ? : unlink( $file );
			//	$class->setParameter( backup_filename )
			//	var_export( $class->getParameter() );
			}
			catch( Exception $e ){ continue; }
		}
		try
		{ 
			Ayoola_Phar_Data::unlinkArchive( $tempBackupFilename );
		}
		catch( Exception $e )
		{ 
			null;
		}
		@unlink( $tempBackupFilename );
		
		Application_Cache_Clear::viewInLine();

		if( ! $response = Application_User_Abstract::getUsers( array( 'access_level' => 99 ) ) )  
		{
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Personalization' );
			exit();
		//	$this->setViewContent( '<div class="pc-notify-info">Back up restored successfully.</div>', true );
		}
		return true;
	//	$tempBackupFilename;
    } 
	// END OF CLASS
}
