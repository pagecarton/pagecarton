<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_UserPhoneNumber_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_UserPhoneNumber_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_UserPhoneNumber_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserPhoneNumber_Editor extends Application_User_UserPhoneNumber_Abstract
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
		//	var_export( $data );
			$this->createForm( 'Edit', 'Address Information', $data );
			$this->setViewContent( "<h4>Editing {$data['street_address']}</h4>" );
		//	$this->setViewContent( '<p>You will need to verify this Credit/Debit card if edited.</p>' );
			$this->setViewContent( $this->getForm()->view() );
			if( $this->updateDb() ){ $this->setViewContent( 'Address information edited successfully', true ); }
		}
		catch( Application_User_UserPhoneNumber_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
