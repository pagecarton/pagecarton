<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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

        $processDir = $this->getMyTempProcessDirectory();
     //   var_export( $this->loadTableDataFromFile( $this->_myFilename ) );
        if( ! $this->loadTableDataFromFile( $this->_myFilename ) )
        {
            Ayoola_Doc::createDirectory( $processDir );
            $tempData = serialize( func_get_args() );
            $tempFile = $processDir . DS . md5( $tempData . time() );
        //    var_export( func_get_args() );
            file_put_contents( $tempFile, $tempData );
            return true;
        }

		
		// Set unset Values  to default
		foreach( $this->getDataTypes() as $key => $value )
		{
			if( ! isset( $values[$key] ) ){ $values[$key] = null; }
		}
		
		//	CHECK IF WE HAVE UP TOO MUCH RECORDS IN A SINGLE FILE
		$i = 0;
		$filename = null;
		$filename = $this->_myFilename;

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
            //    var_export( $filename );
            if( ! $this->getXml()->load( $filename ) )
            {
            //    var_export( $filename );
            }
		}

		$recordRowId = $this->getXml()->autoId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );  		
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
    
        if( $processes = Ayoola_Doc::getFilesRecursive( $processDir ) AND empty( $this->proccesses ) )
        {
            $this->proccesses = $processes;
        //    var_export( $processes );
        //    exit( $processes );
            foreach( $processes as $process )
            {
                if( $tempData = unserialize( file_get_contents( $process ) ) )
                {
                    $response = $this->init( $tempData[0], $tempData[1] );
                //    var_export( $response );
                //    var_export( $tempData );
                    unlink( $process );
                }

            }
        }    

		return array( 'insert_id' => $whereValue, $idColumn => $whereValue );
    } 
	// END OF CLASS
}
