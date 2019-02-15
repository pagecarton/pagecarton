<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Backup_Export
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Export.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Backup_Export
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Export extends Application_Backup_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Backup_Exception $e ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }
	//	var_export( time() );
	//	var_export( '' );
	//	var_export( intval( @$data['export_information']['export_expiry'] ) + intval( @$data['export_information']['time'] ) );
	//	exit();
		
		
		
		if( self::hasPriviledge() || ( time() < intval( @$data['export_information']['export_expiry'] ) + intval( @$data['export_information']['time'] ) ) )
		{
			$doc = new Ayoola_Doc( array( 'option' => $data['backup_filename'] ) );			
		//	var_export( $data['backup_filename'] );
		//	$doc->setParameter( array( 'option' => $data['backup_filename'] ) );
			$doc->download();
		}
		else
		{
		//	var_export( intval( @$data['export_information']['export_expiry'] ) + intval( @$data['export_information']['time'] ) );
			$this->setViewContent( '<div class="boxednews greynews">Export expire.</div> <div class="boxednews greynews">' . ( time() - intval( @$data['export_information']['export_expiry'] ) + intval( @$data['export_information']['time'] ) ) . '</div>', true );
		//	$this->setViewContent( '<div class="boxednews greynews">Export expire.</div> <div class="boxednews greynews"><a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Backup_Download/?backup_id=' . $data['backup_id'] . '">Download It!</a></div>', true );
		}
    } 
	// END OF CLASS
}
