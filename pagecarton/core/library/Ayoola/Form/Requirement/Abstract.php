<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Form_Requirement_Exception 
 */
 
require_once 'Ayoola/Form/Requirement/Exception.php';


/**
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Form_Requirement_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'requirement_name',  );
	
    /**
     * 
     * @var string
     */
	protected $_idColumn = 'requirement_name';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Form_Requirement';
	
	
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'requirement_label', 'label' => 'Requirement Name', 'placeholder' => 'Give this form requirement a name', 'type' => 'InputText', 'value' => @$values['requirement_label'] ) );
		
		$fieldset->addElement( array( 'name' => 'requirement_class', 'label' => 'PHP Class to load the requirement', 'placeholder' => 'Ayoola_Form_Requirement', 'type' => 'InputText', 'value' => @$values['requirement_class'] ) );
		
		$fieldset->addElement( array( 'name' => 'requirement_legend', 'label' => 'Title', 'placeholder' => 'Enter a form group heading', 'type' => 'InputText', 'value' => @$values['requirement_legend'] ) );
		
		$fieldset->addElement( array( 'name' => 'requirement_goodnews', 'label' => 'Description', 'placeholder' => 'Write the message to display to user when requesting for the required information in a few words...', 'type' => 'TextArea', 'value' => @$values['requirement_goodnews'] ) );
		
		$options = new Ayoola_Form_Requirement;
		$options = $options->select();
		if( $options )
		{
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'requirement_name', 'requirement_label');
			$options = $filter->filter( $options );
			$fieldset->addElement( array( 'name' => 'requirement_dependencies', 'label' => 'Select other requirements this requirement depends on (advanced)', 'type' => 'Checkbox', 'value' => @$values['requirement_dependencies'] ), $options );
		//	$fieldset->addRequirement( 'requirement_dependencies', array( 'InArray' => array_keys( $options )  ) );
		}
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirement( 'requirement_label', array( 'WordCount' => array( 3, 100 ) ) );
		$fieldset->addRequirement( 'requirement_class', array( 'WordCount' => array( 3, 100 ) ) );
		$fieldset->addRequirement( 'requirement_goodnews', array( 'WordCount' => array( 3, 500 ) ) );
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
//		$form->setParameter( array( 'requirements' => 'email-address, phone-number' ) );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
