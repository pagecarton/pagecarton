<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Cmf_Installer
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Installer.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Cmf_Installer
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Cmf_Installer extends Ayoola_Cmf
{
	
    /**
     * Plays the class
     * 
     */
	public function init()
    {
		$this->createForm();
		$this->setViewContent( $this->getForm()->view() );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
    } 
	
	
    /**
     * creates the form for creating and editing
     */
	public function createForm()
    {
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = 'Continue Installation';
		$form->oneFieldSetAtATime = true;
		
		//	Welcome

		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$html = '<h2>Ayoola Content Management Framework</h2>';
	//	$html .= '<h3></h3>';
		$html .= '<p>Thank you for your interest in <a href="http://ayoo.la/cmf/">Ayoola CMF</a>. The installation is in three easy steps. </p>';
		$values = array( 'html' => $html );
		$fieldset->addElement( array( 'name' => 'welcome', 'type' => 'Html' ), $values );
		$options = array( 'No', 'Yes' => 'Yes' );
		$fieldset->addElement( array( 'name' => 'download_id', 'description' => 'For your security, please enter your unique Download ID.', 'label' => 'Download ID', 'value' => @$settings['download_id'], 'type' => 'InputText' ) );
		$fieldset->addRequirement( 'download_id', array( 'WordCount' => array( 12, 50 ), 'DefiniteValueSilent' => @$_SESSION['download_id'] ) );
		$fieldset->addElement( array( 'name' => 'confirm_installation1', 'label' => 'Do you want to continue with installation?', 'value' => @$settings['confirm_installation1'], 'type' => 'Radio' ), $options );
		$fieldset->addRequirement( 'confirm_installation1', array( 'DefiniteValue' => 'Yes' ) );
		$fieldset->addLegend( 'Ayoola CMF: Installation' );
		$form->addFieldset( $fieldset );
		
		//	License
		$fieldset = new Ayoola_Form_Element;
		$html = '<h2>Licence</h2>';
		$html .= '<p>Installing this application implies that you have read the content and you agree to the licensing terms available at  <a href="http://framework.ayoo.la/license/">http://framework.ayoo.la/license/</a> and <a href="http://ayoo.la/license/">http://ayoo.la/license/</a>.</p>';		
		$values = array( 'html' => $html );
		$fieldset->addElement( array( 'name' => 'license', 'type' => 'Html' ), $values );
		$fieldset->addElement( array( 'name' => 'confirm_installation2', 'label' => 'Do you want to continue with installation?', 'value' => @$settings['confirm_installation2'], 'type' => 'Radio' ), $options );
		$fieldset->addRequirement( 'confirm_installation2', array( 'DefiniteValue' => 'Yes' ) );
		$fieldset->addLegend( 'Ayoola CMF: Installation' );
		$form->addFieldset( $fieldset );
		
		//	Agreement
		$fieldset = new Ayoola_Form_Element;
		$html = '<h2>Terms and Privacy Policy</h2>';
		$html .= '<p>Installing this application implies that you have read the content and you agree to the terms use & privacy policy available at  <a href="http://ayoo.la/site/privacy/">http://ayoo.la/site/privacy/</a> and <a href="http://ayoo.la/site/terms/">http://ayoo.la/site/terms/</a>.</p>';
		$values = array( 'html' => $html );
		$fieldset->addElement( array( 'name' => 'terms', 'type' => 'Html' ), $values );
		$fieldset->addElement( array( 'name' => 'confirm_installation3', 'label' => 'Do you want to continue with installation?', 'value' => @$settings['confirm_installation3'], 'type' => 'Radio' ), $options );
		$fieldset->addRequirement( 'confirm_installation3', array( 'DefiniteValue' => 'Yes' ) );
		$fieldset->addLegend( 'Ayoola CMF: Installation' );
		$form->addFieldset( $fieldset );
		

		//	Create the first (super) user of this application
		$userCreator = new Application_User_Creator();
	//	$userCreator->initiated = true;
		@$form->addFieldsets( $userCreator->getForm()->getFieldsets() );
		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
