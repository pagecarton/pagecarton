<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_ContactUs_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_ContactUs_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs_Delete extends Application_ContactUs_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['contactus_subject'],  'Delete ContactUs Information' );
			$this->setViewContent( $this->getForm()->view(), true );
			
			//	Only remove from DB if file deleted.
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent(  '' . self::__( 'Contact message deleted successfully' ) . '', true  ); 
			}
		}
		catch( Application_ContactUs_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
