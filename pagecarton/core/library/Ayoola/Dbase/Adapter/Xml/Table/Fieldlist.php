<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Fieldlist
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Fieldlist.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Fieldlist
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Fieldlist extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
	
    /**
     * Returns the Fieldlist of the table
     *
     * @param boolean Switch to true to also add Fieldlist of Foreign Table
     * @return array Fieldlist
     */
    public function init( $addForeign = false )
    {
		$fieldList = array_keys( $this->getTableDataTypes() );
		//if( false === $addForeign ){ return array_unique( $fieldList ); } //	Return early
		foreach( $this->getForeignKeys() as $foreignTable => $foreignKey )
		{
			if( ! self::checkValidTable( $foreignTable ) ){ continue; }
		//	var_export( $foreignTable );
			$foreignTable = self::getForeignTable( $foreignTable );
		//	$fieldList = array_merge( $fieldList, array_keys( $foreignTable->getDataTypes() ) );
			$adapter = $foreignTable->getDatabase()->getAdapter();
			$fieldList = array_merge( $fieldList, $adapter->query( 'TABLE', 'FIELDLIST' ) );
		}
	//	var_export( $fieldList );
		return array_unique( $fieldList );
	} 
	// END OF CLASS
}
