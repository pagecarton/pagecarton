<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Domain_Registration_Whois_Abstract
 */
 
require_once 'Application/Domain/Registration/Whois/Abstract.php';


/**
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Whois_List extends Application_Domain_Registration_Whois_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getList(), true );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Domain availability lookup information list';
		$list->setListOptions( 
								array( 
				//						'availability' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'/tools/classplayer/get/object_name/Application_Domain_Registration_Whois_List/\' );" title="Domain Availability Tools">Domain Availability Tools</span>',
									) 
							);
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'whois_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created a whois list for domain registration.' );
		$currency = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' ) ? : '$';
		$list->createList(  
			array(
				'extension' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Whois_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
		//		'server' => null, 
				'price' => '<a rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Price_Editor/?' . $this->getIdColumn() . '=%KEY%">' . $currency . '</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Whois_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
