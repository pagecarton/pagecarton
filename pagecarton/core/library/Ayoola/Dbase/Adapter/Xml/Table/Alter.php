<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Alter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Alter.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Alter
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Alter extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{

    /**
     * Changes a table
     *
     * @param string Table Name 
     * @param array Data Types
     * @param DOMNode The Records
     * @return boolean
     */
    public function init( $tableName = null, Array $dataTypes = null, DOMNode $records = null )
    {
		//var_export( $this->getReferenceKeys() );
		$dataTypes = $dataTypes ? array_merge( $this->getDataTypes(), $dataTypes ) : $this->getDataTypes();
		//$records = $this->getXml()->importNode( $records );
		if( is_null( $records ) ){ $records = $this->getRecords(); }
		$this->query( 'CREATE', $tableName, $dataTypes, $records );
		if( ! is_null( $tableName ) )
		{ 
			$this->query( 'DROP', $this->getTableName() ); 
			$this->setTableName( $tableName );
		}
		return true;
    } 
	// END OF CLASS
}
