<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Delete extends Application_Backup_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['backup_name'],  'Delete Backup Information and Files' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent( 'Backup deleted successfully', true ); 
				if( ! is_file( $data['backup_filename'] ) ){ throw new Application_Backup_Exception( 'File does not exist' ); } 
				unlink( $data['backup_filename'] );
			//	Ayoola_Phar_Data::unlinkArchive( $data['backup_filename'] );
			}
		}
		catch( Application_Backup_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
