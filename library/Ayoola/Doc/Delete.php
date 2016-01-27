<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Doc_Abstract
 */
 
require_once 'Ayoola/Doc/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Delete extends Ayoola_Doc_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete ' . $data['document_name'],  'Delete Document' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent( 'Document deleted successfully', true ); 
				if( ! is_file( $data['document_filename'] ) ){ throw new Ayoola_Doc_Exception( 'File does not exist' ); } 
				unlink( $data['document_filename'] );
				@Ayoola_Doc::removeDirectory( dirname( $data['document_filename'] ) );
			}
		}
		catch( Ayoola_Doc_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
