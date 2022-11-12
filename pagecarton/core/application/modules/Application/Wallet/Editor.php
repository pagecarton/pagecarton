<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Wallet   Ayoola
 * @package    Application_Wallet_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Wallet_Abstract
 */
 
require_once 'Application/Wallet/Abstract.php';


/**
 * @Wallet   Ayoola
 * @package    Application_Wallet_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Wallet_Editor extends Application_Wallet_Abstract 
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
            if( ! $data = self::getIdentifierData() OR empty( $data['user_information'] ) ){ return false; }
            $data = $data['user_information'];

			$this->createForm( 'Update', 'Update wallet balance for ' . ( @$data['username'] ? : $this->getIdentifier( 'username' ) ), $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			// update access
		//	Ayoola_Access::setAccessInformation( $values );

			if( Ayoola_Access_Localize::info( array( 'username' => $data['username'], 'wallet_balance' => $values['wallet_balance'], ) ) ){ $this->setViewContent(  '' . self::__( 'Wallet balance updated successfully.' ) . '', true  ); }
		}
		catch( Application_Wallet_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
