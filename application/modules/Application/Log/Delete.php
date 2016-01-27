<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_Delete
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
require_once 'Application/Log/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Log_Delete
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_Delete extends Application_Log_Abstract
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
			$this->createConfirmationForm( 'Delete ' . $data['log_name'],  'Delete Log' );
			$this->setViewContent( $this->getForm()->view() );
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent( 'Log deleted', true ); 
			}
		}
		catch( Application_Log_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
