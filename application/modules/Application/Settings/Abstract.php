<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: Abstract.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_Abstract
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

abstract class Application_Settings_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Settings
     * 
     * @var array
     */
	protected static $_settings;
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Settings';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );

	
    /**
     * Calls this after every successful settings change
     * 
     */ 
	public static function callback()
    {

	}
	
	
    /**
     * Sets and Returns the setting
     * 
     */
	public static function getSettings( $settingsName, $key = null )
    {
	//	if( is_null( self::$_settings[$settingsName] ) )
		{
			$settings = new Application_Settings();
			$settings = $settings->selectOne( null, array( 'settingsname_name' => $settingsName ) );
		//		self::v( array( 'settingsname_name' => $settingsName ) );  
			if( ! isset( $settings['settings'] ) ){ return false; }
			static::$_settings[$settingsName] = unserialize( $settings['settings'] );
		}
	//	var_export( self::$_settings );
	//	if( is_array( self::$_settings[$settingsName] ) && array_key_exists( $key, self::$_settings[$settingsName] ) )
		if( ! is_null( $key ) )
		{
			return @self::$_settings[$settingsName][$key];
		}
		else
		{
			return self::$_settings[$settingsName];
		}
    } 
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
	//	$form->oneFieldSetAtATime = true;
		do
		{
			$formAvailable = false;
			//	self::v( $values );

			if( empty( $values['class_name'] ) || ! class_exists( $values['class_name'] ) )
			{
				break;
			}
			$player = new $values['class_name'];
			if( $player instanceof Application_Settings_Interface )
			{
				$fieldsets = $player::getFormFieldsets( $values );
			}
			else
			{
				$player->createForm( null, null, $values );
				$fieldsets = $player->getForm()->getFieldsets();
			}
		//	self::v( $form );
			foreach( $fieldsets as $fieldset ){ $form->addFieldset( $fieldset ); }
			$formAvailable = true;
			$form->submitValue = 'Save';
			return $this->setForm( $form );
		}
		while( false );
		
		//	workaround
	//	$this->init();
	//	return false;
		
//		var_export( $values );
	//	echo( 'Please reload this page to continue.' );
/* 		$settings = unserialize( @$values['settings'] );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'value', 'value' => $settings['value'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
 */		$this->setForm( $form );
    } 
	// END OF CLASS
}
