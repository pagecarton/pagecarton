<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Database_Account_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Database_Account_Abstract
 */
 
require_once 'Application/Database/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Database_Account_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Database_Account_Delete extends Application_Database_Account_Abstract
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
			$this->createConfirmationForm( 'Delete ',  'Delete Database' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Delete 
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Database account deleted successfully.', true ); }
		}
		catch( Application_Database_Account_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
