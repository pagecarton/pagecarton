<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_ClassToFilename
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-25-2011 3.25pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_ClassToFilename
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_FilenameToClassname implements Ayoola_Filter_Interface
{
	
	public $directory;

    public function filter( $file )
	{
		$directory = $this->directory ? : Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';
		$directory = str_ireplace( DS, '/', $directory );
		$file = str_ireplace( DS, '/', $file );
		$file = str_ireplace( $directory, '', $file );
		
		//	The label is transformed into the class value
		$className = implode( '_', array_map( 'ucwords', explode( '_', array_shift( explode( '.', trim( str_replace( '/', '_', $file ), '_' ) ) ) ) ) );
		
		return $className;
	}
 
}
