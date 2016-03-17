<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @HashTag   Ayoola
 * @package    Application_HashTag_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_HashTag_Abstract
 */
 
require_once 'Application/HashTag/Abstract.php';


/**
 * @HashTag   Ayoola
 * @package    Application_HashTag_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_HashTag_Editor extends Application_HashTag_Abstract
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
			$this->createForm( 'Edit', 'Edit ' . $data['HashTag_title'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'HashTag edited successfully', true ); }
		}
		catch( Application_HashTag_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
