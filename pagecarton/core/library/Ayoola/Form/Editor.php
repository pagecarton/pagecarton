<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php date time ayoola $
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Ayoola_Form_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_Editor extends Ayoola_Form_Abstract
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
	 //		var_export( $data ); 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Continue...', 'Edit "' . $data['form_title'] . '"', $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//	var_export( $data );
		//	var_export( $data );
		//	var_export( $data );
		//	var_export( $this->getForm()->getValues() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//	var_export( $_POST );
		//	var_export( $values );
			
			
			if( ! $this->updateDb( $values ) ){ return false; }
			
	//		var_export( $data );
			$this->setViewContent(  '<p class="goodnews">' . self::__( 'Form saved successfully' ) . '</p>', true  ); 
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
		
    } 
}
