<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Form_Requirement_Abstract
 */
 
require_once 'Ayoola/Form/Requirement/Abstract.php';


/**
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Requirement_Delete extends Ayoola_Form_Requirement_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['requirement_label'],  'Delete Requirement' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Requirement deleted successfully', true ); }
		}
		catch( Ayoola_Form_Requirement_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
