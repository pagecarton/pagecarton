<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension_Import_Settings
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_Settings extends Ayoola_Extension_Import_Abstract
{	
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Update Plugin Settings'; 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()  
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
	//	var_export( $identifierData );
		if( ! $this->createForm( 'Save...', 'Settings for "' . $identifierData['extension_name'] . '"', $identifierData['settings'] ) )
		{
			$this->setViewContent( '<p class="badnews">This Plugin is not configured to support settings.</p>', true ); 
			return false;
		}
		$this->setViewContent( $this->getForm()->view(), true );

//		var_export( $identifierData );

		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
		$values = array( 'settings' => $values );
	
		if( ! $this->updateDb( $values ) )
		{ 
			$this->setViewContent( '<p class="badnews">Error: could not save Plugin settings.</p>.', true ); 
			return false;
		}
		$this->setViewContent( '<p class="boxednews saved">Plugin settings saved successfully.</p>', true );
	} 
	
    /**
     * creates the form for creating and editing 
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
		if( ! $identifierData = self::getIdentifierData() ){ return false; }
//		$identifierData['settings_class'] = 'Application_Settings_Payment';
//		$identifierData['settings_class'] = 'ThirdPartyAuth_Settings';
		if( $settings = $identifierData['settings_class'] )
		{
			if( Ayoola_Loader::loadClass( $settings ) )
			{
			//	var_export( $settings );
				$settings = new $settings();
				$settings->createForm( $submitValue, $legend, $values );
				$form = $settings->getForm();
				$form->oneFieldSetAtATime = false;
				$form->submitValue = 'Save Settings';
				$this->setForm( $form );
				return true;
			}
		}	
    } 
	// END OF CLASS
}
