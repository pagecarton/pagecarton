<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Download   Ayoola
 * @package    Application_Download_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Download_Abstract
 */
 
require_once 'Application/Download/Abstract.php';


/**
 * @Download   Ayoola
 * @package    Application_Download_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Download_Delete extends Application_Download_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['Download_title'],  'Delete Download' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Download deleted successfully', true ); }
		}
		catch( Application_Download_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
