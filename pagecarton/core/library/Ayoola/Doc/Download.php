<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Doc_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Download.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Doc_Abstract
 */
 
require_once 'Ayoola/Doc/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Doc_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Download extends Ayoola_Doc_Abstract
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
			$this->setViewContent( 'Your download will start in a moment...', true );
/* 			$this->createConfirmationForm( 'Download ' . $data['document_name'],  'Download document' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
 */			$doc = new Ayoola_Doc( $data['document_filename'] );			
			$doc->download();
		}
		catch( Ayoola_Doc_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
