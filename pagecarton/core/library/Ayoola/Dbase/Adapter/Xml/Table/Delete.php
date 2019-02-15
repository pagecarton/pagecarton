<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton 
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.6.12 6.33 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Xml_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Adapter/Xml/Table/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Adapter_Xml_Table_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Xml_Table_Delete extends Ayoola_Dbase_Adapter_Xml_Table_Abstract
{
    /**
     * Deletes record from a db table
     *
     * @param array Filter with field values
     */
    public function init( Array $where = null, array $options = null )
    {
			//var_export( $where );
		//Count the amount of records deleted 
		$count = 0;
	//	$this->setMyFilename( $this->getTableName() );
		$files =  array_unique( array( $this->getFilenameAccordingToScope() => $this->getFilenameAccordingToScope() ) + $this->getSupplementaryFilenames() );
	//	var_export( $files );
		foreach( $files as $filename )
		{
		//	var_export( $this->getMyFilename() );
			$this->setXml();
			$this->getXml()->load( $filename );
	//		return $rows;
			$result = false;
			if(  ! empty( $options['limit'] ) && $count >= $options['limit'] )
			{
			//	PageCarton_Widget::v( $options );
				break;
			}
			else {
			//	PageCarton_Widget::v( $count );
			//	PageCarton_Widget::v( $options );
			} 
			if( ! empty( $where ) )
			{
			//	$rows = $this->query( 'SELECT', null, $where );
				$rows = $this->query( 'SELECT', null, $where, array( 'filename' => $filename ) );
				$this->getXml()->setId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );
			//	var_export( $rows );
				foreach( $rows as $rowId => $row )
				{
						//	var_export( $this->getRelatives() );
				//	PageCarton_Widget::v( $count );
				//	PageCarton_Widget::v( $rowId );
					if(  ! empty( $options['limit'] ) && $count >= $options['limit'] )
					{
					//	PageCarton_Widget::v( $options );
						break;
					}
					else {
					//	PageCarton_Widget::v( $count );
					//	PageCarton_Widget::v( $options );
					} 
					foreach( $this->getRelatives() as $relative => $key )
					{ 
					
						$where = array( $key => $row[$key] );
						//		var_export( $row );
						$count += self::deleteRelative( $relative, $where );
					}
					if( ! $row = $this->getXml()->getElementById( $rowId ) )
					{
						continue 2;
					//	require_once 'Ayoola/Dbase/Adapter/Xml/Table/Exception.php';
					//	throw new Ayoola_Dbase_Adapter_Xml_Table_Exception( "Cannot find the row data to delete for row ID {$rowId}" );
					}
					$result = $row->parentNode->removeChild( $row );
					$count++;
				//	var_export( $result );
				//	if( ! $result )
					{
					//	continue 2;
					}
				}
			}
			else
			{
				$count = $this->getRecords()->childNodes->length;
				$this->getRecords()->parentNode->removeChild( $this->getRecords() );
				$this->getXml()->documentElement->appendChild( $this->setRecords() );
			}
			
			//	Save only when an editing was done
			$result ? $this->saveFile( $filename ) : null;
		}
	//	$this->clearCache();
		return $count;
    } 
	
    /**
     * Deletes records from a database
     *
     * @param string The table of the relative
     * @param array Filter with field values
     */
    public static function deleteRelative( $relative, Array $where )
    {
	//	var_export( $relative );
		if( ! self::checkValidTable( $relative ) ){ return 0; }
		$class = new $relative;
	//	var_export( $class );
	//	var_export( $where );
	
		return $class->delete( $where );
    } 
	// END OF CLASS
}
