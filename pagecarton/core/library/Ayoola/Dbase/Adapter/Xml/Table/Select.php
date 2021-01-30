<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * @param array Fields to fetch
     * @param array Where clause as array
     * @param array Select options
     */
    public function init( $fieldsToFetch = null, Array $where = null, Array $options = null )
    {

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
			$this->setXml();
            if( ! $this->loadTableDataFromFile( $options['filename'], true ) )
            {

            }
			$rows = $this->selectResultKeyReArrange == true ? array_merge( $rows, $this->doSelect( $fieldsToFetch, $where, $options ) ) : $rows + $this->doSelect( $fieldsToFetch, $where, $options );
		}
		elseif( $this->getAccessibility() == self::SCOPE_PRIVATE ) // let PUBLIC PICK FROM CORE AND DEFAULT FOR COMPATIBILITY TO PREVIOUS VERSIONS THAT SAVED IN CORE
		{
            $scopeFile = $this->getFilenameAccordingToScope( false, self::SCOPE_PRIVATE );
            $files =  array_unique( array( $scopeFile => $scopeFile ) + $this->getSupplementaryFilenames() );

			$rows = $this->loopFiles( $files, $fieldsToFetch, $where, $options );

		}
		else
		{
			$rows = array();
			$files = array_unique( $this->getGlobalFilenames() );
			$rows = $this->loopFiles( $files, $fieldsToFetch, $where, $options );
		}
		if( ! empty( $options['sort_column'] ) )
		{
			$rows = PageCarton_Widget::sortMultiDimensionalArray( $rows, $options['sort_column'] );
		}
		if( empty( $options['disable_cache'] ) ){ $this->setCache( $rows ); }
		return $rows;
    }
	
    /**
     * Does the work
     *
     * @param array Files of DB
     * @param array Fields to fetch
     * @param array Where clause as array
     * @param array Select options
     */
    public function loopFiles( Array $files, $fieldsToFetch = null, Array $where = null, Array $options = null  )
    {
		$rows = array();
 		$totalRows = 0;
 		$fileCount = 0;
 		$maxNoOfFiles = 50;
		rsort( $files );

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

			$this->setXml();

            if( ! $this->loadTableDataFromFile( $filename, true ) )
            {

            }
			$rowsInThisFile = $this->doSelect( $fieldsToFetch, $where, $innerOptions );
			$rows = $this->selectResultKeyReArrange == true ? array_merge( $rows, $rowsInThisFile ) : $rows + $rowsInThisFile;
			$totalRows = count( $rows );
		}
		return $rows;
	}

    /**
     * Inject the where clause
     *
     * @param string Field Where key
     * @param mixed Field value to compare
     * @param array Where clause as array
     * @param array Select options
     */
    public static function where( $key, $fieldValue, Array $where, Array $options = null )
    {
        if( ! empty( $options['case_insensitive'] ) && is_string( $where[$key] ) && is_string( $fieldValue ) )
        {
            $fieldValue = strtolower( $fieldValue );
            $where[$key] = strtolower( $where[$key] );
        }
        if( ! is_array( $fieldValue ) )
        {
            switch (@$options[$key . '_operator']) 
            {
                case 'range':
                    if( ! empty( $where[$key][0] ) && ! empty( $where[$key][1] ) )
                    {
                        $filter = new Ayoola_Filter_Time();

                        if( 
                            ( $fieldValue >= $where[$key][1] && $fieldValue <= $where[$key][0] )

                            ||

                            ( $fieldValue <= $where[$key][1] && $fieldValue >= $where[$key][0] )
                        )
                        {
                            break;
                        }
                    }   
                    return false;
                break;
                case '!=':
                    if( ! is_array( $where[$key] ) && $where[$key] == $fieldValue )
                    { 
                        return false; 
                    }
                    elseif( is_array( $where[$key] ) && in_array( $fieldValue, $where[$key] ) )
                    {
                        return false; 
                    }
                break;
                default:

                    if( ! is_array( $where[$key] ) && $where[$key] != $fieldValue )
                    { 
                        return false; 
                    }
                    elseif( is_array( $where[$key] ) && ! in_array( $fieldValue, $where[$key] ) )
                    {
                        return false; 
                    }
                break;
            }
        }
        else
        {
            //	An array is matched if a single member is present.
            switch( @$options[$key . '_operator'] )
            {
                case '!=':
                    if( ! is_array( $where[$key] ) && in_array( $where[$key], $fieldValue ) )
                    {
                        //	only the record is array
                        return false; 
                    }
                    elseif( is_array( $where[$key] ) && array_intersect( $where[$key], $fieldValue) )
                    {
                        return false; 
                    }
                break;
                default:
                    if( ! is_array( $where[$key] ) && ! in_array( $where[$key],$fieldValue ) )
                    {
                        //	only the record is array
                        return false; 
                    }
                    elseif( is_array( $where[$key] ) && ! array_intersect( $where[$key], $fieldValue ) )
                    {
                        //	both element are arrays
                        return false; 
                    }
                break;
            }
        }
        return true;
    }

    /**
     * Does the select work
     *
     * @param array Fields to fetch
     * @param array Where clause as array
     * @param array Select options
     */
    public function doSelect( $fieldsToFetch = null, Array $where = null, Array $options = null )
    {
		//	Calculate the total fields on the table, extended
		$allFields = $this->query( 'FIELDLIST' );

        if( is_null( $fieldsToFetch ) )
        { 
            $fieldsToFetch = $allFields; 
        }
        elseif( is_array( $fieldsToFetch ) &&  is_array( $where ) )
        {
            $fieldsToFetch = array_unique( array_merge( $fieldsToFetch, array_keys( $where ) ) );
        }
		$rows = array();

        $nextRecord = $this->getRecords()->lastChild;
        while( $nextRecord )
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
            $keyFound = array();
            $whereNotFound = array();
			foreach( $eachRecord->childNodes as $countField => $field )
			{
				$keyCount++;
				$key = self::getFieldKey( $field );
                if( is_array( $fieldsToFetch ) && ! in_array( $key, $fieldsToFetch ) )
                {
                    continue; 
                }
                if( is_string( $fieldsToFetch ) && $key !== $fieldsToFetch )
                {
                    if( empty( $options['row_id_column'] ) || $key !== $options['row_id_column'] )
                    {
                        continue; 
                    }
                }
                $fieldValue = null;		

				foreach( $field->childNodes as $value )
				{ 
					if( $value instanceof DOMCDATASection )
					{ 
						$fieldValue = is_string( $value->data ) ? htmlspecialchars_decode( $value->data ) : $value->data;
						break; 
					} 
				}
				
				$fieldValue = self::filterDataType( $fieldValue, $this->getTableDataTypes( $key ) );
				$searchTerm = $fieldValue;
				$otherData = array();
				if( ! empty( $options['key_filter_function'][$key] ) && is_callable( $options['key_filter_function'][$key] ) )
 				{
					//	manipulate them before finally recording them
					$filterFunction = $options['key_filter_function'][$key];

					call_user_func_array( $filterFunction, array( &$fieldValue, &$otherData, &$searchTerm )  );

					if( is_array( $otherData ) )
					{
						$fields += $otherData;
					}
                }
				do
				{
					if( ! empty( $where['*'] ) )
					{
						$recordMatch = $recordMatch ? : false;
						if( ! is_array( $searchTerm ) )
						{
							if( ! is_array( $where['*'] ) ) 
							{
								if( stripos( $searchTerm, $where['*'] ) !== false )
								{ 
									$fields['pc_search_score'] += 1;
									$recordMatch = true;

								}
								else
								{

								}
							}
							else
							{
                                $slimer = array( ' ', ',', '-', '_', '"', '\'' );
								$phrase = implode( ' ', $where['*'] );
								$phrase = str_replace( $slimer, '', $phrase );
								$searchTermSlim = str_replace( $slimer, '', $searchTerm );

								if( stripos( $searchTermSlim, $phrase ) !== false )
								{ 

									$fields['pc_search_score'] += 200;

									$recordMatch = true;

								}
							    foreach( $where['*'] as $keyword )
								{
									if( stripos( $searchTerm, $keyword ) !== false )
									{ 
                                        $fields['pc_search_score'] += ( 2 * strlen( $keyword ) );
                                        if('article_title' === $key )
                                        {
                                            $fields['pc_search_score'] +=  ( 5 * strlen( $keyword ) );
                                        }
                                        elseif( stripos( '_title', $key ) !== false )
                                        {
                                            $fields['pc_search_score'] += ( 2 * strlen( $keyword ) );
                                        }
                                        elseif( stripos( '_name', $key ) !== false )
                                        {
                                            $fields['pc_search_score'] +=  ( 2 * strlen( $keyword ) );
                                        }
										$recordMatch = true;

									}
								}

							}
						}
					}
					if( ! empty( $where ) )
					{ 
						if( array_key_exists( $key, $where ) )
						{
                            $keyFound[$key] = true;
                            if( ! self::where( $key, $fieldValue, $where, $options ) )
                            {
                                if( $options['where_join_operator'] === '||' )
                                {
                                    $whereNotFound[] = $key;
                                }
                                else
                                {
                                    continue 3;
                                }
                            }
						}
                        elseif( @$options['supplementary_data_key'] == $key && is_array( $fieldValue ) )
                        {
                            foreach( $where as $eachKeyWhere => $valueWhere )
                            {
                                if( in_array( $eachKeyWhere, $allFields ) )
                                {
                                    //  this is supplementary search
                                    //  don't check what is going to be checked later in normal search
                                    continue;
                                }
                                if( array_key_exists( $eachKeyWhere, $fieldValue ) )
                                {
                                    $keyFound[$key] = true;
                                    if( ! self::where( $eachKeyWhere, $fieldValue[$eachKeyWhere], $where, $options ) )
                                    {
                                        if( $options['where_join_operator'] === '||' )
                                        {
                                            $whereNotFound[] = $key;
                                        }
                                        else
                                        {
                                            continue 4;
                                        }
                                    }
                                }
                            }
                        }
					}
				}
				while( false );
    
				//	Retrieve values from the foreign keys
				foreach( $this->getForeignKeys() as $foreignTable => $foreignKey )
				{
					if( $key != $foreignKey ){ continue; }
					$foreignWhere = array( $foreignKey => $fields[$foreignKey] );
					if( empty( $temp[serialize( $foreignWhere )] ) )
					{ 
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

									continue 2; 
								}
							}
						}
					}
					$fields = array_merge( $foreignData, $fields );
				}
                if( ! empty( $options['row_id_column'] ) && $key === $options['row_id_column'] )
                {
                    $rowId = $fieldValue; 
                }

                if( is_string( $fieldsToFetch ) && $fieldsToFetch === $key )
                {
                    $fields = $fieldValue;
                    continue;
                }
                $fields[$key] = $fieldValue;

            }
            $whereX = $where;
            unset( $whereX['*'] );
            if( is_array( $whereX ) && count( $whereX ) !== count( $keyFound ) )
            {
                //  Trying strict matching
                //  hopefully it will help solve select errors.
                continue;
            }

            //  || search
            //  late search query effect
            if( $options['where_join_operator'] === '||' && ! empty( $whereNotFound ) && count( $whereNotFound ) >= count( $where ) )
            {
                continue;
            }

			//	Introducing a way to manipulate content of the results on this level might allow 
			//	us to be able to limit the number of times we need to loop through the results.
			//	Saving time or resources? Let's confirm if this is useful for programmers.
			if( ! empty( $options['result_filter_function'] ) && is_callable( $options['result_filter_function'] ) )
			{
				//	manipulate them before finally recording them
				$filterFunction = $options['result_filter_function'];
				$filterFunction( $rowId, $fields );   
			}

			$fields === false || ( $recordMatch === false && ! empty( $where['*'] ) ) ? null : ( $rows[$rowId] = $fields );

			if( ! empty( $options['limit'] ) && count( $rows ) >= $options['limit'] )
			{
				break;
			}
			else
			{
				@$innerOptions['limit'] = $options['limit'] - $totalRows;
			}
		}
	
		// cache result
		if( empty( $options['disable_cache'] ) && $this->cache ){ $this->setCache( $rows ); }
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
        
        
        //  reduce disk io
        //    if( ! $result )
        {
        //    return Ayoola_File_Storage::setToFalseList( $file, $result );
        }
        //    Ayoola_File_Storage::deleteFromFalseList( $file );


        Ayoola_Doc::createDirectory( dirname( $file ) );
		return @Ayoola_File::putContents( $file, serialize( $result ) );
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
		$cTime = time();

		foreach( $this->getGlobalFilenames() as $tableFile )
		{

			if( ! is_file( $tableFile ) )
			{
				continue;
			}
			$fileMTime = @filemtime( $tableFile );
			if( ! ( $fileMTime ) )
			{
				continue;
			}
			if( $cacheTime <= $fileMTime && ( ! $classCachePeriod || ( $classCachePeriod + $fileMTime <= $cTime & stripos( $tableFile, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) !== false ) ) )
			{ 
                //    Ayoola_File_Storage::deleteFromFalseList( $cacheFile );

				@unlink( $cacheFile ); 
				break;
			}
        }
        //    if( ! $falseResult = Ayoola_File_Storage::getFromFalseList( $cacheFile ) )
        {
        //        return $falseResult; 
        }
		return @unserialize( file_get_contents( $cacheFile ) );
    } 
	// END OF CLASS
}
