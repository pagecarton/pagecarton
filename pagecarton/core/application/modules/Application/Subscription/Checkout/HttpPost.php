<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_HttpPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HttpPost.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_HttpPost
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_HttpPost extends Application_Subscription_Checkout_Abstract_HtmlForm
{
		
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => 'ALL', 'blacklist' => '' ); 

		
    /**
     * Creates the request
     * 
     * @param void
     * @return array
     */
	protected static function buildRequest()
    {
		return array();
    } 
	// END OF CLASS
}
