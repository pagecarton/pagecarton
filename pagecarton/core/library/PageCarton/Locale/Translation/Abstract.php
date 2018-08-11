<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Locale_Translation_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 5th of August 2018 05:05PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PageCarton_Locale_Translation_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'translation_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'translation_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_Locale_Translation';
	
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
        $stringID = @$values['originalstring_id'] ? : $_REQUEST['originalstring_id'];
        $fieldset->addElement( array( 'name' => 'originalstring_id', 'type' => 'hidden', 'value' => $stringID ) ); 
        if( $stringID )
        {
	    	$words = PageCarton_Locale_OriginalString::getInstance()->selectOne( null, array( 'originalstring_id' => $stringID ) );
            
            $fieldset->addElement( array( 'name' => 'string', 'readonly' => true, 'type' => 'TextArea', 'value' => @$words['string'] ) ); 
        }
	    $locale = PageCarton_Locale::getInstance()->selectOne( null, array( 'locale_code' => @$values['locale_code'] ) );
        $fieldset->addElement( array( 'name' => 'translation', 'label' => $locale['native_name'] . ' translation', 'type' => 'TextArea', 'value' => @$values['translation'] ) ); 
        $fieldset->addElement( array( 'name' => 'locale_code', 'readonly' => true, 'type' => 'Hidden', 'value' => @$values['locale_code'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
