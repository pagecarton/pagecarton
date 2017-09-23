<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Game   Ayoola
 * @package    Application_Game_Admin_CreateLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Level.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Game_Admin_Abstract
 */
 
require_once 'Application/Game/Abstract.php';


/**
 * @Game   Ayoola
 * @package    Application_Game_Admin_CreateLevel 
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Game_Admin_CreateLevel extends Application_Game_Admin_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'level_id' );
	
    /**     
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Game_Level';
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Continue', 'Create a Game Level' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! $this->insertDb( $values ) ){ return false; }
		
		
		$this->setViewContent( '<p class="goodnews boxednews">You have successfully created a game level </p>', true );
 	//	$this->setViewContent( $this->getForm()->view() );
   } 
	// END OF CLASS
}
