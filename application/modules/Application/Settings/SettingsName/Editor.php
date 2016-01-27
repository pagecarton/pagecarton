<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_SettingsName_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Settings_SettingsName_Abstract
 */
 
require_once 'Application/Settings/SettingsName/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_SettingsName_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_SettingsName_Editor extends Application_Settings_SettingsName_Abstract
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
			$this->createForm( 'Edit', 'Edit ' . $data['settingsname_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			if( ! $this->updateDb( $values ) ){ return false; }
			$this->setViewContent( 'Settings Name edited successfully', true );
		}
		catch( Application_Settings_SettingsName_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
