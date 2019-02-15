<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Form_Requirement_Abstract
 */
 
require_once 'Ayoola/Form/Requirement/Abstract.php';


/**
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Requirement_List extends Ayoola_Form_Requirement_Abstract
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
		$list->listTitle = 'List of Form Requirements on this Application';
		$list->setData( $this->getDbData() );
	//	$this->setIdColumn( 'requirement_name' );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created any Form Requirement yet' );
		$list->createList(  
			array(
				'requirement_label' => '<a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_Editor/?' . $this->getIdColumn() . '=%KEY%">[%FIELD%]</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
