<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Domain_Abstract
 */
 
require_once 'Application/Domain/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_List extends Application_Domain_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Domain'; 
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$this->setViewContent( $this->getList(), true );
		}
		catch( Exception $e ){ $this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  );; }
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
		$list->listTitle = self::getObjectTitle();
		$list->setListOptions( 
								array( 
										'availability' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Registration_Whois_List/\' );" title="Domain Availability Tools">Domain Availability Tools</a>',
										'Domain Options' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Domains/\' );" title="Domain Availability Tools">Domain Options</a>',
									) 
							);
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'advert_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no domains on this application yet.' );  
		$list->createList(  
			array(
				'domain_name' => '%FIELD% <a style="font-size:smaller;" title="Click to preview" rel="shadowbox;" href="http://%FIELD%' . Ayoola_Application::getUrlPrefix() . '/">preview</a>',  
				'admin' => array( 'field' => 'domain_name', 'value' => '<a title="Click to preview" rel="shadowbox;" href="http://%FIELD%' . Ayoola_Application::getUrlPrefix() . '/pc-admin">Admin Panel</a>' ),    
				' ' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
				'   ' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Domain_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
