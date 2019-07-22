<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Whois_Abstract
 */
 
require_once 'Application/Domain/Registration/Whois/Abstract.php';


/**
 * @whois   Ayoola
 * @package    Application_Domain_Registration_Whois_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Whois_Editor extends Application_Domain_Registration_Whois_Abstract
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
			$this->createForm( 'Edit', 'Edit whois lookup info for ' . $data['extension'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent(  '' . self::__( 'Item edited successfully' ) . '', true  ); }
		}
		catch( Application_Domain_Registration_Whois_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
