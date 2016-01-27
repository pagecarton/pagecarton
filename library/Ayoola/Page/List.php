<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_List  extends Ayoola_Page_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'url';
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
	//	$this->setViewContent( '<h3>PAGE OPTIONS:</h3>' );		
	//	$this->setViewContent( '<h4><a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/" title="Create a new page">+</a> | <a rel="spotlight;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/" title="Sanitize all pages. Recreate all page templates.">o</a></h4>' );		
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
		$list->listTitle = 'List of Pages on this Application';
		$list->showSearchBox = true;
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
										'Sanitize' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Sanitize/\' );" title="Sanitize all pages. Recreate all page templates.">Sanitize Pages </span>',
										'Settings' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/\' );" title="Advanced Page Settings.">Page Settings</a>' 
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no pages on this application, please add a page.' );
		
		// _Array function will convert this to array for me
		require_once 'Ayoola/Page.php';
/* 		$list->createList
		(
			'url=><a rel="spotlight" title="%FIELD%" href="http://' . DOMAIN . '%FIELD%">%FIELD%</a>::
			title=><a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>:: 
			-=><a rel="spotlight" href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/edit/layout/get/page_id/%KEY%/">-</a>::
			x=><a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>'
		);
 */		$list->createList(  
			array(
				'url' => '<a title="Preview" onClick="ayoola.spotLight.showLinkInIFrame( \'http://' . DOMAIN . '' . Ayoola_Application::getUrlPrefix() . '%FIELD%\' );" href="javascript:;">' . Ayoola_Application::getUrlPrefix() . '%FIELD%</a>', 
				' ' => '<a rel="spotlight" href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/page/edit/layout/get/page_id/%KEY%/">Editor</a>', 
				'  ' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		return $list;
    } 
}
