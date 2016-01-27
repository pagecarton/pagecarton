<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Mysql.php 1.23.12 8.11 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Interface
 */
 
require_once 'Ayoola/Dbase/Adapter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Mysql
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Mysql extends Ayoola_Dbase_Adapter_Abstract
{
    /**
     * Link to the database connection
     *
     * @var resource
     */
	protected $_link;
	
    /**
     * Last Database query
     *
     * @var string
     */
	protected $_lastQuery;
	
    /**
     * last prepared statement
     *
     * @var object
     */
	protected $_stmt;
	
    /**
     * Result from the last query
     *
     * @var object
     */
	protected $_lastResult;
	
    /**
     * Id from the last insert operation
     *
     * @var int
     */
	protected $_lastInsertId;

    /**
     * Constructor
     *
     * @param array Database Info
     * 
     */
    public function __construct( $databaseInfo = null )
    {
		if( ! is_null( $databaseInfo ) ){ $this->setDatabaseInfo( $databaseInfo ); }
    }
		
    /**
     * This method sets _link to a Value
     *
     * @param MySqli Link
     * @return null
     */
    public function setLink( $link = null )
    {
		if( ! is_resource( $link ) )
		{
		//	var_export( $this->getDatabaseInfo() );
		//	echo $this->getDatabaseInfo( 'username' );
			$link = mysqli_connect( $this->getDatabaseInfo( 'hostname' ), 
				$this->getDatabaseInfo( 'username' ), 
				$this->getDatabaseInfo( 'password' ), 
				$this->getDatabaseInfo( 'database' ) );
		}
		if ( $link )
		{ 
			// Ensure charset is utf-8
			mysqli_set_charset ( $link , 'utf8' );
			return $this->_link = $link; 
		}
		//	var_export( $this->getDatabaseInfo() );
		require_once 'Ayoola/Dbase/Adapter/Exception.php';
		throw new Ayoola_Dbase_Adapter_Exception( 'CONNECTION FAILED TO DATABASE - ' . $this->getDatabaseInfo( 'database' ) );
    } 
	
    /**
     * This method returns _link
     *
     * @return MySqli Link
     */
    public function getLink()
    {
		if( is_null( $this->_link ) ){ $this->setLink(); }
		return $this->_link;
    } 
	
	/* 
	Connects to the database and 
	returns the link for that connection.
	@return resource
	*/	
    public function select( $databaseName = '' )
	{
		$databaseName = $databaseName ? : $this->getDatabaseInfo( 'database' );
		if ( mysqli_select_db( $this->getLink(), $databaseName ) ){ return true; }
		return true;
		require_once 'Ayoola/Dbase/Adapter/Exception.php';
		throw new Ayoola_Dbase_Adapter_Exception( 'CANNOT SELECT DATABASE - ' . $databaseName );
	}
        
    public function query( $query )
	{
		$query = (string) $query;
		$this->_lastQuery = $query;
	//	print( $query );
		$result = mysqli_query( $this->getLink(), $query );
		$this->_lastResult = $result;
		if( $result )
		{
			$this->_lastResult = $result;
			return $result;
		}
	//	print( $query ); 
		require_once 'Ayoola/Dbase/Adapter/Exception.php';
		throw new Ayoola_Dbase_Adapter_Exception( 'DATABASE QUERY IS NOT SUCCESSFUL' );
	}

    /**
     * Returns last insert id
     *
     * @param void
     * @return int
     */
    public function getLastInsertId()
	{
		if( is_null( $this->_lastInsertId ) ){ $this->_lastInsertId = mysqli_insert_id( $this->getLink() ); }
        return $this->_lastInsertId;                
	}

    /**
     * Fetch Records as Multidimentional Array
     *
     * @param void
     * @return mixed
     */
    public function fetchAssoc()
	{
        return mysqli_fetch_assoc( $this->_lastResult );                
	}

    public function fetchAll()
    {
		$row = array();
		while( $data = $this->fetchAssoc() ){ $row[] = $data; }
		$this->freeResult();
		return $row;
    }	
    
    public function freeResult()
	{
        return mysqli_free_result( $this->_lastResult );                
	}
    
    public function insertId()
	{
        return mysqli_insert_id( $this->getLink() );                
	}
    
    public function numRow()
	{
	//	var_export( $this->_lastResult );
        return mysqli_num_rows( $this->_lastResult );                
	}
	
    /**
     * Returns list of tables 
     *
     * @param void
     * @return array
     */
    public function listTables()
    {
        $sql= 'SHOW TABLES';
		$this->query( $sql );
		$row = $this->fetchAll();
		return $row;
		
    } 
	
    /**
     * Returns details of a table
     *
     * @param 
     * @return array
     */
    public function getTableInfo( $tablename )
    {
        $sql= 'DESCRIBE ' . $tablename;
		$this->query( $sql );
		$row = $this->fetchAll();
		return $row;
    } 
	// END OF CLASS
}
