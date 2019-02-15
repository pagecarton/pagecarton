<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_CommentBox_Table_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 17th of June 2018 02:30AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Application_CommentBox_Table_Abstract extends PageCarton_Widget
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
	protected $_tableClass = 'Application_CommentBox_Table';
	
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
        $fieldset->addElement( array( 'name' => 'comment', 'type' => 'InputText', 'value' => @$values['comment'] ) ); 
        $fieldset->addElement( array( 'name' => 'url', 'type' => 'InputText', 'value' => @$values['url'] ) ); 
        $fieldset->addElement( array( 'name' => 'article_url', 'type' => 'InputText', 'value' => @$values['article_url'] ) ); 
        $fieldset->addElement( array( 'name' => 'profile_url', 'type' => 'InputText', 'value' => @$values['profile_url'] ) ); 
        $fieldset->addElement( array( 'name' => 'display_name', 'type' => 'InputText', 'value' => @$values['display_name'] ) ); 
        $fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) ); 
        $fieldset->addElement( array( 'name' => 'website', 'type' => 'InputText', 'value' => @$values['website'] ) ); 
        $fieldset->addElement( array( 'name' => 'creation_time', 'type' => 'InputText', 'value' => @$values['creation_time'] ) ); 
        $fieldset->addElement( array( 'name' => 'parent_comment', 'type' => 'InputText', 'value' => @$values['parent_comment'] ) ); 
        $fieldset->addElement( array( 'name' => 'hidden', 'type' => 'InputText', 'value' => @$values['hidden'] ) ); 
        $fieldset->addElement( array( 'name' => 'enabled', 'type' => 'InputText', 'value' => @$values['enabled'] ) ); 
        $fieldset->addElement( array( 'name' => 'approved', 'type' => 'InputText', 'value' => @$values['approved'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
