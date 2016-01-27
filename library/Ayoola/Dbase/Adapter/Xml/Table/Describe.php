<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Describe
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Describe.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Describe
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Describe extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
	
    /**
     * Creates a table
     *
     * @param string Table Name 
     * @return boolean
     */
    public function init( $tableName = null )
    {
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
		$tableInfo['table_info'] = $this->getXml()->getTagAttributes( $this->getXml()->documentElement );
		$tableInfo['data_types'] = $this->getTableDataTypes();
		$tableInfo['filename'] = $this->getMyFilename();
		return $tableInfo;
		//exit( var_export( $tableName ) );
    } 
	// END OF CLASS
}
