<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Wrapper_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php date time ayoola $
 */

/**
 * @see Ayoola_Object_Wrapper_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Wrapper_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Object_Wrapper_Delete extends Ayoola_Object_Wrapper_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['wrapper_label'],  'Delete Wrapper' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->deleteDb( false ) ){ $this->setViewContent( 'Wrapper deleted successfully', true ); }
		}
		catch( Application_Object_Wrapper_Exception $e ){ return false; }
    } 
}
