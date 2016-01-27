<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @Message   Ayoola
 * @package    Application_Message_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Message_Abstract
 */
 
require_once 'Application/Message/Abstract.php';


/**
 * @Message   Ayoola
 * @package    Application_Message_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Message_Editor extends Application_Message_Abstract
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
			$this->createForm( 'Edit', 'Edit ' . $data['testimonial'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'Message edited successfully', true ); }
		}
		catch( Application_Message_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
