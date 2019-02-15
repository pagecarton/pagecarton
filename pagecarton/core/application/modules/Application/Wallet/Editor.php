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
			if( ! $data = self::getIdentifierData() ){ null; }
/* 			if( empty( $data['username'] ) )
			{
				$data['username'] = $this->getIdentifier( 'username' );
				
				//	Check the user
				$class = new Application_User_Editor();
				$class->setIdentifier( array( 'username' => $data['username'] ) );
				$userInfo = $class->getIdentifierData();
				
				//	Populate the Ayoola_Access_AccessInformation
				$this->getDbTable()->insert( $userInfo );
			}
 */		//	var_export( $data );
		//	var_export(  );
			$this->createForm( 'Update', 'Update wallet balance for ' . ( @$data['username'] ? : $this->getIdentifier( 'username' ) ), $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			// update access
		//	Ayoola_Access::setAccessInformation( $values );

			$values = $values + $data;
		//	var_export( $data );
			if( Ayoola_Access::setAccessInformation( $values ) ){ $this->setViewContent( 'Wallet balance updated successfully.', true ); }
		}
		catch( Application_Wallet_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
