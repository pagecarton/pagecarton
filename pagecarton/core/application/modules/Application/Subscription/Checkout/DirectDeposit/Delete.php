<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_DirectDeposit_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Checkout_DirectDeposit_Abstract
 */
 
require_once 'Application/Subscription/Checkout/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_DirectDeposit_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_DirectDeposit_Delete extends Application_Subscription_Checkout_DirectDeposit_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createDeleteForm( $data['account_name'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Account Info deleted successfully', true ); }
		}
		catch( Application_Subscription_Checkout_DirectDeposit_Exception $e ){ return false; }

    } 
	// END OF CLASS
}
