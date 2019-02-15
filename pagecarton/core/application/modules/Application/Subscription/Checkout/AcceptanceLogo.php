<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AcceptanceLogo.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Checkout_Abstract
 */
 
require_once 'Application/Subscription/Checkout/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_AcceptanceLogo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_AcceptanceLogo extends Application_Subscription_Checkout_Abstract
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Subscription_Checkout_CheckoutOption';

    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getAcceptanceLogos(), true );
	//	$this->setViewContent( $this->getApi(), true );
    } 
	
    /**
     * Returns the acceptance logos
     * 
     */
	public function getAcceptanceLogos()
    {
		$logos = null;
		foreach( $this->getDbData() as $value )
		{
		//	var_export( $value );
			$logos .= htmlspecialchars_decode( $value['checkoutoption_logo'] );
		}
		return $logos;
    } 
	// END OF CLASS
}
