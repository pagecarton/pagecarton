<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Delete extends Application_Subscription_Abstract
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
			$this->createDeleteForm( $data['subscription_name'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( !  $this->deleteDb( false ) ){ return false; }
			$this->setViewContent( 'Subscription package deleted successfully', true );
			try
			{
				$url = '/onlinestore/subscribe/get/subscription_name/' . $data['subscription_name'] . '/';
				$where = array( 'link_url' => $url );
				$link = new Application_Link();
				$link->delete( $where );
			}
			catch( Exception $e ){ return false; }
		}
		catch( Application_Subscription_Exception $e ){ return false; }

    } 
	// END OF CLASS
}
