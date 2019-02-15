<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Default
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DirectDeposit.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Default
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Default extends Application_Subscription_Checkout_Abstract
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => '', 'blacklist' => '' );

    /**
     * Plays the process
     * 
     */
	protected function init()
    {		
		if( ! $values = self::getStorage()->retrieve() ){ return; }
		$this->setViewContent( '<a class="pc-btn" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Subscription_Checkout_Confirmation/get/api/' . __CLASS__ . '/status/1/"> Confirm order</a>' );  
	//	$this->setViewContent( '</p>' );
		
		
    } 
	// END OF CLASS
}
