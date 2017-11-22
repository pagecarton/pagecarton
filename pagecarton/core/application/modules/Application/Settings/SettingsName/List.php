<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_SettingsName_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Settings_SettingsName_Abstract
 */
 
require_once 'Application/Settings/SettingsName/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_SettingsName_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_SettingsName_List extends Application_Settings_SettingsName_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected $_sortColumn = 'settingsname_title'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Site Options'; 
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->setViewContent( $this->getList() );
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
//		$data = 
		$list->setData( $this->getDbData() );
		$this->setIdColumn( 'settingsname_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No settings on this application <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Creator/">Create</a>' );
		$list->createList(  
			array(
				'Category' => array( 'field' => 'settingsname_title', 'value' => '<a href="javascript:"  onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/' . $this->getIdColumn() . '/%KEY%/\' );">[%FIELD%]</a>' ), 
			//	'document_url' => '<img src="%FIELD%" width="32" height="32" />', 
				'-' => '<a title="Change Settings" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_SettingsName_Editor/' . $this->getIdColumn() . '/%KEY%/">-</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_SettingsName_Delete/' . $this->getIdColumn() . '/%KEY%/">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
