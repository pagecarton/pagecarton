<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Api_Editor
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Api_Editor
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_Editor extends Ayoola_Api_Abstract
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
     * 
     */
	public function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Edit ' . $data['api_label'], $data );
			$this->setViewContent( $this->getForm()->view(), true );

			if( ! $values = $this->getForm()->getValues() ){ return false; }

			if( ! $this->updateDb( $values ) ){ return false; }
			$this->setViewContent(  '' . self::__( 'Api link edited successfully.' ) . '', true  );
		}
		catch( Application_Blog_Exception $e ){ return false; }
     
    } 
	// END OF CLASS
}
