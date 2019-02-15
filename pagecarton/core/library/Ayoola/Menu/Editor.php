<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Ayoola_Menu_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Ayoola_Menu_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Menu_Editor extends Ayoola_Menu_Abstract
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
			if( ! $data = $this->getIdentifierData() ){ return false; } 
			$this->createForm( 'Edit Menu', 'Edit ' . $data['template_label'] . ' (' . $data['template_name'] . ') ', $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $_POST );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
						
			if( ! $this->updateDb( $values ) ){ return false; }
			
	//		var_export( $data );
			$this->setViewContent( 'Menu template edited successfully', true );
			
		}
		catch( Exception $e ){ return false; }
		
    } 
}
