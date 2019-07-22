<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AuthLevel_Editor extends Ayoola_Access_AuthLevel_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		if( ! $data = self::getIdentifierData() )
		{ 
			return false; 
		}
		$this->createForm( 'Edit', 'Edit ' . $data['auth_name'], $data );
		$this->setViewContent( $this->getForm()->view(), true );
	//	var_export( $_POST );
	//	var_export( $this->getForm()->getValues() );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
		if( ! $this->updateDb( $values ) ){ return false; }
		
	//	var_export( $data );
		$this->setViewContent(  '' . self::__( 'Auth level updated successfully.' ) . '', true  ); 
    } 
	// END OF CLASS
}
