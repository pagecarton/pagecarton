<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_XmlTableViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: XmlTableViewer.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_XmlTableViewer
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_XmlTableViewer extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
		//	var_export( 232	 );
			if( ! $_REQUEST['table_name'] ){ throw new Application_Log_View_Exception( 'No DB Table found for Log ' . get_called_class() ); }
		//	var_export( 232	 );
			if( is_string( $_REQUEST['table_name'] ) )
			{ 
				$errorMessage = $_REQUEST['table_name'] . ' is not a valid log table';
		//		if( ! $class = Ayoola_Loader::loadClass( $_REQUEST['table_name'] ) ){ throw new Application_Log_View_Exception( $errorMessage ); }
				$_REQUEST['table_name'] = new $_REQUEST['table_name'];
		//		if( ! $_REQUEST['table_name'] instanceof Ayoola_Dbase_Table_Interface ){ throw new Application_Log_View_Exception( $errorMessage ); }
			}
		//	var_export( 232	 );
			$table = $_REQUEST['table_name'];
			$this->setViewContent( $table->view() );
		
		}
		catch( Ayoola_Exception $e )
		{ 
			return false; 
		}
	}
	
	// END OF CLASS
}
