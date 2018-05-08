<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Settings_Abstract
 */
 
require_once 'Application/Settings/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_Editor extends Application_Settings_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Update Settings'; 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
	//	try
		{ 
			
	//		var_export( $this->getIdentifier() );
	//		var_export( $this->getIdentifierData()  );
		//	if( ! $data = $this->getIdentifierData() )
			$settings = new Application_Settings_SettingsName();
			$settingsNameInfo = $settings->selectOne( null, array( 'settingsname_name' => $this->getParameter( 'settingsname_name' ) ? : @$_REQUEST['settingsname_name'] ) );
	//		var_export(  $_REQUEST['settingsname_name'] );
	//		var_export(  $this->getParameter( 'settingsname_name' ) );
	//		var_export(  $settingsNameInfo );
			$settingsInfo = array();
			if( @$settingsNameInfo['settingsname_id'] )
			{
				$settings = new Application_Settings();
				$settingsInfo = $settings->selectOne( null, array( 'settingsname_id' => $settingsNameInfo['settingsname_id'] ) );
		//		var_export(  $settingsInfo );
				$data = $settingsNameInfo + $settingsInfo;
			}
			if( ! $settingsInfo )
			{
				$settingsName = new Application_Settings_SettingsName();
		//		var_export( $settingsName->select() );
			//	var_export( $settings->select() );
				if( ! $settingsNameInfo )
				{
					$this->insertDb( $settingsNameInfo );
					return $this->setViewContent( 'Invalid Settings Name', true );
				}
		//		var_export( $settingsInfo );
		//		var_export( $settingsNameInfo );
				$settings = new Application_Settings();
				$settingsInfo = $settings->selectOne( null, array( 'settingsname_id' => $settingsNameInfo['settingsname_id'] ) ) ? : $settings->selectOne( null, array( 'settingsname_name' => $settingsNameInfo['settingsname_name'] ) ) ;
				$data = $settingsNameInfo + $settingsInfo;
			}
/* 				$settingsName = new Application_Settings_SettingsName();
				$test = $settingsName->select();
				self::v( $test );
 */			
//			self::v( $_REQUEST['settingsname_name'] );
	//		self::v( self::getSettings( $_REQUEST['settingsname_name'] ) );
		//	exit();
	//	var_export( $settings->select() );
//		var_export( $settingsInfo );
//		var_export( $settingsNameInfo );
//		var_export( $data );
			$this->createForm( 'Save', 'Edit ' . @$data['settingsname_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//		self::v( $data );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		//		self::v( $data );
		//		self::v( $values ); 
		//	var_export( $_POST );
			$values = array( 'settings' => serialize( $values ) );
					//		self::v( $this->getIdentifierData() ); 
			$table = Application_Settings::getInstance();
			$previousData = $table->select( null, $this->getIdentifier() );
	//		self::v( $this->getIdentifier() ); 
		//	self::v( $previousData ); 
			if( count( $previousData ) > 1 )
			{
				foreach( $previousData as $key => $each )
				{
					if( ! $key )
					{
						//	skip one, delete the rest
						continue;

					}
			//		var_export( $each['settings_id'] );
			// 		var_export( $each );
					$table->delete( array( 'settings_id' => $each['settings_id'] ) );
				}
			}
			if( $previousData )
			{
				if( ! $table->update( $values, $this->getIdentifier() ) ){ return false; }
			}
			else
			{
	//		self::v( $previousData ); 
	//		self::v( $this->getIdentifier() ); 
				$values = array_merge( $values, $this->getIdentifier() );
				if( ! $table->insert( $values ) ){ return false; }  
			}

			//	 clear this in the settings data so the rest of the app can feel the difference immediately.
			unset( self::$_settings[$data['settingsname_name']] );
			$this->setViewContent( '<p class="goodnews">Settings saved successfully.</p>', true );
			if( ! empty( $data['class_name'] ) && class_exists( $data['class_name'] ) )
			{
			//	var_export( $data['class_name'] );
				$class = $data['class_name'];
				$class::callback();
			}
			//			var_export( __LINE__ );
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( '<p class="pc-notify-info">Proceed to previous link ( <a href="' . $_GET['previous_url'] . '">' . $_GET['previous_url'] . ' ) </a></p>' );
			//	header( 'Location: ' . $_GET['previous_url'] );
			//	exit();
			}
			return true;
		}
	//	catch( Application_Settings_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
