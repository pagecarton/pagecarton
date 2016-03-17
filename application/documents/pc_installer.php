<?php 
/**
 * PageCarton Developer Tool
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton Installer
 * @copyright  Copyright (c) 2011-2016 PageCarton.com (http://www.PageCarton.com/)
 * @license    http://production.cmf.ayoo.la/license.txt
 * @version    $Id: pc_installer.php 01:26:2016 2:35pm  $joywealth
 */


	//	Always show error
	ini_set( 'display_errors', "1" );	
	
	//	We need memory for extraction
	ini_set( "memory_limit","512M" );

	//	Download and extraction can take a while
	set_time_limit( 0 );
	defined('DS') || define('DS', DIRECTORY_SEPARATOR);
	defined('PS') || define('PS', PATH_SEPARATOR);
	
	
	isset( $_SESSION ) ? null : session_start();
	$_SESSION['installer'] = sha1( "11" );
	
	//  Detect path to application
/* 	$home = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) );
	$dir = realpath( dirname( $home ) );
 */	
	//	Using document root now
	$home = realpath( $_SERVER['DOCUMENT_ROOT'] );
	$dir = $oldDir = $baseAppPath = realpath( dirname( $home ) );
	
	//	Shrink everything into a single dir
	$temDir = $dir . '/temp/install/';
	$dir = $newDir = $dir . '/pagecarton';
	
	$oldAppPath = null;
	
	//	Create dir
	if( ! is_dir( $dir ) )
	{
		mkdir( $dir ); 
		
		//	this is an upgrade from before we had the new dir structure of /pagecarton
		$oldAppPath = $oldDir . DS . 'application';
		$newAppPath = $newDir . DS . 'application';
	}
	$_SESSION['installer'] = $dir;

	//	Retrieve the file
	$filename = 'pc_installer.php.tar.gz';
	defined( 'APPLICATION_DIR' ) || define( 'APPLICATION_DIR', $dir );
	
//	use the next sequence to provide installation procedure.
	$content = null;
	$badnews = null;
	$remoteSite = 'http://production.cmf.ayoo.la';
	switch( @$_GET['stage'] )
	{
        case 'start':
            $content .= '<h1>Installing PageCarton</h1>';
            $content .= '<p>PageCarton is an amazing tool for creating powerful and robust websites. It is a free Content Management System which is based on <a href="http://pagecarton.com/">Ayoola Framework</a>. When installed on your server, PageCarton will help you publish contents to the internet.</p>';
            $content .= '<p>Follow these simple steps to install PageCarton on your server. To find out more about PageCarton, visit <a href="http://www.PageCarton.com/">the application homepage</a>. To continue installation, click the button below.</p>';
            $content .= '<input value="Continue..." type="button" onClick="location.href=\'?stage=licence\'" />';
        break;
		case 'licence':
			$content .= '<h1>Continue installation, only if you agree to be bound by the following license terms.</h1>';
			$content .= '<p>Please note that the license terms may change from time to time. Changes will always be on ' . $remoteSite . '/license.txt' . '.</p>';
			$content .= '<textarea rows="10" style="min-width:90%;display:block;">' . file_get_contents( $remoteSite . '/license.txt' ) . '</textarea>';
			$content .= '<p>Continuing with the installation will require an active internet connection. Internet is required to ensure that you install the latest version of PageCarton.</p>';
			$content .= '<input value="I agree" type="button" onClick = "location.href=\'?stage=download\'" />';
		break;
		case 'download':
			//	Check if we can write in the application path
			if( ! is_writable( APPLICATION_DIR ) )
			{ 
				$badnews .= '<p>Application directory is not writable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton will be installed.</p>';
				break;
			}
			//	Retrieve the file
			if( ! is_file( $filename ) )
			{ 
				if( ! $f = @fopen( $remoteSite . '/ayoola/framework/installer.tar.gz', 'r' ) )
				{
					$badnews .= '<p>Application cannot connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
					break;
				}
				file_put_contents( $filename, $f );
			}
			if( ! is_readable( $filename ) )
			{ 
				$badnews .= '<p>Downloaded application is not readable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton is being installed.</p>';
				break;
			}
            $content .= '<h1>PageCarton Downloaded</h1>';
            $content .= '<p>We will now begin installation of PageCarton on the server. Just click on the button below to begin installation.</p>';
            $content .= '<input value="Begin Installation" type="button" onClick="location.href=\'?stage=install\'" />';
		break;
		case 'install':
			if( ! is_file( $filename ) )
			{ 
				header( 'Location: ?stage=download' );
				exit();
			}
			
			//	Preserve some of this data before deleting some files
			$dbDir = '/application/databases/';
			$preserveList = array
			(
				'Application/domain.xml',
				'Application/backup.xml',
				'Ayoola/Access/localuser.xml', 
				'Application/settings.xml',
				'Ayoola/Api/api.xml',
			);
			foreach( $preserveList as $eachDir )
			{
				$oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
				$newEachDir = $temDir . $eachDir;
				@mkdir( dirname( $newEachDir ), 0777, true );
				@copy( $oldEachDir, $newEachDir );       
			}
			
			//	clean all dirs first
		//	$dirsToDelete = array( '/library/', '/temp/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
		
			//	dont clear temp because theres where our preservations are.
			$dirsToDelete = array( '/library/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
			
			/**
			 * Attempts to remove dirs recursively in case
			 *
			 * @param string Path to Directory to be deleted
			 * @return void
			 */
			function deleteDirectoryPlusContent( $eachDir )
			{
				if (!is_dir($eachDir)) {
				//	throw new Ayoola_Doc_Exception("$eachDir is not a directory");
				//	echo $eachDir . ' not found... 
					return false;
				}
				if (substr($eachDir, strlen($eachDir) - 1, 1) != '/') {
					$eachDir .= '/';
				}
				$dotfiles = glob($eachDir . '.*', GLOB_MARK);
				$insideFiles = glob($eachDir . '*', GLOB_MARK);
				$insideFiles = array_merge($insideFiles, $dotfiles);
				foreach ($insideFiles as $insideFile) {
					if (basename($insideFile) == '.' || basename($insideFile) == '..') {
						continue;
					} else if (is_dir($insideFile)) {
						deleteDirectoryPlusContent($insideFile);
					} else {
						unlink($insideFile);
					}
				}
				rmdir($eachDir);
				return ! is_dir( $eachDir );
			}	
			foreach( $dirsToDelete as $eachDir )
			{
				$eachDir = APPLICATION_DIR . $eachDir;
				deleteDirectoryPlusContent($eachDir);
			}	
			
			
			//	Open the downloaded ( and zipped ) framework and save it on the server.
			//	We must have PharData installed for this to work.
			$phar = 'PharData';
			$backup = new $phar( $filename );
			$backup->extractTo( APPLICATION_DIR, null, true );
			
			//	return preserved data
			foreach( $preserveList as $eachDir )
			{
				$oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
				$newEachDir = $temDir . $eachDir;
				@mkdir( dirname( $oldEachDir ), 0777, true );
				@copy( $newEachDir, $oldEachDir );  
		//		var_export( $newEachDir );
		//		var_export( $oldEachDir );
			}
			
			//	
			if( is_dir( $newDir ) )
			{
				
				//	check if we have application in the old dir, so we can copy to the new dir.
				if( @is_dir( @$oldAppPath ) )
				{
					$source = $oldAppPath;
					$dest = $newAppPath;
					/**
					 * Copy a file, or recursively copy a folder and its contents
					 * @author      Aidan Lister <aidan@php.net> 
					 * @version     1.0.1
					 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
					 * @param       string   $source    Source path
					 * @param       string   $dest      Destination path
					 * @param       string   $permissions New folder creation permissions
					 * @return      bool     Returns true on success, false on failure
					 */
					function xcopy($source, $dest, $permissions = 0755) 
					{
						// Check for symlinks
						if( is_link( $source ) ) {
							return symlink(readlink($source), $dest);
						}

						// Simple copy for a file
						if (is_file($source)) {
							return copy($source, $dest);
						}

						// Make destination directory
						if (!is_dir($dest)) {
							mkdir($dest, $permissions);
						}

						// Loop through the folder
						$dir = dir($source);
						while (false !== $entry = $dir->read()) {
							// Skip pointers
							if ($entry == '.' || $entry == '..') {
								continue;
							}

							// Deep copy directories
							xcopy("$source/$entry", "$dest/$entry", $permissions);
						}

						// Clean up
						$dir->close();
						return true;
					}
					xcopy( $source, $dest );
					rename( $oldAppPath, $oldAppPath . '.old' );
				}
			}
			
			//	Transfer the local_html files to the document root
		//	$backup->extractTo( realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ), array( 'local_html/index.php', 'local_html/.htaccess', ), true ); 
			file_put_contents( 'index.php', file_get_contents( $backup['local_html/index.php'] ) );
			file_put_contents( '.htaccess', file_get_contents( $backup['local_html/.htaccess'] ) );
			file_put_contents( 'web.config', file_get_contents( $backup['local_html/web.config'] ) );
			chmod( 'index.php', 0644 );
			chmod( '.htaccess', 0644 );
			chmod( 'web.config', 0644 );

			//	Self destroy file
			unlink( $filename );
	//		$phar::unlinkArchive( $filename );
	//		unlink( $filename );
			//	Register this session as an administrative session
		//	$_SESSION['installer'] = true;
						
			//	Get rid of the cache items. This help for compatibility.
			
			deleteDirectoryPlusContent( $baseAppPath . '/temp' );
			deleteDirectoryPlusContent( $baseAppPath . '/cache' );
			
			$content .= '<h1>Installation Completed</h1>';
			$content .= '<p>The latest PageCarton software has been loaded on your server. You are going to be able to personalize it in a few moments.</p>';
			$content .= '<p>Please ensure you complete the personalization process. Expecially, ensure that you set an account up as the first administrative account. Your installation might be useless, if an administrative account is not set right now.</p>';
			$content .= '<p>Do you have URL rewriting feature ( e.g. mod-rewrite) on your webserver? PageCarton could work with or without it...</p>';
			
	
			//	look for this path prefix dynamically
			$currentDir = explode( DS, realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
			$tempDir = explode( DS, realpath( $_SERVER['DOCUMENT_ROOT'] ) );

			$prefix = null;
			if( $currentDir !== $tempDir )
			{
				$prefix = array_diff( $currentDir, $tempDir );
				if( implode( DS, $currentDir ) === implode( DS, $tempDir + $prefix ) && trim( implode( DS, $prefix ) ) )
				{
				//	var_export( $currentDir );
					$prefix = '/' . implode( DS, $prefix );
				//	var_export( $prefix );
				}
			//	var_export( $tempDir );
			//	var_export( $prefix );
			}
			
			$content .= '<input value="Yes, I am ready for some fine looking URL" type="button" onClick = "location.href=\'' . $prefix . '/object/name/Application_Personalization/\'" />';
			$content .= '<input value="I am not sure..." type="button" onClick = "location.href=\'index.php/object/name/Application_Personalization/\'" />';
			
		break;
		case 'selfdestruct':
			//	remove self
			if( @unlink( __FILE__ ) )
			{
				exit( '1' );
			}
			else
			{
				throw new Exception( 'INSTALLER COULD NOT SELF-DESTRUCT' );
			}
		break;
        default:
		
			//	Upgrade installer before going ahead
			if( is_writable( $_SERVER['SCRIPT_FILENAME'] ) )
            { 
                if( ! $f = @fopen( $remoteSite . '/pc_installer.php?do_not_highlight_file=1', 'r' ) )
                {
                    $badnews .= '<p>Application cannot connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
                    break;
                }
                file_put_contents( $_SERVER['SCRIPT_FILENAME'], $f );
				header( 'Location: ?stage=start' );
				exit();
            }
			else
			{
                $badnews .= '<p>The installer could not be upgraded. It need to be refreshed before installation. Make the installer file writable. The part is - ' . $_SERVER['SCRIPT_FILENAME'] . '</p>';
			}
        break;
	
	}
	if( $badnews )
	{
		$a = '<h1>ERROR: ' . @$_GET['badnews'] . '</h1>';
		$a .= '<p>While installing PageCarton, and error was encountered. Please contact your site administrator to find out if your system has the minimum requirement for installation.</p>';
		$a .= $badnews;
		$a .= '<input value="Try Again" type="button" onClick = "location.href=\'?stage=\'" />';
		echo $a;
	}
	else
	{
		echo $content;
	}