<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Editor extends Ayoola_Extension_Abstract
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
		catch( Ayoola_Extension_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Continue...', 'Editing "' . $identifierData['extension_title'] . '"', $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }

		
		if( ! $this->updateDb( $values ) )
		{ 
			$this->setViewContent(  '' . self::__( '<p class="badnews">Error: could not save plugin.</p>.' ) . '', true  ); 
			return false;
		}
			$this->setViewContent(  '' . self::__( '<p class=" goodnews">Plugin saved successfully.</p>' ) . '', true  );
			$this->setViewContent( self::__( '<a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Extension_Download/?extension_name=' . $identifierData['extension_name'] . '" class="boxednews goodnews">Download</a>' ) );
    } 
	// END OF CLASS
}
