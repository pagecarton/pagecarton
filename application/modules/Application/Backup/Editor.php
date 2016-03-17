<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Backup_Abstract
 */
 
require_once 'Application/Backup/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Editor extends Application_Backup_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! $data = self::getIdentifierData() )
		{ 
			return false; 
		} 
	//	var_export( $data );
		$this->createForm( 'Edit', 'Edit a backup file...', $data );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$this->setViewContent( '<div class="boxednews goodnews">Success.</div>', true );
		if( $values['backup_type'] === 'export' )
		{
			$values['export_information']['export_expiry'] = $values['export_expiry'];
			$values['export_information']['time'] = time();
			$this->setViewContent( '<div class="boxednews greynews">Export URL is "/tools/classplayer/get/object_name/Application_Backup_Export/?backup_id=' . $data['backup_id'] . '"</div>' );
		}
		if( ! $this->updateDb( $values ) ){ return false; }
		$this->setViewContent( '<div class="boxednews greynews">Backup file saved successfully.</div>' );
//		$this->setViewContent( '', true );
		
    } 
	// END OF CLASS
}
