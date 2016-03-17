<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Phar_Data
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Data.php 5.14.2012 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Phar_Data
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Phar_Data extends PharData
{
	
    /**
     * constructor
     * 
     */
	public function __construct( $fname, $flags = null )
    {
		if( ! extension_loaded( 'Phar' ) ){ throw new Ayoola_Phar_Exception( 'PHAR IS NOT LOADED' ); }
		parent::__construct( $fname, $flags = null );
    } 
	
    /**
     * Compress a directory and outputs the file
     * 
     * param @string Directory to compress
     * param @array Options for the compression
     * return @boolean Returns true when completed.
     */
	public static function archiveDirectory( $dir, array $options = null )
	{
		if( ! is_dir( $dir ) || ! is_readable( $dir ) )
		{
			throw new Ayoola_Phar_Exception( 'DIRECTORY DOES NOT EXIST OR NOT READABLE: ' . $dir );
		}
		$outputDir = @$options['output_directory'] ? : dirname( $dir );
		$filename = @$options['filename'] ? : basename( $dir );
		
		//	Make sure there is no extension
		$filename = array_shift( explode( '.', $filename ) );
		set_time_limit( 600 );
		$filename = $outputDir . DS . $filename . '.tar';
		$phar = __CLASS__;
		$backup = new $phar( $filename );
		$backup->startBuffering();
		$backup->buildFromDirectory( $dir, @$options['regex'] );
	//	$backup['backup_information'] = serialize( $values );
		$backup->stopBuffering();
		
		//	Compress by default
		@$options['no_compression'] ? : $backup->compress( Phar::GZ ); 
		unset( $backup );
		
	//	is_file( $filename ) ? $phar::unlinkArchive( $filename ) : null;
		$phar::unlinkArchive( $filename );
		return true;
     } 
	// END OF CLASS
}
