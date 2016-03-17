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
		$this->createForm( 'Create', 'Create a Backup' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
 		switch( $values['backup_type'] )
		{
			case 'installer':
				
				//	copy the file to my document directory so it can be downloadable.
		//		$documentsDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . DOCUMENTS_DIR;
				$documentsDir = Ayoola_Doc::getDocumentsDirectory();
				$installerFilenameGz = $documentsDir . self::getInstallerLink();
				$simpleFilename = Ayoola_Application::$installer;
				$urlPrefix = Ayoola_Application::getUrlPrefix();
				$installerFilenamePhp = $documentsDir . DS . $simpleFilename;
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
				$installerText = 
<<<EOD
<?php 
/**
 * PageCarton Developer Tool
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton Installer
 * @copyright  Copyright (c) 2011-{$year} PageCarton.com (http://www.PageCarton.com/)
 * @license    http://{$domain}/license.txt
 * @version    \$Id: {$simpleFilename} {$date}  \${$userInfo['username']}
 */


	//	Always show error
	ini_set( 'display_errors', "1" );	
	
	//	We need memory for extraction
	ini_set( "memory_limit","512M" );

	//	Download and extraction can take a while
	set_time_limit( 0 );
	defined('DS') || define('DS', DIRECTORY_SEPARATOR);
	defined('PS') || define('PS', PATH_SEPARATOR);
	
	
	isset( \$_SESSION ) ? null : session_start();
	\$_SESSION['installer'] = sha1( "11" );
	
	//  Detect path to application
/* 	\$home = realpath( dirname( \$_SERVER['SCRIPT_FILENAME'] ) );
	\$dir = realpath( dirname( \$home ) );
 */	
	//	Using document root now
	\$home = realpath( \$_SERVER['DOCUMENT_ROOT'] );
	\$dir = \$oldDir = \$baseAppPath = realpath( dirname( \$home ) );
	
	//	Shrink everything into a single dir
	\$temDir = \$dir . '/temp/install/';
	\$dir = \$newDir = \$dir . '/pagecarton';
	
	\$oldAppPath = null;
	
	//	Create dir
	if( ! is_dir( \$dir ) )
	{
		mkdir( \$dir ); 
		
		//	this is an upgrade from before we had the new dir structure of /pagecarton
		\$oldAppPath = \$oldDir . DS . 'application';
		\$newAppPath = \$newDir . DS . 'application';
	}
	\$_SESSION['installer'] = \$dir;

	//	Retrieve the file
	\$filename = '{$simpleFilename}.tar.gz';
	defined( 'APPLICATION_DIR' ) || define( 'APPLICATION_DIR', \$dir );
	
//	use the next sequence to provide installation procedure.
	\$content = null;
	\$badnews = null;
	\$remoteSite = 'http://{$domain}';
	switch( @\$_GET['stage'] )
	{
        case 'start':
            \$content .= '<h1>Installing PageCarton</h1>';
            \$content .= '<p>PageCarton is an amazing tool for creating powerful and robust websites. It is a free Content Management System which is based on <a href="http://pagecarton.com/">Ayoola Framework</a>. When installed on your server, PageCarton will help you publish contents to the internet.</p>';
            \$content .= '<p>Follow these simple steps to install PageCarton on your server. To find out more about PageCarton, visit <a href="http://www.PageCarton.com/">the application homepage</a>. To continue installation, click the button below.</p>';
            \$content .= '<input value="Continue..." type="button" onClick="location.href=\'?stage=licence\'" />';
        break;
		case 'licence':
			\$content .= '<h1>Continue installation, only if you agree to be bound by the following license terms.</h1>';
			\$content .= '<p>Please note that the license terms may change from time to time. Changes will always be on ' . \$remoteSite . '/license.txt' . '.</p>';
			\$content .= '<textarea rows="10" style="min-width:90%;display:block;">' . file_get_contents( \$remoteSite . '/license.txt' ) . '</textarea>';
			\$content .= '<p>Continuing with the installation will require an active internet connection. Internet is required to ensure that you install the latest version of PageCarton.</p>';
			\$content .= '<input value="I agree" type="button" onClick = "location.href=\'?stage=download\'" />';
		break;
		case 'download':
			//	Check if we can write in the application path
			if( ! is_writable( APPLICATION_DIR ) )
			{ 
				\$badnews .= '<p>Application directory is not writable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton will be installed.</p>';
				break;
			}
			//	Retrieve the file
			if( ! is_file( \$filename ) )
			{ 
				if( ! \$f = @fopen( \$remoteSite . '{$filename}', 'r' ) )
				{
					\$badnews .= '<p>Application cannot connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
					break;
				}
				file_put_contents( \$filename, \$f );
			}
			if( ! is_readable( \$filename ) )
			{ 
				\$badnews .= '<p>Downloaded application is not readable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton is being installed.</p>';
				break;
			}
            \$content .= '<h1>PageCarton Downloaded</h1>';
            \$content .= '<p>We will now begin installation of PageCarton on the server. Just click on the button below to begin installation.</p>';
            \$content .= '<input value="Begin Installation" type="button" onClick="location.href=\'?stage=install\'" />';
		break;
		case 'install':
			if( ! is_file( \$filename ) )
			{ 
				header( 'Location: ?stage=download' );
				exit();
			}
			
			//	Preserve some of this data before deleting some files
			\$dbDir = '/application/databases/';
			\$preserveList = array
			(
				'Application/domain.xml',
				'Application/backup.xml',
				'Ayoola/Access/localuser.xml', 
				'Application/settings.xml',
				'Ayoola/Api/api.xml',
			);
			foreach( \$preserveList as \$eachDir )
			{
				\$oldEachDir = APPLICATION_DIR . \$dbDir . \$eachDir;
				\$newEachDir = \$temDir . \$eachDir;
				@mkdir( dirname( \$newEachDir ), 0777, true );
				@copy( \$oldEachDir, \$newEachDir );       
			}
			
			//	clean all dirs first
		//	\$dirsToDelete = array( '/library/', '/temp/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
		
			//	dont clear temp because theres where our preservations are.
			\$dirsToDelete = array( '/library/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
			
			/**
			 * Attempts to remove dirs recursively in case
			 *
			 * @param string Path to Directory to be deleted
			 * @return void
			 */
			function deleteDirectoryPlusContent( \$eachDir )
			{
				if (!is_dir(\$eachDir)) {
				//	throw new Ayoola_Doc_Exception("\$eachDir is not a directory");
				//	echo \$eachDir . ' not found... 
					return false;
				}
				if (substr(\$eachDir, strlen(\$eachDir) - 1, 1) != '/') {
					\$eachDir .= '/';
				}
				\$dotfiles = glob(\$eachDir . '.*', GLOB_MARK);
				\$insideFiles = glob(\$eachDir . '*', GLOB_MARK);
				\$insideFiles = array_merge(\$insideFiles, \$dotfiles);
				foreach (\$insideFiles as \$insideFile) {
					if (basename(\$insideFile) == '.' || basename(\$insideFile) == '..') {
						continue;
					} else if (is_dir(\$insideFile)) {
						deleteDirectoryPlusContent(\$insideFile);
					} else {
						unlink(\$insideFile);
					}
				}
				rmdir(\$eachDir);
				return ! is_dir( \$eachDir );
			}	
			foreach( \$dirsToDelete as \$eachDir )
			{
				\$eachDir = APPLICATION_DIR . \$eachDir;
				deleteDirectoryPlusContent(\$eachDir);
			}	
			
			
			//	Open the downloaded ( and zipped ) framework and save it on the server.
			//	We must have PharData installed for this to work.
			\$phar = 'PharData';
			\$backup = new \$phar( \$filename );
			\$backup->extractTo( APPLICATION_DIR, null, true );
			
			//	return preserved data
			foreach( \$preserveList as \$eachDir )
			{
				\$oldEachDir = APPLICATION_DIR . \$dbDir . \$eachDir;
				\$newEachDir = \$temDir . \$eachDir;
				@mkdir( dirname( \$oldEachDir ), 0777, true );
				@copy( \$newEachDir, \$oldEachDir );  
		//		var_export( \$newEachDir );
		//		var_export( \$oldEachDir );
			}
			
			//	
			if( is_dir( \$newDir ) )
			{
				
				//	check if we have application in the old dir, so we can copy to the new dir.
				if( @is_dir( @\$oldAppPath ) )
				{
					\$source = \$oldAppPath;
					\$dest = \$newAppPath;
					/**
					 * Copy a file, or recursively copy a folder and its contents
					 * @author      Aidan Lister <aidan@php.net> 
					 * @version     1.0.1
					 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
					 * @param       string   \$source    Source path
					 * @param       string   \$dest      Destination path
					 * @param       string   \$permissions New folder creation permissions
					 * @return      bool     Returns true on success, false on failure
					 */
					function xcopy(\$source, \$dest, \$permissions = 0755) 
					{
						// Check for symlinks
						if( is_link( \$source ) ) {
							return symlink(readlink(\$source), \$dest);
						}

						// Simple copy for a file
						if (is_file(\$source)) {
							return copy(\$source, \$dest);
						}

						// Make destination directory
						if (!is_dir(\$dest)) {
							mkdir(\$dest, \$permissions);
						}

						// Loop through the folder
						\$dir = dir(\$source);
						while (false !== \$entry = \$dir->read()) {
							// Skip pointers
							if (\$entry == '.' || \$entry == '..') {
								continue;
							}

							// Deep copy directories
							xcopy("\$source/\$entry", "\$dest/\$entry", \$permissions);
						}

						// Clean up
						\$dir->close();
						return true;
					}
					xcopy( \$source, \$dest );
					rename( \$oldAppPath, \$oldAppPath . '.old' );
				}
			}
			
			//	Transfer the local_html files to the document root
		//	\$backup->extractTo( realpath( dirname( \$_SERVER['SCRIPT_FILENAME'] ) ), array( 'local_html/index.php', 'local_html/.htaccess', ), true ); 
			file_put_contents( 'index.php', file_get_contents( \$backup['local_html/index.php'] ) );
			file_put_contents( '.htaccess', file_get_contents( \$backup['local_html/.htaccess'] ) );
			file_put_contents( 'web.config', file_get_contents( \$backup['local_html/web.config'] ) );
			chmod( 'index.php', 0644 );
			chmod( '.htaccess', 0644 );
			chmod( 'web.config', 0644 );

			//	Self destroy file
			unlink( \$filename );
	//		\$phar::unlinkArchive( \$filename );
	//		unlink( \$filename );
			//	Register this session as an administrative session
		//	\$_SESSION['installer'] = true;
						
			//	Get rid of the cache items. This help for compatibility.
			
			deleteDirectoryPlusContent( \$baseAppPath . '/temp' );
			deleteDirectoryPlusContent( \$baseAppPath . '/cache' );
			
			\$content .= '<h1>Installation Completed</h1>';
			\$content .= '<p>The latest PageCarton software has been loaded on your server. You are going to be able to personalize it in a few moments.</p>';
			\$content .= '<p>Please ensure you complete the personalization process. Expecially, ensure that you set an account up as the first administrative account. Your installation might be useless, if an administrative account is not set right now.</p>';
			\$content .= '<p>Do you have URL rewriting feature ( e.g. mod-rewrite) on your webserver? PageCarton could work with or without it...</p>';
			
	
			//	look for this path prefix dynamically
			\$currentDir = explode( DS, realpath( dirname( \$_SERVER['SCRIPT_FILENAME'] ) ) );
			\$tempDir = explode( DS, realpath( \$_SERVER['DOCUMENT_ROOT'] ) );

			\$prefix = null;
			if( \$currentDir !== \$tempDir )
			{
				\$prefix = array_diff( \$currentDir, \$tempDir );
				if( implode( DS, \$currentDir ) === implode( DS, \$tempDir + \$prefix ) && trim( implode( DS, \$prefix ) ) )
				{
				//	var_export( \$currentDir );
					\$prefix = '/' . implode( DS, \$prefix );
				//	var_export( \$prefix );
				}
			//	var_export( \$tempDir );
			//	var_export( \$prefix );
			}
			
			\$content .= '<input value="Yes, I am ready for some fine looking URL" type="button" onClick = "location.href=\'' . \$prefix . '/object/name/Application_Personalization/\'" />';
			\$content .= '<input value="I am not sure..." type="button" onClick = "location.href=\'index.php/object/name/Application_Personalization/\'" />';
			
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
			if( is_writable( \$_SERVER['SCRIPT_FILENAME'] ) )
            { 
                if( ! \$f = @fopen( \$remoteSite . '{$urlPrefix}/{$simpleFilename}?do_not_highlight_file=1', 'r' ) )
                {
                    \$badnews .= '<p>Application cannot connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
                    break;
                }
                file_put_contents( \$_SERVER['SCRIPT_FILENAME'], \$f );
				header( 'Location: ?stage=start' );
				exit();
            }
			else
			{
                \$badnews .= '<p>The installer could not be upgraded. It need to be refreshed before installation. Make the installer file writable. The part is - ' . \$_SERVER['SCRIPT_FILENAME'] . '</p>';
			}
        break;
	
	}
	if( \$badnews )
	{
		\$a = '<h1>ERROR: ' . @\$_GET['badnews'] . '</h1>';
		\$a .= '<p>While installing PageCarton, and error was encountered. Please contact your site administrator to find out if your system has the minimum requirement for installation.</p>';
		\$a .= \$badnews;
		\$a .= '<input value="Try Again" type="button" onClick = "location.href=\'?stage=\'" />';
		echo \$a;
	}
	else
	{
		echo \$content;
	}
EOD;
	//	each
				//	Build installer into the zipped document so that it always have the fresh content
				file_put_contents( $installerFilenamePhp, $installerText );
				
				if( ! $info  = $this->createFile() ){ return false; }
				rename( $info['backup_filename'], $installerFilenameGz );
				
				//	Save a draft in local_html to use for upgrade
		//		copy( $installerFilenamePhp, APPLICATION_DIR . DS . 'local_html' );
				
				$this->setViewContent( '<p class="goodnews">Archive for installation has been created successfully. It is now accessible publicly for download at <a href="' . self::getInstallerLink() . '">' . 'http://' . Ayoola_Page::getDefaultDomain() .  self::getInstallerLink() . '</a></p>', true );
				$this->setViewContent( '<p class="">The script to install the archive on a new server has been auto-generated and could be found on this link <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '/' . $simpleFilename . '">' . 'http://' . Ayoola_Page::getDefaultDomain() . '/' . $simpleFilename . '</a></p>' );
			break;
			default:
				if( ! $values  = $this->createFile() ){ return false; }
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
	//	if( ! $this->insertDb( $values ) ){ return null; }
		
		//	remove duplicate names
		$phar = 'Ayoola_Phar_Data';
		$backup = new $phar( $values['filename'] );
		$backup->startBuffering();
		//	Cwd is also required
		$requiredList = array( '/' . basename( realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ) ) . '/', '/local_html/', '/license.txt', '/changelog.txt', '/readme.txt', '/application/configs/', '/application/functions/', '/pagecarton/local_html/', '/pagecarton/license.txt', '/pagecarton/changelog.txt', '/pagecarton/copyright.txt', '/pagecarton/readme.txt', '/pagecarton/application/configs/', '/pagecarton/application/functions/' );  
		$userList = array_intersect( array_keys( self::$_exportList ), $values['backup_export_list'] );
		$totalList = array_merge( $requiredList, $userList );
	//	var_export( self::$_exportList );
	//	var_export( $values['backup_export_list'] );
	//	var_export( $totalList );
		$dir = Ayoola_Application::getDomainSettings( APPLICATION_DIR );
//		$f = '/home/server4101/www/avalanchenigeria.com/public_html/ayoola_cmf_controller.php';
//		$f = '/home/server4101/www/avalanchenigeria.com/public_html/.htaccess';
		$f = $dir . '/public_html/ayoola_cmf_controller.php';
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
		set_time_limit( 1200 );
		ignore_user_abort( true );
		ini_set( 'memory_limit', "256M" );
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
				try
				{
					$backup->delete( 'application/databases/Application/domain.xml' );
					$backup->delete( 'application/databases/Application/settings.xml' );
					$backup->delete( 'application/databases/Ayoola/Api/api.xml' );
					$backup->delete( 'pagecarton/application/databases/Application/domain.xml' );
					$backup->delete( 'pagecarton/application/databases/Application/settings.xml' );
					$backup->delete( 'pagecarton/application/databases/Ayoola/Api/api.xml' );
				}
				catch( Exception $e ){ null; }
			break;
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
