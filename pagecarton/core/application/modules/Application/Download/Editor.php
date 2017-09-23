<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Download   Ayoola
 * @package    Application_Download_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Download_Abstract
 */
 
require_once 'Application/Download/Abstract.php';


/**
 * @Download   Ayoola
 * @package    Application_Download_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Download_Editor extends Application_Download_Abstract
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
			$this->createForm( 'Edit', 'Edit ' . $data['Download_title'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->updateDb() ){ $this->setViewContent( 'Download edited successfully', true ); }
		}
		catch( Application_Download_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
