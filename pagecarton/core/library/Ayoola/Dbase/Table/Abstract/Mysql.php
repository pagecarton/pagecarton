<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Abstract_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Mysql.php 1.23.12 8.11 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Abstract_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Table_Abstract_Mysql extends Ayoola_Dbase_Table_Abstract 
{

	
    /**
     *
     * @var boolean
     */
	protected static $_allowApiAccess = false;

	
    /**
     * Database info 
     *
     *
     * @var array
     */
	protected static $_dbInfo = array( 'database' => '', 'username' => '', 'password' => '', 'hostname' => '', 'adapter' => 'mysql', );
		
    /**
     * Allowed Query Syntax
     *
     * @var array
     */
	protected $_querySyntax = array( 'FROM' => null, 'SELECT' => null, 'DELETE' => null, 
									'WHERE' => null, 'INSERT' => null, 'LIMIT' => null,
									'GROUP' => null, 'ORDER' => null );
		
    /**
     * This method populates a table by updating, inserting, ets
     *
     * @param string The Method used in populating the table
     * @return mixed
     */
    public function populateTable( $method, Array $values, $where = null )
    {
		$this->setQuerySyntax();
		$where ? $this->addWhereClause( $where ) : null;
		$where = $this->getQuerySyntax( 'WHERE' );

		//	Builds the $field = $var pairs
		$pair = '';
		$multiInsert = false;
		$pairField = ' ( ';
		$fullMultiInsertValue = '';
			//	echo $pair;
        foreach( $values as $field => $value )
		{
			if( is_array( $value ) ) //	Multi Insert
			{
			//	var_export( $value );
				$pairValue = '( ';
				foreach( $value as $multiInsertField => $multiInsertValue )
				{
					if( array_key_exists( $multiInsertField, $this->getFieldList() ) )
					{
						if( ! $multiInsert )
						{
					//		var_export( $multiInsertField );
							$pairField .= " `{$multiInsertField}` ,"; // After first round, we are OK.
						}
						$pairValue .= " '{$multiInsertValue}' ,";
					}
					else
					{
					//	var_export( $multiInsertField );
					}
				}
				$pairValue = rtrim( $pairValue, ',' ) . ' )';
				$fullMultiInsertValue .= $pairValue . ', ';
				$multiInsert = true;
			}
			else
			{
				if( array_key_exists( $field, $this->getFieldList() ) )
				{
					$pair .= " `{$field}` = '{$value}' ,";
				}
			}
		}
		if( $multiInsert )
		{
			$pairField = rtrim( $pairField, ',' ) . ' ) ';
			$fullMultiInsertValue = rtrim( $fullMultiInsertValue, ', ' ) . ' ';
			$pair = rtrim( $pairField . ' VALUES ' . $fullMultiInsertValue, ', ' );
		}
		else
		{
			$pair = 'SET ' . rtrim( $pair, ', ' );
		
		}
		
		$sql = " {$method} `{$this->getTableName()}` 
				{$pair} 
				{$where} ";
	//	echo( $sql );
		$result = $this->getDatabase()->query( $sql );
		return $result;        
    } 
	
    /**
     * Adds a row to the table
     * 
     * @param array
     * @return mixed
     */
    public function insert( Array $values )
    {
		return $this->populateTable( 'INSERT INTO ', $values );
    } 
	
    /**
     * Updates one or more rows
     * 
     * @param array
     * @return boolean
     */
    public function update( Array $values, $where )
    {
		return $this->populateTable( 'UPDATE ', $values, $where );
    } 
	
    /**
     * Deletes one or more rows
     * 
     * @param string
     * @return boolean
     */
    public function delete( $where )
    {
		$this->addWhereClause( $where );
		$where = $this->getQuerySyntax( 'WHERE' );
		$sql = "DELETE FROM `{$this->getTableName()}` 
				$where 
				";
		return $this->getDatabase()->query( $sql );
    } 
	
    /**
     * Fetchs Records from Database
     * 
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function fetchRecords( $amount, $fields = NULL, $tables = NULL, $where = NULL, $group = NULL, $sort = NULL, $limit = NULL )
    {
		$fields = $fields ? : '*';
		$fields = rtrim( $fields, ',' );
		$tables = $tables ? : NULL;
		$this->setQuerySyntax();
		$where ? $this->addWhereClause( $where ) : null;
		$where = $this->getQuerySyntax( 'WHERE' );
		$group = $group ? "GROUP BY $group" : NULL;
		$sort = $sort ? "ORDER BY $sort" : NULL;
		$limit = $limit ? "LIMIT $limit " : NULL;
        //var_export( $where );
        //var_export( '<br />' );
		$tablesArray = toArray( $tables );
		if( ! empty( $tablesArray ) ):
		$tables = '';
		$tableList ='';
		$this->getFieldlist();
		$keys = array_keys( $this->_pKeys );
			foreach( $tablesArray as $table => $value )
			{
				if( strlen( $table ) > 2 )
				$tableList .= " LEFT JOIN `$table` USING( `$keys[0]` ) ";
			}
		endif;
		$sql = "SELECT $fields 
				FROM `{$this->getTableName()}` $tableList 
				$where 
				$group 
				$sort 
				$limit ";
		//var_export( $sql );
		return $this->fetchSQLQuery( $sql, $amount );
    } 
	
    /**
     * Total number of records
     * 
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function countRecords( $fields = NULL, $tables = NULL, $where = NULL, $group = NULL, $sort = NULL, $limit = NULL )
    {
		return $this->fetchRecords( 1, 'COUNT(*),' . $fields, $tables, $where, $group, $sort, $limit );
    } 

    /**
     * Selects single record from table
     * Returns the array of fetched data
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function selectOne( $fields = NULL, $tables = NULL, $where = NULL, $group = NULL, $sort = NULL, $limit = NULL )
    {
		if( $record = $this->fetchRecords( 1, $fields, $tables, $where, $group, $sort, $limit ) )
		{
			return array_shift( $record );
		}
    } 

    /**
     * Selects Record from table
     * Returns the array of fetched data
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function select( $fields = NULL, $tables = NULL, $where = NULL, $group = NULL, $sort = NULL, $limit = NULL )
    {
		return $this->fetchRecords( 9999, $fields, $tables, $where, $group, $sort, $limit );
    } 
	
    /**
     * Fetches record from DB 
     * 
     * @param string
     * @return array
     */
	public function fetchSQLQuery( $query, $amount = null )
    {
		$this->getDatabase()->query( $query );
		$rows = array();
		$rowsCount = 0;
		while( $data = $this->getDatabase()->fetchAssoc() )
		{ 
			++$rowsCount;
			$rows[] = $data; 
			if( $rowsCount == $amount ){ break; }
		}
		$this->getDatabase()->freeResult();
	//	var_export( $rows );
		return $rows;
	} 
	
    /**
     * Get the SQL syntax that is safe for query
     * 
     * @return string | array
     */
	public function getQuerySyntax( $key )
    {
		return array_key_exists( $key, $this->_querySyntax ) ? $this->_querySyntax[$key] : $this->_querySyntax;
	} 
	
    /**
     * Set the SQL syntax that is safe for query
     * 
     * @param string
     * @param string
     */
	public function setQuerySyntax( $syntax = null, $value = null )
    {
		if( array_key_exists( $syntax, $this->_querySyntax ) )
		{
			$this->_querySyntax[$syntax] = $value;
		}
		elseif( is_null( $syntax ) )
		{
			foreach( $this->_querySyntax as $key => $value ){ $this->_querySyntax[$key] = null;  }
		}
	} 
	
    /**
     * Sanitize the SQL syntax that is safe for query
     * 
     * @param array
     * @return array
     */
	public function sanitizeQuerySyntax( $syntax )
    {
		foreach( $this->getQuerySyntax() as $key => $allowedSyntax )
		{
			$syntax[$key] = array_key_exists( $key, $syntax ) ? $key . $syntax[$key] : null;
		}
		return $syntax;
	} 
	
    /**
     * Updates the where clause to be used in query
     * 
     * @param mixed
     */
	public function addWhereClause( $where )
    {
		$clauseName = 'WHERE';
		$oldClause = $this->getQuerySyntax( $clauseName );
		$oldClause = $oldClause ? $oldClause . ' AND ' : $clauseName;

		if( is_array( $where ) )
		{
			$newClause = null;
			$count = 0;
			foreach( $where as $key => $value )
			{
				++$count;
				
				//	Array means it could be multiple values
				if( is_array( $value ) && $value )
				{
				//	$eachValue = null;
					$i = 0;
					$newClause .= " ( ";
					foreach( $value as $eachValue ) 
					{
						++$i;
						$newClause .= " " . $key . " = '" . $eachValue . "'";
					//	$newClause .= count( $value ) < $i ? ' OR ' : NULL;  
						$newClause .= count( $value ) > $i ? ' OR ' : NULL;  
				//		$newClause .=  ' OR ';
					}
					$newClause .= " ) ";
				}
				else
				{
					$newClause .= " " . $key . " = '" . $value . "'";
				}
				$newClause .= count( $where ) != $count ? ' AND ' : NULL;
				//$newClause .= count( $key ) == $count ? ' AND ' : NULL;
			}
			$where = $newClause;
		}
		$where = $oldClause . ' ' . $where;
		$this->setQuerySyntax( $clauseName, $where );
	} 
	
    /**
     * Select Records from Database table using prepared statements
     * 
     * @return array
     */
	public function selectWithStatement( $syntax, Array $values = array() )
    {
		$columnsForResultBinding = empty( $syntax['SELECT'] ) ? array_keys( $this->getFieldList() ) : $syntax['SELECT'];
		$columnsForResultBinding = is_array( $columnsForResultBinding ) ? $columnsForResultBinding : explode( ', ', $columnsForResultBinding );
		
		//	Make Column names variablename
		extract( array_flip( $columnsForResultBinding ), EXTR_PREFIX_ALL, __FUNCTION__ );
		
		$syntax = $this->sanitizeQuerySyntax( $syntax );
		$where = 'WHERE';
		$dataTypes = null; // For Binding Parameters
		foreach( $values as $key => $value )
		{
			//	Bind Parameters
			$dataType = gettype( $value );
			$dataType = $dataType[0];
			$dataTypes .= $dataType;
			
			//	Bind Results
			
			
			
			$syntax[$where] = $syntax[$where] ? $syntax[$where] . ' AND ' : $this->getQuerySyntax( $where );	
			$preparedWhere .= ' $key = ? ';
		}
		$query = $syntax['SELECT'] . $syntax['FROM'] . $syntax[$where] . $syntax['ORDER'] . $syntax['GROUP'] . $syntax['LIMIT'];
		$this->prepareStatement( $query );
		$this->bindParameters( $dataTypes, implode( ', ', $values ) );
		$this->statementExecute();
		$this->bindResults();
		while( $this-fetchStatementResult() )
		{
			$result[] = getBoundResult();
		}
		$this->statementClose();
		$this->getDatabase()->close();
	} 
	
    /**
     * Prepare a statement for SQL execution
     * 
     * @return boolean
     */
	public function prepareStatement( $query )
    {
		$this->getDatabase()->prepareStatement( $query );
    } 
	
    /**
     * 
     * @return boolean
     */
	public static function isApiConnectionAllowed()
    {
		return static::$_allowApiAccess;
    } 
	
    /**
     * Bind Parameters
     * 
     * @param void
     * @return boolean
     */
	public function bindParameters( $dataTypes, $values )
    {
		return $this->getDatabase()->bindParameters( $dataTypes, implode( ', ', $values ) );
    } 
	
    /**
     * Bind Results
     * 
     * @param array Column - Value Pairs
     * @return boolean
     */
	public function bindResults( Array $values )
    {
		$dataTypes = null;
		foreach( $values as $key => $value )
		{
			$dataType = gettype( $value );
			$dataType = $dataType[0];
			$dataTypes .= $dataType;
		}
		return $this->getDatabase()->bindParameters( $dataTypes, implode( ', ', $values ) );
    } 
	
    /**
     * 
     * 
     * @param void
     * @return array
     */
	public static function getDbInfo()
    {
		return static::$_dbInfo;
    } 
	
	// END OF CLASS
}
