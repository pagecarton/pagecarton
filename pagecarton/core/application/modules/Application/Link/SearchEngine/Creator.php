<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_SearchEngine_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
			$this->createForm( 'Continue', 'Add a Search Engine ' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( $this->insertDb() ){ $this->setViewContent(  '' . self::__( 'Search engine created successfully' ) . '', true  ); }
		}
		catch( Application_Link_SearchEngine_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
