<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Settings_SettingsName_Abstract
 */
 
require_once 'Application/Settings/SettingsName/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Settings_SettingsName_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_SettingsName_Creator extends Application_Settings_SettingsName_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			$this->createForm( 'Create', 'Add a settings widget' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

	//		$values['settingsname_name'] = 
			$filter = new Ayoola_Filter_Name();
			$values['settingsname_name'] = strtolower( $filter->filter( $values['settingsname_title'] ) );

			if( ! $this->insertDb( $values ) ){ return false; }
			$this->setViewContent(  '' . self::__( '<p class="goodnews">Settings widget added successfully</p>' ) . '', true  );
		}
		catch( Application_Settings_SettingsName_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
