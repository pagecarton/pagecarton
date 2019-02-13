<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.2.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Xml_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table extends Ayoola_Dbase_Adapter_Xml
{
	
    /**
     * Constructor
     *
     * @param string Table Name
     * @param string Database Class
     * 
     */
    public function __construct( $tableName = null, $className = null )
    {
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
		if( ! is_null( $className ) ){ $this->select( $className ); }
    }

    /**
     * Queries Database Table
     *
     * @throws Ayoola_Dbase_Adapter_Xml_Exception
     */
    public function query( $keyword = null )
	{
//		PageCarton_Widget::v( $this );
		$arguments = func_get_args();
	//	var_export( $arguments );
		$keyword = array_shift( $arguments );
		$keyword = ucfirst( strtolower( $keyword ) );
		$class = __CLASS__ . '_' . $keyword;
		require_once 'Ayoola/Loader.php';
		if( ! Ayoola_Loader::loadClass( $class ) )
		{
			require_once 'Ayoola/Dbase/Adapter/Xml/Exception.php';
			throw new Ayoola_Dbase_Adapter_Xml_Exception( $keyword . ' is an invalid query keyword' );
		}
		$class = new $class;
		foreach( $this as $key => $value )
		{
			require_once 'Ayoola/Reflection/Property.php';
			try
			{ 
				$thisProperty = new Ayoola_Reflection_Property( __CLASS__, $key ); 
				//if( ! $thisProperty->isDefault ){ continue; }
				$thisProperty->setAccessible( true );
				$thisProperty->setValue( $class, $value );
				//var_export( $key . '-' . $value );
			}
			catch( ReflectionException $e ){ continue; }
		}
		$class = array( $class, 'init' );
	//	var_export( $class );
		$result = call_user_func_array( $class, $arguments );
 	//	PageCarton_Widget::v( $result );
		return $result; 
    }
	// END OF CLASS
}
