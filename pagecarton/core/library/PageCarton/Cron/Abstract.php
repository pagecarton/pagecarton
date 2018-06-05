<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Cron_Abstract
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Wednesday 20th of December 2017 03:26PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Cron_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'table_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'table_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_Cron_Table';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'PageCarton Cron'; 

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
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;
		$fieldset->placeholderInPlaceOfLabel = false;
        
        $fieldset->addElement( array( 'name' => 'class_name', 'label' => 'Widget Class Name', 'placeholder' => 'e.g. PageCarton_Widget_Sample', 'type' => 'InputText', 'value' => @$values['class_name'] ) ); 

		$values['cron_parameters'] = $values['cron_parameters'] ? json_encode( $values['cron_parameters'] ) : $values['cron_parameters'];
        $fieldset->addElement( array( 'name' => 'cron_parameters', 'label' => 'Parameters', 'placeholder' => '[]', 'type' => 'InputText', 'value' => @$values['cron_parameters'] ) ); 
        $options = array(
                            '3600' => '1 hr',
                            '43200' => '12 hr',
                            '86400' => '1 day',
                            '604800' => '7 days',
        );
        $fieldset->addElement( array( 'name' => 'cron_interval', 'label' => 'Cron Interval', 'type' => 'Select', 'value' => @$values['cron_interval'] ), $options ); 
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
