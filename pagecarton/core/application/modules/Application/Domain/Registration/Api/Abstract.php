<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_Registration_Api_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Saturday 25th of August 2018 08:44AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Application_Domain_Registration_Api_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'api_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'api_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain_Registration_Api';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );


    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = false;       
        $options = Ayoola_Object_Widget::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'class_name', 'class_name' );
        $options = $filter->filter( $options );
        $fieldset->addElement( array( 'name' => 'class_name', 'type' => 'Select', 'value' => @$values['class_name'] ), $options ); 

        $options = Application_Domain_Registration_Whois::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'extension', 'extension' );
        $options = $filter->filter( $options );
        $fieldset->addElement( array( 'name' => 'extension', 'type' => 'Checkbox', 'value' => @$values['extension'] ), $options ); 
 
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
