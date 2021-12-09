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

    protected static $_processing = array();

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
        $scopeFile = $this->getFilenameAccordingToScope( false, $this->getAccessibility() );
		$files =  array_unique( array( $scopeFile => $scopeFile ) + $this->getSupplementaryFilenames() );
        $class = $this->getTableInfo( 'table_class' );
		foreach( $files as $filename )
		{
            $this->setXml();

            $delay = 0;
            if( $class AND Ayoola_Loader::loadClass( $class ) AND property_exists( $class, 'insertDelay' ) AND $m = filemtime( $filename ) )
            {

                $delay = intval( $class::$insertDelay );
                $difference = time() - $m;

                if( $difference > $delay )
                {
                    $delay = 0;
                }
            }
    
            $processDir = $this->getMyTempProcessDirectory();

            if( 
                empty( self::$_processing[$class] ) && 
                ( 
                    ( ! $this->loadTableDataFromFile( $scopeFile ) && $this->loadTableDataFromFile( $scopeFile, true ) )  
                    || $delay 
                ) 
            )
            {

                Ayoola_Doc::createDirectory( $processDir );
                $tempData = serialize( func_get_args() );
                $tempFile = $processDir . DS . md5( $tempData . time() );
                Ayoola_File::putContents( $tempFile, $tempData );
                return true;
            }
            
            if( empty( $update['modified_time'] ) )   
			{
				$update['modified_time'] = time();
			}
            
            $update['__update_user_id'] = is_array( $update['__update_user_id'] ) ? $update['__update_user_id'] : array();
            $update['__update_user_id'][time()] = Ayoola_Application::getUserInfo( 'user_id' );

			$this->getXml()->setId( self::ATTRIBUTE_ROW_ID, $this->getRecords() );
			$rows = $this->query( 'SELECT', null, $where, array( 'filename' => $filename, 'populate_record_number' => true ) );
			$result = false;
			foreach( $update as $key => $value )
			{
				foreach( $rows as $rowId => $row )
				{
					$count++;
					if( ! $recordRow = $this->getXml()->getElementById( $rowId ) )
					{
						
						continue 3;
					}
					$result = $this->setRowColumnValue( $recordRow, $key, $value, true );
				}
			}
			
			//	Save only when an editing was done
			$result ? $this->saveFile( $filename ) : null;
		    //	$this->saveFile( $filename );
		}

        if( empty( self::$_processing[$class] ) && self::$_processing[$class] = Ayoola_Doc::getFilesRecursive( $processDir, array( 'no_cache' => true ) ) )
        {

            $cxi = 0;

            foreach( self::$_processing[$class] as $keyX => $process )
            {
                if( $cxi++ > 500 )
                {
                   // break;
                }
                if( $tempData = unserialize( file_get_contents( $process ) ) )
                {
                    $response = $this->init( $tempData[0], $tempData[1] );
                    unlink( $process );
                    @Ayoola_Doc::removeDirectory( dirname( $process ) );
                }
                unset( self::$_processing[$class][$keyX] );
            }
        }    

	//	$this->clearCache();
		return $count;
    } 
	// END OF CLASS
}
