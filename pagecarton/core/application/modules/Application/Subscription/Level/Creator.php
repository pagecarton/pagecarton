<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Level_Abstract
 */
 
require_once 'Application/Subscription/Level/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Level_Creator extends Application_Subscription_Level_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscription_id' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$table = Application_Subscription_Subscription::getInstance();
		if( ! $data = $table->selectOne( null, $this->getIdentifier() ) ){ return false; }
		$this->createForm( 'Add', "Add a new category for \"{$data['subscription_label']}\"" );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->insertDb() ){ $this->setViewContent( 'Product category added successfully.', true ); }
    } 
	// END OF CLASS
}
