<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @HashTag   Ayoola
 * @package    Application_HashTag_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_HashTag_Abstract
 */
 
require_once 'Application/HashTag/Abstract.php';


/**
 * @HashTag   Ayoola
 * @package    Application_HashTag_Creator
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_HashTag_Creator extends Application_HashTag_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create', 'Create an HashTag' );
		$this->setViewContent( $this->getForm()->view(), true );
	//	if( $this->getForm()->getValues() ){ return false; }
		if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent( '<p>HashTag created successfully</p>', true );
   } 
	// END OF CLASS
}
