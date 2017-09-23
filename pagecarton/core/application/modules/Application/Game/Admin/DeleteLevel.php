<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Game_Admin_DeleteLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_ContactUs_Abstract
 */
 
require_once 'Application/ContactUs/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Game_Admin_DeleteLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Game_Admin_DeleteLevel extends Application_Game_Admin_Abstract
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
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete level ' . $data['level'],  'Delete Game Level Information' );
			$this->setViewContent( $this->getForm()->view(), true );
			 
			//	Only remove from DB if file deleted.
			if( $this->deleteDb( false ) )
			{ 
				$this->setViewContent( 'Game level information deleted successfully', true ); 
			}
		}
		catch( Application_Game_Admin_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
