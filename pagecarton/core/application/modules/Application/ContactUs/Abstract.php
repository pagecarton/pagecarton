<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_ContactUs_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $ 
 */

/**
 * @see Application_ContactUs_Exception 
 */
 
require_once 'Application/ContactUs/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_ContactUs_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_ContactUs_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Contact Us';      

	
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'contactus_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_ContactUs';
		
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
   //   $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'class' => 'smallFormElements' ) );   
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->oneFieldSetAtATime = true;
		$form->oneFieldSetAtATimeJs = true;

		//	Contact information
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false;
		$fieldset->addElement( array( 'name' => 'contactus_firstname', 'label' => 'Full name', 'type' => 'InputText', 'value' => @$values['contactus_firstname'] ) );
	//	$fieldset->addElement( array( 'name' => 'contactus_lastname', 'label' => 'Lastname', 'type' => 'InputText', 'value' => @$values['contactus_lastname'] ) );
	//	$fieldset->addElement( array( 'name' => 'contactus_company', 'label' => 'Company', 'type' => 'InputText', 'value' => @$values['contactus_company'] ) );
		$fieldset->addElement( array( 'name' => 'contactus_email', 'label' => 'Email', 'type' => 'InputText', 'value' => @$values['contactus_email'] ) );
//		$fieldset->addElement( array( 'name' => 'contactus_web_address', 'label' => 'Website', 'type' => 'InputText', 'value' => @$values['contactus_web_address'] ) );
		$fieldset->addElement( array( 'name' => 'contactus_phone_number', 'label' => 'Phone Number', 'type' => 'InputText', 'value' => @$values['contactus_phone_number'] ) );

		$fieldset->addElement( array( 'name' => 'contactus_creation_date', 'type' => 'Hidden' ) );	
		$fieldset->addFilter( 'contactus_creation_date', array( 'DefiniteValue' => time() ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'contactus_email', array( 'EmailAddress' => null ) );
	//	$fieldset->addRequirement( 'contactus_phone_number', array( 'Digits' => null ) );
		$fieldset->addRequirement( 'contactus_firstname', array( 'WordCount' => array( 1,100 ) ) );
	//	$fieldset->addRequirement( 'contactus_lastname', array( 'WordCount' => array( 6,100 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );     

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = true;
	//	$fieldset->useDivTagForElement = false;
		$form->submitValue = $submitValue;  
		
		//	Contact form
		$fieldset->addElement( array( 'name' => 'contactus_subject', 'label' => $this->getParameter( 'subject_label' ) ? : 'Subject', 'placeholder' => $this->getParameter( 'subject_placeholder' ) ? : 'Contact subject...', 'type' => 'InputText', 'value' => @$values['contactus_subject'] ) );
		$fieldset->addElement( array( 'name' => 'contactus_message', 'label' => $this->getParameter( 'message_label' ) ? : 'Message', 'placeholder' => $this->getParameter( 'message_placeholder' ) ? : 'Please briefly describe what you are contacting us about...', 'type' => 'TextArea', 'value' => @$values['contactus_message'] ) );  
		$fieldset->addRequirement( 'contactus_message', array( 'WordCount' => array( 10,1000 ) ) );
//		$fieldset->addRequirement( 'contactus_subject', array( 'WordCount' => array( 4,100 ) ) );
		$fieldset->addFilters( array( 'trim' => null ) );
//		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );

		$this->setForm( $form );
    } 
	// END OF CLASS
}
