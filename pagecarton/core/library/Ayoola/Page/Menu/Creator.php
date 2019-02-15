<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
		$this->createForm( 'Continue...', 'Create a new menu' );
		$this->setViewContent( $this->getForm()->view(), true );	
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$filter = new Ayoola_Filter_Name();
		$filter->replace = '-';
		$values['menu_name'] = strtolower( $filter->filter( $values['menu_label'] ) );

		if( ! empty( $_REQUEST['menu_name'] ) )
		{
			$filter = new Ayoola_Filter_Name();
			$values['menu_name'] = strtolower( $filter->filter( '' . $_REQUEST['menu_name'] ) );
		}
		if( $this->getDbTable()->selectOne( null, array( 'menu_name' => $values['menu_name'] ) ) )
		{
			$this->getForm()->setBadnews( 'Please enter a different name for this form. There is a form with the same name: ' . $values['menu_name'] );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
		
		if( $this->insertDb( $values ) )
		{ 
			$menuInfo = $this->getDbTable()->selectOne( null, array( 'menu_name' => $values['menu_name'] ) );
			$this->setViewContent( '<p class="goodnews">A new menu has been created successfully.</p>', true ); 
			$this->setViewContent( '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_List/?menu_id=' . $menuInfo['menu_id'] . '" class="pc-btn">Manage options</a>' );     
		}
    } 
	// END OF CLASS
}
