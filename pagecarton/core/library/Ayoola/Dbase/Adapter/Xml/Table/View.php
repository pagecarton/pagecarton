<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_View extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{

    /**
     * Returns an XHTML formated string useful to view the table
     *
     * @param array Fields to Show
     * @param array filter
     * @return string HTML
     */
    public function init( Array $fieldKeys = null, $where = null )
    {
		$fieldKeys = $fieldKeys ? : array_keys( $this->getTableDataTypes() );
		if( ! in_array( $this->getTableName() . '_id', $fieldKeys ) ){ $fieldKeys[] = $this->getTableName() . '_id'; }
		
		//	power our search box
		$dbWhereClause = array(); 
		if( @$_REQUEST['db_where_clause_field_name']  )
		{
			$dbWhereClause = array_combine( $_REQUEST['db_where_clause_field_name'], @$_REQUEST['db_where_clause_field_value'] );
		//	self::v( $this->_dbWhereClause );
		}
		$records = array_values( $this->query( 'FETCH', $fieldKeys, $dbWhereClause ) );
	//	krsort( $records );
		$records = array_values( $records );
		require_once 'Ayoola/Paginator.php';
		$fieldKeys = $fieldKeys ? array_values( $fieldKeys ) : $this->getTableDataTypes();
		unset( $fieldKeys[$this->getTableName() . '_id'] );
	//	var_export( $fieldKeys );
		$fieldKeys = array_fill_keys( $fieldKeys, null );
	//	var_export( $fieldKeys );
	//	foreach( $fieldKeys as $key => $value ){ $fieldKeys[$key] = null; }
	//	var_export( $records );
		$paginate = new Ayoola_Paginator( $records );
		$paginate->setKey( $this->getTableName() . '_id' );
		$paginate->showSearchBox = true;
		$paginate->createList( $fieldKeys );
		return $paginate->view();
    } 
	// END OF CLASS
}
