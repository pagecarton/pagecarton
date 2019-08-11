<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Ayoola_Page_Menu_Edit_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Edit_Creator extends Ayoola_Page_Menu_Edit_Abstract
{
	
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Add an option to site navigation'; 
	
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
			$this->createForm( 'Save', 'Add a new navigation option' );
			$this->setViewContent( $this->getForm()->view() );
            if( ! $values = $this->getForm()->getValues() ){ return false; } 
            $values['link_options'] = [ 'logged_in', 'logged_out' ];
            $values['auth_level'] = [ 0 ];
			if( $this->insertDb( $values ) ){ $this->setViewContent(  '' . self::__( '<p class="goodnews">A new navigation option added successfully.</p>' ) . '', true  ); }
		}
		catch( Ayoola_Page_Menu_Edit_Exception $e ){ return false; }    
    } 
	
    /**
     * Inserts the Data into Storage
     * 
     * @return bool
     */
	protected function insertDb( Array $values = null )
    {
        if( empty( $values ) )
        {
            if( ! $values = $this->getForm()->getValues() ){ return false; }
        }
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
