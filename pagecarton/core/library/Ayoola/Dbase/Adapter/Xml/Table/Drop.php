<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Drop
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Drop.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Drop
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Drop extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
    /**
     * Destroy a Table
     *
     * @param string Table Name
     */
    public function init( $tableName = null )
    {
	//	var_export( $filename );
		if( ! is_null( $tableName ) ){ $this->setTableName( $tableName ); }
//			
		if( ! $this->query( 'EXISTS' ) )
		{ 
			throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Table ($tableName) Does Not Exist" );
		}
		$filename = $this->getFilename( true );
		if( file_exists( $filename ) )
		{
			//  $result = unlink( $filename );
            Ayoola_File::trash( $filename );
			$dirname = dirname( $filename );
			require_once 'Ayoola/Doc.php';
			$this->clearCache();
			@Ayoola_Doc::removeDirectory( $dirname );
			
			//	Remove supplementary dirs
			$dir = $this->getMySupplementaryDirectory();
			is_dir( $dir ) ? Ayoola_Doc::deleteDirectoryPlusContent( $dir ) : null;
	//		var_export( __LINE__ );
		}
		else
		{
			$result = false;
		}
		return $result;
    } 
	// END OF CLASS
}
