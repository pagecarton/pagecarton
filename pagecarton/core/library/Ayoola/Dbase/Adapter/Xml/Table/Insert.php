<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Insert
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Insert.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Insert
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Insert extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{

    /**
     * Default maximum number of files to create for a table
     *
     * @param int
     */
    public static $_maxNoOfSupplementaryFiles = 200;

    /**
     * Inserts record into a db table
     *
     * @param array Data to insert to table
     */       
    public function init( $values, $options = null )
    {
	//	$recordRowId = $options['record_row_id'] ? : $options;
		$recordRowId = $options['record_row_id'];
		
		// Set unset Values  to default
		foreach( $this->getDataTypes() as $key => $value )
		{
			if( ! isset( $values[$key] ) ){ $values[$key] = null; }
		}
		
		//	CHECK IF WE HAVE UP TOO MUCH RECORDS IN A SINGLE FILE
		$i = 0;
//		while( count( $this->query( 'SELECT', null, array( self::SCOPE_PRIVATE => true ) ) ) > 5 )
//	var_export(	filesize( $this->_myFilename ) );
//	var_export(	filesize( $this->_myFilename ) > 200000 );
	//	$filename = $this->getMyFilename();
		$filename = null;
		$filename = $this->_myFilename;
//		while( count( $this->query( 'SELECT', null, null, array( 'filename' => $filename ) ) ) > 499 || ( filesize( $filename ) > 20000 ) )
		//	Lets use filesize because some db may have many records "light" records e.g. 300000 (~300kb)
		$dir = $this->getMySupplementaryDirectory();
		if( filesize( $filename ) > 300000 )
		{
			$strToUse = str_pad( $i, 3, '0', STR_PAD_LEFT );
			$annexFile = $dir . DS . '' . implode( DS, str_split( $strToUse ) ) . EXT_DATA;     
			while( is_file( $annexFile ) )
			{
				++$i;
				$strToUse = str_pad( $i, 3, '0', STR_PAD_LEFT );
				$annexFile = $dir . DS . '' . implode( DS, str_split( $strToUse ) ) . EXT_DATA;     
			}
				
			$dataTypes = $this->getDataTypes();
			$tableInfo = $this->query( 'DESCRIBE' );
			$tableInfo['table_info']['no_existence_check'] = true;
			$tableInfo['table_info']['filename'] = $filename;
		//	PageCarton_Widget::v( $annexFile );

			Ayoola_Doc::createDirectory( dirname( $annexFile ) );

			rename( $filename, $annexFile );
		//	$this->query( 'DROP' );
			$this->setXml();
			$this->query( 'CREATE', $tableInfo['table_info'], $tableInfo['data_types'] );
			$this->setXml();
			$this->getXml()->load( $filename );
		}

/*		while( filesize( $filename ) > 150000 )
		{
			++$i;
			if( $i > static::$_maxNoOfSupplementaryFiles )
			{
				$badnews = 'MAXIMUM NUMBER OF SUPPLEMENTARY FILES (' . static::$_maxNoOfSupplementaryFiles . ') CREATED FOR XML_DB IN "' . $dir . '"';
			//	Application_Log_View_Error::log( $badnews );
				throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( $badnews );
			}
			$filename = $dir . DS . '' . implode( DS, str_split( $i ) ) . EXT_DATA;     
		//	$filename = $dir . DS . '' . $i . EXT_DATA; 
			
	//		echo $filename;
		//	$this->_myFilename = $filename;
		//	var_export( $filename );
		//	exit();
			if( ! is_file( $filename ) )
			{
				Ayoola_Doc::createDirectory( dirname( $filename ) );
				$dataTypes = $this->getDataTypes();
			//	var_export( $dataTypes );
				$tableInfo = $this->query( 'DESCRIBE' );
				$tableInfo['table_info']['no_existence_check'] = true;
				$tableInfo['table_info']['filename'] = $filename;
			//	var_export( $tableInfo );
		//		var_export( $this->getTableName() );
			//	var_export( $this->_tableVersion );
		//		var_export( $tableInfo );
				$this->setXml();
		//		$this->getXml()->load( $filename );
				
				$this->query( 'CREATE', $tableInfo['table_info'], $tableInfo['data_types'] );
			}
		//	var_export( $tableInfo );
			$this->setXml();
			$this->getXml()->load( $filename );
		//	break;
		}
*/		$recordRowId = $this->getXml()->autoId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );  		
		$row = $this->getXml()->createElement( self::TAG_TABLE_ROW );  
		$idColumn = $this->getTableName() . '_id';
		$whereValue = $recordRowId ? : $values[$idColumn];
		if( ! empty( $values[$idColumn] ) )
		{
			//	Removing this as it might slow things down in large tables like access logs
		//	if( $select = $this->query( 'SELECT', null, array( $idColumn => $values[$idColumn] ) ) )
			{
				//	unset( $values[$idColumn] );
		//		throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( 'PRIMARY ID "' . $idColumn . '" ALREADY HAS "' . $values[$idColumn] . '"' );
			}
		}
	//	var_export( $values['creation_time'] );
		if( empty( $values['creation_time'] ) )   
		{
			$values['creation_time'] = time();
		}
	//	var_export( $values['creation_time'] );
	//		var_export( $recordRowId );
	//		var_export( $values[$idColumn] );
		if( empty( $options['record_row_id'] ) && empty( $values[$idColumn] ) )
		{
			
			//	add app id to record id so as to remove duplicate IDs
			$appId = new Ayoola_Api_Api();
			$appId = $appId->selectOne();
			$appId = strval( intval( $appId ? $appId['application_id'] : time() ) );
		//	$recordRowId = $appId . $recordRowId
			$whereValue = '' . $appId . '-' . $i . '-' . $recordRowId;
			
		//	var_export( array( $idColumn => $whereValue ) );
			//	Removing this as it might slow things down in large tables like access logs
		//	while( $select = $this->query( 'SELECT', null, array( $idColumn => $whereValue ) ) )
			{
			//	++$recordRowId;
			//	var_export( $select );
		//	var_export( $whereValue );
			//	$whereValue = $appId . '_' . $recordRowId;
				$whereValue = '' . $appId . '-' . $i . '-' . $recordRowId;
			//	var_export( $appId . '_' . $recordRowId );
		//		exit();
			}
		}
		
	//	var_export( $appId );
		
		//	Do not assign the recordRowId that is used by the parent table or previous supplement
	//	$whereValue = $appId . '_' . $recordRowId;
	//	var_export( $whereValue );
	//	exit( $whereValue );
		
		$row = self::setRecordRowId( $row, $recordRowId );
		
		$values[$idColumn] = $values[$idColumn] ? : $whereValue;
	//	var_export( $values );
		foreach( $values as $key => $value )
		{
			$this->setRowColumnValue( $row, $key, $value );
			$this->setRecords( $row );	
		}
		
	//	var_export( $values[$idColumn] );
		$this->saveFile( $filename );
	//	$this->clearCache();
		return array( 'insert_id' => $whereValue, $idColumn => $whereValue );
    } 
	// END OF CLASS
}
