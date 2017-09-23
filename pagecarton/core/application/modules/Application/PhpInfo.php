<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_PhpInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhpInfo.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_PhpInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_PhpInfo extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
			ob_start();
  //  $remoteSite = 'https://production.cmf.ayoo.la'; 
            // 	if( $f = @fopen( $remoteSite . '/pc_installer.php?do_not_highlight_file=1', 'r' ) )
                {
			//		$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
 //           $core = file_get_contents( $remoteSite . '/pc_installer.php?do_not_highlight_file=1',false,$context);
		//			echo  $core  ;  
             //       file_put_contents( $_SERVER['SCRIPT_FILENAME'], $f );
                }
 			  

			
			phpinfo();
			$output = ob_get_clean( );
			$output = str_ireplace( array( 'body {', 'a:link', 'a:hover', '<html', '<body', '<head', '<title' ), array( 'xbody {', 'xa:link', 'xa:hover', '<div', '<div', '<div', '<style' ), $output );
			$output = str_ireplace( array( '</html', '</body', '</head', '</title' ), array( '</div', '</div', '</div', '</style' ), $output );
			$this->setViewContent( $output, true );   

		}  
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
