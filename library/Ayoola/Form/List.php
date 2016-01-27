<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php date time username $
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_List  extends Ayoola_Form_Abstract
{
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'form_title';
	
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
		$list->listTitle = 'List of Forms on this Website';
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
										'Form Requirements' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_List/\' );" title="Manage Form Requirements.">Form Requirements </span>',
										'Form Data' => '<span rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Inspect/\' );" title="Check form data.">Form Data </span>',
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'There are no forms on this website, please add a form.' );
		
		$list->createList
		(
			array(
				'form_title' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Editor/?' . $this->getIdColumn() . '=%KEY%">%FIELD%</a>', 
				'View Form' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_View/?' . $this->getIdColumn() . '=%KEY%">View Form</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
				)
		);
		return $list;
    } 
}
