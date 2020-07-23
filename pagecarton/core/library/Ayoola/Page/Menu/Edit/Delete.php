<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Edit_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Edit/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Edit_Delete extends Ayoola_Page_Menu_Edit_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'option_id' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->setIdentifier();
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete ' . $data['option_name'],  'Delete Menu option' );
			$this->setViewContent( $this->getForm()->view() );
			if( $this->deleteDb( false ) ){ $this->setViewContent(  '' . self::__( '<p class="goodnews">Menu Option deleted successfully</p>' ) . '', true  ); }
		}
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
