<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_Adapter_DbaseTable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DbaseTable.php 1.23.12 1234am ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 * @see Ayoola_Access_Adapter_Interface
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';
require_once 'Ayoola/Access/Adapter/Interface.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_Adapter_DbaseTable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Adapter_DbaseTable extends Ayoola_Dbase_Table_Abstract implements Ayoola_Access_Adapter_Interface 
{
    /**
     * The table in use
     *
     * @var string
     */
	protected $_databaseTable;
	
    /**
     * The table that holds the access credentials
     *
     * @var string
     */
	protected $_tableClassName = 'Application_Dbase_Table_User';
	
    /**
     * Because it is possible to have credentials in other tables
     *
     * @var string
     */
	protected $_otherTables = 'userpassword, userpersonalinfo, useremail, socialmediauser';
	
    /**
     * Id column
     *
     * @var string
     */
	protected $_idColumn = 'id';
	
    /**
     * Value for the $_idColumn
     *
     * @var string
     */
	protected $_id = null;
		
    /**
     * The row of data for db query
     *
     * @var array
     */
	protected $_resultRow = array();

    /**
     * Constructor 
     *
     * @param array Credentials Used in Authentication 
     * @param string Specifies the Table Name
     * @param array Provide Other Tables on Adapter 
     * 
     */
    public function __construct( $credentials = null, $tableName = null, $databaseName = null )
    {
		if( ! is_null( $credentials ) ){ $this->setCredentials( $credentials ); }
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
		parent::__construct( $databaseName ); // Get the full features of Ayoola_Dbase_Table_Abstract
    }
	
    /**
     * Returns the value for other tables used in query 
     *
     * @param void
     * @return string
     */
    public function getOtherTables()
    {
        return $this->_otherTables;
    } 
	
	
    /**
     * Sets value for other tables use in the db query
     *
     * @param string
     * @return null
     */
    public function setOtherTables( $tables )
    {
		$this->_otherTables =  $tables;	
    } 
	
    /**
     * The authentication mechanism is this: 
     * The database is queried for $_id 
     * With the credentials, The rows found will be 
     * This method is an implementation of Ayoola_Acess_Adapter_Interface
     *
     * @param array The Credentials that is being Authenticated
     * @return boolean
     */
    public function authenticate( Array $credentials )
    {		
		//	Builds the SQL Query
		try
		{
			if( ! $result = $this->getDatabaseTable()->selectOne( null, 'applicationusersettings,' . $this->getOtherTables(), $credentials ) )
			{
				throw new Ayoola_Access_Adapter_Exception( 'USER NOT FOUND. WILL ATTEMPT TO CHECK WITHOUT SETTINGS' );
			}
		}
		catch( Exception $e )
		{
				//	try signin without user settings
			$result = $this->getDatabaseTable()->selectOne( null, $this->getOtherTables(), array( 'username' => $credentials['username'], 'password' => $credentials['password'], ) );
		}
		if( ! $result ){ return false; }
		$this->setResultRow( $result );
		return true;
    } 
	
    /**
     * This method retrieves the Database Table
     *
     * @param void
     * @return array
     */
    public function getDatabaseTable()
    {
		if( is_null( $this->_databaseTable ) ){	$this->setDatabaseTable(); }
        return $this->_databaseTable;
    } 
	
    /**
     * This method retrieves the credentials used in authentication
     *
     * @param Ayoola_Dbase_Table_Interface
     * @return void
     */
    public function setDatabaseTable( Ayoola_Dbase_Table_Interface $table = null )
    {
		if( is_null( $table ) )
		{
			$class = $this->getTableClassName();
			$table = new $class();
		}
        return $this->_databaseTable = $table;
    } 
	
    /**
     * Returns the result row of authentication
     *
     * @param void
     * @return array
     */
    public function getResultRow()
    {
        return $this->_resultRow;
    } 
	
	
    /**
     * Sets the resultRow Property to a value
     * @param array
     */
    public function setResultRow( Array $row )
    {
        $this->_resultRow = $row;
    } 
	
	
    /**
     * This method retrieves the id used in authentication
     *
     * @param void
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    } 
	
    /**
     * This method sets a value for the Id used in authentication 
     *
     * @param string
     */
    public function setId( $id )
    {
        $this->_id = $id;
    } 
	
    /**
     * This method retrieves the column for id
     *
     * @param void
     * @return string
     */
    public function getIdColumn()
    {
        return $this->_idColumn;
    } 
	
    /**
     * Sets the column for id
     *
     * @param string
     * @return mixed
     */
	public function setIdColumn( $columnName = null )
    {
        $this->_idColumn = $column;
    } 
	
    /**
     * Retrieves the table class name  from the object. 
     *
     * @param void
     * @return string
     */
    public function getTableClassName()
    {
        return $this->_tableClassName;
    } 
	
    /**
     * This method sets the table class name
     *
     * @param string
     * @return void
     */
    public function setTableClassName( $class )
    {
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $class ) )
		{
			require_once 'Ayoola/Access/Adapter/Exception.php';
			throw new Ayoola_Access_Adapter_DbaseTable_Exception( 'Table not a valid class - ' . $class );
		}
		$class = new $class;
 		if( ! $class instanceof Ayoola_Dbase_Table_Interface )
		{
			require_once 'Ayoola/Access/Adapter/Exception.php';
			throw new Ayoola_Access_Adapter_Exception( get_class( $class ) . ' is an invalid class for ' . __METHOD__ );
		}
       $this->_tableName = get_class( $class );
    } 
	// END OF CLASS
}
