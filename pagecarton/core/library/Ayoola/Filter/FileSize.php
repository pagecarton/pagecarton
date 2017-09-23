<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_FileSize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: FileSize.php 10.13.2011 1:55PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_FileSize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Filter_FileSize implements Ayoola_Filter_Interface
{
    /**
     * The Output Unit
     *
     * @var string
     */
	protected static $_outputUnit;
	
    /**
     * The Input Unit
     *
     * @var string
     */
	protected static $_inputUnit;
	
	
    /**
     *
     * @param float Filesize 
     * @param float Units of the input filesize 
     * @param float Output of the input filesize 
     * @return mixed
     */
    public function __construct( $filesize = null, $_outputUnit = 'MB', $_inputUnit = 'Bytes' )
    {
        self::$_outputUnit = $_outputUnit;
        self::$_inputUnit = $_inputUnit;
		if( $filesize ){ return $this->filter( $filesize ); }
    } 

    /**
     * This method does the main filtering biz.
     *
     * @param 
     * @return 
     */
    public function filter( $value )
    {
		$filesizeTypes = array( 'B' => 1, 'kB' => 1024, 'MB' => 1048576, 'GB' => 1073741824 );
		krsort( $filesizeTypes );
	//	$value = $value * $filesizeTypes[self::$_inputUnit]; //	Convert to B
	//	$value = $value / $filesizeTypes[self::$_outputUnit];
		$values = array();
		foreach( $filesizeTypes as $key => $multiplier )
		{
			$temp = intval( $value ) / intval( $multiplier );
		//	var_export( $key . '<br />' );
		//	var_export( $temp . '<br />' );
			if( $temp >= 1 ){ $values[$key] = $temp; }
		}
		$lastKey = 'kb';
		foreach( $values as $key => $each )
		{
			if( $temp > $each )
			{ 
				$temp = $each;
				$lastKey = $key;
			
			}
		}
	//	var_export( $timeDifference );
		return number_format( $temp, 2 ) . ' ' . $lastKey;
    } 
	
	
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function autofil( $parameter )
    {
		if( ! empty( $parameter[0] ) ){ self::$_outputUnit = $parameter[0]; }
		if( ! empty( $parameter[1] ) ){ self::$_inputUnit = $parameter[1]; }		
    } 
	
	
	// END OF CLASS
}
