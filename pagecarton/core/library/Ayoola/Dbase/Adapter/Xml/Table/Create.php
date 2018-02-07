<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Create
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Create.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Create
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Create extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
	
    /**
     * Creates a table
     *
     * @param string Table Name 
     * @return boolean
     */
    public function init( $tableInfo = null, Array $dataTypes = array(), DOMNode $records = null )
    {
	//	var_export( func_get_args() );
	//	var_export( $this->getMyFilename() );
		$tableName = is_array( $tableInfo ) ? $tableInfo['table_name'] : $tableInfo;
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
	//		var_export( $tableName );
	//	var_export( $this->getMyFilename() );
		$this->_myFilename = @$tableInfo['filename'] ? : $this->_myFilename;
		if( empty( $tableInfo['no_existence_check'] ) && $this->query( 'EXISTS' ) )
		{ 
			//	var_export( $tableName );
			//	if(  ){}
			require_once 'Ayoola/Dbase/Adapter/Xml/Table/Exception.php';
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Table ($tableName) Already Exists" );
		}
	//	var_export( $tableInfo['table_version'] );
	//	var_export( $tableInfo['filename'] );
	//	if( empty( $dataTypes[$this->getTableName() . '_id'] ) ){ $dataTypes[$this->getTableName() . '_id'] = 'INT'; }
	
		//	CHANGE TO INPUTTEXT AS WORKAROUND FOR AMBIGUITY IN SUPPLEMENTARY TABLES.
		if( empty( $dataTypes[$this->getTableName() . '_id'] ) )
		{ 
			$dataTypes[$this->getTableName() . '_id'] = 'INPUTTEXT'; 
		}
		if( empty( $dataTypes['creation_time'] ) )  
		{ 
			$dataTypes['creation_time'] = 'INPUTTEXT'; 
		}
		
		//	Refresh Xml Memory to start a new Document
		require_once 'Ayoola/Xml.php';
		$this->setXml( new Ayoola_Xml );
		$documentNode = $this->getXml()->createElement( self::TAGNAME_DOCUMENT_ELEMENT );
		$documentNode->setAttribute( 'table_name', $this->getTableName() );
		$documentNode->setAttribute( 'creation_time', time() );
		$documentNode->setAttribute( 'table_version', @$tableInfo['table_version'] );
		$documentNode->setAttribute( 'module_version', @$tableInfo['module_version'] );
		$documentNode->setAttribute( 'table_class', @$tableInfo['table_class'] );
		
		$documentNode->setAttribute( 'creation_ip', ip2long( long2ip( ip2long( $_SERVER['REMOTE_ADDR'] ) ) ) );
        $node = $this->getXml()->createElement( self::TAGNAME_TABLE_SETTINGS );
        $node->appendChild( $this->setDataTypes( $dataTypes ) );
        $node->appendChild( $this->setForeignKeys() );
        $node->appendChild( $this->setRelatives() );
		
		//var_export( $records->childNodes->length );
		$documentNode->appendChild( $this->setRecords( $records ) );
        $documentNode->appendChild( $node );
		$this->getXml()->appendChild( $documentNode );
	//	$this->clearCache();
		$this->saveFile( @$tableInfo['filename'] );
	//	var_export( $tableInfo['table_version'] );
		return true;
    } 
	// END OF CLASS
}
