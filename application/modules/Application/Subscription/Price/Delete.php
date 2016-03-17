<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Price_Abstract
 */
 
require_once 'Application/Subscription/Price/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Price_Delete extends Application_Subscription_Price_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Price_Exception $e ){ return false; }
		if( ! $data = $this->getIdentifierData() ){ return false; }
		$this->createDeleteForm( $data['price'] );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->deleteDb( false ) ){ $this->setViewContent( 'Price deleted successfully', true ); }
    } 
	// END OF CLASS
}
