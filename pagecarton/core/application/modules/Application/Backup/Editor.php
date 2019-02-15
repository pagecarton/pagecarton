<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
		$this->createForm( 'Export', '', $data );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
	//	$this->setViewContent( '<span class="boxednews goodnews">Success.</span>', true );
	//	$this->setViewContent( '<span class="boxednews greynews">Backup file saved successfully.</span>' );
//		$this->setViewContent( '', true );
	//	var_export( $values );
	//	if( $values['backup_type'] === 'export' )
		{
			$values['export_information']['export_expiry'] = $values['export_expiry'];
			$values['export_information']['time'] = time();
			$this->setViewContent( '<p class="goodnews">Export URL is "http://' . DOMAIN . '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Backup_Export/?backup_id=' . $data['backup_id'] . '"</p>', true );
		}
		if( ! $this->updateDb( $values ) ){ return false; }
		
    } 
	// END OF CLASS
}
