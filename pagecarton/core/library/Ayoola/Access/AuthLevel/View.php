<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AuthLevel_View extends Ayoola_Access_AuthLevel_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( @$_REQUEST['type'] )
		{
			$typeInfo = new Ayoola_Access_AuthLevel;
			if( $typeInfo = $typeInfo->selectOne( null, array( 'auth_name' => $_REQUEST['type'] ) ) )
			{
				$this->_objectTemplateValues = $typeInfo;
				$this->_objectData = $typeInfo;
			}
		//	var_export( $typeInfo );
		//	var_export( $path );
		}
	
    } 
	// END OF CLASS
}
