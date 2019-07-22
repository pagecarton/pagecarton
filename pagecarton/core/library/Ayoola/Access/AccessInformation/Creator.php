<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_AccessInformation_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_AccessInformation_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AccessInformation_Creator extends Ayoola_Access_AccessInformation_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */  
	protected static $_accessLevel = array( 1, 98 );      
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create profile', '' );
		$this->setViewContent( $this->getForm()->view(), true );				
		if( $this->insertDb() ){ $this->setViewContent(  '' . self::__( '<div class="boxednews greynews">Profile saved successfully.</div>' ) . '', true  ); }
    } 
	// END OF CLASS
}
