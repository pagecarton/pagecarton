<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_Facebook
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Facebook.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_Facebook
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_Facebook extends Ayoola_Abstract_Table implements Application_Settings_Interface
{
	
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
     * Returns the form fieldsets
     * 
     * @return array The Fieldsets
     */
	public static function getFormFieldsets( $values )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldsets = array();
	//	$settings = unserialize( @$values['settings'] );
		$settings = @$values['data'] ? : unserialize( @$values['settings'] );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'app_id', 'label' => 'App ID/API Key', 'value' => $settings['app_id'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'app_secret', 'label' => 'App Secret', 'value' => $settings['app_secret'], 'type' => 'InputText' ) );
		$fieldset->addLegend( 'Facebook Application Settings' );
		$fieldsets[] = $fieldset;
	//	$form->addFieldset( $fieldset );
		
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'page_url', 'label' => 'Facebook Page Url', 'value' => $settings['page_url'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => 'Change Facebook Settings', 'type' => 'Submit' ) );
		$fieldset->addLegend( 'Facebook Personalization Settings' );
		$fieldsets[] = $fieldset;
//		var_export( $fieldsets );
		return $fieldsets;
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
