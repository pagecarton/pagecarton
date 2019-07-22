<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Abstract_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Xml.php 4.9.12 11.52 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 * @see Ayoola_Dbase_Table_Abstract_Exception
 */

require_once 'Ayoola/Dbase/Table/Abstract.php';
require_once 'Ayoola/Dbase/Table/Abstract/Exception.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Abstract_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Table_Abstract_Xml extends Ayoola_Dbase_Table_Abstract implements Ayoola_Dbase_Table_Interface
{

    /**
     * The DataTypes of the Table
     *
     * @param array
     *
     */
    protected $_dataTypes;

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */

    /**
     * The Version of the general table module
     *
     * @param int
     */
    protected static $_version = '1.00';

    /**
     * The Accessibility of the Table
     *
     * @param string
     */
    protected $_accessibility = SELF::DEFAULT_SCOPE;

    /**
     *
     *
     * @param array
     */
    protected static $_tableInfo;

    /**
     *
     *
     * @param bool
     */
    protected static $_alreadyRan;

    /**
     * This property determines how this table relates to other table
     *
     * @param string
     */
    protected $_relationship = SELF::DEFAULT_SCOPE;

    /**
     * Table version number
     *
     * @param string
     */
    protected $_tableVersion = '0.01';

    /**
     * Time to hold the cache before refreshing
     *
     * @param int
     */
    public static $cacheTimeOut;

    const DEFAULT_SCOPE   = SELF::SCOPE_PRIVATE;
    const SCOPE_PRIVATE   = 'PRIVATE';
    const SCOPE_PROTECTED = 'PROTECTED';
    const SCOPE_PUBLIC    = 'PUBLIC';

    /**
     * Constructor
     *
     * @param
     *
     */
    public function __construct()
    {
        //    var_export( get_called_class() );
        //    var_export( "\r\n" );

        // wish i can do this...
        //    $this = static::getInstance();
        //    get_class( $this );

        //   if( empty( static::$_alreadyRan[get_class( $this )] ) )
        {
            //     var_export( get_class( $this ) );
            //  everything here needs to be ran only once
            $this->initOnce();
            static::$_alreadyRan[get_class($this)] = true;
        }
    }

    /**
     * Initialize the Table
     *
     * @param void
     */
    public function init()
    {
        //    We are using the XML Adapter
        require_once 'Ayoola/Dbase.php';
        $database = new Ayoola_Dbase(array('adapter' => 'Xml'));
        parent::__construct($database);
        $this->selectDatabase();
        $adapter = $this->getDatabase()->getAdapter();
        $adapter->setTableName($this->getTableName());
        $adapter->setAccessibility($this->_accessibility);
        $adapter->setRelationship($this->_relationship);
        $adapter->_resultKeyReArrange = true;
        //var_export( self::$_accessibility );
        //    var_export( $this->_accessibility );
        //    Attempts to create the table if not exist
        static::$_tableInfo = $this->query('TABLE', 'DESCRIBE');

        //    Attempts to automagically update table versions
        do {
            //        var_export(  static::$_tableInfo );
            //    exit();
            $backupFile = static::$_tableInfo['filename'] . '.backup';
            //            var_export( $backupFile );
            //      exit();

            if (!$this->exists() && !file_exists($backupFile))
            //        if( ! $this->exists() )
            {
                //   var_export(  static::$_tableInfo );
                //    cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
                break;
            }
            if(
                ! empty( static::$_tableInfo['table_info'] ) && ( empty( static::$_tableInfo['table_info']['table_version'] ) || empty( static::$_tableInfo['table_info']['module_version'] ) ) ) 
            {
                break;
            }
            if(
                ! empty( static::$_tableInfo['table_info'] ) && ( @static::$_tableInfo['table_info']['table_version'] === $this->_tableVersion && @static::$_tableInfo['table_info']['module_version'] === self::$_version)
            ) 
            {
                break;
            }
            //  don't recreate if we are locked
            $xml = new Ayoola_Xml();
            if( ! $xml->load( static::$_tableInfo['filename'] ) )
            {
                break;
            }
            if(
                empty( static::$_tableInfo['table_info'] )
            ) 
            {
                //  for some reasons this is not set after file lock is removed on article views
                break;
            }

            //
            $previousAccessibility = $this->_accessibility;
            $previousRelationship  = $this->_relationship;
            $adapter->setAccessibility(self::SCOPE_PRIVATE);
            $adapter->setRelationship(self::SCOPE_PRIVATE);
            $adapter->cache = false;
            $values         = $this->select();
            //    var_export( $values );
            //   var_export( static::$_tableInfo['filename'] );
            //  exit();

            //    Backup the previous table
            if (!empty(static::$_tableInfo['filename'])) {
                $backupFile = static::$_tableInfo['filename'] . '.backup';
                if (file_exists($backupFile)) {
                    //      self::v( $backupFile );

                    //    if( time() - filemtime( $backupFile ) < 86400 )
                    if (time() - filemtime($backupFile) < 86400) {
                        //    Backup in progress. Don't duplicate progress unless its more than one day
                        break;
                    } else {
                        //    Something has gone wrong, restore the backup automagically.
                        $values = include $backupFile;
                        //    var_export( $values );
                        $newBackUpFile = $backupFile . '_error_' . filemtime($backupFile);
                        rename($backupFile, $newBackUpFile);
                        //      var_export( $backupFile );
                        //    var_export( $newBackUpFile );
                        //    exit;
                        Application_Log_View_Error::log("There is an error on an XML Database. The back up file {$backUpFile} as been copied to {$newBackUpFile} for safe keep.");
                        //           var_export(  static::$_tableInfo );

                    }
                } else {
                    file_put_contents($backupFile, '<?php return ' . var_export($values, true) . ';');
                }
                //    copy(  static::$_tableInfo['filename'], $backupFile );
            }
            //    Store the values in the backup file
            //    var_export( static::$_tableInfo );
            //    var_export( $values );
            try
            {
                $this->drop();
            } catch (Exception $e) {
                null;
            }
            try
            {
                $this->create();
            } catch (Exception $e) {
                null;
            }

            //    exit( __LINE__ );
            set_time_limit(86400); //    We may need time to update a very large table
            //    ignore_user_abort( true );
            foreach ($values as $each) {
                //    $this->insert( $each, array( 'record_row_id' => $each[$this->getTableName() . '_id'] ) );
                //    set_time_limit( 30 );
                try
                {
                    $this->insert($each);
                } catch (Exception $e) {
                    null;
                }
            }
            if (count($values) === count($this->select())) {
                unlink($backupFile);
                //    var_export( $values );
                //    var_export( $this->select() );
                //    copy( $backupFile,  static::$_tableInfo['filename'] ) ? : copy( $backupFile, $backupFile . '.backup' );
            } else {
                //        var_export( $values );
                //        var_export( $this->select() );
            }
            $adapter->setAccessibility($previousAccessibility);
            $adapter->setRelationship($previousRelationship);
        } while (false);
        //     var_export(  $_GET['show_class_data'] === get_class( $this  ) );
        //     PageCarton_Widget::v( $_GET['show_class_data'] );
        //     PageCarton_Widget::v( get_class( $this  ) );

        if (isset($_GET['show_class_data']) && ($_GET['show_class_data'] === get_class($this))) {
            $records = $this->query('TABLE', 'FETCH');
            //    var_export( $records );

            if (!empty($_GET['pc_form_values']) && !empty($_GET['pc_form_labels'])) {
                //    var_export( $_GET['pc_form_values'] );
                $filter  = new Ayoola_Filter_SelectListArray($_GET['pc_form_values'], $_GET['pc_form_labels']);
                $records = $filter->filter($records);
            }
            //    var_export( $records );
            //    var_export( $_GET );
            //    krsort( $records );
            $this->_objectTemplateValues = $this->_objectData = $records;
            //    var_export( $this->getParameter() );
            $this->setViewContent($this->query('TABLE', 'VIEW', $fieldsKey, $where));
        } elseif (isset($_SERVER['HTTP_AYOOLA_PLAY_CLASS']) && ($_SERVER['HTTP_AYOOLA_PLAY_CLASS'] === get_class($this))) {
            $this->setViewContent(  '' . self::__( '<a href="?show_class_data=' . get_class($this) . '">View Table Data for ' . get_class($this) . '</a>' ) . '' );
        }
        //    var_export( $_SERVER['HTTP_AYOOLA_PLAY_CLASS'] );

    }

    /**
     * Returns true if the table exists
     *
     * @return boolean
     */
    public function exists()
    {
        return $this->query('TABLE', 'EXISTS', $this->getTableName());
    }

    /**
     * Select all records matching a criteria
     *
     * @param Array Fields to Select
     * @param Array The Criteria
     * @return Array
     */
    public function select(array $fieldsToSelect = null, array $where = null, array $options = null)
    {
        try
        {
            $result = $this->query('TABLE', 'FETCH', $fieldsToSelect, $where, $options);
        } catch (Exception $e) {
            return array();
        }

        //    krsort( $result );

        //      return array_map( 'unserialize', array_unique( array_map( 'serialize', $result ) ) );
        return $result;
    }

    /**
     * Select one records matching a criteria
     *
     * @param Array The Criteria
     * @return Array
     */
    public function selectOne(array $fieldsToSelect = null, array $where = null, array $options = null)
    {
        $options['limit'] = 1;
        $data             = $this->select($fieldsToSelect, $where, $options);
        if (!empty($data)) {$data = array_shift($data);}
        return $data;
    }

    /**
     * Insert a record into the database
     *
     * @param Array Values
     * @return boolean
     */
    public function insert(array $values, $options = null)
    {
        if (!$this->exists()) {
            try
            {
                $this->create();
            } catch (Ayoola_Dbase_Table_Abstract_Exception $e) {
                null;
            }
        } else {
            //    var_export( $this->exists() );
        }
        $values                                = $this->filterValues($values);
        $result                                = $this->query('TABLE', 'INSERT', $values, $options);
        static::$_alreadyRan[get_class($this)] = true;
        return $result;
    }

    /**
     * Updates one or more record in the database
     *
     * @param Array Values
     * @param Array Criteria
     * @return boolean
     */
    public function update(array $values, array $where = null)
    {
        if (!$this->exists()) {

            //    cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
            return false;
        }
        $values                                = $this->filterValues($values);
        $result                                = $this->query('TABLE', 'UPDATE', $values, $where);
        static::$_alreadyRan[get_class($this)] = true;
        return $result;
    }

    /**
     * Deletes on or more records in the database
     *
     * @param Array Criteria
     * @return boolean
     */
    public function delete(array $where = null, array $options = null)
    {
        if (!$this->exists()) {

            //    cannot throw error again since we are not auto-creating tables again. There's possibility that table isn't available
            return false;
        }
        $result                                = $this->query('TABLE', 'DELETE', $where, $options);
        static::$_alreadyRan[get_class($this)] = true;
        return $result;
    }

    /**
     * Creates a table in the current database
     *
     * @param Array Datatypes
     * @return boolean
     */
    public function create(array $dataTypes = null)
    {
        if (is_null($dataTypes)) {$dataTypes = $this->getDataTypes();}
        //    exit( get_class( $this ) );
        //    exit( var_export( $dataTypes ) );
        //var_export( $this->_tableVersion );
        //    var_export( $dataTypes );
        $result = $this->query('TABLE', 'CREATE', array('table_name' => $this->getTableName(),
            'table_version'                                              => $this->_tableVersion,
            'module_version'                                             => self::$_version,
            'table_class'                                                => get_class($this)), $dataTypes);
        static::$_alreadyRan[get_class($this)] = true;
        return $result;
    }

    /**
     * Alters a table
     *
     * @param string Table Name
     * @param Array Datatypes
     * @return boolean
     */
    public function alter($tableName = null, array $dataTypes = null)
    {
        $result = $this->query('TABLE', 'ALTER', $tableName, $dataTypes);
        if (!is_null($tableName)) {$this->getDatabase()->getAdapter()->setTableName($tableName);}
        static::$_alreadyRan[get_class($this)] = true;
        return $result;

    }

    /**
     * Destroys table
     *
     * @return boolean
     */
    public function drop()
    {
        $result                                = $this->query('TABLE', 'DROP');
        static::$_alreadyRan[get_class($this)] = true;
        return $result;
    }

    /**
     * Get the table info
     *
     * @return array
     */
    public function describe()
    {
        return $this->query('TABLE', 'DESCRIBE');
    }

    /**
     * Select Classname as the database
     *
     * @param Classname
     */
    public function selectDatabase($className = null)
    {
        if (is_null($className)) {
            $className = get_class($this);
            $className = explode('_', $className);
            array_pop($className);
            $className = implode('_', $className);
            //    if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
            {
                //        var_export( $className );
                //        var_export( get_class( $this ) );
            }
        }
//    if( ! $class = Ayoola_Loader::loadClass( $className ) ){ $className = null; }
        $this->getDatabase()->getAdapter()->select($className);
    }

    /**
     * Filter values that their keys are not a field on the table
     *
     * @param array
     * @return array
     */
    public function filterValues(array $values)
    {
        static::$_tableInfo = $this->query('TABLE', 'DESCRIBE');
        foreach ($values as $key => $value) {
            if (!array_key_exists($key, static::$_tableInfo['data_types'])) {unset($values[$key]);}
        }
        return $values;
    }

    /**
     * Returns the _dataTypes property
     *
     * @param void
     * @return array
     */
    public function getDataTypes()
    {
        if (is_array($this->_dataTypes)) {
            return $this->_dataTypes;
        }
        throw new Ayoola_Dbase_Table_Abstract_Exception('No Datatype on file. Set with ' . __CLASS__ . '::setDataTypes()');
    }

    /**
     * Sets the _dataTypes property
     *
     * @param array
     */
    public function setDataTypes(array $dataTypes)
    {
        $this->_dataTypes = $dataTypes;
    }

    /**
     * View the Table
     *
     * @return string
     */
    //  public function view( Array $fieldsKey = null, Array $where = null )
    //    {
    //    return $this->query( 'TABLE', 'VIEW', $fieldsKey, $where );
    //    }
    // END OF CLASS
}
