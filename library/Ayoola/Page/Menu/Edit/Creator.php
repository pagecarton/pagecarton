<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Edit_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Menu_Edit_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Edit/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Edit_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Edit_Creator extends Ayoola_Page_Menu_Edit_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'menu_id' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			//if( ! parent::_process() ){ return false; }
		//	$data = $this->getIdentifierData();
		//	var_export( $data );
			$this->createForm( 'Save', 'Add a new link to menu' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
			if( $this->insertDb() ){ $this->setViewContent( 'A new link added successfully.', true ); }
		}
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ return false; }    
    } 
	
    /**
     * Inserts the Data into Storage
     * 
     * @return bool
     */
	protected function insertDb()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		//var_export( $values );
		try{ $this->getDbTable()->insert( $values ); }
		catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e )
		{
			//$class = get_class( $this->getDbTable()->getDatabase()->getAdapter() );
			if( $e->getMessage() == Ayoola_Dbase_Adapter_Xml_Table_Abstract::ERROR_INSERT_AMBIGUOUS )
			{
				$this->getForm()->setBadnews( 'Name already exist, please choose a different name' );
				//$this->setViewContent( $this->getForm()->view(), true );
			}
			return false;
		}
		return true;
    } 

    /**
     * Produces the HTML output of the object
     * 
     * @param void
     * @return string
     */
	public function view()
    {
		return $this->getViewContent(); 
    } 
	// END OF CLASS
}
