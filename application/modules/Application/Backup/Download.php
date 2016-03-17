<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Download.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Download extends Application_Backup_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Backup_Exception $e ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }
		$this->setViewContent( 'You download will start in a moment', true );
/* 		$this->createConfirmationForm( 'Download ' . $data['backup_name'],  'Download the backup. Be aware that it is a security risk to keep this file in a loose location' );
 */		
	//	if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	var_export( $data['backup_filename'] );
		$doc = new Ayoola_Doc( array( 'option' => $data['backup_filename'] ) );			
	//	var_export( $data['backup_filename'] );
	//	$doc->setParameter( array( 'option' => $data['backup_filename'] ) );
		$doc->download();
    } 
	// END OF CLASS
}
