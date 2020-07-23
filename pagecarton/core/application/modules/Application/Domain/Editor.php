<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Abstract
 */
 
require_once 'Application/Domain/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Editor extends Application_Domain_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Edit ' . $data['domain_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			$this->resetDefaultDomain();
			if( $this->updateDb() )
			{ 
				$this->setViewContent(  '' . self::__( 'Domain edited successfully' ) . '', true  );   
				
				//	clear domain cache
				Ayoola_File_Storage::purgeDomain( $data['domain_name'] );
			}
			
			//	reset domain
			Ayoola_Application::setDomainSettings( true );
		}
		catch( Application_Domain_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
