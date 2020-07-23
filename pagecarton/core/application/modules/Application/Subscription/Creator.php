<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Creator extends Application_Subscription_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Add', 'Add a new product or service.' );
		$this->setViewContent( $this->getForm()->view(), true );
		$values = $this->getForm()->getValues();
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$access = new Ayoola_Access();
		$userInfo = $access->getUserInfo();
		$values['subscription_name'] = strtolower( $filter->filter( $values['subscription_label'] ) );
		if( ! $this->insertDb( $values ) ){ return false; }
		
		$this->setViewContent(  '' . self::__( 'Product or service added successfully' ) . '', true  );
		//	Create Sitemap link for SEO
	//	var_export( $values );
		try
		{
			$url = '/onlinestore/subscribe/get/subscription_name/' . $values['subscription_name'] . '/';
			$linkValues = array
			( 
				'link_name' => strtolower( $values['subscription_name'] ), 'link_url' => $url, 'link_domain' => DOMAIN, 'link_priority' => 8
			);
			$link = new Application_Link();
			$link->insert( $linkValues );
		}
		catch( Exception $e ){ return false; }
    } 
	// END OF CLASS
}
