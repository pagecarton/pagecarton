<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Level_Abstract
 */
 
require_once 'Application/Subscription/Level/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_Level_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Level_Editor extends Application_Subscription_Level_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Level_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Save', "Edit product category for \"{$identifierData['subscription_label']}\"", $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->updateDb() ){ $this->setViewContent( 'Subscription Level edited successfully', true ); }
    } 
	// END OF CLASS
}
