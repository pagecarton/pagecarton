<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
			$settingsNameToUse = $this->getParameter( 'settingsname_name' ) ? : @$_REQUEST['settingsname_name'];
	//		var_export(  $settingsNameToUse );
			$settings = new Application_Settings_SettingsName();
			if( ! $settingsNameToUse )
			{
				$class = get_class( $this );
				if( $settingsNameInfo = $settings->selectOne( null, array( 'class_name' => $class ) ) )
				{
					$settingsNameToUse = $settingsNameInfo['settingsname_name'];
				}
				else
				{
					return $this->setViewContent(  '' . self::__( '<div class="pc-notify-info">Class settings need to be setup. <a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_SettingsName_Creator/?class_name=' . $class . '" >Setup Now</a></div>' ) . '', true  );
				}
			//	var_export(  $settingsNameInfo );
			}
			else
			{
				$settingsNameInfo = $settings->selectOne( null, array( 'settingsname_name' => $settingsNameToUse ) );
			}
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
				if( ! $settingsNameInfo )
				{
					$this->insertDb( $settingsNameInfo );
					return $this->setViewContent(  '' . self::__( 'Invalid Settings Name' ) . '', true  );
				}
				$settings = new Application_Settings();
				$settingsInfo = $settings->selectOne( null, array( 'settingsname_id' => $settingsNameInfo['settingsname_id'] ) ) ? : $settings->selectOne( null, array( 'settingsname_name' => $settingsNameInfo['settingsname_name'] ) ) ;
				$data = $settingsNameInfo + $settingsInfo;
			}
			$this->createForm( 'Save', 'Edit ' . @$data['settingsname_name'], $data );
			$this->setViewContent( $this->getForm()->view(), true );
		//		self::v( $data );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$values = array( 'settings' => serialize( $values ), 'data' => $values );
					//		self::v( $this->getIdentifierData() ); 
			$table = Application_Settings::getInstance();
			$previousData = $table->select( null, array( 'settingsname_name' => $settingsNameToUse ) );
			if( count( $previousData ) > 1 )
			{
				foreach( $previousData as $key => $each )
				{
					if( ! $key )
					{
						//	skip one, delete the rest
						continue;

					}
					$table->delete( array( 'settings_id' => $each['settings_id'] ) );
				}
			}
			if( $previousData )
			{
				if( ! $table->update( $values, array( 'settingsname_name' => $settingsNameToUse ) ) ){ return false; }
			}
			else
			{
				$values = array_merge( $values, array( 'settingsname_name' => $settingsNameToUse ) );
				if( ! $table->insert( $values ) ){ return false; }  
			}

			//	 clear this in the settings data so the rest of the app can feel the difference immediately.
			unset( self::$_settings[$data['settingsname_name']] );
			$this->setViewContent(  '' . self::__( '<p class="goodnews">Settings saved successfully.</p>' ) . '', true  );
			if( ! empty( $data['class_name'] ) && class_exists( $data['class_name'] ) )
			{
			//	var_export( $data['class_name'] );
				$class = $data['class_name'];
				$class::callback();
			}
			//			var_export( __LINE__ );
			if( @$_GET['previous_url'] )
			{
				$this->setViewContent( self::__( '<p class="pc-notify-info">Proceed to previous link ( <a href="' . $_GET['previous_url'] . '">' . $_GET['previous_url'] . ' ) </a></p>' ) );
			//	header( 'Location: ' . $_GET['previous_url'] );
			//	exit();
			}
			return true;
		}
	//	catch( Application_Settings_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
