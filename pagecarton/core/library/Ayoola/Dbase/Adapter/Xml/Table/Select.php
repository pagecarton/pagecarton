<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Select
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Select.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Select
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Select extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
	
    /**
     * Switch to true to rearrange the result array  
     *
     * @var boolean
     */
    public $selectResultKeyReArrange = false;
	
    /**
     * Switch to true to use a namespace for rowIds of parent table
     *
     * @var boolean
     */
    protected $_useParentNamespace = false;
	
    /**
     * Selects record into from db table
     *
     * @param array Fields of Data to select from the table
     * @param array Filter with field values
     */
    public function init( Array $fieldsToFetch = null, Array $where = null, Array $options = null )
    {
//		PageCarton_Widget::v( $this );  
		/**
		 * If the accessibility NOT PRIVATE, we need to load all files
		 * The reason for the protected scope is to be able to select prIvate files
		 */
		$result = null;
		$result = $this->getCache( func_get_args() );
		if( is_array( $result ) && empty( $options['disable_cache'] ) && $this->cache ){ return $result; }
		$rows = array();
		if( ! empty( $options['filename'] ) )
		{
		//	var_export( $this->getMyFilename() );
			$this->setXml();
			$this->getXml()->load( $options['filename'] );
			$rows = $this->selectResultKeyReArrange == true ? array_merge( $rows, $this->doSelect( $fieldsToFetch, $where, $options ) ) : $rows + $this->doSelect( $fieldsToFetch, $where, $options );
	//		PageCarton_Widget::v( $rows );
		}
		elseif( $this->getAccessibility() == self::SCOPE_PRIVATE || $this->getAccessibility() == self::SCOPE_PUBLIC )
		{
		//	$rows = $this->doSelect( $fieldsToFetch, $where, $options ); 
			$files =  array_unique( array( $this->getFilenameAccordingToScope() => $this->getFilenameAccordingToScope() ) + $this->getSupplementaryFilenames() );
			$rows = $this->loopFiles( $files, $fieldsToFetch, $where, $options );

		}
		else
		{
	//		var_export( $this->getMyFilename() );
			$rows = array();
			$files = array_unique( $this->getGlobalFilenames() );
			$rows = $this->loopFiles( $files, $fieldsToFetch, $where, $options );
		}
		if( ! empty( $options['sort_column'] ) )
		{
			$rows = PageCarton_Widget::sortMultiDimensionalArray( $rows, $options['sort_column'] );
		}
		if( empty( $options['disable_cache'] ) ){ $this->setCache( $rows ); }
//		var_export( $rows );
		return $rows;
    }
	
    /**
     * Does the work
     *
     * @param void
     */
    public function loopFiles( Array $files, Array $fieldsToFetch = null, Array $where = null, Array $options = null  )
    {
		$rows = array();
 		$totalRows = 0;
 		$fileCount = 0;
 		$maxNoOfFiles = 50;
		rsort( $files );
	//	if( stripos( $this->getMyFilename(), 'localuser' ) )
		{
	//		PageCarton_Widget::v( $files );
		}
//		PageCarton_Widget::v( count( $files ) . '<br>' );
		foreach( $files as $filename )
		{
			$innerOptions = $options;
			if(  ! empty( $options['limit'] ) && $totalRows >= $options['limit'] )
			{
				break;
			}
			elseif( ! empty( $options['limit'] ) )
			{
				$innerOptions['limit'] = $options['limit'] - $totalRows;
			}
			if( ! empty( $options['record_search_limit'] ) && $this->recordCount >= $options['record_search_limit'] )
			{
				break;
			}
			if( ! is_file( $filename ) ){ continue; }
			if( ++$fileCount >= $maxNoOfFiles ){ break; }
	//			Ayoola_Page::v( $filename );
		//	var_export( $this->getMyFilename() );
			$this->setXml();
			$this->getXml()->load( $filename );
			$rowsInThisFile = $this->doSelect( $fieldsToFetch, $where, $innerOptions );
			$rows = $this->selectResultKeyReArrange == true ? array_merge( $rows, $rowsInThisFile ) : $rows + $rowsInThisFile;
			$totalRows = count( $rows );
		}
		return $rows;
	}

    /**
     * Does the work
     *
     * @param void
     */
    public function doSelect( Array $fieldsToFetch = null, Array $where = null, Array $options = null )
    {
		//	Calculate the total fields on the table, extended
		$allFields = $this->query( 'FIELDLIST' );

		if( is_null( $fieldsToFetch ) ){ $fieldsToFetch = $allFields; }
		$rows = array();
	//	var_export( $this->getRecords()->childNodes->length );

		$nextRecord = $this->getRecords()->lastChild;
		while( $nextRecord )
//		foreach( $this->getRecords()->childNodes as $eachRecord )
		{
			$eachRecord = $nextRecord;
		 	$nextRecord = $eachRecord->previousSibling;
			$this->recordCount = @$this->recordCount ? : 0;

			if( ! empty( $options['record_search_limit'] ) && $this->recordCount >= $options['record_search_limit'] )
			{
				break;
			}
			$this->recordCount++;
			$fields = array();		
			$searchResultIsHere = false;
			$rowId = self::getRecordRowId( $eachRecord );
			$recordMatch = false;
			$keyCount = 0;
			foreach( $eachRecord->childNodes as $countField => $field )
			{
				$keyCount++;
				$key = self::getFieldKey( $field );
				if( ! in_array( $key, $fieldsToFetch ) ){ continue; }
				foreach( $field->childNodes as $value )
				{ 
					if( $value instanceof DOMCDATASection )
					{ 
						$fields[$key] = is_string( $value->data ) ? htmlspecialchars_decode( $value->data ) : $value->data;
						break; 
					} 
				}
				
				$fields[$key] = self::filterDataType( $fields[$key], $this->getTableDataTypes( $key ) );
				do
				{
					if( ! empty( $where['*'] ) )
					{
						$recordMatch = $recordMatch ? : false;
						if( ! is_array( $fields[$key] ) )
						{
							if( ! is_array( $where['*'] ) ) 
							{
								if( stripos( $fields[$key], $where['*'] ) !== false )
								{ 
									$fields['pc_search_score'] += 1;
									$recordMatch = true;
								//	break 2;  
								}
								else
								{
								//	continue 2;
								}
							}
							else
							{
								foreach( $where['*'] as $keyword )
								{
									if( stripos( $fields[$key], $keyword ) !== false )
									{ 
										$fields['pc_search_score'] += 1;
										//	var_export( $where );
								//	var_export( $fields );
							//	var_export( $fields[$key] );
										$recordMatch = true;
									//	break 3;  
									}
								}
							//	continue 2;
							}
						}
					//	if( $keyCount === count(  $eachRecord->childNodes ) && $recordMatch )
						{

						}

					}
					if( ! empty( $where ) )
					{ 
						if( array_key_exists( $key, $where ) )
						{
							if( ! is_array( $fields[$key] ) )
							{
								if( ! is_array( $where[$key] ) && $where[$key] != $fields[$key] )
								{ 
									continue 3; 
								}
								elseif( is_array( $where[$key] ) && ! in_array( $fields[$key], $where[$key] ) )
								{
								//	var_export( $fields[$key] );
									continue 3; 
								}
							}
							else
							{
								//	An array is matched if a single member is present.
								if( ! is_array( $where[$key] ) && ! in_array( $where[$key], $fields[$key] ) )
								{
									//	only the record is array
									continue 3; 
								}
								elseif( is_array( $where[$key] ) && ! array_intersect( $where[$key], $fields[$key]) )
								{
									//	both element are arrays
								//	var_export( $fields[$key] );
									continue 3; 
								}
							//	var_export( $where[$key] );
							//	var_export( $fields[$key] );
							//	continue 2; 
							}
						}
					}
				}
				while( false );
				
				//	Retrieve values from the foreign keys
			//	$temp = array();
				foreach( $this->getForeignKeys() as $foreignTable => $foreignKey )
				{
					if( $key != $foreignKey ){ continue; }
					$foreignWhere = array( $foreignKey => $fields[$foreignKey] );
					if( empty( $temp[serialize( $foreignWhere )] ) )
					{ 
				//		var_export( $foreignTable );
				//		var_export( $foreignWhere );
						$temp[serialize( $foreignWhere )] = self::selectForeign( $foreignTable, $foreignWhere );
					}
					$foreignData = $temp[serialize( $foreignWhere )];
					if( ! empty( $where ) )
					{ 
						foreach( $foreignData as $foreignDataKey => $foreignDataValue )
						{
							if( array_key_exists( $foreignDataKey, $where ) )
							{
								if( ! is_array( $where[$foreignDataKey] ) && $where[$foreignDataKey] != $foreignData[$foreignDataKey] )
								{ 
									continue 4; 
								}
								elseif( is_array( $where[$foreignDataKey] ) && ! in_array( $foreignData[$foreignDataKey], $where[$foreignDataKey] ) )
								{
								//	var_export( $fields[$key] );
									continue 2; 
								}
							}
						}
					}
					$fields = array_merge( $foreignData, $fields );
				}

			}
	//		$rowId = $this->_useParentNamespace ? 'parent_' . $rowId : $rowId;
			//	Introducing a way to manipulate content of the results on this level might allow 
			//	us to be able to limit the number of times we need to loop through the results.
			//	Saving time or resources? Let's confirm if this is useful for programmers.
			if( ! empty( $options['result_filter_function'] ) && is_callable( $options['result_filter_function'] ) )
			{
				//	manipulate them before finally recording them
				$filterFunction = $options['result_filter_function'];
				$filterFunction( $rowId, $fields );   
			//	var_export( $rowId );
			//	var_export( $fields );
			}
			
			//	If the filter wants to skip this record. It just need to switch the $fields to false;
		//	var_export( $recordMatch );
			$fields === false || ( $recordMatch === false && ! empty( $where['*'] ) ) ? null : ( $rows[$rowId] = $fields );

			if( ! empty( $options['limit'] ) && count( $rows ) >= $options['limit'] )
			{
				break;
			}
			else
			{
				$innerOptions['limit'] = $options['limit'] - $totalRows;
			}
			
			//	
			 
		}
	//	var_export( $rows );
	
		// cache result
		if( empty( $options['disable_cache'] ) && $this->cache ){ $this->setCache( $rows ); }
	//	var_export( $rows );
		return $rows;
	}
	
    /**
     * Select from foreign tables
     *
     * @param string The table of the foreign
     * @param array Filter with field values
     */
    public static function selectForeign( $table, Array $foreignWhere )
    {
		return self::getForeignTable( $table )->selectOne( null, $foreignWhere );
    } 
		
    /**
     * sets the result from the last cache update
     *
     */
    public function setCache( $result )
    {
		$file = $this->getCacheFilename();
	//	var_export( $file );
		Ayoola_Doc::createDirectory( dirname( $file ) );
		return @file_put_contents( $file, serialize( $result ) );
    } 
		
    /**
     * sets the result from the last cache update
     *
     */
    public function getCache()
    {
		$cacheFile = $this->getCacheFilename( func_get_args() );
		$cacheTime = @filemtime( $cacheFile );
		if( $classCachePeriod = $this->getTableInfo( 'table_class' ) )
		{
			if( Ayoola_Loader::loadClass( $classCachePeriod ) )
			{
				$classCachePeriod = $classCachePeriod::$cacheTimeOut;
			}
		}
//		$classCachePeriod = $classCachePeriod::$cacheTimeOut;
	//	PageCarton_Widget::v( $classCachePeriod );
		$cTime = time();
//		PageCarton_Widget::v( $cacheFile . "\r\n" );
		foreach( $this->getGlobalFilenames() as $tableFile )
		{
		//	var_export( Ayoola_Application::getRequestUri() . '<br />' );
			if( ! is_file( $tableFile ) )
			{
				continue;
			}
			$fileMTime = @filemtime( $tableFile );
			if( ! ( $fileMTime ) )
			{
				continue;
			}
		//	if( Ayoola_Application::getRequestedUri() == '/AyoolaX' && stripos( $tableFile, '/Application/Profile/table.xml' ) )
			{
			//	var_export( $tableFile . '<br>' );  
			//	var_export( $cacheTime . '<br>' );
			//	var_export( $fileMTime . '<br>' );  
			}
			if( $classCachePeriod )
			{
			//	var_export( $cTime . '<br>' );
		//		var_export( $tableFile . '<br>' );
		//		var_export( $fileMTime . '<br>' );  
			//	var_export( ( $classCachePeriod + $fileMTime ) . '<br>' );  
			//	PageCarton_Widget::v( $cacheTime < $fileMTime );  
			//	PageCarton_Widget::v( $classCachePeriod + $fileMTime < $cTime );  

			}
			if( $cacheTime <= $fileMTime && ( ! $classCachePeriod || $classCachePeriod + $fileMTime <= $cTime ) )
			{ 
			//	var_export( $cacheTime . '<br>' );
			//	var_export( $fileMTime . '<br>' );  
			//	var_export( $tableFile );
			//	var_export( $cacheFile );
				@unlink( $cacheFile ); 
				break;
			}
		}
		return @unserialize( file_get_contents( $cacheFile ) );
    } 
	// END OF CLASS
}
