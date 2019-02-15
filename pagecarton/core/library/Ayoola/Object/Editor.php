<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Ayoola_Object_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Object_Editor extends Ayoola_Object_Abstract
{
	
    /**
     * Key for the id column
     * 
     * @var string
     */
	protected $_identifierKeys = array( 'viewableobject_id' );
	
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
			$this->createForm( 'Save', 'Edit ' . $identifierData['object_name'], $identifierData );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
		//	self::v( $values );
			if( $this->updateDb() ){ $this->setViewContent( 'Widget saved Successfully', true ); }
		}
		catch( Exception $e ){ return false; }
		
    } 
}
