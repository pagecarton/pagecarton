<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Cycle_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Cycle_Abstract
 */
 
require_once 'Application/Subscription/Cycle/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Cycle_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Cycle_Editor extends Application_Subscription_Cycle_Abstract
{	
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try{ $this->setIdentifier(); }
		catch( Application_Subscription_Cycle_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
		$this->createForm( 'Edit Cycle', 'Edit ' . $identifierData['cycle_name'], $identifierData );
		$this->setViewContent( $this->getForm()->view(), true );
		if( $this->updateDb() ){ $this->setViewContent(  '' . self::__( 'Billing cycle edited successfully' ) . '', true  ); }
    } 
	// END OF CLASS
}
