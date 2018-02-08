<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Price_Abstract
 */
 
require_once 'Application/Subscription/Price/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Price_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Price_Creator extends Application_Subscription_Price_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscriptionlevel_id' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$table = Application_Subscription_SubscriptionLevel::getInstance();
		if( ! $data = $table->selectOne( null, $this->getIdentifier() ) ){ return false; }
		$this->createForm( 'Add', 'Add a new Price for category "' . $data['subscriptionlevel_name'] . '"' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->insertDb() ){ $this->setViewContent( 'Price added for product category successfully.', true ); }
    } 
	// END OF CLASS
}
