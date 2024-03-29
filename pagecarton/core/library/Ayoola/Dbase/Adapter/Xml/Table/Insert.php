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

    protected static $_processing = array();


    /**
     * Inserts record into a db table
     *
     * @param array Data to insert to table
     */       
    public function init( $values, $options = null )
    {
        if( ! is_array( $options ) )
        {
            $options = array();
        }

        $recordRowId = null;

        if( ! empty( $options['record_row_id'] ) )   
		{
            $recordRowId = $options['record_row_id'];
        }

        $processDir = $this->getMyTempProcessDirectory();
        $scopeFile = $this->getFilenameAccordingToScope( false, $this->getAccessibility() );

        $delay = 0;
        if( $class = $this->getTableInfo( 'table_class' ) AND Ayoola_Loader::loadClass( $class ) AND property_exists( $class, 'insertDelay' ) AND $m = filemtime( $scopeFile ) )
        {
            $delay = intval( $class::$insertDelay );
            $difference = time() - $m;
            if( $difference > $delay )
            {
                $delay = 0;
            }
        }
        //  set this default table values
        if( empty( $values['creation_time'] ) )   
		{
			$values['creation_time'] = time();
        }
        if( empty( $values['__duuid'] ) )   
		{
            $values['__duuid'] = Ayoola_Application::getDeviceUId();
        }
        if( empty( $values['__user_id'] ) )   
		{
            $values['__user_id'] = Ayoola_Application::getUserInfo( 'user_id' );
        }
        if( empty( $values['__ip'] ) )   
		{
            $values['__ip'] = Ayoola_Application::getRuntimeSettings( 'user_ip' );
        }



        if( 
            empty( self::$_processing[$class] ) && 
            ( 
                ( ! $this->loadTableDataFromFile( $scopeFile ) && $this->loadTableDataFromFile( $scopeFile, true ) )  
                || $delay 
            ) 
        )
        {
            Ayoola_Doc::createDirectory( $processDir );

            $tempData = serialize( func_get_args() );

            $tempFile = $processDir . DS . md5( $tempData . microtime() );
            Ayoola_File::putContents( $tempFile, $tempData );
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
		$filename = $scopeFile;

        //	Lets use filesize because some db may have many records "light" records e.g. 300000 (~300kb)
        $dir = $this->getMySupplementaryDirectory();

		if( is_file( $filename ) && filesize( $filename ) > 300000 )
		{
			$strToUse = $strToUseX = str_pad( $i, 3, '0', STR_PAD_LEFT );
			$annexFile = $dir . DS . '' . implode( DS, str_split( $strToUse ) ) . EXT_DATA;  

			while( is_file( $annexFile ) )
			{
				++$i;
				$strToUse = str_pad( $i, 3, '0', STR_PAD_LEFT );
				$annexFile = $dir . DS . '' . implode( DS, str_split( $strToUse ) ) . EXT_DATA;     
			}
            if( is_file( $annexFile ) )
            {
                //  archive files? 
                $xtDir = $dir . '/z/z/z';
                Ayoola_Doc::createDirectory( dirname( $xtDir ) );
                if( rename( $dir, $xtDir ) )
                {
                    $annexFile = $dir . DS . '' . implode( DS, str_split( $strToUseX ) ) . EXT_DATA;
                }
            }
				
			$dataTypes = $this->getDataTypes();
			$tableInfo = $this->query( 'DESCRIBE' );
			$tableInfo['table_info']['no_existence_check'] = true;
			$tableInfo['table_info']['filename'] = $filename;

			Ayoola_Doc::createDirectory( dirname( $annexFile ) );

			rename( $filename, $annexFile );
			$this->setXml();
			$this->query( 'CREATE', $tableInfo['table_info'], $tableInfo['data_types'] );
            $this->setXml();
            if( ! $this->getXml()->load( $filename ) )
            {

            }
		}
        
		$recordRowId = $this->getXml()->autoId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );  		
		$row = $this->getXml()->createElement( self::TAG_TABLE_ROW );  
		$idColumn = $this->getTableName() . '_id';
        $whereValue = $recordRowId ? : $values[$idColumn];
		if( ! empty( $values[$idColumn] ) )
		{

        }
		if( empty( $options['record_row_id'] ) && empty( $values[$idColumn] ) )
		{
			
			//	add app id to record id so as to remove duplicate IDs
			$appIdX = new Ayoola_Api_Api();
            $appId = 0;
			if( $appIdX = $appIdX->selectOne() )
            {
                $appId = strval( intval( $appIdX['application_id'] ) );
            }
            
			$whereValue = '' . $appId . '-' . time() . '-' . $recordRowId;
		}
				
		//	Do not assign the recordRowId that is used by the parent table or previous supplement
		
		$row = self::setRecordRowId( $row, $recordRowId );
		
		$values[$idColumn] = $values[$idColumn] ? : $whereValue;
		foreach( $values as $key => $value )
		{
			$this->setRowColumnValue( $row, $key, $value );
			$this->setRecords( $row );	
		}

		$this->saveFile( $filename );


        if( empty( self::$_processing[$class] ) AND self::$_processing[$class] = Ayoola_Doc::getFilesRecursive( $processDir ) )
        {
            $cxi = 0;

            foreach( self::$_processing[$class] as $keyX => $process )
            {
                if( $cxi++ > 500 )
                {
                    break;
                }

                if( $tempData = unserialize( file_get_contents( $process ) ) )
                {
                    $response = $this->init( $tempData[0], $tempData[1] );

                    unlink( $process );

                    @Ayoola_Doc::removeDirectory( dirname( $process ) );

                }
                unset( self::$_processing[$class][$keyX] );
            }
        }    

		return array( 'insert_id' => $whereValue, $idColumn => $whereValue );
    } 
	// END OF CLASS
}
