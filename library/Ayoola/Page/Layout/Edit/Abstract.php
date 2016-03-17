<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Edit_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Edit_Exception 
 */
 
require_once 'Ayoola/Page/Layout/Edit/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Edit_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Page_Layout_Edit_Abstract extends Ayoola_Page_Layout_Abstract
{

    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		
		//	We don't allow editing UNIQUE Keys
		$fieldset->addElement( array( 'name' => self::VALUE_CONTENT, 'rows' => 20, 'description' => 'Enter the template text here', 'type' => 'Textarea', 'value' => @$values[self::VALUE_CONTENT] ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 10,30000 ) ) );
	//	$fieldset->addFilters( array( 'Trim' => null, 'Escape' => null ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 

	// END OF CLASS
}
