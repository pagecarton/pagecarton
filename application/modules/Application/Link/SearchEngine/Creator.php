<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Link_SearchEngine_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_SearchEngine_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Link_SearchEngine_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Link_SearchEngine_Creator extends Application_Link_SearchEngine_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Create', 'Add a Search Engine ' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->insertDb() ){ $this->setViewContent( 'Search engine created successfully', true ); }
		}
		catch( Application_Link_SearchEngine_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
