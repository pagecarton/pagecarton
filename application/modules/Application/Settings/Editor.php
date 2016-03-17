<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Settings_Abstract
 */
 
require_once 'Application/Settings/Abstract.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Editor
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_Editor extends Application_Settings_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	try
		{ 
			if( ! $data = self::getIdentifierData() )
			{
				$settingsName = new Application_Settings_SettingsName();
				if( ! $settingsName = $settingsName->selectOne( null, $this->getIdentifier() ) )
				{
					return $this->setViewContent( 'Invalid Settings Name', true );
				}
				$this->insertDb( $settingsName );
				self::setIdentifierData();
			}
/* 				$settingsName = new Application_Settings_SettingsName();
				$test = $settingsName->select();
				self::v( $test );
 */			$data = self::getIdentifierData();
		//	self::v( $data );
		//	exit();
			$this->createForm( 'Edit', 'Edit ' . @$data['settingsname_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//		self::v( $data );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//		self::v( $data );
		//		self::v( $values ); 
		//	var_export( $_POST );
			$values = array( 'settings' => serialize( $values ) );
			if( ! $this->updateDb( $values ) ){ return false; }
			$this->setViewContent( '<p class="boxednews normalnews">Settings edited successfully.</p>', true );
			if( ! empty( $data['class_name'] ) && class_exists( $data['class_name'] ) )
			{
			//	var_export( $data['class_name'] );
				$class = $data['class_name'];
				$class::callback();
			}
			//			var_export( __LINE__ );
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<p class="boxednews goodnews">Proceed to previous link ( <a href="' . $_GET['previous_url'] . '">' . $_GET['previous_url'] . ' ) </a></p>' );
			//	header( 'Location: ' . $_GET['previous_url'] );
			//	exit();
			}
		}
	//	catch( Application_Settings_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
