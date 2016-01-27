<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Settings_Abstract
 */
 
require_once 'Application/Settings/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_Creator extends Application_Settings_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Create', 'Create a Settings Name' );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( ! $this->insertDb( $values ) ){ return false; }
			$this->setViewContent( 'Settings Name created successfully', true );
		}
		catch( Application_Settings_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
