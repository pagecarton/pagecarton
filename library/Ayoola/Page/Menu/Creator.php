<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Creator extends Ayoola_Page_Menu_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create Menu', 'Create a new menu' );
		$this->setViewContent( $this->getForm()->view(), true );	
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$values['menu_name'] = strtolower( $filter->filter( $values['menu_label'] ) );
		if( $this->insertDb( $values ) )
		{ 
			$menuInfo = $this->getDbTable()->selectOne( null, array( 'menu_name' => $values['menu_name'] ) );
			$this->setViewContent( '<span class="boxednews normalnews centerednews">A new menu has been created successfully.</span>', true ); 
			$this->setViewContent( '<a href="/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_List/?menu_id=' . $menuInfo['menu_id'] . '" class="boxednews goodnews centerednews">Manage options</a>' );     
		}
    } 
	// END OF CLASS
}
