<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Delete extends Ayoola_Page_Menu_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createDeleteForm( $data['menu_name'] );
			$this->setViewContent( $this->getForm()->view(), true );
			
			//	Delete the meu style file
		//	var_export( Ayoola_Menu::getCssFilename( $subscriptionInfo['menu_name'] ) );
		//	@unlink( APPLICATION_PATH . DS . Ayoola_Menu::getCssFilename( $data['menu_name'] ) );
			if( $this->deleteDb( false ) ){ $this->setViewContent( '<p class="goodnews">Menu deleted successfully</p>', true ); }
		}
		catch( Application_Backup_Exception $e ){ return false; }
 	} 
	// END OF CLASS
}
