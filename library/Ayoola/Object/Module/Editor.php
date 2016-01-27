<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Module_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Ayoola_Object_Module_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Module_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Object_Module_Editor extends Ayoola_Object_Module_Abstract
{
	
    /**
     * This method starts the chain for update
     *
     * @param void
     * @return null
     */
    public function init()
    {
		try
		{
//			$this->_identifierKeys = array( $this->getIdColumn() );
			if( ! $identifierData = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Edit Module', 'Edit ' . $identifierData['module_name'], $identifierData );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'Module Edited Successfully', true ); }
		}
		catch( Exception $e ){ return false; }
		
    } 
}
