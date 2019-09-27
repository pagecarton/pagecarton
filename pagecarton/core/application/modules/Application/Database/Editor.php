<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Database_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Database_Abstract
 */
 
require_once 'Application/Database/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Database_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Database_Editor extends Application_Database_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Edit Database', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			if( $this->updateDb() ){ $this->setViewContent(  '' . self::__( 'Database edited successfully' ) . '', true  ); }
			self::setDefaultDatabase( $values );
		}
		catch( Application_Database_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
