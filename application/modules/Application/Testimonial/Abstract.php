<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Testimonial   Ayoola
 * @package    Application_Testimonial_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Testimonial_Exception 
 */
 
require_once 'Application/Testimonial/Exception.php';


/**
 * @Testimonial   Ayoola
 * @package    Application_Testimonial_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Testimonial_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'testimonial_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Testimonial';
		
    /**
     * Returns a db data
     * 
     */
	public function getPublicDbData()
    {
		$data = $this->getDbTable()->select( null, array( 'verified' => 1 ) );
		return $data; 
	} 
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false;
		$form->submitValue = $submitValue;
		$fieldset->addElement( array( 'name' => 'testimonial', 'placeholder' => 'Tell the world how special we are...', 'type' => 'TextArea', 'value' => @$values['testimonial'] ) );
		$fieldset->addRequirement( 'testimonial', array( 'WordCount' => array( 10, 1000 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );

		//	Contact information
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = true;
		$fieldset->useDivTagForElement = false;
		$fieldset->addElement( array( 'name' => 'full_name', 'label' => 'Full Name', 'placeholder' => 'Full name', 'type' => 'InputText', 'value' => @$values['full_name'] ) );
		
		//	Image
		$fieldset->addElement( array( 'name' => 'document_url', 'label' => '', 'placeholder' => 'Snapshot ', 'type' => 'Hidden', 'value' => @$values['document_url'] ? : '/img/logo.png' ) );
		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'document_url' ) : 'document_url' );
		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['document_url'] ? : '/img/logo.png' ), 'field_name' => $fieldName, 'width' => '480', 'height' => '480', 'crop' => true, 'field_name_value' => 'url' ) ) ) );
		$fieldset->addElement( array( 'name' => 'city', 'placeholder' => 'Enter a location', 'type' => 'InputText', 'value' => @$values['city'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'full_name', array( 'WordCount' => array( 2, 100 ) ) );
		$fieldset->addRequirement( 'city', array( 'WordCount' => array( 2, 100 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );

		//	Admin
		if( self::hasPriviledge() )
		{
			$fieldset = new Ayoola_Form_Element;
			$fieldset->placeholderInPlaceOfLabel = true;
			$fieldset->useDivTagForElement = false;
			$fieldset->addElement( array( 'name' => 'verified', 'label' => 'Verified?', 'type' => 'Radio', 'value' => @$values['verified'] ), array( 'No', 'Yes' ) );
			$fieldset->addRequirement( 'verified', array( 'Range' => array( 0, 1 ) ) );
			$fieldset->addLegend( $legend );
			$form->addFieldset( $fieldset );
		}
		$this->setForm( $form );
    } 
	// END OF CLASS
}
