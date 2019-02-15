<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Wallet   Ayoola
 * @package    Application_Wallet_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Wallet_Abstract
 */
 
require_once 'Application/Wallet/Abstract.php';


/**
 * @Wallet   Ayoola
 * @package    Application_Wallet_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Wallet_Delete extends Application_Wallet_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['Wallet_title'],  'Delete Wallet' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Wallet deleted successfully', true ); }
		}
		catch( Application_Wallet_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
