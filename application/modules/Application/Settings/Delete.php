<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Delete
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Settings_Abstract
 */
 
require_once 'Application/Settings/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Delete
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_Delete extends Application_Settings_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['settingsname_name'],  'Delete Settings Name' );
			$this->setViewContent( $this->getForm()->view(), true );
			
			//	Only remove from DB if file deleted.
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent( 'Settings deleted successfully', true );
			}
		}
		catch( Application_Settings_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
