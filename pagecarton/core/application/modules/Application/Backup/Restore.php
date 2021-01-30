<?php
/**
 * PageCarton 
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
			//  Application_Cache_Clear::viewInLine();
			if( $this->restore() )
			{ 
				$this->setViewContent(  '' . self::__( '<div class="goodnews">Back up restored successfully.</div>' ) . '', true  ); 
			}
			Application_Cache_Clear::viewInLine();  
		//	else
			//	do we have admin user
			//		if( $this->restore() ){ null; }
		}
		catch( Exception $e )
		{
		//	var_export( $e->getMessage() );
			$this->getForm()->setBadnews( 'Invalid Backup File' );
			$this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  );		
			$this->setViewContent( $this->getForm()->view() );		
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

        //	we cant use Ayoola_Application::getDomainName( array( 'no_cache' => true ) ) because it causes infinite loop
		$tempDir = CACHE_DIR . DS . md5( Ayoola_Page::getDefaultDomain() ) . DS . __CLASS__ . DS . 'backups';
		
		//	USING DOMAIN NAME FIXES ERROR OF FILE PERMISSIONS
		$tempDirForPresentFile = CACHE_DIR . DS . md5( Ayoola_Page::getDefaultDomain() ) . DS . __CLASS__ . DS . 'present';
		Ayoola_Doc::createDirectory( $tempDir );
		Ayoola_Doc::createDirectory( $tempDirForPresentFile );
		
		//	copy the backup file to the temp dir so as to remain live through out the process
		$tempBackupFilename = $tempDirForPresentFile . DS . basename( $data['backup_filename'] );
		copy( $data['backup_filename'], $tempBackupFilename );
	//	try
		{ 
			$backup = new $phar( $tempBackupFilename );
		}
		$dir = APPLICATION_DIR;
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
			try
			{ 
				
				$class = Application_Backup_Upload::viewInLine( array( 'local_file' => $file ) ); 
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
        ignore_user_abort( false ); 

		
		//  Application_Cache_Clear::viewInLine();

		if( ! $response = Application_User_Abstract::getUsers( array( 'access_level' => 99 ) ) )  
		{
            //  refresh index to allow admin creator to work
            Ayoola_File::putContents( 'index.php', file_get_contents( 'index.php' ) );
            
			header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Personalization' );
			exit();
		//	$this->setViewContent(  '' . self::__( '<div class="pc-notify-info">Back up restored successfully.</div>' ) . '', true  );
		}
		return true;
	//	$tempBackupFilename;
    } 
	// END OF CLASS
}
