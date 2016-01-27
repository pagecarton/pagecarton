<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Level_Abstract
 */
 
require_once 'Application/Subscription/Level/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Level_Delete extends Application_Subscription_Level_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Level_Exception $e ){ return false; }
		if( ! $data = $this->getIdentifierData() ){ return false; }
		$this->createDeleteForm( $data['subscriptionlevel_name'] );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->deleteDb( false ) ){ $this->setViewContent( 'Product category deleted successfully.', true ); }
    } 
	// END OF CLASS
}
