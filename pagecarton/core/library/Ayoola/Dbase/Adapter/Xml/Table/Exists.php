<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Exists
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Exists.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Exists
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Exists extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
	
    /**
     * Returns true if table exits
     *
     * @param string Table Name 
     * @return boolean
     */
    public function init( $tableName = null )
    {
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
		try
		{ 
			$filename = $this->getFilenameAccordingToScope( true ); 
		//	var_export( $tableName );
			if( ! file_exists( $filename ) )
			{
				return false;
			}
			else
			{
				return true;
			}

		}
		catch( Ayoola_Dbase_Adapter_Exception $e ){ return false; }
		return true;
	} 
	// END OF CLASS
}
