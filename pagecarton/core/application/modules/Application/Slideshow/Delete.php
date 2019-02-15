<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Slideshow_Abstract
 */
 
require_once 'Application/Slideshow/Abstract.php';


/**
 * @Slideshow   Ayoola
 * @package    Application_Slideshow_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_Delete extends Application_Slideshow_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['slideshow_name'],  'Delete Slideshow' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Slideshow deleted successfully', true ); }
		}
		catch( Application_Slideshow_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
