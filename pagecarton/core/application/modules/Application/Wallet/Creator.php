<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Wallet   Ayoola
 * @package    Application_Wallet_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Wallet_Abstract
 */
 
require_once 'Application/Wallet/Abstract.php';


/**
 * @Wallet   Ayoola
 * @package    Application_Wallet_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Wallet_Creator extends Application_Wallet_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
/* 	protected function init()
    {
		$this->createForm( 'Continue', 'Create an Wallet' );
		$this->setViewContent( $this->getForm()->view(), true );
	//	if( $this->getForm()->getValues() ){ return false; }
		if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent(  '' . self::__( '<p>Wallet created successfully</p>' ) . '', true  );
   } 
 */	// END OF CLASS
}
