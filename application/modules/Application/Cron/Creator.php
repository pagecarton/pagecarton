<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Cron_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Cron_Abstract
 */
 
require_once 'Application/Cron/Abstract.php';


/**
 * @advert   Ayoola
 * @package    Application_Cron_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Cron_Creator extends Application_Cron_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Schedule', 'Schedule a cron job' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		$this->update( array( 'insert' => $values['task'] ) );
		if( ! $this->insertDb() ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent( '<p>Cron job scheduled successfully.</p>', true );
   } 
	// END OF CLASS
}
