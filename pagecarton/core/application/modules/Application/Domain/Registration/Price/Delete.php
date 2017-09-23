<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Price_Abstract
 */
 
require_once 'Application/Domain/Registration/Price/Abstract.php';


/**
 * @price   Ayoola
 * @package    Application_Domain_Registration_Price_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Price_Delete extends Application_Domain_Registration_Price_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['extension'],  'Delete price for ' . $data['extension'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Item removed from price list successfully', true ); }
		}
		catch( Application_Domain_Registration_Price_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
