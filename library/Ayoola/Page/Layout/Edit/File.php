<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Edit_File
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: File.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Edit_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Edit/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Edit_File
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Edit_File extends Ayoola_Page_Layout_Edit_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$values = array();
		@$values[self::VALUE_CONTENT] = file_get_contents( $this->getMyFilename() );
		$this->createForm( 'Edit File', 'Edit ' . $identifierData['layout_name'], $values  );
		$this->setViewContent( $this->getForm()->view() );
		if( $this->updateFile() ){ $this->setViewContent( 'Layout file edited successfully', true ); }
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
