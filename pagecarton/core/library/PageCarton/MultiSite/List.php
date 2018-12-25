<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_MultiSite_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_MultiSite_List extends PageCarton_MultiSite_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Multi-site Manager'; 

    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = self::getObjectTitle();
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
										'Domain Manager' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_List/\' );" title="">Domain Manager </a>',
							//			'Sub Domains' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_SubDomainList/\' );" title="">Sub Domains</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no other sites created yet.' );
		
		$list->createList
		(
			array(
                    'Site' => array( 'field' => 'directory', 'value' =>  '<a style="font-size:smaller;" target="_blank" href="' . Ayoola_Page::getRootUrl() . '%FIELD%">' . Ayoola_Page::getRootUrl() . '%FIELD%</a>' ), 
                    '  ' => array( 'field' => 'directory', 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;" href="' . Ayoola_Page::getRootUrl() . '%FIELD%' . Ayoola_Application::getUrlPrefixController() .  '/pc-admin">Admin Panel</a>' ), 
               //     ' ' => array( 'field' => 'directory', 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;" href="' . Ayoola_Page::getRootUrl() . '%FIELD%' . Ayoola_Application::getUrlPrefixController() .  '/tools/classplayer/get/name/Application_Personalization">Personalize</a>' ), 
                    array( 'field' => 'directory', 'value' =>  '<a style="font-size:smaller;" rel="shadowbox;" href="' . Ayoola_Page::getRootUrl() . '%FIELD%' . Ayoola_Application::getUrlPrefixController() .  '/tools/classplayer/get/name/PageCarton_NewSiteWizard">New Website Wizard</a>' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_MultiSite_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;   
    } 
	// END OF CLASS
}
