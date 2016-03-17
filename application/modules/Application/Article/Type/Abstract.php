<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Type_Exception 
 */
 
require_once 'Application/Article/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Article_Type_Abstract extends Application_Article_Abstract
{
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
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true; 
		$fieldset->addElement( array( 'name' => 'post_type_name', 'placeholder' => 'Give this post type a name', 'type' => 'InputText', 'value' => @$values['post_type_name'] ) );

		$form->addFieldset( $fieldset );		
		$this->setForm( $form );
    } 
	// END OF CLASS
}
