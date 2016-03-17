<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Whitelist_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Ayoola_Api_Whitelist_Abstract
 */
 
require_once 'Ayoola/Api/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Whitelist_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Api_Whitelist_List  extends Ayoola_Api_Whitelist_Abstract
{
	
 		
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
	//	$list->listTitle = 'API Whitelist';
		$list->setData( $this->getDbData() );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No API links have been added to the whitelist.' );
		
		// _Array function will convert this to array for me
		require_once 'Ayoola/Api.php';
		$list->createList
		(
			array
			(
	//			'api_label' => '[%FIELD%]',
				'api_label' => '<a title="Click to edit" rel="shadowbox;height=300px;width=600px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Api_Whitelist_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>',
				'X' => '<a title="Delete" rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Api_Whitelist_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>',
			)
		);
		return $list;
    } 

}
