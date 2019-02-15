<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Edit_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Edit/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Edit_Editor extends Ayoola_Page_Menu_Edit_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->setIdentifier(); 
			if( ! $identifierData = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Edit ' . $identifierData['option_name'], $identifierData );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
			
		//	self::v( $values ); 
			if( $this->updateDb() ){ $this->setViewContent( '<p class="goodnews">Option edited successfully</p>', true ); }
		}
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ return false; }    
	} 
	// END OF CLASS
}
