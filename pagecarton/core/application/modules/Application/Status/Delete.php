<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Status   Ayoola
 * @package    Application_Status_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Status_Abstract
 */
 
require_once 'Application/Status/Abstract.php';


/**
 * @Status   Ayoola
 * @package    Application_Status_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status_Delete extends Application_Status_Abstract
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
			$this->createConfirmationForm( 'Delete ',  'Delete Status' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent(  '' . self::__( 'Status deleted successfully' ) . '', true  ); }
		}
		catch( Application_Status_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
