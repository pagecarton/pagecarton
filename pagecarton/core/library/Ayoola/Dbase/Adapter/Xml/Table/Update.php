<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Update
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Update.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Update
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Update extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{

    /**
     * Updates a record on a db table
     *
     * @param array Data to Update on the table
     * @param array Selectively pick Data to Update on the table
     * @return int The Number of Successful Updates
     */
    public function init( Array $update, Array $where = null )
    {
		//Count the amount of records updated
		$count = 0;
	//	var_export( $rows );
		$files =  array_unique( array( $this->getFilenameAccordingToScope() => $this->getFilenameAccordingToScope() ) + $this->getSupplementaryFilenames() );
		foreach( $files as $filename )
		{
		//	var_export( $this->getMyFilename() );
			$this->setXml();
			$this->getXml()->load( $filename );
			$this->getXml()->setId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );
			$rows = $this->query( 'SELECT', null, $where, array( 'filename' => $filename ) );
	//		return $rows;
			$result = false;
			if( empty( $update['modified_time'] ) )   
			{
				$update['modified_time'] = time();
			}
			foreach( $update as $key => $value )
			{
				foreach( $rows as $rowId => $row )
				{
					$count++;
			//		var_export( $rowId );
					if( ! $recordRow = $this->getXml()->getElementById( $rowId ) )
					{
						
						continue 3;
					//	require_once 'Ayoola/Dbase/Adapter/Xml/Table/Exception.php';
					//	throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Cannot find the row data to update for row ID {$rowId}" );
					}
				//	var_export( $recordRow );
				//	var_export( $rowId );
					$result = $this->setRowColumnValue( $recordRow, $key, $value, true );
					//try{ $this->setRowColumnValue( $row, $key, $value, true ); }
					//catch( Ayoola_Dbase_Adapter_Xml_Table_Exception $e ){ null; }
				}
			}
			
			//	Save only when an editing was done
		//	var_export( $result );
			$result ? $this->saveFile( $filename ) : null;
		//	$this->saveFile( $filename );
		}
	//	$this->clearCache();
		return $count;
    } 
	// END OF CLASS
}
