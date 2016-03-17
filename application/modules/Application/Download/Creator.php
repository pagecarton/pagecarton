<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Download   Ayoola
 * @package    Application_Download_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Download_Abstract
 */
 
require_once 'Application/Download/Abstract.php';


/**
 * @Download   Ayoola
 * @package    Application_Download_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Download_Creator extends Application_Download_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Create', 'Create an Download' );
		$this->setViewContent( $this->getForm()->view(), true );
	//	if( $this->getForm()->getValues() ){ return false; }
		if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent( '<p>Download created successfully</p>', true );
   } 
	// END OF CLASS
}
