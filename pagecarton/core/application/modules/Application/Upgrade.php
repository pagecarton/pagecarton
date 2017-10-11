<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Upgrade
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Upgrade.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Upgrade
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
   
class Application_Upgrade extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
		//	$this->setViewContent( '<p></p>' );
			$this->createConfirmationForm( 'Upgrade', '<p>Upgrade your PageCarton to the latest version. You are currently running version ' . PageCarton::VERSION . '</p>' ); 
			$this->setViewContent( '<h1 class="pc-heading">PageCarton Upgrade</h1>' );
			$this->setViewContent( '<p>Upgrade your PageCarton to the latest version. You are currently running version ' . PageCarton::VERSION . '. It is recommended that you do a backup of your application before you go ahead, do you want to do that now? </p>' );
			$this->setViewContent( '<div style="padding-top:1.5em;padding-bottom:1em;"><a onClick="ayoola.spotLight.showLinkInIFrame( this.href ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_Backup_List" class="pc-btn">Backup Now!</a>
			<a onClick="ayoola.spotLight.showLinkInIFrame( this.href ); return false;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . __CLASS__ . '?stage=upgrade" class="pc-btn pc-bg-color">Begin Upgrade!</a></div>' );
		//	$this->setViewContent( '' );
		//	$this->setViewContent( $this->getForm()->view() ); 
			$values = $this->getForm()->getValues();
	//		var_export( $values );
	//		var_export( isset( $_GET['stage'] ) ); 
			if( ! isset( $_GET['stage'] ) && ! $values )
			{
			//	if( ! $values = $this->getForm()->getValues() ){ return false; }
				return false;
			}
			$this->setViewContent( '<p></p>', true );
			
			//	Installer would do the whole process
		//	$documentsDir = Ayoola_Doc::getDocumentsDirectory();
		
			//	Upgrade to the innermost app path
			$documentsDir = APPLICATION_PATH . DS . DOCUMENTS_DIR;;
			$simpleFilename = Ayoola_Application::$installer;
			$installerFilenamePhp = $documentsDir . DS . $simpleFilename;   
		//	var_export( $installerFilenamePhp );
			if( ! is_file( $installerFilenamePhp ) )
			{
				$oldFilename = $documentsDir . DS . 'ayoola_framework_installer.php';
				$installerFilenamePhp = $oldFilename;  
			}  
		//	var_export( $installerFilenamePhp );
			if( ! is_file( $installerFilenamePhp ) )
			{
				$this->setViewContent( '<h1 href="" class="badnews blockednews">ERROR: </h1>' );
				$this->setViewContent( '<p href="" class="badnews blockednews">Installer not found. Upgrade aborted.</p>' );
				return false;
			}
		//	$this->setViewContent( '<a href="" class="goodnews blockednews">Continue Upgrade...</a>' );			
			include $installerFilenamePhp;
			//	make upgrader
		//	copy( $installerFilenamePhp, Ayoola_Application::$upgrader );
			
		//	header( 'Location: /' . Ayoola_Application::$upgrader );
		//	exit();
			
	//		file_get_contents( Ayoola_Application::$upgrader );
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
