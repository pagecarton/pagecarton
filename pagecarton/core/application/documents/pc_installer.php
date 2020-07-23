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

//    Always show error
ini_set( 'display_errors', "1" );

//    We need memory for extraction
ini_set( "memory_limit", "512M" );

//    Download and extraction can take a while
set_time_limit(0);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

//  Detect path to application

/**
 * the document root of the server
 */
$cwd = dirname( __FILE__ ); //  Let doc_root default to current directory
$home = $cwd;
$docRoot = $cwd;

// Now, set the real doc root
if (!empty($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']) ?: $_SERVER['DOCUMENT_ROOT'];
    if (is_dir($docRoot) && is_writable($docRoot)) {
        $home = $docRoot;
    }
}

/**
 * directory to install into. This resolves to the parent of $home
 */
$dir = $oldDir = $baseAppPath = dirname($home);

//    Shrink everything into a single dir
$dir        = $newDir        = $dir . '/pagecarton';
$temDirMain = $dir . '/temp/';
$temDir     = $dir . '/temp/install/';

$oldAppPath  = null;
$filename    = 'pc_installer.php.tar.gz';
$content     = null;
$badnews     = null;
$remoteSite  = 'http://updates.pagecarton.org';
$remoteSite2 = 'http://s1.updates.pagecarton.org';
$remoteSite3 = 'http://s2.updates.pagecarton.org';

//    Create dir
if (!is_dir($dir)) {

    //ensure we have the access before creating dirs
    if (is_writable(dirname($dir))) {
        if (!mkdir($dir, 0777, true)) {
            $badnews = "Error creating directory: <code class='annotated'>$dir</code>";
        }
    } else {
        $badnews = "<p>The directory <code class='annotated'>" . dirname($dir) . "</code> is not writeable. Please grant access to the directory and try again.</p>";
    }

    if (is_dir($oldDir . DS . 'application')) {
        //    this is an upgrade from before we had the new dir structure of /pagecarton
        $oldAppPath = $oldDir . DS . 'application';
        $newAppPath = $newDir . DS . 'application';
    }
}

//    introducing separate core dir to make this one easily replaceable during upgrades
$newDir2 = $newDir . DS . 'core';
if (!is_dir($newDir2)) {

    //ensure we have the access before creating dirs
    if (is_writable(dirname($newDir2))) {
        if (!mkdir($newDir2, 0777, true)) {
            $badnews = "Error creating directory: <code class='annotated'>$newDir2</code>";
        }
    } else {
        $badnews = "
        <p>
        PageCarton installer could not create <code class='annotated'>$newDir2</code> because
		there is no access to create <code class='annotated'>" . basename(dirname($newDir2)) . "</code> folder inside
        <code class='annotated'>" . dirname(dirname($newDir2)) . "</code>.
        </p>
		<p>
		Please adjust the permission on <code class='annotated'>" . dirname(dirname($newDir2)) . "</code> so that the server can write to it.
		However, if you do not want server access to <code class='annotated'>" . dirname(dirname($newDir2)) . "</code>,
		you can manully create a folder named <code class='annotated'>" . basename(dirname($newDir2)) . "</code> inside <code class='annotated'>" . dirname(dirname($newDir2)) . "</code>
		and then grant server write access to the <code class='annotated'>" . dirname($newDir2) . "</code> directory only.
		</p>
		";
    }
}

//$filename signifies the file that downloaded installation will be written to in the current directory. Hence, it
//is important that curr dir is writeable to PHP
if (!is_writable(__DIR__)) {
    $badnews = "The current directory <code class='annotated'>" . realpath(__DIR__) . "</code> is not writeable for your web server process. Please check this folder permission and try again.";
}

defined('APPLICATION_DIR') || define('APPLICATION_DIR', $newDir2);

//    look for this path prefix dynamically

$currentDir = explode('/', str_replace(array('/', '\\'), '/', dirname($_SERVER['SCRIPT_FILENAME'])));
$tempDir    = explode('/', str_replace(array('/', '\\'), '/', rtrim( $docRoot, '/\\')));

$prefix = null;
if ($currentDir !== $tempDir) {
    $prefix = array_diff($currentDir, $tempDir);
    if (implode('/', $currentDir) === implode('/', $tempDir + $prefix) && trim(implode('/', $prefix))) {
        $prefix = '/' . implode('/', $prefix);
    } else {
        $prefix = null;
    }
}
if (!empty($_SERVER['CONTEXT_PREFIX'])) {
    #    for cpanel temp user links
    #    http://199.192.23.45/~nustreamscentre/pc_installer.php?stage=start
    $prefix = $_SERVER['CONTEXT_PREFIX'] . $prefix;
}

//    Preserve some of this data before deleting some files
$dbDir        = '/application/databases/';
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

//    system check
$function_dependencies = array(
    'dom_import_simplexml' => 'PHP DOM XML',
    'curl_version'         => 'PHP CURL',
    'imagegd'              => 'PHP GD',
    'zip_open'             => 'PHP ZIP',
);

$class_dependencies = ["PharData"];

foreach ($function_dependencies as $key => $each) {
    if (!function_exists($key)) {
        $badnews .= '<p>An important component is missing on your web server. Please ensure <code class="annotated">' . $each . '</code> library is installed and try again.</p>';
    }
}

foreach ($class_dependencies as $class_dependency) {
    if (!class_exists($class_dependency)) {
        $badnews .= "<p>An important component is missing on your web server. Please ensure that <code class='annotated'>$class_dependency</code> is installed and try again.</p>";
    }
}

if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
    //    echo 'I am at least PHP version 5.3.0, my version: ' . PHP_VERSION . "\n";
} else {
    $badnews .= '<p>PageCarton requires PHP 5.3 or later. You are running version <code class="annotated">' . PHP_VERSION . '</code>. We recommend PHP 7.0 or later.</p>';
}

//    Now use back-up server
if (!$res = fetchLink($remoteSite . '/pc_check.txt')) {
    $remoteSite = $remoteSite2;
    if (!fetchLink($remoteSite . '/pc_check.txt')) {
        $remoteSite = $remoteSite3;
    }
}

//non-null bad news signifies that something went wrong. So ensure that everyhting is fine before proceeding (in case of non-nullness, $badnews already contains info about what went wrong)
if (
    !isset($_GET['stage']) ||
    (isset($_GET['stage']) && empty($_GET['stage'])) ||
    is_null($badnews) ||
    !isset($_GET['stage']) ||
    ((isset($_GET['stage']) && !empty($_GET['stage']) && ($_GET['stage'] == InstallationStages::START)))) {

    switch (@$_GET['stage']) {

        case InstallationStages::START:
            $content .= '<h1>PageCarton Installation</h1>';
            $content .= '<p>A tool to build a great website in 2 hrs or less - fast and easy. Automate things programmers & designers spend several hours putting together. When installed on your server, PageCarton will help you publish contents to the internet.</p>';
            $content .= '<p>Follow these simple steps to install PageCarton on your server. To find out more about PageCarton, visit <a target="_blank" href="http://www.PageCarton.org/">www.PageCarton.org</a>. To continue installation, click the button below if you agree to be bound by the license terms below:</p>';
            //        $content .= '<input value="Continue..." type="button" onClick="location.href=`?stage=licence`" />';
            //      break;
            //        case 'licence':
            //            $content .= '<h1>Continue installation, only if you agree to be bound by the following license terms.</h1>';
            //        $content .= '<p>Please note that the license terms may change from time to time. Changes will always be on <a href="http://PageCarton.org/license.txt">http://www.PageCarton.org/license.txt</a>.</p>';
            $content .= '<textarea rows="10" class="leading-normal" style="min-width:90%;display:block; margin-top:20px; font-family:mono;">' . ((@file_get_contents('license.txt') ?: @fetchLink($remoteSite . '/license.txt')) ?: @fetchLink($remoteSite2 . '/license.txt')) . '</textarea>';
            //        $content .= '<p>Having an active internet connection is preferred when installing PageCarton</p>';
            $content .= '<input value="I agree" type="button" onClick = "location.href=`?stage=' . InstallationStages::DOWNLOAD . '`" />';
            break;

        case InstallationStages::DOWNLOAD:
            //    Check if we can write in the application path
            if (!is_writable(APPLICATION_DIR)) {
                $badnews .= '<p>Application directory is not writable. Please ensure you have correct permissions set on <code class="annotated">' . APPLICATION_DIR . '</code>. This is where PageCarton will be installed.</p>';

                //instead of forcing user to make their ../doc_root writeable to us, they may choose to create the required folder
                $root_pc_folder         = dirname(APPLICATION_DIR);
                $outside_root_pc_folder = dirname($root_pc_folder);
                $badnews .= "<p>
				If you do not want to grant server write access to <code class='annotated'>$outside_root_pc_folder</code>,
				you can simply create a folder named <code class='annotated'>" . basename($root_pc_folder) . "</code> in the <code class='annotated'>$outside_root_pc_folder</code> directory,
                and then grant server write access to only <code class='annotated'>$root_pc_folder</code>.
                </p>";
                break;
            }
            //    Retrieve the file
            if (!is_file($filename) || !filesize($filename)) {
                /*                 if( ! $f = @fopen( $remoteSite . '/ayoola/framework/installer.tar.gz', 'r' ) )
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
                if (!$f = fetchLink($remoteSite . '/widgets/Application_Backup_GetInstallation/?pc_core_only=1', array('time_out' => 300))) {
                    $badnews .= '<p>The installation archive is missing. We also tried to connect to the internet to download it but application could not connect to the internet. Please ensure that allow_fopen_url is not switched off in your server configuration.</p>';
                    $badnews .= '<p>Please try copying the files back into your web root again and restart your installation. You may also resolve this issue by connecting to the internet.</p>';
                    break;
                }

                $bytes_written = file_put_contents($filename, $f);
                if ($bytes_written === false) {
                    $badnews .= "<p>PageCarton installer could not write downloaded data into <code class='annotated'>$filename</code></p>";
                }
            }

            if (!is_readable($filename)) {
                $badnews .= '<p>Downloaded application is not readable. Please ensure you have correct permissions set on <code class="annotated">' . APPLICATION_DIR . '</code>. This is where PageCarton is being installed.</p>';
                break;
            }
            $content .= '<h1>PageCarton Ready for Installation</h1>';
            $content .= '<p>PageCarton is now ready to be installed on your server. Just click on the button below to begin installation. This may take a few moments; do not click the button more than once.</p>';
            $content .= '<input value="Begin Installation" type="button" onClick="location.href=`?stage=' . InstallationStages::INSTALL . '`;this.value=`Please wait...`; this.readOnly =true; " />';
            break;

        case InstallationStages::INSTALL:
            if (!is_file($filename)) {
                header('Location: ?stage=' . InstallationStages::DOWNLOAD);
                exit();
            }
            foreach ($preserveList as $eachDir) {
                //    set_time_limit( 30 );
                $oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
                if (!is_file($oldEachDir)) {
                    //    reach out to old dir
                    $oldEachDir = $newDir . $dbDir . $eachDir;
                }
                if (is_file($oldEachDir)) {
                    $newEachDir = $temDir . $eachDir;
                    @mkdir(dirname($newEachDir), 0777, true);
                    @copy($oldEachDir, $newEachDir);
                }
            }
            header('Location: ?stage=' . InstallationStages::INSTALL_DELETE_DIR);
            exit();
            break;

        case InstallationStages::INSTALL_DELETE_DIR:

            //    clean all dirs first
            //    $dirsToDelete = array( '/library/', '/temp/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/', );

            //    dont clear temp because theres where our preservations are.
            $dirsToDelete = array('/library/', '/cache/', '/local_html/', '/application/databases/', '/application/configs/', '/application/modules/', '/application/functions/', '/application/pages/', '/application/documents/');
            foreach ($dirsToDelete as $eachDir) {
                //    set_time_limit( 30 );
                $eachDir = APPLICATION_DIR . $eachDir;
                @deleteDirectoryPlusContent($eachDir);
            }
            header('Location: ?stage=' . InstallationStages::INSTALL_EXTRACT);
            exit();
            break;

        case InstallationStages::INSTALL_EXTRACT:

            //    Open the downloaded ( and zipped ) framework and save it on the server.
            //    ini_set('max_execution_time', 200*60);
            $phar   = 'PharData';
            $backup = new $phar($filename);
            $backup->extractTo(APPLICATION_DIR, null, true);

            //    return preserved data
            //    ini_set('max_execution_time', 200*60);
            foreach ($preserveList as $eachDir) {
                //        ini_set('max_execution_time', 200*60);
                $oldEachDir = APPLICATION_DIR . $dbDir . $eachDir;
                $newEachDir = $temDir . $eachDir;
                @mkdir(dirname($oldEachDir), 0777, true);
                @copy($newEachDir, $oldEachDir);
                //        ini_set('max_execution_time', 200*60);
            }
            header('Location: ?stage=' . InstallationStages::INSTALL_COMPATIBILITY_FIX);
            exit();
            break;

        case InstallationStages::INSTALL_COMPATIBILITY_FIX:

            if (is_dir($newDir)) {

                //    check if we have application in the old dir, so we can copy to the new dir.
                if (@is_dir(@$oldAppPath)) {
                    $source = $oldAppPath;
                    $dest   = $newAppPath;
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
                        if (is_link($source)) {
                            return symlink(readlink($source), $dest);
                        }

                        // Simple copy for a file
                        if (is_file($source)) {
                            return copy($source, $dest);
                        }

                        // Make destination directory
                        if (!is_dir($dest)) {
                            mkdir($dest, $permissions, true);
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
                    xcopy($source, $dest);
                    rename($oldAppPath, $oldAppPath . '.old');
                }
            }
            header('Location: ?stage=' . InstallationStages::INSTALL_COPY_CONTROLLER);
            exit();
            break;

        case InstallationStages::INSTALL_COPY_CONTROLLER:

            //    Transfer the local_html files to the document root

            $filesToCopy = array(
                'index.php',
                '.htaccess',
                'web.config',
            );
            foreach ($filesToCopy as $eachFile) {
                $backupFile = $eachFile . '.pc_backup';
                if (is_file($eachFile) && !is_file($backupFile)) {
                    copy($eachFile, $backupFile);
                }
                file_put_contents($eachFile, file_get_contents(APPLICATION_DIR . DS . 'local_html' . DS . $eachFile));
                chmod($eachFile, 0644);
            }

            header('Location: ?stage=' . InstallationStages::INSTALL_FINALIZE);
            exit();

            break;
        case InstallationStages::INSTALL_FINALIZE:
            //    Get rid of the cache items. This help for compatibility.
            deleteDirectoryPlusContent($temDirMain);
            //    deleteDirectoryPlusContent( $baseAppPath . '/cache' );
            header('Location: ?stage=' . InstallationStages::INSTALL_COMPLETE);
            exit();
            break;

        case InstallationStages::INSTALL_COMPLETE:

            $content .= '<h1>Installation Completed</h1>';
            $content .= '<p>The latest PageCarton software has been loaded on your server. You are going to be able to personalize it in a few moments. Please ensure you complete the personalization in two easy steps.</p>';

            //    Check if we have mod-rewrite

            //    start PC
            //    include_once 'index.php';
            $protocol = 'http';
            if ($_SERVER['SERVER_PORT'] == 443 && !empty($_SERVER['HTTPS'])) {
                $protocol = 'https';
            }

            $urlToLocalInstallerFile = $protocol . '://' . $_SERVER['HTTP_HOST'] . $prefix . '/widgets?pc_clean_url_check=1';

            $modRewriteEnabled = get_headers($urlToLocalInstallerFile);
            $responseCode      = explode(' ', $modRewriteEnabled[0]);
            if (in_array('200', $responseCode)) {
                $content .= '<br /><input value="Proceed to Personalization" type="button" onClick = "location.href=`' . $prefix . '/widgets/name/Application_Personalization/`" />';
            } else {
                $content .= '<p>You do not have URL rewriting feature (e.g. mod-rewrite) on your webserver? PageCarton would work without it; But you would need to prefix your URLs with "index.php" when entering it on the web browser e.g. http://' . $_SERVER['HTTP_HOST'] . $prefix . '/index.php/page/url. On many of your pages, PageCarton will add this automatically.  </p>';
                //    $content .= '<p><a href="index.php/widgets/name/Application_Personalization/"> Proceed to Personalization...</a></p>';
                $content .= '<br /><input value="Proceed to Personalization" type="button" onClick = "location.href=`index.php/widgets/name/Application_Personalization/`" />';
            }
            //    Self destroy file
            @unlink($filename);//suppress warnings AARO refreshing the page (re-POSTing)
            //        $phar::unlinkArchive( $filename );
            //        unlink( $filename );

            break;

        case InstallationStages::SELFDESTRUCT:
            //    remove self
            if (@unlink(__FILE__)) {
                exit('1');
            } else {
                throw new Exception('INSTALLER COULD NOT SELF-DESTRUCT');
            }

            break;

        default:

            //    Upgrade installer before going ahead
            //    if( 'index.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )
            {
                //    don't replace controller.
                //    ONLY replace if its pc_installer.php
                //    header( 'Location: ' . {$prefix}/pc_installer.php );
            }
            if (is_writable($_SERVER['SCRIPT_FILENAME'])) {
                //    if( $f = @fopen( $remoteSite . '/pc_installer.php?do_not_highlight_file=1', 'r' ) )
                if ($f = fetchLink($remoteSite . '/pc_installer.php?do_not_highlight_file=1')) {
                    file_put_contents('pc_installer.php', $f);
                }
            } else {
                //    Upgrade no longer required to cater for offline installers
                //      $badnews .= '<p>The installer could not be upgraded. It need to be refreshed before installation. Make the installer file writable. The part is - ' . $_SERVER['SCRIPT_FILENAME'] . '</p>';
            }
            //    header( 'Location: ?stage=start' );
            header("Location: {$prefix}/pc_installer.php?stage=" . InstallationStages::START);
            exit();
            break;

    }
}

//let's not start with errors
if (!is_null($badnews) && !empty($badnews) && (empty($_GET['stage']) || $_GET['stage'] == InstallationStages::START)) {
    $badnews = null;
}

/**
 * Class to ensure SOT (source-of-truth) identifiers for installation stages - The const props ensure that we don't introduce bugs when we have any reason to change names for any of the stage verbatim,
 * This will also make UI progress notification to be agnostic to the name of any of the installation stages
 */
class InstallationStages
{
    const START                     = "start";
    const DOWNLOAD                  = "download";
    const INSTALL                   = "install";
    const INSTALL_DELETE_DIR        = "install-delete-dir";
    const INSTALL_EXTRACT           = "install-extract";
    const INSTALL_COMPATIBILITY_FIX = "install-compatibility-fix";
    const INSTALL_COPY_CONTROLLER   = "install-copy-controller";
    const INSTALL_FINALIZE          = "install-finalize";
    const INSTALL_COMPLETE          = "install-complete";
    const SELFDESTRUCT              = "selfdestruct";

}

//Not all installation stages are visible to the user
$gui_steps = array(
    InstallationStages::START            => "Licence Agreement",
    InstallationStages::DOWNLOAD         => "Files Installation",
    InstallationStages::INSTALL_COMPLETE => "Proceed to Personalization",
);

/**
 * Fetches a remote link. Lifted from Ayoola_Abstract_Viewable
 *
 * @param string Link to fetch
 * @param array Settings
 */
function fetchLink($link, array $settings = null)
{
    $request = curl_init($link);
    //        curl_setopt( $request, CURLOPT_HEADER, true );
    curl_setopt($request, CURLOPT_URL, $link);

    //    dont check ssl
    curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($request, CURLOPT_USERAGENT, @$settings['user_agent'] ?: __FILE__);
    curl_setopt($request, CURLOPT_AUTOREFERER, true);
    curl_setopt($request, CURLOPT_REFERER, @$settings['referer'] ?: $link);
    if (@$settings['destination_file']) {
        $fp = fopen($settings['destination_file'], 'w');
        curl_setopt($request, CURLOPT_FILE, $fp);
        curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($request, CURLOPT_HEADER, 0);
    } else {
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    }
    //        curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, @$settings['follow_redirect'] === false ? false : true); //    By default, we follow redirect
    curl_setopt($request, CURLOPT_CONNECTTIMEOUT, @$settings['connect_time_out'] ?: 9000); //    Max of 1 Secs on a single request
    curl_setopt($request, CURLOPT_TIMEOUT, @$settings['time_out'] ?: 9000); //    Max of 1 Secs on a single request
    if (@$settings['post_fields']) {
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $settings['post_fields']);
    }
    if (@$settings['raw_response_header']) {
        $headerBuff = fopen('/tmp/headers' . time(), 'w+');
        curl_setopt($request, CURLOPT_WRITEHEADER, $headerBuff);
    }
    if (is_array(@$settings['http_header'])) {
        curl_setopt($request, CURLOPT_HTTPHEADER, $settings['http_header']);
    }
    $response        = curl_exec($request);
    $responseOptions = curl_getinfo($request);

    // close cURL resource, and free up system resources
    curl_close($request);

    //    if( ! $response || $responseOptions['http_code'] != 200 ){ return false; }
    if (empty($settings['return_error_response'])) {
        if ($responseOptions['http_code'] != 200) {return false;}
    }
    if (@$settings['return_as_array'] == true) {
        if (@$settings['raw_response_header']) {
            rewind($headerBuff);
            $headers                                 = stream_get_contents($headerBuff);
            @$responseOptions['raw_response_header'] = $headers;
        }
        $response = array('response' => $response, 'options' => $responseOptions);
    }
    return $response;
}

/**
 * Attempts to remove dirs recursively in case
 *
 * @param string Path to Directory to be deleted
 * @return void
 */
function deleteDirectoryPlusContent($eachDir)
{
    if (!is_dir($eachDir)) {
        //    throw new Ayoola_Doc_Exception("$eachDir is not a directory");
        //    echo $eachDir . ' not found...
        return false;
    }
    if (substr($eachDir, strlen($eachDir) - 1, 1) != '/') {
        $eachDir .= '/';
    }
    $dotfiles    = glob($eachDir . '.*', GLOB_MARK);
    $insideFiles = glob($eachDir . '*', GLOB_MARK);
    $insideFiles = array_merge($insideFiles, $dotfiles);
    foreach ($insideFiles as $insideFile) {
        //    set_time_limit( 30 );
        if (basename($insideFile) == '.' || basename($insideFile) == '..') {
            continue;
        } else if (is_dir($insideFile)) {
            deleteDirectoryPlusContent($insideFile);
        } else {
            unlink($insideFile);
        }
    }
    @rmdir($eachDir);
    return !is_dir($eachDir);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="">
<title>PageCarton Installation</title>

<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">

<style>
body{
	max-width: 720px;
	margin: auto auto;
}

p{
	margin-top: 1em;
	margin-bottom: 1em;
	text-align:justify;
}

textarea{
	width: 100%;
	background: inherit;
	padding-top: 2em;
}

input[type="button"],
input[type="submit"]{
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
	margin: 2.5em auto;
    text-transform: uppercase;
    text-decoration: none;
}

input[type="button"]:hover,
input[type="submit"]:hover{
    background:linear-gradient(to bottom, #34CACA 5%, #30C9C9 100%);
    background-color:#34CACA;
}

h1{
    margin: auto;
}


/***************************************************************************
************************ P R O G R E S S   S T E P S ***********************
****************************************************************************/

.container {
  width: 100%;
  margin: 60px auto auto 70px;
}
.progressbar {
  margin: 0;
  padding: 0;
  counter-reset: step;
}
.progressbar li {
  list-style-type: none;
  width: 25%;
  float: left;
  font-size: 12px;
  position: relative;
  text-align: center;
  text-transform: uppercase;
  color: #7d7d7d;
}
.progressbar li:before {
  width: 30px;
  height: 30px;
  content: counter(step);
  counter-increment: step;
  line-height: 32px;
  border: 2px solid #7d7d7d;
  display: block;
  text-align: center;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  background-color: white;
}
.progressbar li:after {
  width: 100%;
  height: 2px;
  content: '';
  position: absolute;
  background-color: #7d7d7d;
  top: 15px;
  left: -50%;
  z-index: -1;
}
.progressbar li:first-child:after {
  content: none;
}
.progressbar li.active {
  color: green;
}
.progressbar li.active:before {
  border-color: #55b776;
}
.progressbar li.active + li:after {
  background-color: #55b776;
}

/***************************************************************************
************************ C O D E   A N N O T A T I O N *********************
****************************************************************************/

code {
	margin: 0;
	padding: 0;
	border: 0;
	font-weight: 500;
	vertical-align: baseline;
	box-sizing: inherit;
	font-family: monospace, sans-serif;
	-moz-osx-font-smoothing: auto;
	-webkit-font-smoothing: auto;
	font-family: Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
	line-height: 1.25;
	background-color: #f5f7fa;
	color: #ed6c63;
	font-size: 12px;
	padding: 1px 2px 2px;
}

code.annotated {
	background: rgba(238, 238, 238, 0.35);
	border-radius: 3px;
	padding: 10px;
	-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.125);
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.125);
	color: #f4645f;
	padding: 1px 5px;
	border-radius: 3px;
}

* {
	font-family: 'Roboto Condensed', sans-serif;
}

button,
input {
	font-family: inherit;
	font-size: 100%;
	line-height: 1.15;
	margin: 0;
}

button,
input {
	overflow: visible;
}

button {
	text-transform: none;
}

button {
	-webkit-appearance: button;
}

button::-moz-focus-inner {
	border-style: none;
	padding: 0;
}

button:-moz-focusring {
	outline: 1px dotted ButtonText;
}

*,
:after,
:before {
	box-sizing: inherit;
}

button {
	background: 0 0;
	padding: 0;
}

button:focus {
	outline: 1px dotted;
	outline: 5px auto -webkit-focus-ring-color;
}

input::-webkit-input-placeholder {
	color: inherit;
	opacity: .5;
}

input::-moz-placeholder {
	color: inherit;
	opacity: .5;
}

input:-ms-input-placeholder {
	color: inherit;
	opacity: .5;
}

input::-ms-input-placeholder {
	color: inherit;
	opacity: .5;
}

input::placeholder {
	color: inherit;
	opacity: .5;
}

button {
	cursor: pointer;
}

a {
	color: inherit;
	text-decoration: inherit;
}

button,
input {
	padding: 0;
	line-height: inherit;
	color: inherit;
}


.bg-white {
	background-color: #fff;
}

.bg-gray-200 {
	background-color: #edf2f7;
}

.bg-gray-500 {
	background-color: #a0aec0;
}

.bg-teal-400 {
	background-color: #4fd1c5;
}

.hover\:bg-teal-600:hover {
	background-color: #319795;
}

.border-white {
	border-color: #fff;
}

.border-teal-500 {
	border-color: #38b2ac;
}

.rounded {
	border-radius: .25rem;
}

.rounded-full {
	border-radius: 9999px;
}

.border-2 {
	border-width: 2px;
}

.border {
	border-width: 1px;
}

.border-b-2 {
	border-bottom-width: 2px;
}

.border-b-4 {
	border-bottom-width: 4px;
}

.border-t {
	border-top-width: 1px;
}

.border-l {
	border-left-width: 1px;
}

.cursor-pointer {
	cursor: pointer;
}

.block {
	display: block;
}

.inline {
	display: inline;
}

.flex {
	display: flex;
}

.hidden {
	display: none;
}

.flex-row {
	flex-direction: row;
}

.flex-row-reverse {
	flex-direction: row-reverse;
}

.flex-col {
	flex-direction: column;
}

.flex-wrap {
	flex-wrap: wrap;
}

.items-center {
	align-items: center;
}

.self-start {
	align-self: flex-start;
}

.self-center {
	align-self: center;
}

.justify-start {
	justify-content: flex-start;
}

.justify-end {
	justify-content: flex-end;
}

.justify-center {
	justify-content: center;
}

.justify-between {
	justify-content: space-between;
}

.justify-around {
	justify-content: space-around;
}

.float-right {
	float: right;
}

.font-medium {
	font-weight: 500;
}

.font-semibold {
	font-weight: 600;
}

.font-bold,
.hover\:font-bold:hover {
	font-weight: 700;
}


.m-4 {
    margin: 1rem
}

.m-6 {
    margin: 1.5rem
}
.m-6 {
    margin: 1.5rem
}

.mx-auto {
	margin-left: auto;
	margin-right: auto;
}

.mr-1 {
	margin-right: .25rem;
}

.mt-4 {
	margin-top: 4rem;
}

.mb-8 {
	margin-bottom: 2rem;
}

.p-2 {
	padding: .5rem;
}

.p-4 {
	padding: 1rem;
}

.p-8 {
	padding: 2rem;
}

.py-2 {
	padding-top: .5rem;
	padding-bottom: .5rem;
}

.px-2 {
	padding-left: .5rem;
	padding-right: .5rem;
}

.py-3 {
	padding-top: .75rem;
	padding-bottom: .75rem;
}

.px-3 {
	padding-left: .75rem;
	padding-right: .75rem;
}

.px-4 {
	padding-left: 1rem;
	padding-right: 1rem;
}

.pt-2 {
	padding-top: .5rem;
}

.pb-3 {
	padding-bottom: .75rem;
}

.pt-4 {
	padding-top: 1rem;
}

.pr-4 {
	padding-right: 1rem;
}

.pt-8 {
	padding-top: 2rem;
}

.fixed {
	position: fixed;
}

.absolute {
	position: absolute;
}

.relative {
	position: relative;
}


.text-center {
	text-align: center;
}

.text-white {
	color: #fff;
}

.text-gray-500 {
	color: #a0aec0;
}

.text-gray-600 {
	color: #718096;
}

.text-gray-700 {
	color: #4a5568;
}

.text-gray-800 {
	color: #2d3748;
}

.text-red-400 {
	color: #fc8181;
}

.text-teal-400 {
	color: #4fd1c5;
}

.text-teal-500 {
	color: #38b2ac;
}

.text-pink-500 {
	color: #ed64a6;
}

.text-sm {
	font-size: .875rem;
}

.text-base {
	font-size: 1rem;
}

.text-xl {
	font-size: 1.25rem;
}

.text-2xl {
	font-size: 1.5rem;
}

.text-4xl {
	font-size: 2.25rem;
}

.no-underline {
	text-decoration: none;
}

.w-full {
	width: 100%;
}


.shadow {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)
}

.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05)
}

.shadow-outline {
    box-shadow: 0 0 0 3px rgba(66,153,225,.5)
}


.leading-none {
    line-height: 1
}

.leading-tight {
    line-height: 1.25
}

.leading-normal {
    line-height: 1.5
}

.leading-spaced {
    line-height: 2.5
}

.flex {
    display: flex
}

.inline-flex {
    display: inline-flex
}

.hidden {
    display: none
}

.flex-row {
    flex-direction: row
}

.flex-row-reverse {
    flex-direction: row-reverse
}

.flex-col {
    flex-direction: column
}

.flex-col-reverse {
    flex-direction: column-reverse
}

.flex-wrap {
    flex-wrap: wrap
}

.items-start {
    align-items: flex-start
}

.items-center {
    align-items: center
}

.self-start {
    align-self: flex-start
}

.self-center {
    align-self: center
}

.self-stretch {
    align-self: stretch
}

.justify-start {
    justify-content: flex-start
}

.justify-end {
    justify-content: flex-end
}

.justify-center {
    justify-content: center
}

.justify-between {
    justify-content: space-between
}

.justify-around {
    justify-content: space-around
}
</style>

</head>

<body class="bg-gray-200 text-gray-700 leading-normal">

    <div class="container">
		<ul class="progressbar">
            <?php
            foreach ($gui_steps as $step => $text ) {
    			echo "<li class='" . ($_GET['stage'] == $step ? 'active' : '') . "'> $text </li>";
            }
            ?>
		</ul>
    </div>

    <br>
    <br>
    <br>

    <div class="bg-white shadow-md flex flex-row flex-wrap items-center text-justify rounded m-4 leading-spaced p-8 mt-4">
        <?php
        if ($badnews) {
            $a = '<h1>ERROR: ' . @$_GET['badnews'] . '</h1>';
            $a .= '<p>While installing PageCarton, and error was encountered. Please contact your site administrator to find out if your system has the minimum requirement for installation.</p>';
            $a .= $badnews;
            $a .= '<br /> <input value="Try Again" type="button" onClick = "location.href=`?stage=' . InstallationStages::START . '`" />';
            echo $a;
        } else {
            echo $content;
            // echo '<p style="font-size:x-small; margin: 1em; text-align:center;">PageCarton version 1.7.5</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
exit();
?>