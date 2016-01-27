<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Delete extends Ayoola_Page_Layout_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
		//	var_export( $this->getFilename() );
			$this->getFilename();
			$this->createDeleteForm( $data['layout_name'] );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( $this->deleteDb( false ) )
			{ 
				$this->deleteFile();
				$this->setViewContent( 'Layout deleted successfully', true ); 
			} 
		}
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
    } 
	
    /**
     * Delete the layout file
     * 
     */
	protected function deleteFile()
    {
		Ayoola_Doc::deleteDirectoryPlusContent( dirname( $this->getMyFilename() ) );
		@unlink( $this->getMyFilename() );
    } 
	// END OF CLASS
}
