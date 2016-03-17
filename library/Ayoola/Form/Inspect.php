<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Inspect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Inspect.php date time username $ 
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Inspect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_Inspect extends Ayoola_Form_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'form_name' );
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
		//	if( ! $data = $this->getIdentifierData() ){ null; }
		//	if( ! $data = $this->getIdentifierData() ){ return false; }
	//		if( ! $data = $this->getIdentifierData() ){  }
			if(  ! self::hasPriviledge() )
			{
			//	var_export( $data );
				$this->setViewContent( '<p class="boxednews badnews">The requested form was not found on the server. Please check the URL and try again. </p>', true );
				return false;
			//	self::setIdentifierData( $data );
			}
		//	var_export( $this->getDbData() );
		//	var_export( $data );
			$table = new Ayoola_Form_Table_Data();
			$this->setViewContent( $table->view(), true );
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
    } 
	
}
