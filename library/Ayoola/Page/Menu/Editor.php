<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Editor extends Ayoola_Page_Menu_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Edit Menu', 'Edit ' . $identifierData['menu_name'], $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->updateDb() )
		{ 
			$menuInfo = $this->getDbTable()->selectOne( null, array( 'menu_name' => $identifierData['menu_name'] ) );
			$this->setViewContent( '<span class="boxednews normalnews centerednews">Menu information saved successfully.</span>', true ); 
			$this->setViewContent( '<a href="/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_List/?menu_id=' . $menuInfo['menu_id'] . '" class="boxednews goodnews centerednews">Manage options</a>' );     
		}
    } 
	// END OF CLASS
}
