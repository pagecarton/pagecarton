<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Log_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Log_Abstract
 */
 
require_once 'Application/Log/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Log_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Log_Editor extends Application_Log_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	var_export( __LINE__ );
		try{ $this->setIdentifier(); }
		catch( Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Edit', 'Edit Log Viewer - ' . $identifierData['log_name'], $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->updateDb() ){ $this->setViewContent( 'Log Viewer Edited Successfully', true ); }
    } 
	// END OF CLASS
}
