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
 * @version    $Id: filename.php date time username $
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
class Ayoola_Form_Delete extends Ayoola_Form_Abstract
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
			$this->createConfirmationForm( 'Delete', 'Delete this form, "' . $data['form_title'] . '" and all its associated files? This cannot be undone.' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( $this->deleteDb() ){ $this->setViewContent(  '' . self::__( '<div class="goodnews">Form deleted successfully</div>' ) . '', true  ); } 
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
    } 
}
