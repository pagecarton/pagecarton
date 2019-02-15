<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Table_Sample
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php Sunday 5th of August 2018 02:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PageCarton_Locale_Settings extends PageCarton_Settings
{


	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	$values = unserialize( @$values['settings'] );
	//	$settings = unserialize( @$values['settings'] );
		$settings = @$values['data'] ? : unserialize( @$values['settings'] );
//$values = @$values['data'] ? : unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;

        //  Sample Text Field Retrieving E-mail Address
		$options = PageCarton_Locale::getInstance()->select();
		$filter = new Ayoola_Filter_SelectListArray( 'locale_code', 'locale_name' );
		$options = array( '' => 'Default' ) + ( $filter->filter( $options ) ? : array() );
	//	var_dump()
		$fieldset->addElement( array( 'name' => 'default_locale', 'label' => 'Default Locale', 'value' => @$settings['default_locale'], 'type' => 'Select' ), $options );


        //  Check box
		$options = array( 
							'auto_translate' => 'Auto Translate Output Text', 
							'autosave_new_words' => 'Save new words', 
							'auto-detect-user-locale' => 'Auto-Detect Locale', 
							);
		$fieldset->addElement( array( 'name' => 'locale_options', 'label' => 'Locale Options', 'value' => @$settings['locale_options'], 'type' => 'Checkbox' ), $options );
		
		$fieldset->addLegend( 'Locale Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
