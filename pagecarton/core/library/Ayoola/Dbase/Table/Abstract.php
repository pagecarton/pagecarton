<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 1.23.2012 1.00am ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Interface
 */
 
require_once 'Ayoola/Dbase/Table/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Table_Abstract implements Ayoola_Dbase_Table_Interface
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Table name
     *
     * @var string
     */
	protected $_tableName;

    /**
     * Database Object
     * @see Ayoola_Dbase
     * @var Ayoola_Dbase
     */
	protected $_database;
	
    /**
     * Array of Fieldlist
     * key is column name
     * @var array
     */
	protected $_fieldList;
	protected $_uKeys = array();
	protected $_pKeys = array();
	
    /**
     * Constructor
     *
     * @param string Database Name
     * 
     */
    public function __construct( $database = null )
    {		
		if( ! is_null( $database ) ){ $this->setDatabase( $database ); }
    }

    /**
     * 
     *
     * @param __CLASS__
     */
    protected static $_instance;
	
    /**
     * Get the table info
     *
     * @return array
     */
    public static function getInstance()
    {
        $class = get_called_class();
     //   var_export( get_called_class() );
        if( ! empty( static::$_instance[$class] ) )
        {
          return static::$_instance[$class];
        }

        static::$_instance[$class] = new static;
        return static::$_instance[$class];  
    }
	
    /**
     * Gets a _database property 
     *
     * @param void
     * @return Ayoola_Database
     */
    public function getDatabase()
    {
		if( is_null( $this->_database ) )
		{
			require_once 'Ayoola/Dbase.php';
			$this->setDatabase( new Ayoola_Dbase() ); 
		}
		return $this->_database;
    } 
	
    /**
     * Sets the _database property 
     *
     * @param Ayoola_Dbase
     */
    public function setDatabase( Ayoola_Dbase $database )
    {
		return $this->_database = $database;
    } 

    /**
     * returns the table name
     *
     * @param void
     * @return string
     */
    public function getTableName()
    {
 		//var_export( $this->_tableName );
		if( is_null( $this->_tableName ) )
		{ 
			$this->setTableName();
		}
		return $this->_tableName;
	} 

    /**
     * Sets a table name
     *
     * @param string
     * @return string
     */
    public function setTableName( $name = null )
    {
		//var_export( $this->_tableName );
		if( is_null( $name ) )
		{ 
			$name = strtolower( get_class($this) );
			$name = trim( substr( $name, strrpos( $name, '_' ) ), '_' );
		}
		$this->_tableName = $name;
    } 
	
    /**
     * Sets the fieldlist
     *
     * @param array
     * @return array
     */
    public function setFieldList( Array $fields =array() )
    {
        if( $fields ){ $this->_fieldList = $fields; }
		$tableInfo = $this->getDatabase()->getTableInfo( $this->getTableName() );
		$fields = array();
		$primaryKeys = array();
		$uniqueKeys = array();
		foreach( $tableInfo as $values ):
			foreach( $values as $key => $value ):
				$this->_fieldList[$values['Field']] = $values;
				if( $values['Key'] === 'PRI' ):
				
					$primaryKeys[$values['Field']] = NULL;
					$uniqueKeys[$values['Field']] = NULL;
					continue;
				endif;
			$fields[$values['Field']] = NULL;
			endforeach;
		endforeach;
		
		$this->_pKeys = $primaryKeys;
		$this->_uKeys = $uniqueKeys;
	} 

    /**
     * retrieves the fieldlist
     *
     * @param string Optional The Column Name
     * @return string | array
     */
    public function getFieldList( $column = null )
    {
		if( is_null( $this->_fieldList ) )
		{ 
			$this->setFieldList();
		}
        return array_key_exists( $column, $this->_fieldList ) ? $this->_fieldList[$column] : $this->_fieldList;
    } 

    /**
     * Overloading the Methods
     *
     * @throws Ayoola_Dbase_Exception
     */
    public function __call( $name, $arguments) 
	{
	//	var_export( func_get_args() );
		if( method_exists( $this->getDatabase()->getAdapter(), $name ) )
		{
			return call_user_func_array( array( $this->getDatabase()->getAdapter(), $name ), $arguments );
		}
		require_once 'Ayoola/Dbase/Table/Exception.php';
		throw new Ayoola_Dbase_Table_Exception( 'Invalid Method For Database - ' . $name );
    }
	// END OF CLASS
}
