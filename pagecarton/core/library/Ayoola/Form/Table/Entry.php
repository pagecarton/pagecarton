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
class Ayoola_Form_Table_Entry extends Ayoola_Form_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'data_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_Table_Data';
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'data_id';
	
    /**
     * 
     * 
     */
	public function getPercentageCompleted()
    {
        return 100;
    }
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 		
            if( ! $data = $this->getIdentifierData() ){ return false; }
            $newData = $data['form_data'];
            $category = strtolower( $_GET['category'] );
            if( ! empty( $newData['entry_categories'][$category ] ) )
            {
                unset( $newData['entry_categories'][$category] );
                unset( $newData['entry_categories'][$_GET['category']] );
                $this->createConfirmationForm( 'Remove ', 'Remove form entry "' . $data['data_id'] . '" to ' . $_GET['category'] . ' category ' );
            }
            else
            {
                $newData['entry_categories'][$category ] = $_GET['category'] ;
                $this->createConfirmationForm( 'Set', 'Set form entry "' . $data['data_id'] . '" to ' . $_GET['category'] . ' category ' );
            }
			$this->setViewContent( $this->getForm()->view(), true );
		//	if( ! $values = $this->getForm()->getValues() ){ return false; }
            if( $this->getDbTable()->update( array( 'form_data' => $newData ), array( 'data_id' => $data['data_id'] )  ) )
            { 
                $this->setViewContent(  '' . self::__( '<div class="goodnews">Form entry category updated successfully</div>' ) . '', true  ); 
            } 
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		}
    } 
}
