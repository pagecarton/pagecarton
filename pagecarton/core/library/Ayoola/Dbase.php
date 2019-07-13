<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase
 * @copyright  Copyright (c) 2011 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Dbase.php 1.23.12 3.18 ayoola $
 */

/**
 * @see Ayoola_
 */

//require_once 'Ayoola/.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Dbase
 * @copyright  Copyright (c) 2011 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/*
This class is the main database for the application.
Detects the adapter and loads it for use.
 */
class Ayoola_Dbase
{
    //    Holds the details of the database
    protected $_databaseInfo;

    //    Object of the validated adapter
    protected $_adapter;

    /**
     * Constructor
     *
     * @param array Database Info
     *
     */
    public function __construct($databaseInfo = null)
    {
        if (!is_null($databaseInfo)) {$this->setDatabaseInfo($databaseInfo);}
    } //    ends constructor

    /**
     * Returns list of tables
     *
     * @param void
     * @return array
     */
    public function listTables()
    {
        return $this->getAdapter()->listTables();
    }

    /**
     * Loads an Adapter, checking if it is a valid one
     *
     * @param string Adapter Name
     * @return null
     */
    protected function _loadAdapter($adapterName = null)
    {
        $adapter = $adapterName ?: $this->getDatabaseInfo('adapter');
        $adapter = 'Ayoola_Dbase_Adapter_' . ucfirst($adapter);
        require_once 'Ayoola/Loader.php';
        if (!Ayoola_Loader::loadClass($adapter)) {
            require_once 'Ayoola/Dbase/Exception.php';
            throw new Ayoola_Dbase_Exception('Invalid Database Adapter - ' . $adapter);
        }
        $this->setAdapter(new $adapter($this->getDatabaseInfo()));
        return true;
    } //    ends _loadAdapter method

    /**
     * This method sets _adapter to a Value
     *
     * @param Ayoola_Dbase_Adapter_Interface
     * @return null
     */
    public function setAdapter(Ayoola_Dbase_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * This method returns _adapter
     *
     * @return Ayoola_Dbase_Adapter_Interface
     */
    public function getAdapter()
    {
        if (is_null($this->_adapter)) {$this->_loadAdapter();}
        return $this->_adapter;
    }

    /**
     * This method sets _databaseInfo to a Value
     *
     * @param array Database Info
     * @return null
     */
    public function setDatabaseInfo($databaseInfo = array())
    {
        //    Import the Database Credentials
        //    Dummy DB INFO
        $_DATABASE = array(
            'username' => 'username',
            'password' => 'password',
            'hostname' => 'localhost',
            'adapter'  => 'mysql',
            'database' => 'ayoola',
        );

        //    Load default DB if available
        @include 'configs/database' . '.php';

        //    Set Database details to default if none is sent
        $databaseInfo = _Array($databaseInfo);
        //    if( $_DATABASE )
        foreach ($_DATABASE as $key => $value) {
            if (!array_key_exists($key, $databaseInfo)) {$databaseInfo[$key] = $value;}
        }
        $this->_databaseInfo = $databaseInfo;
    }

    /**
     * This method returns _databaseInfo
     *
     * @param string Info to Return
     * @return mixed Database Info
     */
    public function getDatabaseInfo($key = null)
    {
        if (is_null($this->_databaseInfo)) {$this->setDatabaseInfo();}
        if (is_null($key)) {return $this->_databaseInfo;}
        if (array_key_exists($key, $this->_databaseInfo)) {return $this->_databaseInfo[$key];}

        //    Error
        require_once 'Ayoola/Dbase/Adapter/Exception.php';
        //    throw new Ayoola_Dbase_Exception( 'Database Info Not Available - ' . $key );
    }

    /**
     * Overloading the Methods
     *
     * @throws Ayoola_Dbase_Exception
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->getAdapter(), $name)) {
            $arguments = implode(', ', $arguments);
            return $this->getAdapter()->$name($arguments);
        }
        require_once 'Ayoola/Dbase/Exception.php';
        throw new Ayoola_Dbase_Exception('Invalid Method For Adapter - ' . get_class($this->getAdapter()));
    }

}
