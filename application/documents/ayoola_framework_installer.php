 <?php 
/**
 * Ayoola Developer Tool
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton Installer
 * @copyright  Copyright (c) 2011-2015 Ayoola Online Inc. (http://www.ayoo.la/)
 * @license    http://pagecarton.com/license.txt
 * @version    $Id: ayoola_framework_installer.php 09:18:2015 2:04pm  $joywealth
 */


    //    Always show error
    ini_set( 'display_errors', "1" );    
    
    //    We need memory for extraction
    ini_set( "memory_limit","512M" );

    //    Download and extraction can take a while
    set_time_limit( 0 );
    
    
    isset( $_SESSION ) ? null : session_start();
    $_SESSION['installer'] = sha1( "11" );
    
    //  Detect path to application
/*     $home = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) );
    $dir = realpath( dirname( $home ) );
 */    
    //    Using document root now
    $home = realpath( $_SERVER['DOCUMENT_ROOT'] );
    $dir = realpath( dirname( $home ) );
    
    //    Shrink everything into a single dir
    $dir = $dir . '/pagecarton';
	
	//	Create dir
	mkdir( $dir, 0777 );
    $_SESSION['installer'] = $dir;

    //    Retrieve the file
    $filename = 'ayoola_framework_installer.php.tar.gz';
    defined( 'APPLICATION_DIR' ) || define( 'APPLICATION_DIR', $dir );
//	var_export( APPLICATION_DIR ); 
   
//    use the next sequence to provide installation procedure.
    $content = null;
    $badnews = null;
    $remoteSite = 'http://production.pagecarton.com';
    switch( @$_GET['stage'] )
    {
        case 'licence':
            $content .= '<h1>Continue installation, only if you agree to be bound by the following license terms.</h1>';
            $content .= '<p>Please note that the license terms may change from time to time. Changes will always be on ' . $remoteSite . '/license.txt' . '.</p>';
            $content .= '<pre>' . file_get_contents( $remoteSite . '/license.txt' ) . '</pre>';
            $content .= '<p>Continuing with the installation will require an active internet connection. Internet is required to ensure that you install the latest version of PageCarton.</p>';
            $content .= '<input value="I agree" type="button" onClick = "location.href=\'?stage=download\'" />';
        break;
        case 'selfdestruct':
            //    remove self
            if( @unlink( __FILE__ ) )
            {
                exit( '1' );
            }
            else
            {
                throw new Exception( 'INSTALLER COULD NOT SELF-DESTRUCT' );
            }
        break;
        case 'download':
            //    Check if we can write in the application path
            if( ! is_writable( APPLICATION_DIR ) )
            { 
                $badnews .= '<p>Application directory is not writable. Please ensure you have correct permissions set on this directory (' . APPLICATION_DIR . '). This is where PageCarton will be installed.</p>';
                break;
            }
            //    Retrieve the file
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
            //    Open the downloaded ( and zipped ) framework and save it on the server.
            //    We must have PharData installed for this to work.
            $phar = 'PharData';
            $backup = new $phar( $filename );
            $backup->extractTo( APPLICATION_DIR, null, true );
            
            //    Transfer the local_html files to the document root
        //    $backup->extractTo( realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) ), array( 'local_html/index.php', 'local_html/.htaccess', ), true ); 
            file_put_contents( 'index.php', file_get_contents( $backup['local_html/index.php'] ) );
            file_put_contents( '.htaccess', file_get_contents( $backup['local_html/.htaccess'] ) );
            chmod( 'index.php', 0644 );
            chmod( '.htaccess', 0644 );

            //    Self destroy file
            unlink( $filename );
    //        $phar::unlinkArchive( $filename );
    //        unlink( $filename );
            //    Register this session as an administrative session
        //    $_SESSION['installer'] = true;
            
            $content .= '<h1>Installation Completed</h1>';
            $content .= '<p>The latest PageCarton software has been loaded on your server. You are going to be able to personalize it in a few moments.</p>';
            $content .= '<p>Please ensure you complete the personalization process. Expecially, ensure that you set an account up as the first administrative account. Your installation might be useless, if an administrative account is not set right now.</p>';
            $content .= '<input value="Continue Personalization" type="button" onClick = "location.href=\'tools/classplayer/get/object_name/Application_Personalization/\'" />';
        break;
        case 'error':
            $content .= '<h1>ERROR: ' . $_GET['badnews'] . '</h1>';
            $content .= '<p>While installing PageCarton, and error was encountered. Please contact your site administrator to find out if your system has the minimum requirement for installation.</p>';
            $content .= '<input value="I agree" type="button" onClick = "location.href=\'?stage=licence\'" />';
        break;
        case 'start':
            $content .= '<h1>Installing PageCarton</h1>';
            $content .= '<p>PageCarton is an amazing tool for creating powerful and robust websites. It is a free Content Management System which is based on <a href="http://pagecarton.com/">Ayoola Framework</a>. When installed on your server, PageCarton will help you publish contents to the internet.</p>';
            $content .= '<p>Follow these simple steps to install PageCarton on your server. To find out more about PageCarton, visit <a href="http://www.PageCarton.com/">the application homepage</a>. To continue installation, click the button below.</p>';
            $content .= '<input value="Begin Installation" type="button" onClick="location.href=\'?stage=licence\'" />';
        break;
        default:
		
			//	Upgrade installer before going ahead
			if( is_writable( $_SERVER['SCRIPT_FILENAME'] ) )
            { 
                if( ! $f = @fopen( $remoteSite . '/ayoola_framework_installer.php?do_not_highlight_file=1', 'r' ) )
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
				$badnews .= 'The installer could not be upgraded. It need to be refreshed before installation. Make the installer file writable. The part is - ' . $_SERVER['SCRIPT_FILENAME'];
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