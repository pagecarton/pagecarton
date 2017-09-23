<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_Delete extends Ayoola_Page_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 		
			if( @$_GET['url'] )
			{
				$this->_identifierKeys = array( 'url' );
			}
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete', 'Delete this page, "' . $data['url'] . '" and all its associated files? This cannot be undone.' );
			self::resetCacheForPage( $data['url'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->_deleteFiles() ){ $this->setViewContent( '<p class=" goodnews ">Page deleted successfully</p>', true ); } 
		}
		catch( Exception $e )  
		{ 
		//	return false; 
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		}
    } 
	
    /**
     * Deletes the files and dir content
     *
     * @param 
     * @return boolean
     */
    protected function _deleteFiles()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$data = $this->getIdentifierData();
		$this->setPageFilesPaths( $data['url'] );
		$files = $this->getPageFilesPaths( $data['url'] );
	//	var_export( $files );  
		foreach( $files as $file )
		{
			//	It must be a file
		//	if( is_file( $file ) )
			{
				if( ! @unlink( $file ) )
				{
				//	throw new Ayoola_Page_Exception( 'UNABLE TO DELETE FILE: ' . $file );
				}
				@Ayoola_Doc::removeDirectory( dirname( $file ) );
			}
			
		}
		
		//	Removing from DB is done late to avoid orphan links
		if( ! $this->deleteDb( false ) )
		{	
			return false;
		}
		return true;
    } 
}
