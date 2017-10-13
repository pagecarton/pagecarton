<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_Editor extends Ayoola_Extension_Import_Abstract
{	
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Update plugin'; 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()  
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Continue...', 'Editing "' . $identifierData['extension_name'] . '"', $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }

		
		if( ! $this->updateDb() )
		{ 
			$this->setViewContent( '<p class="badnews">Error: could not save Plugin.</p>.', true ); 
			return false;
		}
			$this->setViewContent( '<p class="boxednews goodnews">Plugin saved successfully.</p>', true );
			$this->setViewContent( '<a href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Extension_Import_Download/?extension_name=' . $identifierData['extension_name'] . '" class="boxednews goodnews">Download</a>' );
    } 
	// END OF CLASS
}
