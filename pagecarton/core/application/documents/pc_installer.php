<?php 

/**
 * PageCarton Developer Tool
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton Installer
 * @copyright  Copyright (c) 2011-2017 PageCarton.com (http://www.PageCarton.com/)
 * @license    http://www.pagecarton.org/license.txt
 * @version    $Id: pc_installer.php 09:21:2017 11:35pm  $joywealth
 */


	//	Always show error
	ini_set( 'display_errors', "1" );	
	
	//	We need memory for extraction
	ini_set( "memory_limit","512M" );

	//	Download and extraction can take a while
	set_time_limit( 0 );
	defined( 'DS' ) || define( 'DS', DIRECTORY_SEPARATOR );
	defined( 'PS' ) || define( 'PS', PATH_SEPARATOR );
			
	//  Detect path to application
	

	//	Using document root now
	$home = dirname( __FILE__ );
	//	var_export( $home );

	if( ! empty( $_SERVER['DOCUMENT_ROOT'] ) )
	{
		$docRoot = realpath( $_SERVER['DOCUMENT_ROOT'] ) ? : $_SERVER['DOCUMENT_ROOT'];  
		if( is_dir( $docRoot ) && is_writable( $docRoot ) )
		{
			$home = $docRoot;
		}
	}
//	var_export( $home );
//	var_export( $_SERVER['DOCUMENT_ROOT'] );
//	exit();
	$dir = $oldDir = $baseAppPath = dirname( $home );
//		var_export( $oldDir );
//		var_export( $baseAppPath );
//		var_export( dirname( $home ) );

	//	Shrink everything into a single dir
	$dir = $newDir = $dir . '/pagecarton';
	$temDirMain = $dir . '/temp/';
	$temDir = $dir . '/temp/install/'; 
	
	
	$oldAppPath = null;
	
	//	Create dir
	if( ! is_dir( $dir ) )
	{
		mkdir( $dir, 0777, true ); 
		
		if( is_dir( $oldDir . DS . 'application' ) )
		{
			//	this is an upgrade from before we had the new dir structure of /pagecarton
			$oldAppPath = $oldDir . DS . 'application';
			$newAppPath = $newDir . DS . 'application';
		}
	}
	
	//	introducing separate core dir to make this one easily replaceable during upgrades
	$newDir2 = $newDir . DS . 'core';
	if( ! is_dir( $newDir2 ) )
	{
		mkdir( $newDir2, 0777, true ); 
	}
	

	//	Retrieve the file
	$filename = 'pc_installer.php.tar.gz';
	defined( 'APPLICATION_DIR' ) || define( 'APPLICATION_DIR', $newDir2 );
	
//	use the next sequence to provide installation procedure.
	$content = null;
	$badnews = null;
	$remoteSite = 'http://updates.pagecarton.org';  
	$remoteSite2 = 'http://s1.updates.pagecarton.org';  
	$remoteSite3 = 'http://s2.updates.pagecarton.org';  


	//	look for this path prefix dynamically

    $currentDir = explode( '/', str_replace( array( '/', '\\' ), '/', dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
    $tempDir = explode( '/', str_replace( array( '/', '\\' ), '/', rtrim( $home, '/\\' ) ) );   

//	var_export( $currentDir );
//	var_export( $baseAppPath );
//	var_export( $tempDir );
//	exit;
	$prefix = null;
	if( $currentDir !== $tempDir )
	{
		$prefix = array_diff( $currentDir, $tempDir );
		if( implode( '/', $currentDir ) === implode( '/', $tempDir + $prefix ) && trim( implode( '/', $prefix ) ) )
		{
			//	var_export( $currentDir );
			$prefix = '/' . implode( '/', $prefix );  
		//	var_export( $prefix );
		}
		else
		{
			$prefix = null;
		}
	//	var_export( $tempDir );
	//	var_export( $prefix );
	}
	if( ! empty( $_SERVER['CONTEXT_PREFIX'] ) )
	{
		#	for cpanel temp user links
		#	http://199.192.23.45/~nustreamscentre/pc_installer.php?stage=start
		$prefix = $_SERVER['CONTEXT_PREFIX'] . $prefix;
	}
			
	//	Preserve some of this data before deleting some files
	$dbDir = '/application/databases/';
	$preserveList = array
	(
		'Application/backup.xml',
		'Application/domain.xml',
		'Application/Domain/userdomain.xml',
		'Ayoola/Access/localuser.xml', 
		'Ayoola/Api/api.xml',
		'Application/settings.xml',
		'PageCarton/MultiSite/table.xml',
	);

	//	system check
	$check = array( 
					'dom_import_simplexml' => 'PHP DOM XML',
					'curl_version' => 'PHP CURL',
					'imagegd' => 'PHP GD',
					'zip_open' => 'PHP ZIP',
		);
	foreach( $check as $key => $each )
	{
		if( ! function_exists( $key ) )
		{
			$badnews .= '<p>An important component is missing on your web server. Please ensure "' . $each . '" library is installed and try again.</p>';   
		}
	}
	if( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) 
	{
	//	echo 'I am at least PHP version 5.3.0, my version: ' . PHP_VERSION . "\n";
	}
	else
	{
		$badnews .= '<p>PageCarton requires PHP 5.3 or later. You are running version "' . PHP_VERSION .  '". We recommend PHP 7.0 or later.</p>';   
	}
	
	//	Now use back-up server
	if( ! $res = fetchLink( $remoteSite . '/pc_check.txt' ) )
	{
		$remoteSite = $remoteSite2;
		if( ! fetchLink( $remoteSite . '/pc_check.txt' ) )
		{
			$remoteSite = $remoteSite3;
		}
	}
//	var_export( $res );
//	exit()
  	
    /** 
     * Fetches a remote link. Lifted from Ayoola_Abstract_Viewable
     *
     * @param string Link to fetch
     * @param array Settings  
     */
    function fetchLink( $link, array $settings = null )
    {	
		$request = curl_init( $link );
//		curl_setopt( $request, CURLOPT_HEADER, true );
		curl_setopt( $request, CURLOPT_URL, $link );

		//	dont check ssl
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);   

		curl_setopt( $request, CURLOPT_USERAGENT, @$settings['user_agent'] ? : __FILE__ ); 
		curl_setopt( $request, CURLOPT_AUTOREFERER, true );
		curl_setopt( $request, CURLOPT_REFERER, @$settings['referer'] ? : $link );
		if( @$settings['destination_file'] )
		{
			$fp = fopen( $settings['destination_file'], 'w' );
			curl_setopt( $request, CURLOPT_FILE, $fp );
			curl_setopt( $request, CURLOPT_BINARYTRANSFER, true );
			curl_setopt( $request, CURLOPT_HEADER, 0 ); 
		}
		else  
		{
			curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		}
//		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $request, CURLOPT_FOLLOWLOCATION, @$settings['follow_redirect'] === false ? false : true ); //	By default, we follow redirect
		curl_setopt( $request, CURLOPT_CONNECTTIMEOUT, @$settings['connect_time_out'] ? : 9000 );	//	Max of 1 Secs on a single request
		curl_setopt( $request, CURLOPT_TIMEOUT, @$settings['time_out'] ? : 9000 );	//	Max of 1 Secs on a single request
		if( @$settings['post_fields'] )
		{ 
			curl_setopt( $request, CURLOPT_POST, true );
		//	var_export( $request );
		//	var_export( $settings['post_fields'] );   
			curl_setopt( $request, CURLOPT_POSTFIELDS, $settings['post_fields'] );
		}
		if( @$settings['raw_response_header'] )
		{
		//	var_export( $settings );
			$headerBuff = fopen( '/tmp/headers' . time(), 'w+' );
			//	var_export( $headerBuff );
			curl_setopt( $request, CURLOPT_WRITEHEADER, $headerBuff );
		}
		if( is_array( @$settings['http_header'] ) )
		{
			curl_setopt( $request, CURLOPT_HTTPHEADER, $settings['http_header'] );
		}
		$response = curl_exec( $request );
		$responseOptions = curl_getinfo( $request );

			// close cURL resource, and free up system resources
		curl_close( $request );
	//	var_export( htmlentities( $response ) );
		
 		//	var_export( $responseOptions );
	//	exit( var_export( $responseOptions ) );
		//	var_export( $settings['post_fields'] );
 	//	if( ! $response || $responseOptions['http_code'] != 200 ){ return false; }
		if( empty( $settings['return_error_response'] ) )
		{   
 			if( $responseOptions['http_code'] != 200 ){ return false; }
		}
		if( @$settings['return_as_array'] == true )
		{   
			if( @$settings['raw_response_header'] )
			{
			//	var_export( $headerBuff );
				rewind($headerBuff);
				$headers = stream_get_contents( $headerBuff );
				@$responseOptions['raw_response_header'] = $headers;
			}
			$response = array( 'response' => $response, 'options' => $responseOptions );
		}
 		//	var_export( $response );
		return $response;
    } 
			
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
		//	set_time_limit( 30 );
			if (basename($insideFile) == '.' || basename($insideFile) == '..') {
				continue;
			} else if (is_dir($insideFile)) {
				deleteDirectoryPlusContent($insideFile);   
			} else {
				unlink($insideFile);
			}
		}
		@rmdir($eachDir);   
		return ! is_dir( $eachDir );
	}	
	switch( @$_GET['stage'] )
	{
        case 'start':
            $content .= '<h1>PageCarton Installation</h1>';
            $content .= '<p>A tool to build a great website in 2 hrs or less - fast and easy. Automate things programmers & designers spend several hours putting together. When installed on your server, PageCarton will help you publish contents to the internet.</p>';
            $content .= '<p>Follow these simple steps to install PageCarton on your server. To find out more about PageCarton, visit <a target="_blank" href="http://www.PageCarton.org/">www.PageCarton.org</a>. To continue installation, click the button below if you agree to be bound by the license terms below.</p>';
    //        $content .= '<input value="Continue..." type="button" onClick="location.href=\'?stage=licence\'" />';
  //      break;
//		case 'licence':
//			$content .= '<h1>Continue installation, only if you agree to be bound by the following license terms.</h1>';
	//		$content .= '<p>Please note that the license terms may change from time to time. Changes will always be on <a href="http://PageCarton.org/license.txt">http://www.PageCarton.org/license.txt</a>.</p>';
			$content .= '<textarea rows="10" style="min-width:90%;display:block;">' . ( ( @file_get_contents( 'license.txt' ) ? : @fetchLink( $remoteSite . '/license.txt' ) ) ? : @fetchLink( $remoteSite2 . '/license.txt' ) ) . '</textarea>';
	//		$content .= '<p>Having an active internet connection is preferred when installing PageCarton</p>';
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
			if( ! is_file( $filename ) || ! filesize( $filename ) )
			{ 
/* 				if( ! $f = @fopen( $remoteSite . '/ayoola/framework/installer.tar.gz', 'r' ) )
				{
					if( ! $f = @fopen( $remoteSite2 . '/ayoola/framework/installer.tar.gz', 'r' ) )
					{
						$badnews .= '<p>The installation archive is missing. We also tried to connect to the internet to download it but application coult not connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
						$badnews .= '<p>Please try copying the files back into your web root again and restart your installation. You may also resolve this issue by connecting to the internet.</p>';
						break;
					}
				}
				file_put_contents( $filename, $f );
 */				
        //        var_export( $remoteSite . '/widgets/Application_Backup_GetInstallation/?pc_core_only=1' );
				if( ! $f = fetchLink( $remoteSite . '/widgets/Application_Backup_GetInstallation/?pc_core_only=1', array( 'time_out' => 300 ) ) ) 
				{ 
					$badnews .= '<p>The installation archive is missing. We also tried to connect to the internet to download it but application coult not connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
					$badnews .= '<p>Please try copying the files back into your web root again and restart your installation. You may also resolve this issue by connecting to the internet.</p>';
					break;
				} 
				$f ? file_put_contents( $filename, $f ) : null;
			}
			if( ! is_readable( $filename ) )
			{ 
				$badnews .= '<p>Downloaded application is not readable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton is being installed.</p>';
				break;
			}
            $content .= '<h1>PageCarton Ready for Installation</h1>';
            $content .= '<p>PageCarton is now ready to be installed on your server. Just click on the button below to begin installation. This may take a few moments; do not click the button more than once.</p>';
            $content .= '<input value="Begin Installation" type="button" onClick="location.href=\'?stage=install\';this.value=\'Please wait...\'; this.readOnly =true; " />';  
		break;
		case 'install':
			if( ! is_file( $filename ) )
			{ 
				header( 'Location: ?stage=download' );
				exit();
			}
			foreach( $preserveList as $eachDir )
			{
			//	set_time_limit( 30 );
				$oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
				if( ! is_file( $oldEachDir ) )
				{
					//	reach out to old dir
					$oldEachDir = $newDir . $dbDir . $eachDir;     
				}
				if( is_file( $oldEachDir ) )
				{
					$newEachDir = $temDir . $eachDir;
					@mkdir( dirname( $newEachDir ), 0777, true );
					@copy( $oldEachDir, $newEachDir );  
				}
			}
			header( 'Location: ?stage=install-delete-dir' );
			exit();
		break;
		case 'install-delete-dir':
			
			//	clean all dirs first
		//	$dirsToDelete = array( '/library/', '/temp/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
		
			//	dont clear temp because theres where our preservations are.
			$dirsToDelete = array( '/library/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );
			foreach( $dirsToDelete as $eachDir )
			{
			//	set_time_limit( 30 );
				$eachDir = APPLICATION_DIR . $eachDir;
				@deleteDirectoryPlusContent($eachDir);
			}	
			header( 'Location: ?stage=install-extract' );
			exit();
		break;
		case 'install-extract':
			
			
			//	Open the downloaded ( and zipped ) framework and save it on the server.
			//	We must have PharData installed for this to work.
		//	  ini_set('max_execution_time', 200*60);
			$phar = 'PharData';
			$backup = new $phar( $filename );
			$backup->extractTo( APPLICATION_DIR, null, true );
			
			//	return preserved data
		//	ini_set('max_execution_time', 200*60);
			foreach( $preserveList as $eachDir )
			{
		//		ini_set('max_execution_time', 200*60);
				$oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
				$newEachDir = $temDir . $eachDir;
				@mkdir( dirname( $oldEachDir ), 0777, true );
				@copy( $newEachDir, $oldEachDir );  
		//		ini_set('max_execution_time', 200*60);    
		//		var_export( $newEachDir );
		//		var_export( $oldEachDir );
			}
			header( 'Location: ?stage=install-compatibility-fix' );
			exit();
		break;
		case 'install-compatibility-fix':
			
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
							mkdir($dest, $permissions, true );
						}

						// Loop through the folder
						$dirX = dir($source);
						while (false !== $entry = $dirX->read()) {
							// Skip pointers
							if ($entry == '.' || $entry == '..') {
								continue;
							}

							// Deep copy directories
							xcopy("$source/$entry", "$dest/$entry", $permissions);
						}

						// Clean up
						$dirX->close();
						return true;
					}
					xcopy( $source, $dest );
					rename( $oldAppPath, $oldAppPath . '.old' );
				}
			}
			header( 'Location: ?stage=install-copy-controller' );
			exit();
		break;
		case 'install-copy-controller':
			
			//	Transfer the local_html files to the document root
			
			$filesToCopy = array(
				'index.php',
				'.htaccess',
				'web.config',
			);
			foreach( $filesToCopy as $eachFile )
			{
				$backupFile = $eachFile . '.pc_backup';
				if( is_file( $eachFile ) && ! is_file( $backupFile ) )
				{
					copy( $eachFile, $backupFile );
				}
				file_put_contents( $eachFile, file_get_contents( APPLICATION_DIR . DS . 'local_html' . DS . $eachFile ) );
				chmod( $eachFile, 0644 );
			}

			header( 'Location: ?stage=install-finalize' );
			exit();
						
		break;
		case 'install-finalize':
			//	Get rid of the cache items. This help for compatibility.
			deleteDirectoryPlusContent( $temDirMain );
		//	deleteDirectoryPlusContent( $baseAppPath . '/cache' );
			header( 'Location: ?stage=install-complete' );
			exit();
		break;
		case 'install-complete':
			
			$content .= '<h1>Installation Completed</h1>';
			$content .= '<p>The latest PageCarton software has been loaded on your server. You are going to be able to personalize it in a few moments. Please ensure you complete the personalization in two easy steps.</p>';
			
			
			//	Check if we have mod-rewrite
			
			//	start PC
		//	include_once 'index.php';
			$protocol = 'http';
			if( $_SERVER['SERVER_PORT'] == 443 && ! empty( $_SERVER['HTTPS'] ) )
			{
				$protocol = 'https';
			}
		
			$urlToLocalInstallerFile = $protocol . '://' . $_SERVER['HTTP_HOST'] . $prefix . '/widgets?pc_clean_url_check=1';
			
			$modRewriteEnabled = get_headers( $urlToLocalInstallerFile );
			$responseCode = explode( ' ', $modRewriteEnabled[0] );
		//	var_export( $urlToLocalInstallerFile );
		//	var_export( $modRewriteEnabled );
		//	var_export( $responseCode );
			if( in_array( '200', $responseCode ) )
			{
				$content .= '<p><input value="Proceed to Personalization" type="button" onClick = "location.href=\'' . $prefix . '/widgets/name/Application_Personalization/\'" /></p>';
			}
			else
			{
				$content .= '<p>You do not have URL rewriting feature (e.g. mod-rewrite) on your webserver? PageCarton would work without it; But you would need to prefix your URLs with "index.php" when entering it on the web browser e.g. http://' . $_SERVER['HTTP_HOST'] . $prefix . '/index.php/page/url. On many of your pages, PageCarton will add this automatically.  </p>';
			//	$content .= '<p><a href="index.php/widgets/name/Application_Personalization/"> Proceed to Personalization...</a></p>';
				$content .= '<p><input value="Proceed to Personalization" type="button" onClick = "location.href=\'index.php/widgets/name/Application_Personalization/\'" /></p>';
			}
			//	Self destroy file
			unlink( $filename );
	//		$phar::unlinkArchive( $filename ); 
	//		unlink( $filename );
			
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
		//	if( 'index.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )  
			{
				//	don't replace controller. 
				//	ONLY replace if its pc_installer.php
			//	header( 'Location: ' . {$prefix}/pc_installer.php );
			}
			if( is_writable( $_SERVER['SCRIPT_FILENAME'] ) )
            { 
             //	if( $f = @fopen( $remoteSite . '/pc_installer.php?do_not_highlight_file=1', 'r' ) )
             	if( $f = fetchLink( $remoteSite . '/pc_installer.php?do_not_highlight_file=1' ) )
                {
				//	var_export( $f );
				//	exit();
					file_put_contents( 'pc_installer.php', $f );   
				//	file_put_contents( 'pc_installer.php',  );
                }
          	}
			else  
			{
				//	Upgrade no longer required to cater for offline installers
          //      $badnews .= '<p>The installer could not be upgraded. It need to be refreshed before installation. Make the installer file writable. The part is - ' . $_SERVER['SCRIPT_FILENAME'] . '</p>';
			}
		//	header( 'Location: ?stage=start' );
	//    var_export( $prefix );
			header( "Location: {$prefix}/pc_installer.php?stage=start" );
			exit();
        break;
	
	}
	echo '
<!DOCTYPE html>
<html lang="en" style="background-color:#ccc;">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="">
<title>PageCarton Installation</title>
<style>
body
{
	max-width: 720px;
	margin: auto auto;
}
p
{
	margin-top: 1em;
	margin-bottom: 1em;
	text-align:justify;
}
textarea
{
	width: 100%;
	background: inherit;
	padding-top: 2em;
}
input[type="button"],
input[type="submit"]
{
    -moz-box-shadow: inset 0px 1px 0px 0px #45D6D6;
    -webkit-box-shadow: inset 0px 1px 0px 0px #45D6D6;
    box-shadow: inset 0px 1px 0px 0px #45D6D6;
    background-color: #2CBBBB;
    border: 1px solid #27A0A0;
    display: inline-block;
    cursor: pointer;
    color: #FFFFFF;
    font-size: 14px;
    padding: 8px 18px;
	margin-top: 1em;
    text-decoration: none;
    text-transform: uppercase;
}
input[type="button"]:hover,
input[type="submit"]:hover 
{
    background:linear-gradient(to bottom, #34CACA 5%, #30C9C9 100%);
    background-color:#34CACA;
}

</style>
</head>
<body style="padding:1em; background-color:#ccc;">
<div>';
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
	//		echo '<p style="font-size:x-small; margin: 1em; text-align:center;">PageCarton version 1.7.5</p>';
	}
	echo '
</div>
</body>
</html>';

	exit();
