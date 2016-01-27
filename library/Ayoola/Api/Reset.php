<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Reset
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Reset.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Api_Reset
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Api_Reset extends Ayoola_Api implements Ayoola_Api_Interface
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
     * Plays the class
     * 
     */
	public function init()
    {
	//	$this->createForm();
		$form = $this->getForm()->view();
		if( $values = $this->getForm()->getValues() )
		{
			$response = self::send( $values );
		//	var_export( $settings );
			if( is_array( $response ) )
			{
				$this->setViewContent( '<p class="goodnews">API keys for ' . $values['api_url'] . ' is now reset. You should only use this keys on trusted third party apps. </p>' );
				//	var_export( $response );
/* 				$table = new Ayoola_Api_Api();
				$where = array( 'api_url' => $response['api_url'] );
				if( $settings = $table->selectOne( null, $where ) )
				{
						$settings = array_merge( $settings, $response );
						$update = $table->update( $settings, $where );
				}
				else
				{
					$table->insert( $response );
				}
 */			//	var_export( $settings );
			//	$update = $table->update( $settings, $where );
			//	var_export( $update );
			//	var_export( $settings );
			}
			else
			{
				$this->setViewContent( '<p class="badnews">' . $response . '</p>' );
			}
	//		var_export( $response );
		//	$this->setViewContent( $response );
		}
		//	var_export( $this->getForm()->getValues() );
		//	var_export( $_POST );
		
		$this->setViewContent( $form );
	}
	
    /**
     * CALL THE required api
     * 
     */
	public static function call( $data )
    {
	//	var_export( $data );
		$table = 'Ayoola_Application_Application';
		$table = new $table();
		$where = array( 'application_id' => $data['options']['authentication_info']['application_id'] );
		$keys = self::generateKeys();
		$keys['application_salt'] = $data['data']['application_salt'];
		$keys['api_url'] = $data['data']['api_url'];
		$keys['api_label'] = Ayoola_Page::getDefaultDomain();
		$keys['application_id'] = $data['options']['authentication_info']['application_id'];
		$data['options']['return_info']['callbacks']['self::saveKeys'] = $keys;
		if( ! $table->update( $keys, $where ) )
		{
			throw new Ayoola_Api_Exception( 'COULD NOT SAVE NEW KEYS ON API SERVER' );
		}
		$data['options']['server_response'] = $keys;
		return $data;
    } 
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Reset' ;
	//	$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		
		$options = new Ayoola_Api_Api;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'api_url', 'api_label');
		$options = $filter->filter( $options ) + array( self::$_url => 'Default' );
		$fieldset->addElement( array( 'name' => 'api_url', 'type' => 'Select', 'value' => @$values['api_url'] ), $options );
		$fieldset->addRequirement( 'api_url', array( 'InArray' => array_keys( $options )  ) );
		unset( $options );
		
		//	Random key
		$fieldset->addElement( array( 'name' => 'application_salt', 'placeholder' => 'Enter a random value', 'type' => 'InputText' ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 12, 100 ) ) );
		$fieldset->addLegend( 'Reset API Keys' );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
